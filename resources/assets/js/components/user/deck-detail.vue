<template>
    <div>
       <div class="row">
          <div class="col-sm-6">
              <div class="panel panel-primary">
                  <div class="panel-heading">
                      Deck Details
                  </div>
                  <div class="panel-body">
                      <ul class="list-group">
                          <li class="list-group-item text-primary">Deck Name : {{deck.name}}</li>
                          <li class="list-group-item text-primary">Version : {{deck.version}}</li>
                          <li class="list-group-item text-primary">Format : {{deck.format}}</li>
                          <li class="list-group-item text-primary">Season : {{deck.season}}</li>
                          <li class="list-group-item text-primary">Style : {{deck.style}}</li>
                          <li class="list-group-item text-primary">Description : {{deck.descrption}}</li>
                      </ul>
                  </div>
              </div>
          </div>
           <div class="col-sm-6">
               <div class="panel panel-default">
                   <div class="panel-heading">Suggest better for this deck</div>
                   <div class="panel-body">
                       <div class="form-group">
                           <label for="season">Season</label>
                           <select class="form-control" id="season" v-model="currentSeason" name="season">
                               <option v-for="season in seasons" :value="season.id" >{{season.name}}</option>
                           </select>
                       </div>
                       <div class="form-group">
                           <label for="format">Format</label>
                           <select name="" id="format" class="form-control" v-model="currentFormat" name="format">
                               <option v-for="format in formats" :value="format.id">{{format.name}}</option>
                           </select>
                       </div>

                       <div class="form-group">
                           <label for="deck">Deck Name</label>
                           <input type="text" class="form-control" id="deck" v-model="deckName" name="deck">
                       </div>
                       <div class="form-group">
                           <label for="version">Version</label>
                           <input type="text" class="form-control" id="version" v-model="deckVersion" :value="deck.version" name="version" >
                       </div>
                       <div class="form-group">
                           <label for="style">Style</label>
                           <input type="text" class="form-control" v-model="deckStyle" id="style" name="style">
                       </div>
                       <div class="form-group">
                           <label for="description">Description</label>
                           <input type="text" class="form-control" v-model="deckDescription" id="description" name="description">
                       </div>

                       <button class="btn btn-primary pull-right" @click="suggestBetter()">Suggest</button>

                   </div>

               </div>
           </div>
       </div>





    </div>

</template>

<script>

    export default {
        props:{
            slug:null,
            token:null,


        },
        data(){
            return {
                deck:[],
                seasons:[],
                currentSeason:null,
                formats:[],
                currentFormat:null,
                deckName:null,
                deckVersion:null,
                deckStyle:null,
                deckDescription:null,
                root:null

            }
        },
        methods: {
            findFormat(){
                let formats=this.formats;
                for(let i=0;i<formats.length;i++){

                    if(formats[i].name.toLowerCase()===this.deck.format.toLowerCase()){
                       this.currentFormat=formats[i].id;
                    }

                }
            },
            suggestBetter(){
                let deck={
                    name:this.deckName,
                    season:this.currentSeason,
                    format:this.currentFormat,
                    version:this.deckVersion,
                    style:this.deckStyle,
                    description:this.deckDescription
                }
                axios.post("/api/suggest",deck).then((response)=>{
                  let data=response.data;
                  console.log(data.status);

                  if(data.status===0){
                      this.root.showsuccess(data.message)



                  }else{

                      this.root.showsuccess(data.message)
                      setTimeout(function () {
                          console.log(23);
                          window.location.replace("/magic-decks");
                      },500)




                  }

                })
            }
        },
        mounted() {
              axios.get("/api/deck/"+this.slug+"?detailed=true").then((response)=>{
                 this.deck=response.data.deck;
                 this.deckName=this.deck.name;
                 this.deckVersion=this.deck.version;
                 this.deckStyle=this.deck.style;
                 this.deckDescription=this.deck.description;


              })

            axios.get("/public/seasons?game=magic").then((response)=>{
                  this.seasons=response.data.seasons;
                  this.currentSeason=response.data.default


            })

            axios.get("/api/format-game?game=magic").then((response)=>{
                  this.formats=response.data.formats;
                  this.currentFormat=this.formats[0].id;
                  this.findFormat()
            })

            this.root=this.$root.$root;





        }
    }
</script>
