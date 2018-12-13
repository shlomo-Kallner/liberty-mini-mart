import {Stack} from './LibertyStack'
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
    this._children = ComponentTree.checkChildrenArray(children)
      ? children : []
    this._children.forEach(value => value.setParent(this))
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

  parent () {
    return this._parent
  }

  setParent (parent) {
    if (parent instanceof ComponentTree) {
      this._parent = parent
    }
  }

  value (val = null) {
    var res = this._value
    if (val !== null || val !== undefined) {
      this._value = val
    }
    return res
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
    return new TreeWalkIterator(this)
  }

  getChildren () {
    return this._children
  }

  numChildren () {
    return this._children.length
  }

  push (tree) {
    if (tree instanceof ComponentTree) {
      tree.setParent(this)
      this._children.push(tree)
    } else if (typeof tree === 'object') {
      var {value, children} = tree
      this._children.push(new ComponentTree(value, children, this))
    }
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
    if (value !== undefined && value !== null) {
      if (this.value() === value || (typeof comp === 'function' &&
        comp(this.value(), value))) {
        return this
      } else {
        var res = null
        for (var i of this._children) {
          var tmp = i.findSubTreeWithValue(value, comp)
          if (tmp !== undefined && tmp !== null) {
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
