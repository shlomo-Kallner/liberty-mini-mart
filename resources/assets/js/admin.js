
window.Vue.component(
  'admin-panel-component',
  require('./components/admin/adminPanel.vue')
);
window.Laravel.page.admin = {}

const Foo = {template: '<div>Hello World!</div>'}

const routes = [
  {path: 'users/:id', component: Foo, props: () => {}},
  {path: 'pages/:id', component: Foo, props: () => {}},
  {path: 'sections/:id', component: Foo, props: () => {}}
]


if (window.jQuery('#cms-app').length > 0 || true) {
  window.Laravel.page.admin.router = new window.VueRouter(
    {
      routes
    }
  )
  window.Laravel.page.admin.app = new window.Vue(
    {
      el: '#cms-app',
      router: window.Laravel.page.admin.router,
      template: '<admin-panel-component v-bind="initData"></admin-panel-component>',
      data: {
        initData: JSON.parse(window.Laravel.admin)
      }
    }
  )
}
