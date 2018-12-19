import url from 'url'
import _ from 'lodash'
import axios from 'axios'
import json5 from 'json5'
export default {
  genUrl: function (urlObj, pageNum, viewNum, pagingFor) {
    return url.format({
      protocol: urlObj.protocol,
      host: urlObj.host,
      pathname: urlObj.pathname,
      query: {
        pageNum: pageNum,
        viewNum: viewNum,
        pagingFor: pagingFor
      }
    })
  },
  genPagingData: function (data, numPerPage, currentPage =  1,
    numTotal = 0, numPerView = 0
  ) {
    if (this.testData(data) && _.size(data) > 0 && Array.isArray(data)) {
      var numpages = numTotal <= 0
        ? this.genNumSubRanges(_.size(data), numPerPage)
        : this.genNumSubRanges(numTotal, numPerPage)
      return this.getPagingData({
        totalNumPages: numpages,
        currentPage: currentPage,
        numPerView: numPerView <= 0
          ? this.genNumSubRanges(numpages, numPerPage)
          : this.genNumSubRanges(numpages, numPerView),
        numItemsPerPage: numPerPage,
        totalItems: numTotal <= 0
          ? _.size(data)
          : numTotal
      })
    } else {
      return null
    }
  },
  getPagingData: function (paging) {
    if (this.testData(paging) && _.size(paging) >= 5) {
      return {
        numPages: paging.totalNumPages !== undefined ? paging.totalNumPages : 0,
        currentPage: paging.currentPage !== undefined ? paging.currentPage : 0,
        pagesPerView: paging.numPerView !== undefined ? paging.numPerView : 0,
        itemsPerPage: paging.numItemsPerPage !== undefined ? paging.numItemsPerPage : 0,
        totalItems: paging.totalItems !== undefined ? paging.totalItems : 0
      }
    } else {
      return {
        numPages: 0,
        currentPage: 0,
        pagesPerView: 0,
        itemsPerPage: 0,
        totalItems: 0
      }
    }
  },
  getArticleData: function (article, headerCss = null, articleCss = null, useSepRows = false) {
    return {
      initHeader: article.header,
      initSubheading: article.subheading,
      initArticle: article.article,
      initHeaderCss: headerCss,
      initArticleCss: articleCss,
      initImage: article.img,
      initUseSepparateRow: useSepRows
    }
  },
  getFigureData: function (figure) {
    return {
      initImage: figure.img,
      initAlt: figure.alt,
      initCaption: figure.cap
    }
  },
  testData: function (data) {
    return data !== undefined && data !== null
  },
  testStr: function (str) {
    return typeof str === 'string' && _.isString(str) && _.size(str) > 0 && str !== ''
  },
  doOnArray: function (data, func) {
    var res = []
    for (var i in data) {
      res = _.concat(res, func(data[i]))
    }
    return res
  },
  makeData: function (info, url, token, redirect, action, nut = '') {
    return {
      info: info,
      url: url,
      token: token,
      _token: token,
      redirect: redirect,
      action: action,
      nut: nut,
      _nut: nut
    }
  },
  makeDataWithLaravel: function (info, url, redirect, action) {
    return this.makeData(
      info, url, window.Laravel.csrfToken,
      redirect, action, window.Laravel.nut
    )
  },
  doAjax: function (data, method = 'post', success = null, fail = null, timeout = 3000) {
    // handleCart.dumpData(data);
    axios(
      {
        url: data.url,
        dataType: 'json',
        responseType: 'json',
        headers: {
          'X-CSRF-TOKEN': data.token,
          'X-XSRF-TOKEN': data._token
        },
        method: method,
        data: data,
        withCredentials: true,
        timeout: timeout
      }
    ).then(response => {
      if (typeof success === 'function') {
        success(response)
      }
    }).catch(reason => {
      if (typeof fail === 'function') {
        fail(reason)
      }
    })
  },
  genNumSubRanges: function (numItems, itemsPerSubRange) {
    var ni = _.floor(numItems)
    var ipsr = _.floor(itemsPerSubRange)
    if (ni <= ipsr) {
      return 1
    } else {
      var num = ni / ipsr
      return ni % ipsr > 0 ? num + 1 : num
    }
  },
  getCurrentSubRange: function (currentItem, itemsPerSubRange) {
    return _.floor(currentItem / itemsPerSubRange);
  },
  genFirstAndLastIndex: function (numItems, pageNum, itemsPerPage) {
    var numPages = this.genNumSubRanges(numItems, itemsPerPage);
    if (pageNum > 0 && pageNum >= numPages) {
      pageNum = pageNum % numPages;
    } else if (pageNum < 0) {
      pageNum = -pageNum;
      if (pageNum >= numPages) {
        pageNum = pageNum % numPages;
      } 
      pageNum = numPages - pageNum;
    } 
    var first = _.max([0, pageNum * itemsPerPage]);
    if (first > numItems) {
      first -= numItems;
    }
    var last = first + itemsPerPage;
    if (last >= numItems) {
      last -= (last - numItems);  
    } 
    return {
      begin: first,
      end: last,
      index: pageNum
    };
  },
  JsonParseOrRetObj: function (data = null, def = null, err = null) {
    if (typeof data === 'string') {
      var res = def
      try {
        res = JSON.parse(data)
      } catch (error) {
        var te = error
        try {
          res = json5.parse(data)
        } catch (error) {
          if (this.testData(err) && typeof err === 'function') {
            err([te, error])
          } else {
            throw new Error(te.message + error.message)
          }
        }
      }
      return res
    } else if (typeof data === 'object') {
      return data
    } else {
      return def
    }
  },
  outputErrorsToConsole: function (error) {
    var [e1, e2] = error
    console.log(e1.toString() + e2.toString())
  },
  isScalar: function (data = null) {
    if (typeof data === 'boolean' ||
      typeof data === 'number' ||
      typeof data === 'string' ||
      typeof data === 'undefined' ||
      typeof data === 'symbol' ||
      data === null
    ) {
      return true
    } else if (typeof data === 'object' ||
      Array.isArray(data) ||
      typeof data === 'function'
    ) {
      return false
    } else {
      return false
    }
  },
  genIndent: function (indent = 2, indChar = ' ') {
    var res = ''
    for (var i = 0; i < indent; i++) {
      res = res + indChar
    }
    return res
  },
  dataToString: function (data = null, indent = 2, indChar = ' ') {
    if (this.isScalar(data)) {
      return String(data) + '\n'
    } else {
      var res = '{\n'
      for (var i in data) {
        if (this.isScalar(data[i])) {
          res = res + i + ' => ' + String(data[i]) + '\n'
        } else if (typeof data[i] === 'object') {
          // res = res + 
          res = res + this.genIndent(indent, indChar) +
          i + ' => [ ' + '\n' +
          this.dataToString(data[i], indent + indent, indChar) + '\n'
          res = res + ']\n'
        }
      }
      res = res + '\n}'
      return res
    }
  },
  dumpData: function (data = null) {
    if (this.isScalar(data)) {
      console.log( data )
    } else {
      for (var i in data) {
        if (this.isScalar(data[i])) {
          console.log( i + ' => ' + data[i])
        } else if (typeof data[i] === 'object') {
          console.log( i + ' => [ ')
          this.dumpData(data[i])
          console.log(']')
        }
      }
    }
  },
  getPagingFrom: (data = null, def = null) => {
    if (this.testData(data) && typeof data === 'object') {
      if (_.has(data, 'pagination') &&
        this.testData(data.pagination) &&
        typeof data.pagination === 'object') {
        return data.pagination
      } else if (_.has(data, 'value')) {
        if (typeof _.get(data, 'value') === 'function') {
          return this.getPagingFrom(data.value(), def)
        } else if (typeof _.get(data, 'value') === 'object') {
          return this.getPagingFrom(data.value, def)
        }
      }
    } else if (this.testStr(data)) {
      return this.getPagingFrom(this.JsonParseOrRetObj(data, null, _.noop), def)
    }
    return def
  },
  getItemsFrom: (data = null, def = null) => {
    if (this.testData(data) && typeof data === 'object') {
      if (_.has(data, 'items') &&
        this.testData(data.items) &&
        typeof data.items === 'object' &&
        Array.isArray(data.items)) {
        return data.items
      } else if (_.has(data, 'children')) {
        if (typeof _.get(data, 'children') === 'function') {
          return this.getItemsFrom(data.children(), def)
        } else if (typeof _.get(data, 'children') === 'object') {
          return this.getItemsFrom(data.children, def)
        }
      } else if (Array.isArray(data)) {
        return data
      }
    } else if (this.testStr(data)) {
      return this.getItemsFrom(this.JsonParseOrRetObj(data, null, _.noop), def)
    }
    return def
  },
  getValueFrom: (data = null, field = '', def = null) => {
    if (this.testData(data) && typeof data === 'object') {
      if (_.has(data, field) && this.testData(_.get(data, field))) {
        if (typeof _.get(data, field) === 'function') {
          return _.invoke(data, field)
        } else {
          return _.get(data, field, def)
        }
      }
    } else if (this.testStr(data)) {
      return this.getValueFrom(this.JsonParseOrRetObj(data, null, _.noop), field, def)
    }
    return def
  }
}
