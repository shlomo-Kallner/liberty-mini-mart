
import _ from 'lodash'
import myUtils from '../utils'
import {ComponentTree, TreeWalkIterator} from './LaravelComponentTree'

class AssetDownloader {
  constructor (tree, method = 'get', timeout = 3000) {
    this.tree = tree instanceof ComponentTree ? tree : null
    this.iter = !_.isNull(this.tree) ? new TreeWalkIterator(this.tree) : null
    this.method = myUtils.testStr(method) ? method : 'get'
    this.timeout = myUtils.testData(timeout) && _.isInteger(timeout) ? timeout : 3000
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
        var val = value.value()
        var url = myUtils.getValueFrom(val, 'next', null)
        if (!myUtils.testStr(url)) {
          url = myUtils.getValueFrom(val, 'path', null)
          if (!myUtils.testStr(url)) {
            url = myUtils.getValueFrom(val, 'url', null)
          }
        }
        if (myUtils.testStr(url)) {
          myUtils.doAjax(
            myUtils.makeDataWithLaravel(val, url), this.method,
            (res) => { response = res },
            (res) => { throw new Error('Error!' + myUtils.dataToString(res)) },
            this.timeout
          )
        }
        if (myUtils.testData(response) && response.status === 200 &&
          response.statusText === 'OK'
        ) {
          var res = undefined
          if (response.data.done !== false &&
            myUtils.testData(response.data.value)) {
            value.value(response.data.value)
            if (_.has(response.data, 'children')) {
              for (var i of response.data.children) {
                value.push(i)
              }
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
