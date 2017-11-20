<template>
    <div>
<create-deck :verified="1"></create-deck>
    </div>

</template>

<script>
    import createDeck from "../../user/suggest-deck.vue";
    export default {
        data(){
            return {}
        },
        methods: {},
        mounted() {

            this.$events.$on('deck-added',eventData=>{
                this.$root.$root.showsuccess("Deck added successfully")
                window.location.href="/admin/deck"
            });

        },
        components:{
            "create-deck":createDeck

        }
    }
</script>
