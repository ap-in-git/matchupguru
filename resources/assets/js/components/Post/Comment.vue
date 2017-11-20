<template>
  <span>
      <div class="col-sm-12">
         <div class="media" v-for="comment in comments ">
  <div class="media-left">
    <a href="#">
      <i class="fa fa-user-circle-o fa-4x"></i>
    </a>
  </div>
  <div class="media-body">
    <h5 class="media-heading">{{comment.name}} <small>{{comment.created}}</small></h5>
     {{comment.text}}
  </div>
</div>

      </div>

      <div class="col-sm-12">
          <h4>Leave a comment</h4>
          <form class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="comment-name">Name</label>
                  <input class="form-control form-control-rounded" type="text" id="comment-name" placeholder="John Doe"  v-model="name">
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="comment-email">E-mail</label>
                  <input class="form-control form-control-rounded" type="email" id="comment-email" placeholder="johndoe@email.com" v-model="email">
                </div>
              </div>
              <div class="col-sm-12">
                <div class="form-group">
                  <label for="comment-text">Comment</label>
                  <textarea class="form-control form-control-rounded" rows="7" id="comment-text" placeholder="Write your comment here..." v-model="textComment"></textarea>
                </div>
              </div>
              <div class="col-sm-12 text-right">
                <button class="btn btn-pill btn-primary" type="submit" @click.prevent="post">Post Comment</button>
              </div>
            </form>

      </div>

  </span>

</template>

<script>



    export default {
        props:{
            post_id:null

        },
        data(){
            return {
                comments:[],

                 name:null,
                 email:null,
                textComment:null,
                root:null

            }
        },
        methods: {
            post(){
                if(this.name===null)
                {
                    this.root.showsuccess("Please insert the name")
                    return;
                }

                if(this.email===null){
                    this.root.showsuccess("Please insert the email")
                    return;
                }

                if(this.comment===null){
                    this.root.showsuccess("Please insert the comment")
                    return;
                }

             axios.post("/post-comment",{
                 post_id:this.post_id,
                 name:this.name,
                 email:this.email,
                 comment:this.textComment
             }).then((response)=>{
                    this.root.showsuccess("Comment added successfully")
                    this.comments.unshift(response.data.comment)
                 this.name=null;
                    this.email=null;
                    this.textComment=null;

             })

            }
        },
        mounted() {
            axios.get("/post-comment/"+this.post_id).then((response)=>{
            this.comments=response.data.comments;
            })

            this.root=this.$root.$root;
        }
    }
</script>
