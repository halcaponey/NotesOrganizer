<md-dialog aria-label="List dialog" flex="50">
  <md-dialog-content>
    <div layout="row" layout-wrap="layout-wrap">
      <div flex="100" layout-margin="layout-margin">
        <h3>New categorie</h3>
        <md-input-container class="no-margin-bottom md-block">
          <input placeholder="Name" type="text" ng-model="name"/>
        </md-input-container>
      </div>
    </div>
  </md-dialog-content>
  <md-dialog-actions>
    <md-button ng-click="add()" class="md-primary">
      Add
    </md-button>
  </md-dialog-actions>
</md-dialog>
