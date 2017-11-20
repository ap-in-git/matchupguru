  <template lang="html">
  <div>
    <form class="form-horizontal" id="registerform" >
    <fieldset>
    <!-- Sign Up Form -->
    <!-- Text input-->
    <div class="control-group">
      <label class="control-label" for="Name">Name <span>*</span>:</label>
      <div class="controls">
          <input v-validate="'required|min:2|max:255'" :class="{'form-control input-large':true ,'has-error': errors.has('name')}" name="name" type="text" placeholder="Name" v-model="name">
          <span v-show="errors.has('name')" class="help is-danger">{{ errors.first('name') }}</span>
     </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="Email">Email <span>*</span>:</label>
      <div class="controls">
          <input v-validate="'required|email'" :class="{'form-control input-large':true ,'has-warning': errors.has('email')}" name="email" type="text" placeholder="Email" v-model="email">
          <span v-show="errors.has('email')" class="help is-danger">{{ errors.first('email') }}</span>
          <span v-if="!isemailunique" >Email is already taken</span>
     </div>
    </div>



    <!-- Password input-->
    <div class="control-group">
      <label class="control-label" for="password">Password <span>*</span>:</label>
      <div class="controls">
        <input v-validate data-vv-rules="required|min:8|max:100" :class="{'form-control input-large':true ,'has-warning': errors.has('password')}" name="password" type="password" placeholder="******" v-model="password"  @keyup="isconfirmed=true;">
        <span v-show="errors.has('password')" class="help">{{ errors.first('password') }}</span>
  </div>
    </div>

    <!-- Text input-->
    <div class="control-group">
      <label class="control-label" for="reenterpassword">Re-Enter Password <span>*</span>:</label>
      <div class="controls">
       <input  v-validate="'required|min:8|max:100'"  class="form-control input-large" name="password_confirmation" type="password" placeholder="******"  v-model="confirm_password" @keyup="isconfirmed=true;">
       <span v-show="errors.has('pasword_confirmation')" class="help">{{ errors.first('password_confirmation') }}</span>
       <span  v-if="isconfirmed==false" class="help">Password and confirm Password doesn't match</span>
    </div>

    </div>

    <div class="control-group">
      <label class="control-label" for="userid">Magic Username <span>*</span>: </label>
      <div class="controls">
        <input id="userid" name="mtgo_user" class="form-control input-large" type="text" placeholder="JoeSixpack" v-model="mtgo_name" >

      </div>

    </div>
    <div class="control-group">
      <label class="control-label" for="userid">Gwent Username : </label>
      <div class="controls">
        <input id="userid" name="gwentt_user" class="form-control input-large" type="text" placeholder="JoeSixpack" v-model="gwentt_name"  >
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="userid">Hearthstone Username : </label>
      <div class="controls">
        <input id="userid" name="heart_user" class="form-control input-large" type="text" placeholder="JoeSixpack" v-model="heart_name" >
      </div>

    </div>


    <!-- Button -->
    <div class="control-group">
      <label class="control-label" for="confirmsignup"></label>
      <div class="controls">
      <input type="submit" :class="{'btn btn-success':true,'disabled':isregistering}"  value="sign up"  @click.prevent="register()" >
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
      name:'',
      email:'',
      password:'',
      confirm_password:'',
      mtgo_name:'',
      gwentt_name:'',
      heart_name:'',
      isconfirmed:true,
      ismtgo_unique:true,
      isgwennt_unique:true,
      isheart_unqiue:true,
      isemailunique:true,
      isregistering:false,
      root:null,


    }
  },
  methods:{
    register:function(){
      if(this.password!=this.confirm_password){
        this.isconfirmed=false;
        return;

      }

this.$validator.validateAll().then(result=>{

    this.isconfirmed=true;

  this.isregistering=true;
    if(result){


      axios.post("/register",
      {
       name:this.name,
       email:this.email,
       password:this.password,
       password_confirmation:this.confirm_password,
       magic_name:this.mtgo_name,
       gwennt_name:this.gwentt_name,
       heart_name:this.heart_name,


      }).then((data)=>{
      if(data.data.send==1){
        this.name;
        this.email=null;
        this.password=null;
        this.password_confirmation=null;
        this.mtgo_name=null;
        this.gwent_name=null;
        this.heart_name=null;
        this.root.showsuccess("Please check your email for registration");
        $("#myRegisterModal").modal("hide");
        $("#myModal").modal("hide");




      }

      this.isregistering=false;
      }).catch((error)=>{
        this.isregistering=false;
        let data=error.response.data;
        if(data.email){
          this.isemailunique=false;

        }
      });
    }


})    },


  },
mounted(){
this.root=this.$root.$root;

}

}
</script>

<style lang="css">
</style>
