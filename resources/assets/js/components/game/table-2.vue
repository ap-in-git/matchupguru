<template lang="html">
<span>

<div class="card"  style="padding: 10px;">

<div class="row">

<div class="col-sm-10">
   <select class="form-control" v-model="viewStat">
    <option :value="1" >Match Status</option>
    <option :value="2" >History</option>
</select>

</div>
<div class="col-sm-2">
     <button class="btn btn-success btn-block" @click.prevent="getNewData">View</button>
</div>

<div class="clearfix"></div>

 <div class="col-sm-2">
     <div class="form-group">
       <label class="b-blue-border ">Data</label>
        <div class="radio radio-primary">
            <input type="radio" name="data" id="me" :value="1" v-model="data_type"  >
            <label for="me">
               Me
            </label>
        </div>
        <div class="radio radio-primary">
            <input type="radio" name="data"  id="all" :value="2" v-model="data_type" >
            <label for="all">
               All
            </label>
        </div>
        <div class="radio radio-primary">
            <input type="radio" name="data"  id="me-all" :value="3" v-model="data_type" >
            <label for="me-all">
               Me vs all
            </label>
        </div>
</div>
</div>
 <div class="col-sm-2">
     <div class="form-group">
       <label class="b-blue-border ">Online/Offline</label>
        <div class="radio radio-primary">
            <input type="radio" name="on_off" id="online" :value="1" v-model="internetConnection"  @change="changeEvent">
            <label for="online">
               Online
            </label>
        </div>
        <div class="radio radio-primary">
            <input type="radio" name="on_off"  id="offline" :value="0" v-model="internetConnection" @change="changeEvent">
            <label for="offline">
               Offline
            </label>
        </div>
        <div class="radio radio-primary">
            <input type="radio" name="on_off"  id="both" :value="2" v-model="internetConnection" @change="changeEvent">
            <label for="both">
                Both
            </label>
        </div>
</div>
</div>



 <div class="col-sm-2">
     <div class="form-group">
       <label class="b-blue-border ">Events Type</label>

       <span v-for="event in events " v-if="viewStat===1">
       <div class="checkbox" v-if="event.id!=0">
                        <input :id="'event_'+event.id" type="checkbox"  :value="event.id" @change="storeEventsType(event.id,event.checked)" v-model="event.checked">
                        <label :for="'event_'+event.id" >
                            {{event.name}}
                        </label>
                    </div>
</span>

</div>
       <select class="form-control" v-if="viewStat===2" v-model="currentEvent" >

         <option v-for="event in events" :value="event.id" v-if="event.id!=10&&event.id!=11">{{event.name}}</option>
</select>



</div>



 <div class="col-sm-2">
      <div class="form-group ">
       <label class="b-blue-border">Seasons</label>

           <span v-for="season in seasons ">
       <div class="checkbox">
                        <input :id="'season_'+season.id" type="checkbox"  v-model="season.checked" @change="storeSeason(season.id,season.checked)">
                        <label :for="'season_'+season.id" class="text-capitalize">
                            {{season.name}}
                        </label>
                    </div>
</span>
</div>


  </div>


   <div class="col-sm-2">
       <div class="form-group form-inline ">
        <label class="b-blue-border" >Format</label>
            <select class="form-control" style="width:100%;" @change="changeformat" v-model="currentFormat">

            <option v-for="format in formats" :value="format.id">{{format.name}}</option>
            </select>
    </div>
</div>

   <div class="col-sm-2">
       <div class="form-group form-inline ">
        <label class="b-blue-border" >Deck</label>
            <select class="form-control" style="width:100%;" v-model="currentDeck" @change="getVersions">
              <option v-for="deck in decks" :value="deck.name">{{deck.name}}</option>

            </select>
    </div>




</div>

  <div class="col-sm-12">

       <label class="b-blue-border">Versions</label>
       <multiselect v-model="selected_versions" :options="options" :multiple="true" group-values="decks" group-label="season" placeholder="Type to search" track-by="id" label="name"><span slot="noResult">Oops! No elements found. Consider changing the search query.</span></multiselect>

  </div>
</div>




</div>




<div class="card">



<div class="row">
<div class="col-sm-offset-5" v-if="data_loading"><div class="loader"></div></div>
<div id="stat">
</div>


<div class="col-sm-12" v-if="table_data_type==1||table_data_type==2">
     <table class="table table-responsive table-bordered">
     <thead>
      <tr>
        <th>Deck Name</th>
        <th> Deck Version</th>
        <th>Opposing Deck</th>
        <th>Opposing Deck Version</th>
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
          <td>{{match.deck}}</td>
          <td>{{match.version}}</td>
          <td>{{match.opposing_deck_name}}</td>
          <td>{{match.opposing_deck_version}}</td>
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

<div class="col-sm-12" v-if="table_data_type==3">
      <table class="table table-responsive table-bordered">
     <thead>
      <tr>
        <th>Deck Name</th>
        <th>Deck Version</th>
        <th >Opposing Deck</th>
        <th>Opposing Deck Version</th>
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
     <td>Me</td>
     <td>All</td>
      </tr>
       <tr v-for="match in matches_result">
         <td>{{match.deck}}</td>
         <td>{{match.version}}</td>
         <td>{{match.opp_deck_name}}</td>
         <td>{{match.opp_deck_version}}</td>
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
</div>






</div>

</span>
</template>

<script>
    import matchEvents from "../../data/matchEvent"
    import matchViews from "../../data/matchView"
    import Multiselect from 'vue-multiselect'
export default {
 props:{
   game_id:null,
   format:null,
 },
    components: {
        Multiselect
    },
  data(){
    return{
        viewStat:1,
    root:null,
    formats:null,
    currentFormat:0,
    decks:[],
        currentDeck:0,
    status:null,
    matches_result:[],
    data_type:2,
        currentEvent:0,
        internetConnection:1,
       message:null,
        events:[],
        onlineEvents:matchEvents.onlineEventsDev,
        offlineEvents:matchEvents.offlineEventsDev,
        bothEvents:matchEvents.bothEventsDev,
        currentViews:[],
        seasons:[],
        currentSeason:null,
        versions:[],
        currentVersion:null,
        options:[],
        selected_versions:[],
        selected_events:[],
        selected_seasons:[],
        table_data_type:null,
        data_loading:false,


    }
  },
    computed:{
        eventsArray:function(){
            return this.events.filter(function (l) {

                if(l.id!==11||l.id!==10)
                return l.checked
            }).map(function (l) {

                return l.id
            })
        }  ,    seasonsArray:function(){
            return this.seasons.filter(function (s) {
                return s.checked
            }).map(function (s) {

                return s.id
            })
        }


    },
  methods:{
      changeformat:function(){
          axios.get("/api/deck-format-group?format="+this.currentFormat).then((response)=>{

              this.decks=response.data.decks;
              this.currentDeck=this.decks[0].name;
              this.getVersions();
          }).catch((error)=>{
          });

      },
      getVersions:function (){
          axios.get("/api/deck-format-version-season?name="+this.currentDeck).then((response)=>{
             let data=response.data;
              this.options=data.decks;
          })


      },
      changeEvent(){
          if(this.internetConnection===1){
              this.events=this.onlineEvents;
          }else if(this.internetConnection===0){
              this.events=this.offlineEvents;
          }else{
              this.events=this.bothEvents;
          }
       this.storeEventsType(11,true);


      },
      changeEventType(){


          if(this.internetConnection==0){
              this.events= this.offlineEvents;
          }else{
              this.events=this.onlineEvents;

          }
          this.getData();
          this.currentEvent=0;

      },
      storeEventsType(id,checked){
                  if(id===11){
                      this.selected_events=[];

            for(let i=0;i<this.events.length;i++){
                if(this.events[i].id!==11){
                    this.events[i].checked=false;
                }

            }
                  }else if(id===10){
                      this.selected_events=[];
                   if(checked===true){

              for(let i=0;i<this.events.length;i++){

                if(this.events[i].id!==11){

                    this.events[i].checked=true;
                }else{
                    this.events[i].checked=false;
                }
                if(this.events[i].id!==11&&this.events[i].id!==10){
                    this.selected_events.push(this.events[i].id)
                }


              }


                   }

            }else{
                      if(checked===true){
                          for(let i=0;i<this.events.length;i++){
                              if(this.events[i].id===11){
                                  this.events[i].checked=false;
                              }


                          }
                      }else{
                          for(let i=0;i<this.events.length;i++){
                              if(this.events[i].id===11||this.events[i].id===10){
                                  this.events[i].checked=false;
                              }


                          }

                      }



                  }





      },

      storeSeason(id,checked){

          if(checked===true){
             if(id===0){

                 for(let i=0;i<this.seasons.length;i++){
                     this.seasons[i].checked=true;

                 }
             }

          }else{
              for(let i=0;i<this.seasons.length;i++){
                  if(this.seasons[i].id===0){
                      this.seasons[i].checked=false;
                  }


              }

          }


          },

      getNewData(){


          this.table_data_type=0;
          this.matches_result={};
          document.getElementById("stat").innerHTML="";

          if(this.viewStat===1){
              if(this.eventsArray.length===0)
              {
                  this.root.showsuccess("Please select a event type")
                  return
              }else{
                  for(let i=0;i<this.eventsArray.length;i++){

                      if(this.eventsArray[i]===11){
                          this.root.showsuccess("Please select a event type")
                          break
                      }else if(this.eventsArray[i]===10){
                          this.eventsArray.splice(i,1)
                          break;
                      }else{

                      }

                  }
              }

              if(this.seasonsArray.length===0){
                  this.root.showsuccess("Please select season")
                  return;
              }else{
                  for(let i=0;i<this.seasonsArray.length;i++){
                      if(this.seasonsArray[i]===0){
                          this.seasonsArray.splice(i,1)
                      }
                  }
              }


              if(this.selected_versions.length===0)
              {
                  this.root.showsuccess("Please select the versions")
                  return
              }
              let sending_versions=this.selected_versions.map( (m) =>{
                  return m.id;
              })

              this.data_loading=true;


              axios.post("/user/match/data",{
                  events:this.eventsArray,
                  seasons:this.seasonsArray,
                  versions:sending_versions,
                  data_type:this.data_type
              }).then((response)=>{
                  this.data_loading=false;
                  let status=response.data.match_data.status;
                  if(status===0)
                  {
                      this.root.showsuccess(response.data.match_data.Message)
                      return;


                  }

                  this.table_data_type=response.data.data_type;
                  this.matches_result=response.data.match_data.data;


              })

          }

          if(this.viewStat===2){
              if(this.currentEvent===0){
                  this.root.showsuccess("Please select a event ")
                  return;
              }
              if(this.seasonsArray.length===0){
                  this.root.showsuccess("Please select season")
                  return;
              }else{
                  for(let i=0;i<this.seasonsArray.length;i++){
                      if(this.seasonsArray[i]===0){
                          this.seasonsArray.splice(i,1)
                      }
                  }
              }


              if(this.selected_versions.length===0)
              {
                  this.root.showsuccess("Please select the versions")
                  return
              }
              let sending_versions=this.selected_versions.map( (m) =>{
                  return m.id;
              })

              this.data_loading=true;

              axios.post("/user/match/view",{
                  event:this.currentEvent,
                  seasons:this.seasonsArray,
                  versions:sending_versions,
              }).then((response)=>{
                   this.data_loading=false;
                   document.getElementById("stat").innerHTML=response.data.view;
              }).catch((error)=>{
                  this.data_loading=false;
//                  this.root.showsucces("Something went wrong")

              })


          }




      },



    loaddata:function(){
   axios.get("/api/format-game?game=magic").then((data)=>{
  this.currentFormat=0;
  this.formats=data.data.formats;
   }).catch((error)=>{
})
        var self=this;
        axios.get("/public/seasons?game=magic").then((response)=>{
               let data=response.data;
               let unformatted_season=data.seasons;
               let formatted_season=[{
                   id:0,
                   name:"All seasons",
                   checked:true,
               }];
               for(let i=0;i<unformatted_season.length;i++){
                                    let new_season={
                      id: unformatted_season[i].id,
                      name: unformatted_season[i].name,
                      checked: true
                  };
                   formatted_season.push(new_season);
               }

              this.seasons=formatted_season;




        })


      }


  },
  mounted(){
  this.loaddata();
    this.currentView=1;
    this.data_type=0;
    this.root=this.$root.$root;

this.events=this.onlineEvents;
this.storeEventsType(10,true)
      this.data_type=1;





}

}
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<style lang="css">
    .loader {
        border: 16px solid #f3f3f3;
        border-radius: 50%;
        border-top: 16px solid blue;
        border-right: 16px solid green;
        border-bottom: 16px solid red;
        border-left: 16px solid pink;
        width: 120px;
        height: 120px;
        -webkit-animation: spin 2s linear infinite;
        animation: spin 2s linear infinite;
    }

    @-webkit-keyframes spin {
        0% { -webkit-transform: rotate(0deg); }
        100% { -webkit-transform: rotate(360deg); }
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

</style>
