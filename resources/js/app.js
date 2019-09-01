require('./bootstrap');

window.Vue = require('vue');

Vue.component('app-users', require('./components/users/Users.vue').default);

const app = new Vue({
    el: '#app',
});
