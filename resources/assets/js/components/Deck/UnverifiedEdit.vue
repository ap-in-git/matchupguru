<template lang="html">
<div>
<div class="form-group">
  <label for="game">Game</label>
  <select class="form-control" name="game" @change=changeformat() :disabled="loading"  v-model="currentgame" >
      <option v-for="game in games" :value="game.id" >{{game.name}}</option>
  </select>
</div>
<div class="form-group">
  <label for="game">Format</label>
  <select class="form-control" name="format" :disabled="loading"  v-model="currentformat" @change="changeDeckWithSeasonAndFormat">
      <option v-for="format in formats" :value="format.id" >{{format.name}}</option>
  </select>
</div>
<div class="form-group">
  <label for="game">Season</label>
  <select class="form-control" name="season" :disabled="loading"  v-model="season" @change="changeDeckWithSeasonAndFormat">
      <option v-for="s in seasons" :value="s.id" >{{s.name}}</option>
  </select>
</div>
<div class="form-group">
  <label for="deck">Deck</label>
  <select class="form-control" name="deck" :disabled="loading"  v-model="currentdeck" @change="getVersion()">
      <option value="0">Select a deck</option>
      <option v-for="deck in decks" :value="deck.name" >{{deck.name}}</option>
  </select>
</div>
<div class="form-group">
  <label for="deck">Version</label>
  <select class="form-control" name="version" :disabled="loading"  >
      <option value="0">Select a version</option>
      <option v-for="version in versions" :value="version.slug" >{{version.name}}</option>
  </select>
</div>


</div>
</template>

<script>
export default {
  props:{
    gameurl:null,
    formaturl:null,
    defaultgame:null,
    defaultformat:null,

  },
  data(){
    return{
      games:null,
      formats:null,
      loading:true,
      currentgame:null,
      currentformat:null,
      currentdeck:0,
      first:0,
      decks:[],
        season:null,
        seasons:null,
        versions:[],
        currentVersion:null
    }
  },
  methods:{
    loadgamedata:function(id){
     axios.get(this.gameurl).then((data)=>{
       this.games=data.data;


       this.currentgame=id;


       this.changeformat();``

     }).catch((error)=>{

     });
     axios.get("/public/seasons?game=magic").then((data)=>{
       this.seasons=data.data.seasons;
       this.season=data.data.default;

     }).catch((error)=>{

     });

     this.loading=false;
   },

      getVersion(){
          axios.get("/api/deck-format-version?name="+this.currentdeck+"&season="+this.season).then((response)=>{
              let data=response.data;
              this.versions=data.decks;

              this.currentVersion=this.versions[0].slug;
          })

      },
      changeDeckWithSeasonAndFormat:function () {
          axios.get("/api/deck-format-group?format="+this.currentformat+"&season="+this.season).then((response)=>{
              let data=response.data;
              this.decks=data.decks;


          });

      },
   changeformat:function(){
     this.loading=true;
     let url=this.formaturl+this.currentgame;
   axios.get(url).then((data)=>{
          this.formats=data.data;
    if(this.first==0){
      if(this.defaultformat==null){
       let kehi=data.data[0];
       this.currentformat=kehi.id;
       this.first++;

      }else{
       this.currentformat=this.defaultformat;
       this.first++;
      }
    }

      this.changeDeckWithSeasonAndFormat();
     this.loading=false;
}).catch((error)=>{

   this.loading=false;
   });
 }

  },
  mounted(){
    if(this.defaultgame==null){
      this.loadgamedata(1);

    }else{
      this.loadgamedata(this.defaultgame);


    }

  }
}
</script>

<style lang="css">
</style>
