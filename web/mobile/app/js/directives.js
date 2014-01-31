'use strict';

weaverApp.directive('parseUrl', function () {
    var urlPattern = /(http|ftp|https):\/\/[\w-]+(\.[\w-]+)+([\w.,@?^=%&amp;:\/~+#-]*[\w@?^=%&amp;\/~+#-])?/gi;
    return {
        restrict: 'A',
        require: 'ngModel',
        replace: true,
        scope: { props: '=parseUrl', ngModel: '=ngModel' },
        link: function compile(scope, element, attrs, controller) {
            scope.$watch('ngModel.text', function (text) {
                angular.forEach(text.match(urlPattern), function (url) {
                    if (scope.ngModel.parsed === undefined) {
                        var value = text.replace(url, '<a target="' + url + '" href=' + url + '>' + url + '</a>');
                        scope.ngModel.originalText = text;
                        scope.ngModel.parsed = true;
                        element.html(value);
                    }
                });
            });
        }
    };
});