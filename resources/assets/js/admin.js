
window.Vue.component(
  'admin-panel-component',
  require('./components/admin/adminPanel.vue')
);

window.Laravel.page.admin.app = new window.Vue({
  el: '#cms-app',
  template: '<admin-panel-component></admin-panel-component>'
});


