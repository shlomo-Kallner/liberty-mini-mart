
import VueRouter from 'vue-router'
import AdminCompList from './components/admin/adminCompList.vue'

const Foo = { template: '<div>Hello World!</div>' }

const routes = [
  {
    path: '/',
    component: Foo,
    props: () => {}
  },
  {
    path: 'users',
    component: AdminCompList,
    props: () => {},
    children: [
      {path: '', component: Foo, props: () => {}},
      {path: ':id', component: Foo, props: () => {}}
    ]
  },
  {
    path: 'pages',
    component: AdminCompList,
    props: () => {},
    children: [
      {path: '', component: Foo, props: () => {}},
      {path: ':id', component: Foo, props: () => {}}
    ]
  },
  {
    path: 'store/sections', 
    component: AdminCompList,
    children: [
      {path: '', component: Foo, props: () => {}},
      {path: ':sid', component: Foo, props: () => {}}
    ]
  },
  {
    path: 'store/sections/:sid/category', 
    component: AdminCompList,
    children: [
      {path: '', component: Foo, props: () => {}},
      {path: ':cid', component: Foo, props: () => {}}
    ]
  },
  {
    path: 'store/sections/:sid/category/:cid/product/:pid', component: Foo, props: () => {}
  }
]

export default new VueRouter(
  {
    routes,
    base: 'admin'
  }
)