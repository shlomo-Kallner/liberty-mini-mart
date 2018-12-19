
import VueRouter from 'vue-router'
import AdminCompList from './components/admin/adminCompList.vue'

const Foo = { template: '<div>Hello World!</div>' }

export function genRoutes (basePath = '') {
  return new VueRouter(
    {
      base: basePath,
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
                return {
                  path: route.path, 
                  extra: {id: route.params.id}
                }
              },
              children: [
                {
                  path: 'orders/:oid',
                  component: AdminCompList,
                  props: (route) => {
                    return {
                      path: route.path, extra: {id: route.params.id, oid: route.params.oid}
                    }
                  }
                },
                {
                  path: 'carts/:cid',
                  component: AdminCompList,
                  props: (route) => {
                    return {
                      path: route.path, extra: {id: route.params.id, cid: route.params.cid}
                    }
                  }
                },
                {
                  path: 'roles/:rid',
                  component: AdminCompList,
                  props: (route) => {
                    return {
                      path: route.path, extra: {id: route.params.id, rid: route.params.rid}
                    }
                  }
                }
                /* {
                  path: 'wishlist'
                } */
              ]
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
}
