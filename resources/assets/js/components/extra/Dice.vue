<template>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-primary">
                    <div class="panel-heading">DICE</div>

                    <div class="panel-body">
                        <div style="font-size: 200px; color:black;"  class="text-center">{{diceOne}} &nbsp;{{diceTwo}}</div>

                       <div class="text-center">
                           <button class="btn btn-success text-center" @click="rollDice">Roll Dice </button>
                       </div>
                    </div>


                </div>
            </div>
        </div>

</template>

<script>
    export default {
        data(){
            return{
                diceOne:'\u2680',
                diceTwo:'\u2680'
            }
        },
        methods:{
            rollDice(){
                var dice = ['\u2680', '\u2681', '\u2682', '\u2683', '\u2684', '\u2685'];
               this.diceOne= dice[Math.floor(Math.random()*6)];
               this.diceTwo= dice[Math.floor(Math.random()*6)];
            }
        },
        mounted() {

        }
    }
</script>
