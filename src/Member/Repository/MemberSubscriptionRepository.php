<?php
declare(strict_types=1);

namespace App\Member\Repository;

use App\Domain\Publication\PublicationListIdentity;
use App\Domain\Publication\PublicationListIdentityInterface;
use App\Infrastructure\Http\PaginationParams;
use App\Infrastructure\Repository\Membership\MemberRepositoryInterface;
use App\Infrastructure\Repository\Subscription\MemberSubscriptionRepositoryInterface;
use App\Member\Entity\MemberSubscription;
use App\Membership\Entity\MemberInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use JsonSchema\Exception\JsonDecodingException;
use Symfony\Component\HttpFoundation\Request;
use function array_diff;
use function array_key_exists;
use function array_map;
use function array_values;
use function explode;
use function implode;
use function json_decode;
use function json_last_error;
use function sprintf;
use function str_replace;
use const JSON_ERROR_NONE;
use const JSON_THROW_ON_ERROR;
use const PHP_EOL;

class MemberSubscriptionRepository extends ServiceEntityRepository implements MemberSubscriptionRepositoryInterface
{
    private const SORT_BY_ASCENDING_MEMBER_ID  = 'ORDER BY u.usr_id ASC';
    private const SORT_BY_DESCENDING_MEMBER_ID = 'ORDER BY u.usr_id DESC';

    public MemberRepositoryInterface $memberRepository;

    /**
     * @param MemberInterface $member
     *
     * @return bool
     * @throws DBALException
     */
    public function cancelAllSubscriptionsFor(MemberInterface $member): bool
    {
        $query = <<< QUERY
            UPDATE member_subscription ms, weaving_user u
            SET has_been_cancelled = 1
            WHERE ms.member_id = :member_id
            AND ms.subscription_id = u.usr_id
            AND u.suspended = 0
            AND u.protected = 0
            AND u.not_found = 0
QUERY;

        $connection = $this->getEntityManager()->getConnection();
        $statement  = $connection->executeQuery(
            strtr(
                $query,
                [':member_id' => $member->getId()]
            )
        );

        return $statement->closeCursor();
    }

    /**
     * @param MemberInterface $member
     *
     * @return array|mixed
     * @throws DBALException
     */
    public function countMemberSubscriptions(MemberInterface $member)
    {
        $queryTemplate = <<< QUERY
            SELECT 
            {selection}
            {constraints}
QUERY;
        $query         = strtr(
            $queryTemplate,
            [
                '{selection}'   => 'COUNT(*) count_',
                '{constraints}' => $this->getConstraints(),
            ]
        );

        $connection = $this->getEntityManager()->getConnection();
        $statement  = $connection->executeQuery(
            strtr(
                $query,
                [
                    ':member_id' => $member->getId(),
                ]
            )
        );

        $results = $statement->fetchAll();
        if ($this->emptyResults($results, 'count_')) {
            return 0;
        }

        return $results[0]['count_'];
    }

    /**
     * @param MemberInterface $member
     * @param array           $subscriptions
     *
     * @return array
     * @throws DBALException
     */
    public function findMissingSubscriptions(MemberInterface $member, array $subscriptions)
    {
        $query = <<< QUERY
            SELECT GROUP_CONCAT(sm.usr_twitter_id) subscription_ids
            FROM member_subscription s,
            weaving_user sm
            WHERE sm.usr_id = s.subscription_id
            AND member_id = :member_id1
            AND (s.has_been_cancelled IS NULL OR s.has_been_cancelled = 0)
            AND sm.usr_twitter_id is not null
            AND sm.usr_twitter_id in (:subscription_ids)
QUERY;

        $connection = $this->getEntityManager()->getConnection();
        $statement  = $connection->executeQuery(
            strtr(
                $query,
                [
                    ':member_id'        => $member->getId(),
                    ':subscription_ids' => (string) implode(',', $subscriptions)
                ]
            )
        );

        $results = $statement->fetchAll();

        $remainingSubscriptions = $subscriptions;
        if (array_key_exists(0, $results) && array_key_exists('subscription_ids', $results[0])) {
            $subscriptionIds        = array_map(
                'intval',
                explode(',', $results[0]['subscription_ids'])
            );
            $remainingSubscriptions = array_diff(
                array_values($subscriptions),
                $subscriptionIds
            );
        }

        return $remainingSubscriptions;
    }

    public function getConstraints(PublicationListIdentityInterface $publicationListIdentity = null)
    {
        $restrictionByAggregate = '';

        if ($publicationListIdentity) {
            $restrictionByAggregate = sprintf(
                <<<QUERY
                AND a.name IN ( SELECT name FROM weaving_aggregate WHERE id = %d)
QUERY
                ,
                (int) ((string) $publicationListIdentity)
            );
        }

        $constraintsTemplates = implode(
            PHP_EOL,
            [
                <<<QUERY
                FROM member_subscription ms,
                weaving_user u
                {join} weaving_aggregate a
                ON a.screen_name = u.usr_twitter_username
                AND a.screen_name IS NOT NULL 
                WHERE member_id = :member_id 
                AND ms.subscription_id = u.usr_id
                AND u.suspended = 0
                AND u.protected = 0
                AND u.not_found = 0
QUERY
                ,
                $restrictionByAggregate
            ]
        );

        return str_replace(
            '{join}',
            $restrictionByAggregate ? 'INNER JOIN' : 'LEFT JOIN',
            $constraintsTemplates
        );
    }

    /**
     * @param MemberInterface $member
     * @param Request         $request
     *
     * @return array
     * @throws DBALException
     */
    public function getMemberSubscriptions(
        MemberInterface $member,
        Request $request = null
    ): array {
        $memberSubscriptions = [];

        $paginationParams        = null;
        $publicationListIdentity = null;
        if ($request instanceof Request) {
            $paginationParams        = PaginationParams::fromRequest($request);
            $publicationListIdentity = PublicationListIdentity::fromRequest($request);
        }

        $totalSubscriptions = $this->countMemberSubscriptions($member);
        if ($totalSubscriptions) {
            $memberSubscriptions = $this->selectMemberSubscriptions(
                $member,
                $paginationParams,
                $publicationListIdentity
            );
        }

        $aggregates = [];
        if ($paginationParams instanceof PaginationParams) {
            $aggregates = $this->getAggregatesRelatedToMemberSubscriptions(
                $member,
                $paginationParams
            );
        }

        return [
            'subscriptions'       => [
                'aggregates'    => $aggregates,
                'subscriptions' => $memberSubscriptions
            ],
            'total_subscriptions' => $totalSubscriptions,
        ];
    }

    public function getSelection()
    {
        return <<<QUERY
            u.usr_twitter_username as username,
            u.usr_twitter_id as member_id,
            u.description,
            u.url,
            IF (
              COALESCE(a.id, 0),
              CONCAT(
                '{',
                GROUP_CONCAT(
                  CONCAT('"', a.id, '": "', a.name, '"') ORDER BY a.name DESC SEPARATOR ","
                ), 
                '}'
              ),
              '{}'
            ) as aggregates
QUERY;
    }

    /**
     * @param MemberInterface $member
     * @param MemberInterface $subscription
     *
     * @return MemberSubscription
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function saveMemberSubscription(
        MemberInterface $member,
        MemberInterface $subscription
    ) {
        $memberSubscription = $this->findOneBy(['member' => $member, 'subscription' => $subscription]);

        if (!($memberSubscription instanceof MemberSubscription)) {
            $memberSubscription = new MemberSubscription($member, $subscription);
        }

        $this->getEntityManager()->persist($memberSubscription->markAsNotBeingCancelled());
        $this->getEntityManager()->flush();

        return $memberSubscription;
    }

    /**
     * @param MemberInterface                       $member
     * @param PaginationParams|null                 $paginationParams
     * @param PublicationListIdentityInterface|null $publicationListIdentity
     *
     * @return array
     * @throws DBALException
     */
    public function selectMemberSubscriptions(
        MemberInterface $member,
        PaginationParams $paginationParams = null,
        PublicationListIdentityInterface $publicationListIdentity = null
    ): array {
        $queryTemplate = $this->queryMemberSubscriptions(
            $publicationListIdentity,
            $paginationParams,
            $selection = '',
            $group = '',
            $sort = self::SORT_BY_DESCENDING_MEMBER_ID
        );

        $connection = $this->getEntityManager()->getConnection();

        $offset   = '';
        $pageSize = '';
        if ($paginationParams instanceof PaginationParams) {
            $offset   = $paginationParams->getFirstItemIndex();
            $pageSize = $paginationParams->pageSize;
        }

        $query     = strtr(
            $queryTemplate,
            [
                ':member_id'    => $member->getId(),
                ':offset'       => $offset,
                ':page_size'    => $pageSize,
                ':aggregate_id' => (string) $publicationListIdentity
            ]
        );
        $statement = $connection->executeQuery($query);

        $results = $statement->fetchAll();
        if (!array_key_exists(0, $results)) {
            return [];
        }

        return array_map(
            function (array $row) {
                $row['aggregates'] = json_decode($row['aggregates'], $asArray = true);

                $lastJsonError = json_last_error();
                if ($lastJsonError !== JSON_ERROR_NONE) {
                    throw new JsonDecodingException($lastJsonError);
                }

                return $row;
            },
            $results
        );
    }

    /**
     * @param array $results
     * @param       $column
     *
     * @return bool
     */
    private function emptyResults(array $results, $column): bool
    {
        return !isset($results[0][$column]);
    }

    /**
     * @param MemberInterface  $member
     * @param PaginationParams $paginationParams
     *
     * @return array
     * @throws DBALException
     */
    private function getAggregatesRelatedToMemberSubscriptions(
        MemberInterface $member,
        PaginationParams $paginationParams
    ): array {
        $query = sprintf(
            <<<QUERY
                SELECT CONCAT(
                    '{',
                    GROUP_CONCAT(
                        DISTINCT CONCAT(
                            '"',
                            select_.id, 
                            '": "', 
                            select_.name, 
                            '"'
                        ) SEPARATOR ', '
                    ),
                    '}'
                ) as aggregates
                FROM (%s) select_
QUERY
            ,
            $this->queryMemberSubscriptions(
                $publicationListIdentity = null,
                $paginationParams,
                'a.name, a.id'
            )
        );

        $connection       = $this->getEntityManager()->getConnection();
        $statement        = $connection->executeQuery(
            strtr(
                $query,
                [
                    ':member_id' => $member->getId(),
                    ':offset'    => $paginationParams->getFirstItemIndex(),
                    ':page_size' => $paginationParams->pageSize,
                ]
            )
        );
        $aggregateResults = $statement->fetchAll();

        $aggregates = [];
        if (!$this->emptyResults($aggregateResults, 'aggregates')) {
            $aggregates = json_decode(
                $aggregateResults[0]['aggregates'],
                $asArray = true,
                512,
                JSON_THROW_ON_ERROR
            );
        }

        return $aggregates;
    }

    /**
     * @param PublicationListIdentityInterface|null $publicationListIdentity
     * @param PaginationParams|null                 $paginationParams
     * @param string                                $selection
     * @param string                                $group
     * @param string                                $sort
     *
     * @return string
     */
    private function queryMemberSubscriptions(
        PublicationListIdentityInterface $publicationListIdentity = null,
        PaginationParams $paginationParams = null,
        string $selection = '',
        string $group = '',
        string $sort = ''
    ): string {
        $queryTemplate = <<< QUERY
            SELECT 
            {selection}
            {constraints}
            {group}
            {sort}
            {limit}
QUERY;

        return strtr(
            $queryTemplate,
            [
                '{selection}'   => $selection ?: $this->getSelection(),
                '{constraints}' => $this->getConstraints($publicationListIdentity),
                '{group}'       => $group ?: 'GROUP BY u.usr_twitter_username',
                '{sort}'        => $sort ?: self::SORT_BY_ASCENDING_MEMBER_ID,
                '{limit}'       => $paginationParams instanceof PaginationParams ? 'LIMIT :offset, :page_size' : '',
            ]
        );
    }
}