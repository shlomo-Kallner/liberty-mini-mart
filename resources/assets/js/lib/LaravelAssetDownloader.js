
import _ from 'lodash'
import myUtils from '../utils'
import {ComponentTree, TreeWalkIterator} from './LaravelComponentTree'

class AssetDownloader {
  constructor (tree, method = 'post', timeout = 3000) {
    this.tree = tree instanceof ComponentTree ? tree : null
    this.iter = !_.isNull(this.tree) ? new TreeWalkIterator(this.tree) : null
    this.method = myUtils.testStr(method) ? method : 'get'
    this.timeout = myUtils.testData(timeout) && _.isInteger(timeout) ? timeout : 3000
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
        var val = value instanceof ComponentTree ? value.value() : value
        var url = myUtils.getValueFrom(val, 'path', null)
        myUtils.doAjax(
          myUtils.makeDataWithLaravel(val, url),
          this.method, (res) => { response = res },
          (res) => { throw new Error (myUtils.dataToString(res)) },
          this.timeout
        )
        
      }
    }
  }
}
