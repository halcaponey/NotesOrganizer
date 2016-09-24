var app = angular.module('note_organizer', ['ngMaterial', 'treeControl']);
app.controller('notes', function notes($scope, $location, $mdDialog, $http) {
  $scope.notes = {};

  $scope.showCat = true;

  $scope.showNoteAddModify = true;

  $scope.cat = [];

  $scope.addEditText = "Add";

  $scope.idNotesEdit = -1;

  $scope.treeOptions = {multiSelection: true, addModDel: true};

  $scope.selectedNodes = [];

  $scope.addCat = function(cat){
    var parentEl = angular.element(document.body);
    $mdDialog.show({
      parent: parentEl,
      clickOutsideToClose: true,
      templateUrl: $location.path() + 'add-categorie-dialog-template.php',
      locals: {
        name: ""
      },
      controller: DialogController
    });
    function DialogController($scope, $mdDialog, name) {
      $scope.name = name;
      $scope.add = function() {
        console.log($scope.name);
        console.log(cat.id);
        $mdDialog.hide();
      }
    }
  }

  $scope.$on('tree:add.node', function(event, node) {
    $scope.addCat(node);
  });

  $scope.$on('tree:edit.node', function(event, node) {
    console.log(node);
  });

  //automaticly reparent in php
  $scope.$on('tree:delete.node', function(event, node) {
    $http.delete($location.path() + 'categories-ws.php?id='+node.id+'&id_parent='+node.id_parent).success(function(resp) {
      $scope.update();
    });
  });

  function searchTree(element, matchingId){
     if(element.id == matchingId){
          return element;
     }else if (element.children != null){
          var i;
          var result = null;
          for(i=0; result == null && i < element.children.length; i++){
               result = searchTree(element.children[i], matchingId);
          }
          return result;
     }
     return null;
  }

  $scope.deleteCat = function(note){
    console.log(node);
  }
  $scope.edit = function(note){
    $scope.idNotesEdit = note.id;
    $scope.title = note.title;
    $scope.description = note.description;
    $scope.addEditText = "Modify"
    $scope.selectedNodes = [];
    note.categorie.forEach(function(cat){
      var res = searchTree({"children": $scope.cat}, cat.id)
      if (res != null){
        $scope.selectedNodes.push(res);
      }

    });

  };

  $scope.addModify = function(title, description) {

    var cat_ids = [];
    $scope.selectedNodes.forEach(function(item){
      cat_ids.push(item.id);
    });

    if ($scope.addEditText === "Add"){
      $http.post($location.path() + 'notes-ws.php', JSON.stringify({"title":title, "description":description, "categories":cat_ids})).success(function(resp) {
        $scope.update();
      });
    } else {
      $http.put($location.path() + 'notes-ws.php', JSON.stringify({"id":$scope.idNotesEdit, "title":title, "description":description, "categories":cat_ids})).success(function(resp) {
        //console.log(resp);
        $scope.update();
      });
      $scope.idNotesEdit = -1;
      $scope.addEditText = "Add";
    }

    $scope.title = '';
    $scope.description = '';
  };

  $scope.delete = function(id) {
    $http.delete($location.path() + 'notes-ws.php?id='+id).success(function(resp) {
      $scope.update();
    });
  };

  $scope.update = function() {
    $http.get($location.path() + 'notes-ws.php').success(function(resp) {
      $scope.notes = resp;
    });
    $http.get($location.path() + 'categories-ws.php').success(function(resp) {
      $scope.cat = resp;
    });
  };

  setInterval(function() {
    $scope.update();
  }, 3 * 60 * 1000); // update every 3 minutes;

  $scope.update();


});
