require('./pages')
const app = require('./main')

app.controller('MainCtrl', ['$scope', 'HTTP', ($scope, HTTP) => {
    window.s = $scope
    HTTP.get('/gold-prices/live')
        .fork(console.log, r => {
            $scope.livePrice = r.rate || null
        })
    $scope.goTo = function(link) {
        window.location.href = link
    }
    $scope.activeUserProfile = me

    $scope.goToW = function(link) {
        window.open(link)
    }

}]);