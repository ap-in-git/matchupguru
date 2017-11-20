<template>
    <div>
        <div class="row">
            <div class="col-sm-6">
                <h5 class="page-header">Add a new deck</h5>
                    <div class="form-group">
                        <label for="season">Season</label>
                        <select class="form-control" id="season" v-model="currentSeason">
                          <option v-for="season in seasons" :value="season.id">{{season.name}}</option>
                        </select>

                    </div>
                    <div class="form-group">
                        <label for="format">Format</label>
                        <select class="form-control" id="format" v-model="currentFormat">
                          <option v-for="format in formats" :value="format.id">{{format.name}}</option>
                        </select>

                    </div>
                   <div class="form-group">
                       <label for="deck">Deck Name</label>
                       <input type="text" id="deck" class="form-control" v-model="deckName" placeholder="E.g Tron">
                   </div>
                    <div class="form-group">
                       <label for="version">Version</label>
                       <input type="text" id="version" class="form-control"  v-model="deckVersion" placeholder="E.g. Eldrazi Tron">
                   </div>
                    <div class="form-group">
                       <label for="style">Style</label>
                       <input type="text" id="style" class="form-control"  v-model="deckStyle" placeholder="E.g. Combo/Control">
                   </div>
                   <div class="form-group">

                       <label for="description">Description</label>
                       <textarea v-model="deckDescription" id="description" class="form-control" placeholder="Description"></textarea>
                   </div>

                    <button type="button" class="btn btn-primary" @click.prevent="createDeck()">Create Deck</button>



            </div>
            <div class="col-sm-6">
                <h5 class="page-header text-center">Copy from previous season</h5>
                <div class="form-group">
                    <label for="seasoncopy">Season</label>
                    <select class="form-control" id="seasoncopy" v-model="currentCopySeason" @change="getDeckFromFormat()">
                        <option v-for="season in seasons" :value="season.id">{{season.name}}</option>
                    </select>

                </div>
                <div class="form-group">
                    <label for="formatcopy">Format</label>
                    <select class="form-control" id="formatcopy" v-model="currentFormat" @change="getDeckFromFormat()">
                        <option v-for="format in formats" :value="format.id">{{format.name}}</option>
                    </select>

                </div>
                <div class="form-group">
                    <label for="deckcopy">Deck</label>
                    <select class="form-control" id="deckcopy" v-model="currentDeck"  @change="getVersion()" >
                        <option v-for="deck in decks" :value="deck.name">{{deck.name}}</option>
                    </select>

                </div>
                <div class="form-group">
                    <label for="deckcopy">Version</label>
                    <select class="form-control" id="deckversion" v-model="currentVersion"   >
                        <option v-for="version in versions" :value="version.slug">{{version.name}}</option>
                    </select>

                </div>
                <button class="btn btn-primary" @click="copyDeck()">Copy Deck </button>

            </div>

        </div>
    </div>

</template>

<script>
    export default {
        props:['token','verified','test','format_id'],
        data(){
            return {
                deckName:null,
                deckVersion:null,
                deckStyle:null,
                deckDescription:null,
                seasons:[],
                currentSeason:null,
                formats:[],
                currentFormat:null,
                decks:null,
                currentDeck:null,
                currentCopySeason:null,
                root:null,
                is_verified:0,
                currentVersion:null,
                versions:[]


            }
        },
        methods: {
            loadFormat(){
                axios.get("/api/format-game?game=magic").then((response)=>{
                    let data=response.data;
                    this.formats=data.formats;
                    this.currentFormat=this.formats[0].id;
                    this.getDeckFromFormat();


                })



            },
            getVersion(){
                axios.get("/api/deck-format-version?name="+this.currentDeck+"&season="+this.currentCopySeason).then((response)=>{
                    let data=response.data;
                    this.versions=data.decks;

                    this.currentVersion=this.versions[0].slug;
                })

            },
            getDeckFromFormat(){
                axios.get("/api/deck-format-group?format="+this.currentFormat+"&season="+this.currentCopySeason).then((response)=>{
                    let data=response.data;
                    this.decks=data.decks;
                    this.currentDeck=this.decks[0].name;
                    this.getVersion();
                });


            },

            copyDeck(){
                axios.get("/api/deck/"+this.currentVersion).then((response)=>{
                    let deck=response.data.deck;
                    this.deckName=deck.name;
                    this.deckVersion=deck.version;
                    this.currentFormat=deck.format_id;
                    this.deckDescription=deck.description;


                })
            },
            createDeck(){




                if(this.currentFormat==0){

                    this.root.showsuccess("Please select a Format")
                    return;
                }

                if(this.currentSeason==0){
                    this.root.showsuccess("Please select a season")
                    return;
                }
                if(this.deckName==null){
                    this.root.showsuccess("Please insert a deck!!!")
                    return;
                }

                if(this.deckVersion==null){
                    this.root.showsuccess("Please insert a version")
                    return;
                }

                axios.post("/user/deck/add",{
                    deck:this.deckName,
                    format:this.currentFormat,
                    season:this.currentSeason,
                    style:this.deckStyle,
                    description:this.deckDescription,
                    version:this.deckVersion,
                    verified:this.is_verified
                }).then((response)=>{
                    if(response.data===0){
                        this.root.showsuccess("Deck already exist")
                        return;
                    }

                    this.$events.fire("deck-added",response.data)

                })
            }

        },
        mounted() {

            this.currentFormat=this.format_id;
            this.root=this.$root.$root;
            axios.get("/public/seasons?game=magic").then((response)=>{
                let data=response.data;
                this.seasons=data.seasons;
                this.currentSeason=data.default;
                this.currentCopySeason=data.default;
                 this.loadFormat();
            })

            if(this.verified==1){
                this.is_verified=1;
            }

            this.$events.$on('format-changed',eventData=>{
                this.currentFormat=eventData;
                this.getDeckFromFormat();
            });










        }
    }
</script>
