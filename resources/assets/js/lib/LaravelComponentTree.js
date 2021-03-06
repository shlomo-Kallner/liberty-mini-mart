import {Stack} from './LibertyStack'
import _ from 'lodash'
import myUtils from '../utils'

export class TreeWalkIterator {
  constructor (tree) {
    this.root = tree
    this.current = 0
    this.stack = new Stack([])
  }

  [Symbol.iterator] () {
    return this
  }

  next () {
    if (this.root.numChildren() > this.current) {
      var val = this.root.at(this.current)
      if (val.hasChildren()) {
        this.stack.push({root: this.root, current: this.current})
        this.current = 0
        this.root = val
      } else if (this.root.numChildren() === (this.current + 1) &&
      !this.stack.empty()) {
        var {root, current} = this.stack.pop()
        this.root = root
        this.current = current + 1
      } else {
        this.current++
      }
      return {done: false, value: val}
    } else {
      return {done: true, value: undefined}
    }
  }
}

export class ComponentTree {
  constructor (value, children = [], parent = null) {
    this._value = value
    this._parent = parent instanceof ComponentTree ? parent : null
    this._children = ComponentTree.retrieveChildren(children, this)
    // this._children.forEach(value => value.setParent(this))
  }

  static checkChildrenArray (children) {
    if (Array.isArray(children)) {
      var bol = true
      for (var i of children) {
        if (!(i instanceof ComponentTree)) {
          bol = false
        }
      }
      return bol
    } else {
      return false
    }
  }

  static retrieveChildren (children, parent = null) {
    var res = []
    if (myUtils.testData(children)) {
      if (Array.isArray(children)) {
        for (var i of children) {
          var c = ComponentTree.retrieveChild(i, parent)
          if (myUtils.testData(c)) {
            res.push(c)
          }
        }
      }
    }
    return res
  }

  static retrieveChild (child, parent = null) {
    if (myUtils.testData(child)) {
      if (child instanceof ComponentTree) {
        if (myUtils.testData(parent)) {
          child.setParent(parent)
        }
        return child
      } else if (typeof child === 'object') {
        var children = myUtils.getValueFrom(child, 'children', null)
        var value = myUtils.getValueFrom(child, 'value', null)
        if (myUtils.testData(value)) {
          return new ComponentTree(value, children, parent)
        }
      }
    } else {
      return null
    }
  }

  get length () {
    return this._children.length;
  }

  get parent () {
    return this._parent
  }

  setParent (parent) {
    if (parent instanceof ComponentTree) {
      this._parent = parent
    }
  }

  set value (val = null) {
    if (val !== null || val !== undefined) {
      this._value = val
    }
  }

  get value () {
    return this._value
  }

  get empty () {
    return this._children.length === 0
  }

  get children () {
    return this._children
  }

  set children (children) {
    this._children = ComponentTree.retrieveChildren(children, this)
  }

  at (index) {
    if (typeof index === 'number' || typeof index === 'symbol') {
      return this._children[index]
    } else {
      return undefined
    }
  }

  hasChildren () {
    return this._children.length > 0
  }

  [Symbol.iterator] () {
    // return new TreeWalkIterator(this)
    return function* () {
      yield this._value
      if (!this.empty) {
        for (var i of this._children) {
          yield * i[Symbol.iterator]()
        }
      }
    }
  }

  getChildren () {
    return this._children
  }

  numChildren () {
    return this._children.length
  }

  push (tree) {
    var c = ComponentTree.retrieveChild(tree, this)
    if (myUtils.testData(c)) {
      return this._children.push(c)
    }
  }

  pop () {
    return this._children.pop()
  }

  findSubTree (tree) {
    if (tree instanceof ComponentTree) {
      if (tree !== this) {
        var res = null
        for (var i of this._children) {
          var tmp = i.findSubTree(tree)
          if (tmp === tree) {
            res = tmp
            break
          }
        }
        return res
      } else {
        return this
      }
    } else {
      return undefined
    }
  }

  findSubTreeWithValue (value, comp = null) {
    if (myUtils.testData(value)) {
      if (myUtils.compData(this._value, value) ||
        (typeof comp === 'function' &&
          comp(this._value, value))) {
        return this
      } else {
        var res = null
        for (var i of this._children) {
          var tmp = i.findSubTreeWithValue(value, comp)
          if (myUtils.testData(tmp)) {
            res = tmp
            break
          }
        }
        return res
      }
    } else {
      return undefined
    }
  }
}
