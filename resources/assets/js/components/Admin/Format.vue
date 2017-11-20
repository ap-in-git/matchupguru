<template lang="html">
  <div>
    <v-pagination ref="pagination"
       @vuetable-pagination:change-page="onChangePage"
       class="pull-right"

    ></v-pagination>
    <v-table ref="vuetable"
      :api-url="url"
      :fields="fields"
        pagination-path=""
        :css="css.table"

      @vuetable:pagination-data="onPaginationData"

    >

    <template slot="actions" scope="props">
            <div class="custom-actions">

              <a :href="'/admin/format/'+props.rowData.id+'/edit' " class="btn btn-primary">Manage Format</a>
              <a :href="'/admin/format/find/deck/'+props.rowData.id " class="btn btn-success">Manage Deck</a>

            </div>
          </template>
  </v-table>

  <div class="modal show" v-if="showdelete">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" @click="showdelete=false">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Confirm Delete</h4>
      </div>

      <div class="modal-body">
        <div  class="alert alert-info" hidden="">
        </div>
        <p>You are about to delete one track, this procedure is irreversible. <br>
        </p>

        <p>Do you want to proceed?</p>

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" @click="showdelete=false">Cancel</button>
        <a class="btn btn-danger btn-ok" id="finaldeletecategory" @click="confirmdelete()">Delete</a>
      </div>
    </div>
  </div>
</div>
  </div>
</template>

<script>
import Vuetable from 'vuetable-2/src/components/Vuetable'
import VuetablePaginationBootstrap from '../../components/VuetablePaginationBootstrap.vue'
import CssConfig from '../../components/VuetableCssConfig'

export default {
  components: {
    'v-table': Vuetable,
    'v-pagination': VuetablePaginationBootstrap,

},
  data () {
    return {
      fields: [
    {
      name:'id',

    },
    {
      name:'name',
      sortField: 'name',
      direction:'asc'

    },
    {
      name:"game.name",
      title:"Game"
    },
    {
         name: '__slot:actions',   // <----
         title: 'Actions',
         titleClass: 'center aligned',
         dataClass: 'center aligned'
       }

     ],
     css:CssConfig,
     url:"/admin/format-ajax/",
     root:null,
     showdelete:false,
     deleteid:null,
    }
  },
  methods: {
  onPaginationData (paginationData) {
    this.$refs.pagination.setPaginationData(paginationData)
  },
  onChangePage (page) {
    this.$refs.vuetable.changePage(page)
  },
  onAction (action, data, index) {
    if(action=='edit-item')
     this.approveDeck(data.id)

    if(action=="delete-item")
    this.deleteDeck(data.id);

 },
   approveDeck:function(id){

     axios.get("/admin/unverified-deck/approve/"+id).then((data)=>{
     this.root.showsuccess("Deck has been approved");
     this.$refs.vuetable.refresh()
     })
   },
   deleteDeck:function(id){
  this.showdelete=true;
  this.deleteid=id;

},
confirmdelete:function(){
  axios({
  method:'delete',
  url:' /admin/deck/'+this.deleteid,
})
  .then((data) =>{
    this.$refs.vuetable.refresh()
     this.root.showsuccess("Deck has been deleted")
     this.showdelete=false;

});

}
   ,
},
mounted:function(){
this.root=this.$root.$root;
}
}
</script>

<style lang="css">
</style>
