<template lang="html">
<div>
 <div class="row">
<div class="col-sm-3">
  <div class="form-group form-inline">
        <label for="something" >Online/Offline</label>
         <select class="form-control" style="width:100%;" v-model="internetConnection" @change="changeEVentType()">

          <option value="1">Online</option>
           <option value="0">Offline</option>
            </select>
    </div>

</div>
<div class="col-sm-3">
  <div class="form-group form-inline">
        <label for="something" >Event Type</label>
            <select class="form-control" style="width:100%;" v-model="currentEvent">
              <option v-for="event in events" :id="event.id" :value="event.id">{{event.name}}</option>
            </select>
    </div>

</div>
<div class="col-sm-3">
  <div class="form-group form-inline">
        <label for="exampleInputEmail1" >Format</label>
            <select class="form-control" style="width:100%;" @change="changedeck" v-model="currentformat">
             <option value="0">Select a format</option>
            <option v-for="format in formats" :value="format.id">{{format.name}}</option>
            </select>
    </div>

</div>
<div class="col-sm-3">
  <div class="form-group form-inline">
        <label for="exampleInputEmail1" style="width:100%;">
           <div class="row" >
             <div class="col-xs-3  col-md-3">Deck</div>
              <div class="col-xs-offset-7 col-md-offset-7 text-primary "><a href="#"  data-toggle="tooltip" title="Add a new Deck" @click="opendeckmodel()">Add a new Deck?</a></div>
           </div>
         </label>
            <select class="form-control" style="width:100%;" :disabled="formatloading" v-model="currentdeck" >
            <option value="0">Select a deck</option>
            <option :value="deck.slug" v-for="deck in decks">{{deck.version}}</option>
            </select>
    </div>
</div>
<div class="col-sm-4">
<div class="modal fade" id="adddeckmodal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-body">
<create-deck :test="test()"  :format_id="currentformat"></create-deck>

      </div>

       <div class="modal-footer">


        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>



</div>

<div id="myTournamentModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Start a new tournament</h4>
      </div>
      <form action="/tournament" method="get" >
      <input type="hidden" name="deck_id" :value="currentdeck"/>
      <input type="hidden" name="event_type" :value="currentEvent"/>
      <div class="modal-body">
         <div class="form-group">
       <label for="">Tournament Name</label>
       <input type="text" class="form-control" name="name" required>
      </div>
      <div class="form-group">
       <label for="">Tournament Size</label>
       <select name="size"  class="form-control">
       <option value="1">< 20 </option>
       <option value="2">21 - 50 </option>
       <option value="3">51 - 100 </option>
       <option value="4">100+ </option>
</select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary" >Start</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
      </form>
    </div>

  </div>
</div>



<div class="col-sm-12">
  <button class="btn btn-primary pull-right" @click="checkStatus()">Log a Match</button>

</div>
 </div>


 <div class="row">
<h2 class="page-header">Active Events</h2>
<div id="activeEvent">
</div>

</div>

</div>


</template>



<script>

    import createDeck from "../user/suggest-deck.vue";
export default {
 props:{
   game_id:null,
   format:null,
 },
  data(){
    return{
        hasActiveEvent:true,

    root:null,
    formatloading:true,
    formats:null,
    currentformat:0,
    decks:null,
    currentdeck:0,
    status:null,
    currentaddformat:0,
    currentadddeck:null,
    currentaddstyle:null,
    currentadddesc:null,
        currentVersion:null,
    internetConnection:1,
    events:null,
    currentEvent:0,
        seasons:null,
        season:0,
    onlineEvents:[
        {
           id:0,
           name:'Select a event'
        },
        {
          id:1,
          name:'Competitive League',

        },
        {
          id:2,
          name:'Friendly League',

        },
        {
          id:3,
          name:'Tournament',

         
        },
        {
          id:4,
          name:'Single Match',
         
        },
        { 
          id:5,
          name:'Practice Match',
        
        }
    ],
  offlineEvents:[
      {
        id:0,
        name:'Select a event'
      },
      {
      id:6,
      name:'League'
      },
      {
       id:7,
       name:'Tournament'
      },
      {
       id:8,
       name:'Singe Match'
      },
      {
        id:9,
        name:'Practice Match'
      }

  ]  


    }
  },
  methods:{
      viewActiveEvents () {
          axios.get("/continue/magic").then((response)=>{
          if(response.data.data==0){
              return;
          }

          document.getElementById("activeEvent").innerHTML=response.data.data;
          })

      },
    checkStatus(){
      if(this.currentEvent==0){
        this.root.showsuccess("Please select a event")
        return;
      }
      if(this.currentformat==0){
        this.root.showsuccess("Please select a format");
        return;
      }
      console.log(this.currentdeck);
        if(this.currentdeck===0){
          this.root.showsuccess("Please select a deck");
          return;
        }


     let postdata={
       formatid:this.currentformat,
       currentEvent:this.currentEvent,
       connection_type:this.internetConnection  
     };
      if(this.currentEvent==6||this.currentEvent==1||this.currentEvent==2){
          axios.post("/checkevent",postdata).then((data)=>{
              if(data.data.status==1){

                  //Redirects User to log a match page
                  window.location.href="/game/magic/"+this.currentdeck +"/start/"+this.currentEvent;
                  return;
              }

              if(data.data.status==0){
                  this.root.showsuccess(data.data.message);
                  return;

              }
          })
      }else if(this.currentEvent==3||this.currentEvent==7){
           //show form for tournament
          $("#myTournamentModal").modal("show")


      }else{
          window.location.href="/game/magic/"+this.currentdeck+'/start/'+this.currentEvent;
      }

    },
    changeEVentType(){
        if(this.internetConnection==0){
           this.events= this.offlineEvents;
        }else{
            this.events=this.onlineEvents;

        }
        this.currentEvent=0;
        this.currentformat=0;
        this.currentdeck=0;


    },
    changedeck:function(){





  this.$events.fire("format-changed",this.currentformat);

   axios.get("/user-format/"+this.currentformat).then((data)=>{
    this.formatloading=false;
   this.decks=data.data.decks;
   this.currentdeck=0;

   }).catch((error)=>{

   });
  },
    loaddata:function(){
   axios.get("/api/format-game?game=magic").then((response)=>{
       let data=response.data;
       this.formats=data.formats;
       this.currentformat=this.formats[0].id;


//  this.currentformat=data.data[0].id;
//  this.formats=data.data;
//  this.changedeck();
}).catch((error)=>{

})
        axios.get("/public/seasons?game=magic").then((data)=>{
  this.seasons=data.data.seasons;
  this.season=data.data.default;
}).catch((error)=>{

})
    },
    opendeckmodel:function(){
  $("#adddeckmodal").modal("show");
},
      test:function (){
          return ()=>{

          }
      },


      deckAdded(data){

       this.decks.push(data);
       $("#adddeckmodal").modal("hide");

      }
  },

  mounted(){
  this.loaddata();
  this.root=this.$root.$root;
  this.events=this.onlineEvents;
  this.viewActiveEvents();

this.$events.$on('deck-added',eventData=>this.deckAdded(eventData));


},
    components:{
      "create-deck":createDeck
    }

}
</script>

<style lang="css">
</style>
