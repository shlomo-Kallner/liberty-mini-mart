

window.axios.defaults.baseURL = window.Laravel.baseUrl;

window.Vue.component(
    'admin-panel-component', 
    require('./components/admin/adminPanel.vue')
);

window.Laravel.page.admin.app = new window.Vue({
    el: '#cms-app',
    template: '<admin-panel-component></admin-panel-component>'
});

window.Laravel.page.admin.makeData = function (info, url, token, redirect, action, nut = '') {
  return {
    info: info,
    url: url,
    token: token,
    _token: token,
    redirect: redirect,
    action: action,
    nut: nut
  };
};
