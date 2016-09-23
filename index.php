<!DOCTYPE html>
<html ng-app="note_organizer">
  <head>
    <?php
      include 'head.php';
    ?>
    <script src="public/javascripts/notes.js"></script>
  </head>
  <body>
    <div ng-controller="notes" layout-fill="layout-fill">
      <md-content layout-fill="layout-fill">
        <md-toolbar md-whiteframe="5">
          <div class="md-toolbar-tools">
            <h2>Notes organizer</h2><span flex=""></span>
          </div>
        </md-toolbar>

        <div layout="row" layout-wrap="layout-wrap">
          <div flex="100" layout-margin="layout-margin"></div>

          <md-card flex="100" md-whiteframe="4">
            <md-card-title>
              <md-card-title-text>
                <span>
                {{addEditText}} note : <md-button ng-click="showNoteAddModify = !showNoteAddModify">{{showNoteAddModify? 'hide' : 'show'}}</md-button>
                </span>
              </md-card-title-text>
            </md-card-title>

            <md-card-content ng-show="showNoteAddModify">
              <md-input-container class="no-margin-bottom md-block">
                <input placeholder="Title" type="text" ng-model="title"/>
              </md-input-container>
              <md-input-container class="md-block">
                <textarea placeholder="Description" type="text" ng-model="description"></textarea>
              </md-input-container>
              <div>
                Categories :
                <md-button ng-click="showCat = !showCat">{{showCat? 'hide' : 'show'}}</md-button>
              </div>
              <treecontrol ng-show="showCat" class="tree-light"
                 tree-model="cat"
                 expanded-nodes="expandedNodes"
                 selected-nodes="selectedNodes"
                 options="treeOptions">
                 {{node.name}}
              </treecontrol>

              <md-button ng-show="showCat" aria-label="Add" ng-click="" class="md-icon-button">
                <md-icon md-svg-src="public\images\ic_add_black_48px.svg"></md-icon>
              </md-button>

              <div class="space-between" ng-show="showCat"></div>
              <div>
                Selected categories :
                <span class="cat" ng-repeat="cat in selectedNodes">
                  {{cat.name}}
                </span>
              </div>
              <div class="space-between"></div>
              <md-button ng-click="addModify(title, description)" class="md-raised btn-login">{{addEditText}}</md-button>
            </md-card-content>
          </md-card>


          <div flex="25" flex-sm="50" flex-xs="100" ng-repeat="note in notes">
            <md-card md-whiteframe="4">
              <md-card-title>
                <md-card-title-text>
                  <span class="md-headline">{{note.title}}</span>
                </md-card-title-text>
              </md-card-title>
              <md-card-content>
                <p>{{note.description}}</p>
              </md-card-content>

              <md-card-content ng-if="note.categorie.length > 0">
                <div ng-if="note.categorie.length == 1">categorie : </div>
                <div ng-if="note.categorie.length > 1">categories : </div>
                <span class="cat" ng-repeat="cat in note.categorie">{{cat.name}}</span>
              </md-card-content>
              <md-divider></md-divider>
              <md-card-actions layout="row" layout-align="space-between stretch">
                <md-button aria-label="Edit" ng-click="edit(note)" class="md-icon-button">
                  <md-icon md-svg-src="public\images\ic_create_black_48px.svg"></md-icon>
                </md-button>

                <md-button aria-label="Delete" ng-click="delete(note.id)" class="md-icon-button">
                  <md-icon md-svg-src="public\images\ic_delete_black_48px.svg"></md-icon>
                </md-button>
              </md-card-actions>
            </md-card>
          </div>
        </div>
      </md-content>
    </div>
  </body>
</html>
