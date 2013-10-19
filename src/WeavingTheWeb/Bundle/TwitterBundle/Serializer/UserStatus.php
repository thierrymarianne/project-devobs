<?php

namespace WeavingTheWeb\Bundle\TwitterBundle\Serializer;

/**
 * Class UserStatus
 * @package WeavingTheWeb\Bundle\TwitterBundle\Accessor
 */
class UserStatus
{
    /**
     * @var \WeavingTheWeb\Bundle\Legacy\ProviderBundle\Reader\FeedReader $feedReader
     */
    protected $feedReader;

    /**
     * @var \WeavingTheWeb\Bundle\ApiBundle\Repository\UserStreamRepository $userStreamRepository
     */
    protected $userStreamRepository;


    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @param \WeavingTheWeb\Bundle\Legacy\ProviderBundle\Reader\FeedReader $feedReader
     */
    public function setFeedReader($feedReader)
    {
        $this->feedReader = $feedReader;
    }

    /**
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param \WeavingTheWeb\Bundle\ApiBundle\Repository\UserStreamRepository $userStreamRepository
     */
    public function setUserStreamRepository($userStreamRepository)
    {
        $this->userStreamRepository = $userStreamRepository;
    }

    /**
     * @param $oauthTokens
     */
    public function setupFeedReader($oauthTokens)
    {
        $this->feedReader->setUserToken($oauthTokens['token']);
        $this->feedReader->setUserSecret($oauthTokens['secret']);
    }

    public function serialize($options, $logLevel = 'info', $greedyMode) {
        $updateMaxId = true;

        while (($context = $this->updateContext($options, $logLevel, $updateMaxId)) && $context['condition']) {
            $saveStatuses = $this->persistStatuses($context['options'], $logLevel);

            if (!$greedyMode || (is_null($saveStatuses) && $updateMaxId === false)) {
                break;
            } else {
                $updateMaxId = false;
            }
        }
    }

    /**
     * @param $options
     * @return array
     */
    protected function updateContext($options, $logLevel = 'info', $updateMaxId = true)
    {
        $apiRateLimitReached = $this->feedReader->isApiRateLimitReached($logLevel, '/statuses/user_timeline');

        if (!$apiRateLimitReached) {
            $remainingStatuses = $this->remainingStatuses($options);

            $options = $this->updateExtremum($options, $logLevel, $updateMaxId);
        } else {
            $remainingStatuses = null;
        }

        return [
            'condition' => !$apiRateLimitReached && $remainingStatuses,
            'options' => $options
        ];
    }

    /**
     * @param $options
     * @param $logLevel
     * @param $updateMaxId
     * @return mixed
     */
    protected function updateExtremum($options, $logLevel, $updateMaxId = true)
    {
        if ($updateMaxId) {
            $option = 'max_id';
            $shift = -1;
            $updateMethod = 'findNextMaximum';
        } else {
            $option = 'since_id';
            $shift = 1;
            $updateMethod = 'findNextMininum';
        }

        $status = $this->userStreamRepository->$updateMethod($options['oauth'], $options['screen_name']);

        if ((count($status) === 1) && array_key_exists('statusId', $status)) {
            $options[$option] = $status['statusId'] + $shift;

            if ($logLevel === 'info') {
                $this->logger->info(
                    '[extremum (' . $option . ') retrieved for "' . $options['screen_name'] . '"] ' . $options[$option]
                );
            }
        }

        return $options;
    }

    /**
     * @param $options
     * @param $count
     * @return bool
     */
    protected function remainingStatuses($options)
    {
        $count = $this->userStreamRepository->countStatuses($options['oauth'], $options['screen_name']);
        $user = $this->feedReader->showUser($options['screen_name']);
        if (!isset($user->statuses_count) || $user->protected) {
            $statusesCount = 0;
        } else {
            $statusesCount = $user->statuses_count;
        }

        // Twitter allows 3200 tweets to be retrieved at most from now for any given user
        return $count < max($statusesCount, 3200);
    }

    /**
     * @param $options
     */
    protected function persistStatuses($options, $logLevel = 'info')
    {
        $statuses = $this->feedReader->fetchTimelineStatuses($options);
        if (is_array($statuses) && count($statuses) > 0) {
            $savedStatuses = $this->userStreamRepository->saveStatuses($statuses, $options['oauth']);

            if ($logLevel === 'info') {
                $this->logger->info('[' . count($savedStatuses) . ' status(es) saved]');
            }

            if (count($savedStatuses) === 0) {
                return null;
            } else {
                return $savedStatuses;
            }
        } else {
            return null;
        }
    }
}