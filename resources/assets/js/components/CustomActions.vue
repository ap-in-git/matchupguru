<template>
  <div class="custom-actions">
    <button class="ui basic button" @click="itemAction('view-item', rowData, rowIndex)"><i class="fa fa-eye"></i></button>
    <button class="ui basic button" @click="itemAction('edit-item', rowData, rowIndex)"><i class="fa fa-edit"></i></button>
    <button class="ui basic button" @click="itemAction('delete-item', rowData, rowIndex)"><i class="fa fa-trash-o"></i></button>
  </div>
</template>

<script>
export default {
  props: {
    rowData: {
      type: Object,
      required: true
    },
    rowIndex: {
      type: Number
    }
  },
  methods: {
    itemAction (action, data, index) {
      console.log('custom-actions: ' + action, data.name, index)
    }
  }
}
</script>

<style>
  .custom-actions button.ui.button {
    padding: 8px 8px;
  }
  .custom-actions button.ui.button > i.icon {
    margin: auto !important;
  }
</style>
