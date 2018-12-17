
import VueRouter from 'vue-router'
import AdminCompList from './components/admin/adminCompList.vue'

const Foo = { template: '<div>Hello World!</div>' }

export default new VueRouter(
  {
    base: 'admin',
    routes: [
      {
        path: '/',
        component: AdminCompList,
        props: (route) => {
          return {path: route.path}
        }
      },
      {
        path: 'users',
        component: AdminCompList,
        children: [
          {
            path: '',
            component: AdminCompList,
            props: (route) => {
              return {path: route.path}
            }
          },
          {
            path: ':id',
            component: AdminCompList,
            props: (route) => {
              return {path: route.path, extra: {id: route.params.id}}
            }
          }
        ]
      },
      {
        path: 'pages',
        component: AdminCompList,
        children: [
          {
            path: '',
            component: AdminCompList,
            props: (route) => {
              return {path: route.path}
            }
          },
          {
            path: ':id',
            component: AdminCompList,
            props: (route) => {
              return {path: route.path, extra: {id: route.params.id}}
            }
          }
        ]
      },
      {path: 'sections', redirect: 'store/sections'},
      {
        path: 'store/sections',
        component: AdminCompList,
        children: [
          {
            path: '',
            component: AdminCompList,
            props: (route) => {
              return {path: route.path}
            }
          },
          {
            path: ':sid',
            component: AdminCompList,
            props: (route) => {
              return {path: route.path, extra: {sid: route.params.sid}}
            }
          }
        ]
      },
      {
        path: 'store/sections/:sid/category',
        component: AdminCompList,
        children: [
          {
            path: '',
            component: AdminCompList,
            props: (route) => {
              return {path: route.path}
            }
          },
          {
            path: ':cid',
            component: AdminCompList,
            props: (route) => {
              return {path: route.path, extra: {sid: route.params.sid, cid: route.params.cid}}
            }
          }
        ]
      },
      {
        path: 'store/sections/:sid/category/:cid/product',
        component: AdminCompList,
        children: [
          {
            path: '',
            component: AdminCompList,
            props: (route) => { return {path: route.path} }
          },
          {
            path: ':pid',
            component: AdminCompList,
            props: (route) => {
              return {path: route.path, extra: {sid: route.params.sid, cid: route.params.cid, pid: route.params.pid}}
            }
          }
        ]
      }
    ]
  }
)
