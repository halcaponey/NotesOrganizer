var app = angular.module('bitcloud', ['ngMaterial']);
app.controller('downloads', function files($scope, $http) {
  $scope.downloads = [];
  $scope.magnet = "";
  /*$scope.localisation = {
    "query": ""
  };

  $scope.goBack = function() {
    $scope.localisation = {
      "query": $scope.localisation.query,
      "goback": ".."
    };
    $scope.update();
  };

  $scope.goToDir = function(file) {
    if (file.IsDirectory){
      $scope.localisation = {
        "query": file.Path
      };
      $scope.update();
    }
  };
*/
  $scope.download = function(magnet) {
    var magnetjson = {'magnet': magnet};
    $http.post('/downloads', magnetjson).success(function(resp) {
      $scope.update();
    });
    $scope.magnet = "";
  };

  $scope.delete = function(id) {
    $http.delete('/downloads/'+id).success(function(resp) {
      $scope.update();
    });
  };

  $scope.update = function() {
    $http.get('/downloads/all').success(function(resp) {
      $scope.downloads = resp;

    });
  };

  setInterval(function() {
    $scope.update();
  }, 30 * 1000);

  $scope.update();
  //magnet:?xt=urn:btih:88594AAACBDE40EF3E2510C47374EC0AA396C08E&dn=bbb_sunflower_1080p_30fps_normal.mp4&tr=udp%3a%2f%2ftracker.openbittorrent.com%3a80%2fannounce&tr=udp%3a%2f%2ftracker.publicbt.com%3a80%2fannounce&ws=http%3a%2f%2fdistribution.bbb3d.renderfarming.net%2fvideo%2fmp4%2fbbb_sunflower_1080p_30fps_normal.mp4
/*
  var magnet = 'magnet:?xt=urn:btih:88594AAACBDE40EF3E2510C47374EC0AA396C08E&dn=bbb_sunflower_1080p_30fps_normal.mp4&tr=udp%3a%2f%2ftracker.openbittorrent.com%3a80%2fannounce&tr=udp%3a%2f%2ftracker.publicbt.com%3a80%2fannounce&ws=http%3a%2f%2fdistribution.bbb3d.renderfarming.net%2fvideo%2fmp4%2fbbb_sunflower_1080p_30fps_normal.mp4';
  var magnetjson = {'magnet': magnet};
  $http.post('/downloads', magnetjson).success(function(resp) {
    console.log(resp);
  });*/

});
