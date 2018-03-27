
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('./register');
require('./active_listings');
require('./accepted_listings');
require('./donor_my_active_listings');
require('./volunteers');
require('./new_listing');

require('./admin/approve_users');
require('./admin/new_organization');
require('./admin/new_product');
require('./admin/locations');
require('./admin/donor_types');
require('./admin/quantity_types');
require('./admin/products');
require('./admin/food_types');
require('./admin/cso_organizations');
require('./admin/donor_organizations');
require('./admin/csos');
require('./admin/donors');

require('admin-lte');
//
// window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
//
// Vue.component('example-component', require('./components/ExampleComponent.vue'));
//
// const app = new Vue({
//     el: '#app'
// });
