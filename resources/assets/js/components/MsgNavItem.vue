<template>
    <li style="">
        <router-link :to="path">
            <div style="float:left;margin-right:5px">
                <img width="50" height="50" class="img-circle" :src="img">
            </div>
            <div class="text-muted small" style="float:right">{{date}}</div>
            <div style=""><strong>{{name}}</strong></div>
            <div style="text-overflow: ellipsis;overflow:hidden;" class="text-muted">{{lastMsg.body}}</div>

        </router-link>


    </li>
</template>
<script>
    const moment=require('moment');
    export default{
        mounted(){
            this.getUser();
            this.getUserImg();
            this.formatDate();
        },
        data(){
            return{
                name:'',
                img:'',
                path:'/chatwith/'+this.userId,
                date:''
            }
        },
        props:['userId','lastMsg'],
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
            formatDate(){
               this.date=moment.utc(this.lastMsg.date,'YYYY-MM-DD HH:mm:ss').fromNow();
            },
        }
    }

</script>
