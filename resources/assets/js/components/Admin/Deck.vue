<template>
  <div>
<div class="row">
  <div class="col-sm-4">
 <div class="btn btn-primary" v-if="formatChanged" @click="viewdecks()">All decks</div>

</div>
  <div class="col-sm-4">
    <label >Filter By Game</label>
 <select class="form-control" style="width:100%;">
  <option value="0">Magic</option>
 </select>
</div>


  <div class="col-sm-4">
    <label >Filter By Format</label>
 <select class="form-control" style="width:100%;" v-model="currentformat" @change="changedeck()">

   <option value="0">Select a format</option>
   <option v-for="format in formats" :value="format.id" >{{format.name}}</option>
 </select>

  </div>
</div>
<div id="deckg" style="margin-top:4%;">

</div>
          </div>

</template>

<script>
export default {
  props:{
    defaultdeck:null,

  },
  data(){
    return {
      loading:false,
      formats:null,
      games:null,
      currentformat:null,
      root:null,
      formatChanged:false,
    }
  },

  methods:{

 changedeck:function(){
   this.formatChanged=true;
   $("#deckg").html("");
   $("#err").hide();
axios.get("/admin/deck/format/"+this.currentformat).then((data)=>{
if(data.data==0){
  this.root.showsuccess("No result Found");
  return;
}else{
  $("#deckg").html(data.data);
}

}).catch((error)=>{

});
 },
loaddata:function(){

 let data=axios.get("/format/game/1", {
  })
  .then((response)=> {
    this.currentformat=0;
   this.formats=response.data;
  })
  .catch((error)=> {
  });

},
viewdecks:function(){
    $("#deckg").html("");
    $("#err").show();
}
  }
  ,

    mounted() {
       this.loaddata();
       this.root=this.$root.$root;
    },


}
</script>
