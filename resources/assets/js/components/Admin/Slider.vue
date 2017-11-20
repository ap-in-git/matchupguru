<template>
  <div>

  <button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target=".bs-example-modal-sm" style="margin-bottom:3%;"> Add a Slider</button>
<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-hidden="true" id="addmodal">
<div class="modal-dialog modal-md">
<div class="modal-content">
<div class="modal-header">
 <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
 </button>
 <div class="modal-title" id="myModalLabel2">Add a new Image (Insert image of widht :622 px and height:671px for better view)</div>
</div>
  <form method="post" >
<div class="modal-body">
    <div class="form-group">
      <label for="">Top Text *</label>
      <input type="text" class="form-control" name="Top text" v-validate="'required|min:6|max:244'" v-model="toptext">
       <span v-show="errors.has('Top text')" >{{ errors.first('Top text') }}</span>
    </div>
    <div class="form-group">
      <label for="">Bottom text <span>*</span></label>
      <input type="text" class="form-control" name="Bottom text" v-validate="'required|min:6|max:244'" v-model="buttomtext">
       <span v-show="errors.has('Bottom text')" >{{ errors.first('Bottom text') }}</span>
    </div>
    <div class="form-group">
      <label for="">Top Text ,Logged In *</label>
      <input type="text" class="form-control" name="Top text Logged In" v-validate="'required|min:6|max:244'" v-model="toptextlogin">
       <span v-show="errors.has('Top text Logged In')" >{{ errors.first('Top text Logged In') }}</span>
    </div>
    <div class="form-group">
      <label for="">Bottom text ,Logged In  <span>*</span></label>
      <input type="text" class="form-control" name="Bottom text Logged In" v-validate="'required|min:6|max:244'" v-model="buttomtextlogin">
       <span v-show="errors.has('Bottom text Logged In')" >{{ errors.first('Bottom text Logged In') }}</span>
    </div>

    <div class="form-group" v-if="!uploading">
      <label for="">Image </label>
      <input type="file" class="form-control" name="Image" v-validate="'required|mimes:image/jpeg,image/png,image,jpg'" id="image">
       <span v-show="errors.has('Image')" >{{ errors.first('Image') }}</span>
    </div>
    <div v-if="uploading">
  <div class="progress-bar" role="progressbar" v-bind:style="{width:FileProgress +'%'}">
  <span class="sr-only"></span>
</div>
 </div>
</div>


<div class="modal-footer">
 <button type="button" class="btn btn-default" data-dismiss="modal" v-if="!uploading" >Close</button>
 <button type="submit" class="btn btn-primary" @click.prevent="saveform()" v-if="!uploading">Add</button>
 <button type="buttom" class="btn btn-success"  v-if="uploading" disabled>Uploading image ...</button>
</div>
</form>

</div>
</div>
</div>
<!-- Modal for update -->
<div class="modal fade " tabindex="-1" role="dialog" aria-hidden="true" id="updateslidermodal">
<div class="modal-dialog modal-md">
<div class="modal-content">
<div class="modal-header">
 <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
 </button>
 <div class="modal-title" id="myModalLabel2">Update the slider (Insert image of widht :622 px and height:671px for better view)</div>
</div>
  <form method="post" :action="updateurl" enctype="multipart/form-data">
    <input type="hidden" name="_method" value="put">
    <input type="hidden" name="_token" :value="token">
<div class="modal-body">
    <div class="form-group">
      <label for="">Top Text *</label>
      <input type="text" name="top" class="form-control"  v-model="topupdatetext" required>
    </div>
    <div class="form-group">
      <label for="">Bottom text <span>*</span></label>
      <input type="text" class="form-control" name="bottom"  v-model="bottomupdatetext" required>
    </div>
    <div class="form-group">
      <label for="">Top Text ,Logged In <span>*</span></label>
      <input type="text" class="form-control" name="auth_top"  v-model="topudateauthtext" required>
    </div>
    <div class="form-group">
      <label for="">Bottom text ,Logged In <span>*</span></label>
      <input type="text" class="form-control" name="auth_bottom"  v-model="bottomupdateauthtext" required>
    </div>

    <div class="form-group" v-if="!uploading">
      <label for="">Image </label>
      <input type="file" class="form-control" name="Image">
    </div>
</div>


<div class="modal-footer">
 <button type="button" class="btn btn-default" data-dismiss="modal" v-if="!uploading" >Close</button>
 <button type="submit" class="btn btn-primary"  >Update</button>
 <button type="buttom" class="btn btn-success"  v-if="uploading" disabled>Uploading image ...</button>
</div>
</form>

</div>
</div>
</div>
  <br>

<div class="row">
  <table class="table table-hover table-bordered">
      <thead>
        <tr>
          <th>#</th>
          <th>Image</th>
          <th>Top text</th>
          <th>Buttom Text</th>
          <th>&nbsp;</th>
        </tr>
      </thead>
      <tbody>
           <tr v-for="image in images">
            <td>{{image.id}}</td>
              <td><img :src="image.image" class="img img-responsive"  style="heigh:30% !important;"></td>
            <td>{{image.top_text}}</td>
            <td>{{image.buttom_text}}</td>
             <td>
               <div class="btn-group-vertical" >
               <button class="btn btn-primary" @click="editslider(image.id,image.top_text,image.buttom_text,image.auth_text_bottom,image.auth_text_top)">Edit</button>
               <button  class="btn btn-danger" @click="deleteitem(image.id)">Delete</button>
               </div>
             </td>

           </tr>
      </tbody>
    </table>
                   <div class="modal show" v-if="showdelete">
                   <div class="modal-dialog">
                     <div class="modal-content">

                       <div class="modal-header">
                         <button type="button" class="close" data-dismiss="modal" aria-hidden="true" @click="showdelete=false">&times;</button>
                         <h4 class="modal-title" id="myModalLabel">Confirm Delete</h4>
                       </div>

                       <div class="modal-body">
                         <div  class="alert alert-info" hidden="">
                         </div>
                         <p>You are about to delete one track, this procedure is irreversible. <br>
                         </p>

                         <p>Do you want to proceed?</p>

                       </div>

                       <div class="modal-footer">
                         <button type="button" class="btn btn-default" data-dismiss="modal" @click="showdelete=false">Cancel</button>
                         <a class="btn btn-danger btn-ok" id="finaldeletecategory" @click="confirmdelete()">Delete</a>
                       </div>
                     </div>
                   </div>
                 </div>
            </div>
          </div>

</template>

<script>
export default {
  props:{
  deleteurl:null,
  dataurl:null,
  uploadurl:null,
  token:null,
  },
  data(){
    return {

        loading:false,
        currentupdatedata:{},
        root:null,
        showdelete:false,
        addform:false,
        toptext:null,
        buttomtext:null,
        toptextlogin:null,
        buttomtextlogin:null,
        file:null,
        FileProgress:null,
        uploading:false,
        images:null,
        topupdatetext:null,
        bottomupdatetext:null,
        updateurl:null,
        topudateauthtext:null,
        bottomupdateauthtext:null

    }
  },

  methods:{
    saveform:function(){
      this.$validator.validateAll().then(result=>{
        if(result){
          this.uploading=true;
          var file=document.getElementById("image").files[0];
       this.file=file;
      var filename=file.name;
      var ext=filename.split(".").pop();
          var form=new FormData;
         form.append("image",this.file);
         form.append("top_text",this.toptext);
         form.append("buttomtext",this.buttomtext);
         form.append("toptextlogin",this.toptextlogin)
         form.append('bottomtextlogin',this.buttomtextlogin)
         form.append("ext",ext)

      var self=this;
      var config = {
      onUploadProgress: (e)=>{
      var percentCompleted = Math.round( (e.loaded * 100) / e.total );
      self.FileProgress=percentCompleted;
      }
      };
      axios.post(this.uploadurl, form, config).
      then((data)=>{
         this.uploading=false;

         this.images.push(data.data);
         this.toptext="";
         this.buttomtext="";
        $("#addmodal").modal("hide");


      },()=>{
 this.uploading=false;
      },()=>{
 this.uploading=false;
      });

        }


      })

    },
confirmdelete:function(){

  let id=this.deleteid;
  axios.delete(this.deleteurl+id).
   then((data)=>{
  this.showdelete=false;
  let deleteId=data.data.id;
  let i=0;
  for(i;i<this.images.length;i++){
   let testi=this.images[i];
   if (testi.id==deleteId) {
     this.root.showsuccess("Slider Delete Successfully");
     this.images.splice(i,1);
   }
 }

  },()=>{

  },()=>{

  });




},
editslider:function(id,top,bottom,auth_bottom,auth_top){
this.updateurl=this.uploadurl+"/"+id;
this.topupdatetext=top;
this.bottomupdatetext=bottom;
this.topudateauthtext=auth_top;
this.bottomupdateauthtext=auth_bottom;
$("#updateslidermodal").modal("show");
},
deleteitem:function(id){
  this.deleteid=id;
  this.showdelete=true;
},
loaddata:function(){
 let data=axios.get(this.dataurl, {
  })
  .then((response)=> {
   this.images=response.data;
  })
  .catch((error)=> {
    console.log(error);
  });

}
  },

    mounted() {
       this.loaddata();
       this.root=this.$root.$root;



    },


}
</script>
