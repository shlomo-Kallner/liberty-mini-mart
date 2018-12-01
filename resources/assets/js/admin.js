
window.Vue.component(
  'admin-panel-component',
  require('./components/admin/adminPanel.vue')
);

if (window.jQuery('#cms-app').length > 0) {
  window.Laravel.page.admin.app = new window.Vue({
    el: '#cms-app',
    template: '<admin-panel-component></admin-panel-component>'
  });
}
