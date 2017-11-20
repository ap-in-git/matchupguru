<template lang="html">
<div class="row">
  <div class="col-sm-3">
  <div class="form-group form-inline">
            <label for="exampleInputEmail1" >View</label>
                <select class="form-control" style="width:100%;" @change="changeview()" v-model="view">
              <option value="0">Active  League Data</option>
               <option value="1">Matchup Stats</option>
               <option value="2">All league</option>
                </select>
        </div>
</div>

<div class="col-sm-3">
  <div class="form-group form-inline">
        <label for="exampleInputEmail1">Data</label>
            <select class="form-control" style="width:100%;" :disabled="view!=1" @change="othercalculation()" v-model="match_other">
            <option value="0">Only Me</option>
            <option value="1">Me vs all player</option>
            <option value="2">All player</option>

            </select>
    </div>
</div>

 <div class="col-sm-3">
  <div class="form-group form-inline">
        <label for="exampleInputEmail1" >Format</label>
            <select class="form-control" style="width:100%;" :disabled="view!=1" @change="changedeck" v-model="currentformat">
              <option value="0">Select a format</option>
            <option v-for="format in formats" :value="format.id">{{format.name}}</option>
            </select>
    </div>

</div>
<div class="col-sm-3">
  <div class="form-group form-inline">
        <label for="exampleInputEmail1">Deck</label>
            <select class="form-control" style="width:100%;"  :disabled="formatloading" v-model="currentdeck" @change="changvalue()">
            <option value="0">Select a deck</option>
            <option :value="deck.id" v-for="deck in decks">{{deck.name}}</option>
            </select>
    </div>
</div>

<div id="stat">

</div>

<div class="col-sm-12" v-if="view_no">
<div class="alert alert-info alert-dismissible" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
  <strong>Info !</strong>Please select a format and deck

 </div>
 </div>

 <div class="col-sm-12" v-if="showmatchstatus">
   <table class="table table-responsive table-bordered">
     <thead>
      <tr>
        <th>Opposing Deck</th>
        <th>Wins</th>
        <th>Loss</th>
        <th>Match win</th>
        <th>Game win</th>
        <th>Play,pre-board</th>
        <th>Draw,pre-board</th>
        <th>Play,post-board</th>
        <th>Draw,post-board</th>
      </tr>
     </thead>
     <tbody>
       <tr v-for="match in matches_result">
         <td>{{match.opposing_deck}}</td>
         <td>{{match.wins}}</td>
         <td>{{match.loss}}</td>
         <td>{{match.match_win}} %</td>
         <td>{{match.game_win}}  %</td>
         <td>{{match.play_pre}} %</td>
         <td>{{match.draw_pre}} %</td>
         <td>{{match.play_post}} %</td>
         <td>{{match.draw_post}} %</td>
       </tr>

     </tbody>
   </table>
</div>
 <div class="col-sm-12" v-if="showmevsall">
   <table class="table table-responsive table-bordered">
     <thead>
      <tr>
        <th >Opposing Deck</th>
        <th >Wins</th>
        <th>Loss</th>
        <th colspan="2">Match win</th>
        <th colspan="2">Game win</th>
        <th colspan="2">Play,pre-board</th>
        <th colspan="2">Draw,pre-board</th>
        <th colspan="2">Play,post-board</th>
        <th colspan="2">Draw,post-board</th>
      </tr>

     </thead>
     <tbody>
      <tr>
     <td> &nbsp;    </td>
     <td> &nbsp;    </td>
     <td>&nbsp;</td>
     <td>Me</td>
     <td>All</td>
     <td>Me</td>
     <td>All</td>
     <td>Me</td>
     <td>All</td>
     <td>Me</td>
     <td>All</td>
     <td>Me</td>
     <td>All</td>
     <td>Me</td>
     <td>All</td>
      </tr>
       <tr v-for="match in mevsall_match_status">
         <td>{{match.opposing_deck}}</td>
         <td>{{match.wins}} </td>
         <td>{{match.loss}}</td>
         <td>{{match.match_win_me}} %</td>
        <td>{{match.match_win_all}} %</td>
         <td>{{match.game_win_me}} %</td>
         <td>{{match.game_win_all}} %</td>
         <td>{{match.play_pre_me}} %</td>
         <td>{{match.play_pre_all}} %</td>
         <td>{{match.draw_pre_me}} %</td>
         <td>{{match.draw_pre_all}} %</td>
         <td>{{match.play_post_me}} %</td>
         <td>{{match.play_post_all}} %</td>
         <td>{{match.draw_post_me}} %</td>
         <td>{{match.draw_post_all}} %</td>
       </tr>

     </tbody>
   </table>
</div>
</div>
</template>

<script>
export default {
 props:{
   game_id:null,
   format:null,
 },
  data(){
    return{
    root:null,
    formatloading:true,
    formats:null,
    currentformat:0,
    decks:null,
    currentdeck:0,
    status:null,
    no_result:true,
    view:1,
    view_no:false,
    showmatchstatus:false,
    matches_result:null,
    showmevsall:false,
    mevsall_match_status:null,
    match_other:2,

  }
  },
  methods:{
    changvalue:function(){
      this.showmevsall=false;

      if(this.currentdeck==0)
      return;

      if(this.match_other!=0)
      {this.othercalculation();
      return;}

       $("#stat").html("");
       this.showmatchstatus=false;

       if(this.view==0||this.view==2){
        this.root.showsuccess("You must sign in to update the view your match status")

       }

      if(this.view==1){
           this.othercalculation();


      }
  },

  othercalculation:function(){

    this.showmatchstatus=false;
     this.showmevsall=false;
  if(this.currentformat==0){
    this.root.showsuccess("Please select a format ")
    return;
  }

  if(this.currentdeck==0) {
     this.root.showsuccess("Please select a deck")
     return;
   }

  if(this.match_other==0){
  this.root.showsuccess("You must sign in to view match status")
  return;
  }
  if(this.match_other==1){
  this.root.showsuccess("You must sign in to view match status")
  return;
  }


  if(this.match_other==2){
    axios.get("/matchstat-all/1/"+this.currentdeck).then((data)=>
        {
          if(data.data==0){
            this.root.showsuccess("No result found")
            this.view_no=true;
            return;
          }
          this.showmatchstatus=true;
          this.view_no=false;
          this.matches_result=data.data;


        }).catch((error)=>{

        })

  }

  if(this.match_other==1){
 return;

  }


  },

    changedeck:function(){
  this.showmevsall=false;
      this.showmatchstatus=false;
  if(this.currentformat==0){
    this.formatloading=true;
    this.currentdeck=0;
     return;
  }


   axios.get("/guest/format/"+this.currentformat).then((data)=>{
    this.formatloading=false;
   this.decks=data.data.decks;
   this.currentdeck=0;
}).catch((error)=>{
});
  },
    loaddata:function(){
   axios.get("/guest/format/game/1").then((data)=>{
  this.currentformat=0;
  this.formats=data.data;
   }).catch((error)=>{
})},
    changeview:function(){
      this.showmatchstatus=false;
      this.currentformat=0;
      this.currentdeck=0;
      this.formatloading=true;
      this.showmevsall=false;
        $("#stat").html("");
      if(this.view==0){
      this.root.showsuccess("You must sign in to view leagues")
      return;
      }

      if(this.view==2)
      {
        this.root.showsuccess("You must sign in to view leauges")
        return;
      }

  }
  },
  mounted(){
  this.loaddata();
  // this.loadleague(0);

  this.root=this.$root.$root;

}

}
</script>

<style lang="css">
</style>
