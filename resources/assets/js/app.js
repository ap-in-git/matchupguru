
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
 require('./bootstrap');

// Vue.use(ClientTable);


 window.Vue = require('vue');

 var something=require("vue-resource");
 Vue.prototype.$axios = axios;

 import VeeValidate from 'vee-validate';

Vue.use(VeeValidate,{fieldsBagName: 'formFields'});
import VueEvents from 'vue-events';
Vue.use(VueEvents);



// Vue.component('login', require('./components/Auth/Login.vue'));
// Vue.component('register', require('./components/Auth/Register.vue'));
Vue.component('slider', require('./components/Admin/Slider.vue'));
Vue.component('formatChange', require('./components/FormatChange.vue'));
Vue.component('match', require('./components/game/StartMatchForm.vue'));
Vue.component('ind',require('./components/game/table-match.vue'));
Vue.component('logmatch',require('./components/game/LogMatch.vue'));
Vue.component("tournament",require('./components/game/Tournament.vue'));
Vue.component('AdminDeck',require("./components/Admin/Deck.vue"));
Vue.component("unverified",require("./components/Admin/deck/unverified.vue"));
Vue.component("FormatTable",require("./components/Admin/Format.vue"));

Vue.component("DeckTable",require("./components/Admin/Deck-table.vue"));
Vue.component("MagicTable",require("./components/game/table-match.vue"));
Vue.component("unveridefineddeckformat",require("./components/Deck/UnverifiedEdit.vue"));


Vue.component('editMatch',require("./components/match/edit.vue"));

Vue.component("Dice",require("./components/extra/Dice.vue"));
Vue.component("life-counter",require("./components/extra/lifeCounter.vue"));
Vue.component("hyper-go",require("./components/extra/Hypergo.vue"));
Vue.component("suggest-better",require("./components/user/suggest-deck.vue"));

Vue.component("deck-user",require("./components/user/deck-user.vue"));
Vue.component("admin-add-deck",require("./components/Admin/deck/admin-add-deck.vue"))
Vue.component("deck-details",require("./components/user/deck-detail.vue"))

Vue.component("test-table",require("./components/game/table-2.vue"))


Vue.component("comment",require("./components/Post/Comment.vue"))
const app = new Vue({
   data:window.Laravel,
    el: '#app',
    methods:{
      showsuccess:function(message){
        var delay = alertify.get('notifier','delay');
           alertify.set('notifier','delay', 3);
        alertify.set('notifier','position', 'top-right');
        alertify.success(message);
      }
    }
});
