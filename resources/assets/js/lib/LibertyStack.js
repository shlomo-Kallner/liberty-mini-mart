// import { isArray } from "util"

// let _ = require('lodash');

export class Stack {
  // private data: Array<any>;
  constructor (data = []) {
    this._data = Array.isArray(data) ? data : []
  }

  at (idx, def = null) {
    if (typeof idx === 'number') {
      let i = window._.floor(idx)
      let s = window._.size(this._data)
      if (i >= 0 && i < s) {
        return this._data[i]
      } else if (i < 0 && (-i) <= s) {
        return this._data[ s + i ]
      }
    } else if (typeof idx === 'symbol') {
      return this._data[idx]
    }
    return def
  }

  data () {
    return this._data
  }

  size () {
    return this._data.length
  }

  empty () {
    return this._data.length === 0
  }

  push (item) {
    this._data.push(item)
  }

  pop () {
    return this._data.pop()
  }

  top () {
    return this._data[this._data.length - 1]
  }
}