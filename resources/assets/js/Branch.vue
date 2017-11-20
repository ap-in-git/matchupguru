<template>
  <div>
<div class="row">
  <div class="col-sm-5 " id="movesearchbar" >
        <input type="text" class="form-control" placeholder="Search" v-model="searchvalue"   @keyup="searchdata(searchvalue)" >
        <div v-if="searchvalue!=''">Searching For :{{searchvalue}}</div>
  </div>
<div class="col-sm-5" id="movesearchbar" >
            <div class="row">
              <div class="col-sm-8">
                  <input type="text" class="form-control" placeholder="Page Number"  v-model="gopageno">
              </div>
              <div class="col-sm-4">
                <button class="btn btn-primary" @click="gotopage()"> Go To Page</button>
              </div>
              </div>
      </div>
      <div class="col-sm-12" v-if="noresult">
            <div class="alert alert-success alert-dismissible fade in" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                                </button>
                                <strong> No result found</strong>
                              </div>
          </div>
          <div class="col-sm-12" v-if="nodataonpage">
            <div class="alert alert-success alert-dismissible fade in" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                                </button>
                                <strong> No data on this page</strong>
                              </div>
          </div>
          <div class="col-sm-12" v-if="loading">
            <div class="alert alert-success alert-dismissible fade in" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                                </button>
                                <strong>Loading ...</strong>
                              </div>
          </div>
           <div class="col-sm-12">

             <div class="row">

               <div class="col-sm-12">

                 <div class="panel" v-for="company in paginatedata">
                        <div class="panel-heading">
                          <div class="panel-title">
                            <div class="row">
                              <div class="col-sm-1">
                                  <a :href="'/back-company/add/'+company.id" class="btn btn-primary">Add branch</a>
                              </div>
                              <div class="col-sm-11">
                                <div class="text-center">
                                  <a class="text-center" style="text-transform:uppercase;" href="#"  @click.prevent="showtable(company.id)">
                                      {{company.name}}
                                  </a>
                                </div>

                              </div>

                            </div>
                            </div>

                           </div>

                        <div>
                          <div class="panel-body" v-if="company.id==currenttableid" >
                            <div role="alert" class="alert alert-success alert-dismissible fade in"  v-if="subloading"><button type="button" data-dismiss="alert" aria-label="Close" class="close"><span aria-hidden="true">×</span></button> <strong>Loading</strong></div>
                            <div role="alert" class="alert alert-success alert-dismissible fade in"  v-if="!subloading &&   nobranchfound"><button type="button" data-dismiss="alert" aria-label="Close" class="close"><span aria-hidden="true">×</span></button> <strong>No Branch found</strong></div>

                            <table class="table table-striped jambo_table" v-if="!subloading && !nobranchfound">
                                <thead>

                                  <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Location</th>
                                     <th>&nbsp;</th>
                                  </tr>
                                </thead>
                                <tbody>

                                 <tr v-for="branch in currentbranches" v-if="!noresult">
                                    <td>{{branch.id}}</td>
                                    <td >{{branch.name}}</td>
                                    <td>
                                      {{branch.location}}
                                    </td>
                                    <td>
                                      <a :href="'/back-company/'+branch.id+'/edit'" class="btn btn-primary">Edit Branch</a>
                                      <button class="btn btn-danger" type="button" @click="deleteitem(branch.id)">Delete</button>

                                    </td>

                                  </tr>
                              </tbody>
                           </table>

                          </div>
                        </div>
                      </div>
                    </div>
             </div>
         </div>

             </div>
           <div class="row" v-if="!noresult" >
             <div class="col-sm-5" v-if="!nodataonpage">
               <div class="dataTables_info" role="status" aria-live="polite">Showing {{from}} to {{to}} of {{totalNumber}} entries</div>
             </div>
               <div class="col-sm-7">
                 <div class="dataTables_paginate paging_simple_numbers" id="datatable_paginate">
                   <ul class="pagination" id="datatable_info" >
                     <li v-if="!haspreviouspage" class="paginate_button previous disabled" id="datatable_previous"><a href="#" aria-controls="datatable" data-dt-idx="0" tabindex="0">Previous</a></li>
                     <li v-if="haspreviouspage" class="paginate_button previous" @click="loadpreviouspage()"><a href="#" aria-controls="datatable" data-dt-idx="0" tabindex="0">Previous</a></li>
                     <li class="paginate_button "  v-if="currentpage>3"><a href="#" aria-controls="datatable" @click="loadpage(currentpage-2)">{{currentpage-2}}</a></li>
                     <li class="paginate_button "  v-if="currentpage>3"><a href="#" aria-controls="datatable" @click="loadpage(currentpage-1)">{{currentpage-1}}</a></li>
                     <li class="paginate_button active" ><a href="#" aria-controls="datatable" >{{currentpage}}</a></li>
                     <li class="paginate_button " v-if="currentpage<totallink-2"><a href="#" aria-controls="datatable" @click="loadpage(currentpage+1)" >{{currentpage+1}}</a></li>
                     <li class="paginate_button " v-if="currentpage<totallink-2"><a href="#" aria-controls="datatable" @click="loadpage(currentpage+2)">{{currentpage+2}}</a></li>
                     <li class="paginate_button next" id="datatable_next" v-if="hasnextlink"><a href="#" aria-controls="datatable" @click="loadnextpage()">Next</a></li>
                     <li class="paginate_button next disabled" id="datatable_next" v-if="!hasnextlink"><a href="#" aria-controls="datatable" >Next</a></li>
                </ul>
              </div>
            </div>
                   </div>

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

                           <strong>This will delete all the branch also</strong>
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
<style>

#fixtable{
  width: 55%;
}
#datatable_info{
margin-top: 0px !important;
}

.smthumb{
  max-width:25%;
}


#movesearchbar{
  margin-bottom: 10px !important;
}
</style>
<script>
export default {
  data(){
    return {
        searchurl:'/back-company-branch/search',
        paginateurl:'/back-company-branch/data',
        deleteurl:'/back-company/',
        loading:false,
        currentupdatedata:{},
        root:null,
        token:null,
        paginatedata:{},
        count:null,
        totalNumber:null,
        nextpage:null,
        prevpage:null,
        currentpage:null,
        haspreviouspage:false,
        hasnextlink:false,
        totallink:null,
        from:null,
        to:null,
        searchvalue:'',
        searchcount:'',
        searchon:false,
        searchResult:{},
        gopageno:null,
        resultcount:null,
        noresult:false,
        showdelete:false,
        deleteid:null,
        nodataonpage:false,
        categoryname:null,
        uploading:false,
        showupdateform:false,
        currenttableid:null,
        currentbranches:{},
        subloading:false,
        nobranchfound:false,
    }
  },
  methods:{
    showtable:function(id){

      this.nobranchfound=false;
      if(this.currenttableid==id){
            this.currenttableid=null;
      }else{
            this.currenttableid=id;
            this.subloading=true;
            $.ajax({
              url:'/back-company-branch/search/'+id,
              type:'get',
              success:(data)=>{
                     this.currentbranches=data.companies;
                    if(this.currentbranches.length>=1){
                    }else{
                        this.nobranchfound=true;
                    }
                    this.subloading=false;
              }
            })
      }

    },
    gotopage:function(){
      this.loadpage(this.gopageno);
    },
    loaddata:function(sendingurl){
      this.loading=true;
      $.ajax({
        url: sendingurl,
        type:'GET',
        success:(data)=>{
               this.managepaginatedata(data);
               this.loading=false;
               }
      });

    }
    ,loadnextpage:function(){
    this.loaddata(this.nextpage);

  },
  loadpreviouspage:function(){
    this.loaddata(this.prevpage);
  },
  loadpage:function(id){
var loadurl=this.paginateurl+'?page='+id;
this.loaddata(loadurl);
  },
  searchdata:function(searchdata){
      this.currenttableid=null;
        this.searchon=true;
          $.ajax({
            url: this.searchurl,
            type: 'GET',
            data: {
              search:searchdata
            },
            success:(data)=>{
            this.managepaginatedata(data);

            }

          });

},
managepaginatedata:function(data){
  this.nodataonpage=false;
  this.paginatedata=data.paginatedata.data;
  this.resultcount=data.paginatedata.data.length;
  if(this.resultcount<=0){
    this.noresult=true;
  }else{
    this.noresult=false;
  }
  this.totalNumber=data.paginatedata.total;
  this.nextpage=data.paginatedata.next_page_url;
  this.currentpage=data.paginatedata.current_page;
  this.from=data.paginatedata.from;
  this.to=data.paginatedata.to;
  this.totallink=data.paginatedata.last_page;
  if(this.currentpage!=1){
  this.prevpage=data.paginatedata.prev_page_url;
  this.haspreviouspage=true;
  }else{
    this.haspreviouspage=false;
  }

  if(this.currentpage==this.totallink){
    this.hasnextlink=false;
  }else{
    this.hasnextlink=true;
  }
},
confirmdelete:function(){
  let id=this.deleteid;
  $.ajax({
url:this.deleteurl+id,
type: 'DELETE',
data:{
  _token:this.token,
  id:this.deleteid
},
success:(data)=>{
  this.showdelete=false;
  let deleteId=data.id;
  let i=0;
  for(i;i<this.currentbranches.length;i++){
   let testi=this.currentbranches[i];
   if (testi.id==deleteId) {
     this.currentbranches.splice(i,1);
     if(this.currentbranches.length==0){
       this.nobranchfound=true;
     }
   }
 }
}


});

},
deleteitem:function(id){
  this.deleteid=id;
  this.showdelete=true;
},
  },

    mounted() {

      this.loaddata(this.paginateurl);
       this.root=this.$root.$root;
        this.token=this.root.csrfToken;

    },


}
</script>
