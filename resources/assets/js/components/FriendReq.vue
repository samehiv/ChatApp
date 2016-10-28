<template>
    <li style="padding-left:10px;padding-bottom:5px;">
        <img width="50" height="50" class="img-circle" :src="img">
        <span>
            <a :href="url">{{name}}</a>
            <span style="float:right;padding-right:5px;padding-top:10px;">
                <a href="#" class="btn btn-success btn-sm" @click="acceptClick">Accept</a>
                <a href="#" class="btn btn-danger btn-sm" @click="ignoreClick">Ignore</a>
            </span>

        </span>
    </li>
</template>
<script>
    export default{
        mounted(){
            this.getUser();
            this.getUserImg();
        },
        data(){
            return{
                name:'',
                img:'',
                url:'/user/'+this.userId,
            }
        },
        props:['userId'],
        methods:{
            getUser(){
                this.$http.get('/home/getuser/'+this.userId).then((response)=>{
                    this.name=response.data.name;
                }).bind(this);
            },
            getUserImg(){
                this.$http.get('/user/'+this.userId+'/getimg').then((response)=>{
                    this.img=response.data;
                }).bind(this);
            },
            acceptClick(e){
                e.preventDefault();
                e.stopPropagation();
                this.$http.get('/user/'+this.userId+'/acceptrequest').then((response)=>{
                    this.getReqs();
                }).bind(this);
            },
            ignoreClick(e){
                e.preventDefault();
                e.stopPropagation();
                this.$http.get('/user/'+this.userId+'/deletefriend').then((response)=>{
                    this.getReqs();
                }).bind(this);

            },
            getReqs(){
                this.$http.get('/home/reqs').then((response)=>{
                    Store.friendReqs=response.data;
                    if(Store.friendReqs.length == 0 ){
                        Store.reqsNav=false
                    }else{
                        Store.reqsNav=true;
                    }
                }).bind(this)
            },


        }
    }
</script>
