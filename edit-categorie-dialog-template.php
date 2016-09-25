<md-dialog aria-label="List dialog" flex="50">
  <md-dialog-content>
    <div layout="row" layout-wrap="layout-wrap">
      <div flex="100" layout-margin="layout-margin">
        <h3>Edit categorie</h3>
        <md-input-container class="no-margin-bottom md-block">
          <input placeholder="Name" type="text" ng-model="currentCat.name"/>
        </md-input-container>

        <div>
          Categories :
        </div>
        <treecontrol class="tree-light"
           tree-model="tree"
           on-selection="showSelected(node)">
           {{node.name}}
        </treecontrol>

        <div>
          Parent categorie :
          <span class="cat" ng-if="selectedNode.name != null">{{selectedNode.name}}</span>
          <span ng-if="selectedNode.name == null">no parent, element will be placed at root</span>
        </div>
      </div>
    </div>
  </md-dialog-content>
  <md-dialog-actions>
    <md-button ng-click="edit()" class="md-primary">
      Modify
    </md-button>
  </md-dialog-actions>
</md-dialog>
