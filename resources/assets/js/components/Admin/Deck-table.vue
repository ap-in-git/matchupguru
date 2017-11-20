<template lang="html">
  <div>

    <div class="row">

      <div class="col-sm-4"></div>
      <div class="col-sm-4">
        <label >Filter By Season</label>
     <select class="form-control" style="width:100%;" v-model="currentSeason" @change="refreshTable()">
     <option v-for="season in seasons" :value="season.id">{{season.name}}</option>
     </select>
    </div>
      <div class="col-sm-4">
        <label >Filter By Format</label>
     <select class="form-control" style="width:100%;" v-model="currentformat" @change="refreshTable()">

       <option value="0">Select a format</option>
       <option v-for="format in formats" :value="format.id" >{{format.name}}</option>
     </select>

      </div>
    </div>
    <div id="err">
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
               <a :href="'/admin/deck/'+props.rowData.slug+'/edit'" class="btn btn-success">Edit deck</a>

                <a href="#" class="btn btn-danger" @click.prevent="deleteDeck(props.rowData.slug)">Delete Deck</a>

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




  </div>
</template>

<script>
import Vuetable from 'vuetable-2/src/components/Vuetable'
import VuetablePaginationBootstrap from '../../components/VuetablePaginationBootstrap.vue'
import CssConfig from '../../components/VuetableCssConfig'
import createDeck from "../user/suggest-deck.vue";
export default {
  components: {
    'v-table': Vuetable,
    'v-pagination': VuetablePaginationBootstrap,
      "create-deck":createDeck

},
  data () {
    return {
      fields: [
    {
      name:'name',
        title:'Name',
      sortField: 'deck',
      direction:'asc'

    },
    {
      name:"version",
        title:'Version',
        sortField:'version'

    },
    {
      name:"style",
      title:"Style",
        sortField:'style'
    },
          {
      name:"format",
      title:"Format"
    },
    {
         name: '__slot:actions',   // <----
         title: 'Actions',
         titleClass: 'center aligned',
         dataClass: 'center aligned'
       }

     ],
     css:CssConfig,
     url:"/adfas",
     root:null,
     showdelete:false,
     delete_slug:null,
     formats:null,
     currentformat:null,
     formatChanged:false,
        currentSeason:0,
        seasons:[]
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
loaddata:function(){
  axios.get("/api/format-game?game=magic", {
   })
   .then((response)=> {
     this.currentformat=0;
    this.formats=response.data.formats;
   })
   .catch((error)=> {
   });


    axios.get("/public/seasons?game=magic").then( (response)=> {
        let data=response.data;
        this.seasons=data.seasons;
        this.currentSeason=data.default;


    }).then(()=>{
        this.refreshTable();

    })

},
      refreshTable(){
          this.url="/magic-decks/table/?season="+this.currentSeason+"&format="+this.currentformat;


      },
      deleteDeck(slug){
          this.delete_slug=slug;
          this.showdelete=true;


      },
      confirmdelete(){
          axios.delete("/admin/deck/"+this.delete_slug).then((data)=>{
             this.$refs.vuetable.refresh()
            this.root.showsuccess("Deck has been deleted")
            this.showdelete=false;
          })

      }

},
mounted:function(){
this.root=this.$root.$root;
this.loaddata()


    this.$events.$on('deck-added',eventData=>{
        this.root.showsuccess("Deck added successfully");
        location.reload();
    });
}
}
</script>

<style lang="css">
