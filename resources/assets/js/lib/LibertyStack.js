// let _ = require('lodash');

export class Stack {
  // private data: Array<any>;
  constructor (data) {
    this.data = data;
  }

  push (item) {
    this.data = window._.concat(this.data, item);
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
    return window._.size(this.data);
  }

  pop () {
    let [res] = window._.pullAt(this.data, window._.size(this.data));
    return res;
  }

  top () {
    return this.data[window._.size(this.data)];
  }
}