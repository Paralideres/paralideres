
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./app-bootstrap');
require('./bootstrap.min');
require('./pnotify');
require('./select2');
require('./loading_overlay.min');
require('./owl.carousel');
require('./jquery.matchHeight');
require('./main');

window.Vue = require('vue');

import Common from './common';
Vue.use(Common);

require('./auth');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// Vue.component('example', require('./components/Example.vue'));

// var SocialSharing = require('vue-social-sharing');
//
// Vue.use(SocialSharing);