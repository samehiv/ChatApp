<template>
    <div class="col-md-3 col-md-offset-2 friend-bar" >
        Friends
        <hr>
        <div class="list-group">
            <friend-icon v-for="item in Store.friends" :user="item"></friend-icon>
        </div>
    </div>
</template>
<script>
    import FriendIcon from './FriendIcon.vue'
    export default{
        mounted(){
            this.getFriends();
        },
        data(){
            return{
                Store
            }
        },
        components:{
            FriendIcon
        },
        methods:{
            getFriends(){
                this.$http.get('/home/getfriends').then((response)=>{
                    Store.friends=response.data;
                }).bind(this);
            }
        }
    }
</script>
