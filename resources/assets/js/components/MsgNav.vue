<template>
    <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" @click="navClicked">
            Messages <span class="badge">{{Store.msgNavCount>0?Store.msgNavCount:''}}</span>
        </a>
        <ul class="dropdown-menu" style="width: 500%;left:-350%" v-show="Store.msgNav">
            <msg-nav-item v-for="item in Store.msgs" :userId="item[0].sender" :lastMsg="item[0]"></msg-nav-item>
        </ul>
        <ul class="dropdown-menu"
            style="width:500%;color:#333;left:-350%" v-show="!Store.msgNav">
            <li style="padding:20px">No Messages</li>
        </ul>
    </li>
</template>
<script>
    import MsgNavItem from './MsgNavItem.vue'
    export default{
        mounted(){
            this.getMsgs();
            this.getUnreadCount();
        },
        data(){
            return{
                Store
            }
        },
        components:{
            MsgNavItem
        },
        methods:{
            getMsgs(){
                this.$http.get('/message/getmsgs').then((response)=>{
                    Store.msgs=response.data;
                    if(Store.msgs.length >0){
                        Store.msgNav=true;
                    }
                });
            },
            getUnreadCount(){
                this.$http.get('/message/unreadmsgs').then((response)=>{
                    Store.msgNavCount=response.data;
                });
            },
            readMsgs(){
                this.$http.get('/message/readmsgs')
                Store.msgNavCount=0;
            },
            navClicked(){
                this.getMsgs();
                this.readMsgs();
            }


        }
    }
</script>
