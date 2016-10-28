<template>
    <li class="dropdown" id="reqsList">
        <a href="#" @click="navClicked" class="dropdown-toggle" data-toggle="dropdown">
            Friends Requests <span class="badge">{{Store.friendNav>0?Store.friendNav:''}}</span>
        </a>
        <ul class="dropdown-menu"
            style="width:300%;left:-100%;color:#333;max-height:300px;overflow-y: auto" v-show="Store.reqsNav">
            <friend-req v-for="item in Store.friendReqs"
                        :userId="item.data.userId">
            </friend-req>
        </ul>
        <ul class="dropdown-menu"
            style="width:300%;left:-100%;color:#333;" v-show="!Store.reqsNav">
            <li style="padding:20px">No Friends Requests</li>
        </ul>
    </li>
</template>
<script>
    import FriendReq from './FriendReq.vue';
    export default{
        mounted(){
            this.getUnreadReqs();
            this.getReqs();
        },
        data(){
            return{
                Store,
            }
        },
        components:{
            FriendReq
        },
        methods:{
            getUnreadReqs(){
                this.$http.get('/home/unreadreqs').then((response)=>{
                    Store.friendNav=response.data;
                }).bind(this)
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
            readReqs(){
                this.$http.get('/home/readreqs');
            },
            navClicked(e){
                this.readReqs();
                this.getUnreadReqs();
                this.getReqs();
            }
        }
    }
</script>
