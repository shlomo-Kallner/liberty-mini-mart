
import _ from 'lodash'
import myUtils from '../utils'
import { ComponentTree } from './LaravelComponentTree'

class AssetDownloader {
  constructor (tree, comp = null, method = 'get', timeout = 3000, modInPlace = true) {
    this.tree = tree instanceof ComponentTree ? tree : null
    this._comp = typeof comp === 'function' ? comp : myUtils.compData
    this.iter = !_.isNull(this.tree) ? this.tree[Symbol.iterator]() : null
    this.method = myUtils.testStr(method) ? method : 'get'
    this.timeout = myUtils.testData(timeout) && _.isInteger(timeout) ? timeout : 3000
    this.modInPlace = _.isBoolean(modInPlace) ? modInPlace : true
    if (myUtils.testData(this.tree)) {
      throw new Error('tree MUST NOT be Null or Undefined!')
    }
  }

  [Symbol.iterator] () {
    return this
  }

  next () {
    var {done, value} = this.iter.next()
    if (done && (typeof value === 'undefined')) {
      return {done, value}
    } else {
      if (value instanceof ComponentTree && true) {
        var response
        var url = myUtils.getValueFrom(value.value, 'next', null)
        if (!myUtils.testStr(url)) {
          url = myUtils.getValueFrom(value.value, 'path', null)
          if (!myUtils.testStr(url)) {
            url = myUtils.getValueFrom(value.value, 'url', null)
          }
        }
        if (myUtils.testStr(url)) {
          myUtils.doAjax(
            myUtils.makeDataWithLaravel(value.value, url), this.method,
            (res) => { response = res },
            (res) => { throw new Error('Error!' + myUtils.dataToString(res)) },
            this.timeout
          )
        }
        if (myUtils.testData(response) && response.status === 200 &&
          response.statusText === 'OK'
        ) {
          var res
          if (response.data.done !== false &&
            myUtils.testData(response.data.value)) {
            var children = _.has(response.data, 'children') ?
              response.data.children : null
            if (this.modInPlace) {
              value.value = response.data.value
              if (!_.isNull(children)) {
                value.children = children
              }
              res = value
            } else {
              res = new ComponentTree(response.data.value, children)
            }
          }
          return {done: response.data.done, value: res}
        } else if (!myUtils.testStr(url)) {
          throw new Error(`value (${val}) does not have a path URL!`)
        } else {
          throw new Error('Error!' + myUtils.dataToString(response)) 
        }
      }
    }
  }
}
