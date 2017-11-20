<template lang="html">
  <div>

    <div class="row">
      <div class="col-sm-12">

      <a href="#" class="btn btn-primary" data-toggle="modal" title="Add a new Deck" data-target="#deckAddModal">Add a new Deck</a>    </div>
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
                <a :href="'/user/deck/'+props.rowData.slug+'/show'" class="btn btn-primary">view</a>

            </div>
          </template>
  </v-table>



</div>

<div id="deckAddModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>

            </div>
            <div class="modal-body">

                <create-deck  :format_id="currentformat"></create-deck>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
     deleteid:null,
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
