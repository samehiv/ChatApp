
/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
let VueResource=require('vue-resource');
import VueRouter from 'vue-router'



/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
Vue.use(VueResource);
Vue.use(VueRouter);
Vue.component('ProfileImage',require('./components/ProfileImage.vue'));
Vue.component('FriendButton',require('./components/FriendButton.vue'));
Vue.component('FriendNav',require('./components/FriendNav.vue'));
Vue.component('FriendBar',require('./components/FriendBar.vue'));
Vue.component('MsgNav',require('./components/MsgNav.vue'));
const chatBox=Vue.component('ChatBox',require('./components/ChatBox.vue'));
const routes=[
    {path:'/chatwith/:id',component:chatBox}
];
const router=new VueRouter({routes});
const app = new Vue({
    el: '#app',
    http:{
        header:{
            'X-CSRF-TOKEN':Laravel.csrfToken
        }
    },
    router
});



