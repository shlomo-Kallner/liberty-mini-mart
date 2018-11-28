import { isArray } from "util";

// let _ = require('lodash');

export default class Stack {
  // private data: Array<any>;
  constructor (data = []) {
    this.data = Array.isArray(data) ? data : [];
  }

  at (idx, def = null) {
    if (typeof idx !== 'number') {
      return def;
    }
    let i = window._.floor(idx);
    let s = window._.size(this.data);
    if (i >= 0 && i < s) {
      return this.data[i];
    } else if (i < 0 && (-i) <= s) {
      return this.data[ s + i ];
    } else {
      return def;
    }
  }

  size () {
    return this.data.length;
  }

  empty () {
    return this.data.length === 0
  }

  push (item) {
    this.data.push(item);
  }

  pop () {
    return this.data.pop();
  }

  top () {
    return this.data[this.data.length - 1];
  }
}