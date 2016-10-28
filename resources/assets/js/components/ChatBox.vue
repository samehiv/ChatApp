<template>
    <div class="row">
        <div class="col-md-3 col-md-offset-6" id="chat">
            <div id="user-chat">
                {{name}}
                <router-link to="/">
                    <span class="glyphicon glyphicon-remove" id="close-button"></span>
                </router-link>
            </div>

            <div id="msgs">
                <chat-msg v-for="item in Store.chatMsgs" :msg="item"></chat-msg>
            </div>
            <form>
                <div class="form-group" id="chat-form">
                    <textarea class="form-control" id="chat-input"
                              rows="1" placeholder="Type a message..." @keyup.enter="sendMsg" v-model="message">
                    </textarea>
                </div>
            </form>
        </div>
    </div>
</template>
<script>
    import ChatMsg from './ChatMsg.vue'
    export default{
        mounted(){
            this.getUser(this.$route.params.id);
            this.getChatMsgs(this.$route.params.id);
            this.readChat();
            this.preventNewLine();
            //this.scroll();

        },
        data(){
            return{
                name,
                message:'',
                Store,
                interval:''
            }
        },
        components:{
            ChatMsg
        },
        watch:{
            $route(){
                this.getUser(this.$route.params.id);
                this.preventNewLine();
                this.getChatMsgs()
                this.readChat();
                //this.scroll();
            },
        },
        methods:{
            preventNewLine(){
                $('#chat-input').keypress((e)=>{
                    if(e.which==13){
                        e.preventDefault();
                    }
                })
            },
            scroll(){
                let el=$('#msgs');
                let h=el[0].scrollHeight;
                el.animate({scrollTop:h});
            },
            getUser(id){
                this.$http.get('/home/getuser/'+id).then((response)=>{
                    this.name=response.data.name;
                }).bind(this);
            },
            sendMsg(e){
                let msg=this.message;
                this.message='';
                this.$http.post('/message/sendmsg',{user:this.$route.params.id,msg})
                    .then((response)=>{
                        Store.chatMsgs=Store.chatMsgs.concat([response.data]);
                        setTimeout(this.scroll,100);
                    }).bind(this)
            },
            getChatMsgs(){
                this.$http.get('/message/getchatmsgs/'+this.$route.params.id)
                    .then((response)=>{
                        Store.chatMsgs=response.data;
                        setTimeout(this.scroll,500);
                    }).bind(this)
            },
            readChat(){
                this.$http.get('/message/readchat/'+this.$route.params.id);
            }
        }
    }
</script>
