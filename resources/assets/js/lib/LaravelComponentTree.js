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
    this.value = value
    this.parent = parent instanceof ComponentTree ? parent : null
    this.children = ComponentTree.checkChildrenArray(children)
      ? children : []
  }

  static checkChildrenArray (children) {
    if (Array.isArray(children)) {
      var bol = true
      for (var i in children) {
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
    return this.parent
  }

  value (val = null) {
    var res = this.value
    if (val !== null || val !== undefined) {
      this.value = val
    }
    return res
  }

  at (index) {
    if (typeof index === 'number' || typeof index === 'symbol') {
      return this.children[index]
    } else {
      return undefined
    }
  }

  hasChildren () {
    return window._.size(this.children) > 0
  }

  [Symbol.iterator] () {
    return new TreeWalkIterator(this)
  }

  getChildren () {
    return this.children
  }

  numChildren () {
    return window._.size(this.children)
  }

  push (tree) {
    if (tree instanceof ComponentTree) {
      window._.concat(this.children, tree)
    } else if (typeof tree === 'object') {
      var {value, children} = tree
      window._.concat(this.children, new ComponentTree(value, children, this))
    }
  }
  findSubTree (tree) {
    if (tree instanceof ComponentTree) {
      if (tree !== this) {
        var res = null
        for (var i in this.children) {
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
      comp(this.value, value))) {
        return this
      } else {
        var res = null
        for (var i in this.children) {
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
