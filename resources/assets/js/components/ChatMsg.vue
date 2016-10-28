<template>
    <div :class="toOrFrom">
        {{msg.body}}<br>
        <span class="small time">{{date}}</span>
    </div>
</template>

<script>
    const moment=require('moment');
    export default{
        mounted(){
            if(this.msg.sender==Store.auth){
                this.toOrFrom='to'
            }else if(this.msg.receiver==Store.auth){
                this.toOrFrom='from'
            }
            this.formatDate();
        },
        data(){
            return{
                toOrFrom:'',
                date:'',
                Store
            }
        },
        props:['msg'],
        methods:{
            formatDate(){
                if(this.msg.date.date !== undefined){
                    this.date=moment.utc(this.msg.date.date.replace('.000000',''),'YYYY-MM-DD HH:mm:ss').fromNow()
                }else if(this.msg.date.date == undefined){
                    this.date=moment.utc(this.msg.date,'YYYY-MM-DD HH:mm:ss').fromNow();
                }
            },
        }
    }
</script>
