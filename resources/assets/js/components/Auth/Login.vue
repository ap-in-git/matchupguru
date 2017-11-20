<template lang="html">
<div>
  <form class="form-horizontal">
  <fieldset>

  <div class="control-group">
    <label class="control-label" for="userid">Email:</label>
    <div class="controls">
      <input   v-validate="'required|email'" name="email" type="email" class="form-control input-medium" placeholder="johndoe@gmail.com" v-model="email" >
     <span v-show="errors.has('email')" >{{ errors.first('email') }}</span>
    </div>
  </div>

  <!-- Password input-->
  <div class="control-group">
    <label class="control-label" for="passwordinput">Password:</label>
    <div class="controls">
      <input v-validate="'required'"  name="password" class="form-control input-medium" type="password" placeholder="********" v-model="password" >
      <span v-show="errors.has('password')" >{{ errors.first('password') }}</span>
    </div>
  </div>


  <div class="control-group" style="margin-right:10px;">
    <label class="control-label" for="rememberme"></label>
    <div class="controls">
        <input type="checkbox" name="rememberme" id="rememberme-0" value="Remember me" style="margin:0px;" v-model="remember">
      Remember me

    </div>
  </div>

  <span v-if="verify" class="text-center" style="color:red;">Your email is not verified</span>
  <span v-if="wrong" class="text-center" style="color:red;">Username/Password doesn't match</span>
  <div class="control-group" style="margin-right:10px;">
    <label class="control-label" for="rememberme"></label>

    <div class="controls">
       <a href="/password/reset">Forget your password ?</a>
    </div>
  </div>

  <!-- Button -->
  <div class="control-group">
    <label class="control-label" for="signin"></label>
    <div class="controls">
      <button id="signin" name="signin" class="btn btn-success" @click.prevent="login">Sign In</button>
    </div>
  </div>
  </fieldset>
  </form>

</div>
</template>

<script>
export default {
  data(){
    return{
      email:'',
      password:'',
      remember:null,
      wrong:false,
      verify:false,

    }
  },
  methods:{
    login:function(){
      this.$validator.validateAll().then(result=>{




          if(result){
            this.verify=false;
            this.wrong=false;
            axios.post("/login",
            {

             email:this.email,
             password:this.password,
             remember:this.remember


            }).then((data)=>{
              if(data.data.login==0){
                this.verify=true;
              }else{
                if(data.data.login==1){
                  location.reload();

                }else{
                 this.wrong=true;
                }

              this.isregistering=false;
              }

            }).catch((error)=>{

              let data=error.response.data;
              this.wrong=true;
            });
          }


      })

    }
  }
}
</script>

<style lang="css">
</style>
