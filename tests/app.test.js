'use strict';

describe('ShowTweetsAction', function () {
    var scope, httpBackend, locationMock, statusId, tweets,
        $controller, $httpBackend, $log, $rootScope;

    beforeEach(angular.mock.module('weaverApp'));
    beforeEach(inject(function ($injector, twitter) {
        statusId = "420103690863669249"
        tweets = [
            {
                "author_avatar": "http://pbs.twimg.com/profile_images/1803355808/377992_203375376416531_100002322093627_443137_1695065966_n_normal.jpg",
                "text": "@schmittjoh Are those changes pushed to https://t.co/8X8XXLOSnB yet? Can't find anything in the recent commits.",
                "screen_name": "nikita_ppv",
                "id": 4366498,
                "status_id": statusId,
                "starred": true
            }
        ];
        $httpBackend = $injector.get('$httpBackend');
        httpBackend = $httpBackend;
        $httpBackend.when('GET', 'https://## FILL HOSTNAME ##/twitter/tweet/latest?username=weaver').respond(tweets);

        locationMock = jasmine.createSpyObj('location', ['protocol', 'host']);
        locationMock.$host = '## FILL HOSTNAME ##';
        locationMock.$protocol = 'https';
        locationMock.host.andCallFake(function () {
            return this.$host;
        });
        locationMock.protocol.andCallFake(function () {
            return this.$protocol;
        });

        $rootScope = $injector.get('$rootScope');
        scope = $rootScope.$new();

        $controller = $injector.get('$controller');
        $log = $injector.get('$log');

        $controller('ShowTweetsAction', {
            $scope: scope,
            $http: $injector.get('$http'),
            $location: locationMock,
            $routeParams: {username: 'weaver'},
            $log: $log,
            twitter: twitter
        });
    }));

    afterEach(function () {
        $httpBackend.verifyNoOutstandingExpectation();
        $httpBackend.verifyNoOutstandingRequest();
    });

    it('should have tweets', function () {
        httpBackend.flush();
        expect(scope.tweets).toEqual(tweets);
    });

    it('should star tweet', function () {
        var endpoint = 'https://## FILL HOSTNAME ##/twitter/tweet/star/' + statusId;
        $httpBackend.when('POST', endpoint).respond({
            "status": statusId
        });
        scope.star(statusId, 0);
        httpBackend.flush();
        expect(scope.tweets[0].starred).toEqual(true);
    });

    it('should unstar tweet', function () {
        var endpoint = 'https://## FILL HOSTNAME ##/twitter/tweet/unstar/' + statusId;
        $httpBackend.when('POST', endpoint).respond({
            "status": statusId
        });
        scope.unstar(statusId, 0);
        httpBackend.flush();
        expect(scope.tweets[0].starred).toEqual(false);
    });
});