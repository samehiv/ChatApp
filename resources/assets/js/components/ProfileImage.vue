<template>
    <div style="display: inline-block">
        <div class="loader-container" v-if="loading">
            <div class="loader"></div>
        </div>
        <img :src="image" id="image" class="img-circle">
        <div class="modal" id="change-img" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <button class="btn btn-block btn-default" @click="openFileInput" data-dismiss="modal">
                            change profile picture
                        </button>
                        <button class="btn btn-block btn-default" :class="{'disabled':removeButton}"
                                @click="removeImage" data-dismiss="modal">
                            remove profile picture
                        </button>
                        <button type="button" class="btn btn-block btn-default" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
        <form id="img-form">
            <input type="file" name="avatar" id="profile-img" @change="changeImg" style="display: none">
        </form>
    </div>
</template>

<script>
    export default{
        mounted(){
            this.getImage();
            if(this.auth){
                let img=document.querySelector('#image')
                img.setAttribute('data-toggle','modal');
                img.setAttribute('data-target','#change-img');
                img.setAttribute('style','cursor: pointer');
            }
        },
        data(){
            return {
                image:'',
                removeButton:false,
                imageUploading:false,
                loading:false
            }
        },
        props:{
            auth:{
                type:Boolean,
                default:false
            },
            userId:{
                type:String,
                require:true
            }
        },
        methods:{
            openFileInput(){
                document.querySelector('#profile-img').click();
            },
            changeImg(){
                let form = document.querySelector('#img-form');
                let formData = new FormData(form);
                let o = this;
                this.$http.post('/home/storeimg', formData,{progress(e){
                    o.loading=true;
                }})
                .then((response)=>{
                    this.getImage();
                    this.loading=false;
                }).bind(this);
            },
            getImage(){
                this.$http.get('/user/'+this.userId+'/getimg').then((response)=>{
                    this.image=response.data;
                    if(this.image.indexOf('default.jpg')==-1){
                        this.removeButton=false;
                    }else{
                        this.removeButton=true;
                    }
                }).bind(this);
            },
            removeImage(){
                let o = this;
                this.$http.post('/home/deleteimg',{},{progress(e){
                    o.loading=true
                }}).then((response)=>{
                    this.getImage();
                    this.loading=false;
                }).bind(this);
            }

        }
    }
</script>
