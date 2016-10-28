<template>
    <router-link :to="path">
        <a href="#" class="list-group-item">
            <img width="50" height="50" class="img-circle" :src="img">
            {{user.name}}
            <span :id="id" class="state"></span>
        </a>
    </router-link>
</template>
<script>
    export default{
        mounted(){
            this.getImg();
        },
        data(){
            return{
                img:'',
                path:'/chatwith/'+this.user.id,
                id:''+this.user.id
            }
        },
        props:['user'],
        methods:{
            getImg(){
                this.$http.get('/user/'+this.user.id+'/getimg').then((response)=>{
                    this.img=response.data;
                }).bind(this);
            }
        }

    }
</script>
