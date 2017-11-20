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
  <select class="form-control" name="format" :disabled="loading"  v-model="currentformat">
      <option v-for="format in formats" :value="format.id" >{{format.name}}</option>
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
      first:0,
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
     this.loading=false;
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
