
import VueRouter from 'vue-router'

const Foo = {template: '<div>Hello World!</div>'}

const routes = [
  {path: '/', component: Foo, props: () => {}},
  {path: 'users/:id', component: Foo, props: () => {}},
  {path: 'pages/:id', component: Foo, props: () => {}},
  {path: 'store/sections/:id', component: Foo, props: () => {}},
  {path: 'store/sections/:sid/category/:cid', component: Foo, props: () => {}},
  {path: 'store/sections/:sid/category/:cid/product/:pid', component: Foo, props: () => {}}
]

export default new VueRouter(
  {
    routes
  }
)