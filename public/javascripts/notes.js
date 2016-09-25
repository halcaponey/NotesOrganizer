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
        update: $scope.update,
        name: ""
      },
      controller: DialogController
    });
    function DialogController($scope, $mdDialog, name, update) {
      $scope.name = name;
      $scope.add = function() {
        $http.post($location.path() + 'categories-ws.php', JSON.stringify({"name":$scope.name, "id_parent":cat.id})).success(function(resp) {
          update();
        });
        $mdDialog.hide();
      }
    }
  }

  $scope.$on('tree:add.node', function(event, node) {
    $scope.addCat(node);
  });

  $scope.$on('tree:edit.node', function(event, node) {

    var nodeparent = searchTree({"children": $scope.cat}, node.id_parent)
    var parentEl = angular.element(document.body);
    $mdDialog.show({
      parent: parentEl,
      clickOutsideToClose: true,
      templateUrl: $location.path() + 'edit-categorie-dialog-template.php',
      locals: {
        update: $scope.update,
        parent: nodeparent,
        currentCat: node,
        tree: $scope.cat
      },
      controller: DialogController
    });
    function DialogController($scope, $mdDialog, update, parent, currentCat, tree) {
      $scope.currentCat = currentCat;
      $scope.selectedNode = parent;
      $scope.showSelected = function(sel) {
        if (sel.id != $scope.currentCat.id) {
          if (sel.id != $scope.selectedNode.id) {
            $scope.selectedNode = sel;
          } else {
            $scope.selectedNode = {id:null};
          }
        }
      };
      $scope.tree = tree;
      $scope.edit = function() {
        $http.put($location.path() + 'categories-ws.php', JSON.stringify({"id": $scope.currentCat.id, "name":$scope.currentCat.name, "id_parent":$scope.selectedNode.id})).success(function(resp) {
          update();
        });
        $mdDialog.hide();
      }
    }
  });

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
