<template>
    <div>
        <a href="#" :class="classes" @click="linkClicked" style="margin-bottom:10px">{{linkText}}</a>
        <a href="#" class="btn btn-danger" v-if="ignoreLink" @click="ignoreLinkClicked"
           style="margin-bottom:10px">
            Ignore Friend Request</a>
    </div>
</template>
<script>
    export default{
       mounted(){
            this.getFriendState();
       },
       data(){
            return{
                url:'',
                linkText:'',
                classes:'',
                ignoreLink:false,
                ignoreUrl:'/user/'+this.userId+'/deletefriend',
            }
       },
       props:['userId'],
       methods:{
           getFriendState(){
                this.$http.get('/user/'+this.userId+'/friendstate').then((response)=>{
                    this.makeLink(response.data)
                }).bind(this);
           },
           makeLink(state){
                if(state =='friend'){
                    this.url='/user/'+this.userId+'/deletefriend';
                    this.linkText='Delete Friend';
                    this.classes='btn btn-danger';
                    this.ignoreLink=false;
                }else if(state =='has_request_from'){
                    this.url='/user/'+this.userId+'/acceptrequest';
                    this.linkText='Accept Friend Request';
                    this.classes='btn btn-success';
                    this.ignoreLink=true;
                }else if(state == 'has_request_to'){
                    this.url='/user/'+this.userId+'/cancelrequest';
                    this.linkText='Cancel Friend Request';
                    this.classes='btn btn-default';
                    this.ignoreLink=false;
                }else if(state == 'not_friend'){
                    this.url='/user/'+this.userId+'/sendrequest';
                    this.linkText='Send Friend Request';
                    this.classes='btn btn-primary';
                    this.ignoreLink=false;
                }
           },
           linkClicked(e){
                e.preventDefault();
                let o = this;
                this.$http.get(this.url,{},{progress(e){
                    o.classes='btn btn-default disabled';
                    o.linkText='loading...';
                }}).then((response)=>{
                    this.getFriendState();
                }).bind(this);
           },
           ignoreLinkClicked(e){
                e.preventDefault();
                let o = this;
                this.$http.get(this.ignoreUrl,{},{progress(e){
                    o.classes='btn btn-default disabled';
                    o.linkText='loading...';
                }}).then((response)=>{
                    this.getFriendState()
                }).bind(this);
           }

       }

    }
</script>
