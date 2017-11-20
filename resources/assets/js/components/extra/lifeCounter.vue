<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">Life Counter</div>

                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <h4 class="page-header text-center">Player One</h4>
                                <div style="font-size: 150px; color:black;"  class="text-center">{{userOneTotal}}</div>
                                <div class="text-center">
                                    <button class="btn btn-primary" @click="goUp(1)">Up</button>
                                    <button class="btn btn-success" @click="goUp(2)">Down</button>
                                    <button class="btn btn-danger" @click="doReset(1)">Reset</button>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <h4 class="page-header text-center">Player Two</h4>
                                <div style="font-size: 150px; color:black;"  class="text-center">{{userTwoTotal}}</div>
                                <div class="text-center">
                                    <button class="btn btn-primary" @click="goUp(2)">Up</button>
                                    <button class="btn btn-success" @click="goUp(1)">Down</button>
                                    <button class="btn btn-danger" @click="doReset(2)">Reset</button>
                                </div>
                            </div>
                        </div>

                    </div>


                </div>
            </div>
        </div>
    </div>

</template>

<script>
    export default {
        data(){
            return {
                userOneTotal:20,
                userTwoTotal:20
            }
        },
        methods: {
           goUp(user){
               if(user===1){
                   if(this.userTwoTotal===0)
                       return;
                   this.userOneTotal++;
                   this.userTwoTotal--;

               }else {
                   if(this.userOneTotal===0)
                       return;

                   this.userTwoTotal++;
                   this.userOneTotal--;

               }



           },
        doReset(user){
            this.userOneTotal=20;
            this.userTwoTotal=20;

    }


        },
        mounted() {

        }
    }
</script>
