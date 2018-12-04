// import url from 'url';
export default {
  genUrl: function (urlObj, pageNum, viewNum, pagingFor) {
    return window.url.format({
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
  getPagingData: function (paging) {
    if (paging !== undefined && window._.size(paging) >= 5) {
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
  doOnArray: function (data, func) {
    var res = []
    for (var i in data) {
      res = window._.concat(res, func(i))
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
      nut: nut
    }
  },
  doAjax: function (data, type = 'POST', success = null, error = null) {
    // handleCart.dumpData(data);
    window.axios(
      {
        url: data.url,
        dataType: 'json',
        responseType: 'json',
        headers: {
          'X-CSRF-TOKEN': data.token,
          'X-XSRF-TOKEN': data._token
        },
        method: type,
        data: data,
        withCredentials: true
      }
    ).then(response => {
      if (typeof success === 'function') {
        success(response)
      }
    }).catch(reason => {
      if (typeof error === 'function') {
        error(reason)
      }
    })
  },
  genNumSubRanges: function (numItems, itemsPerSubRange) {
    var ni = window._.floor(numItems)
    var ipsr = window._.floor(itemsPerSubRange)
    if (ni <= ipsr) {
      return 1
    } else {
      var num = ni / ipsr
      return ni % ipsr > 0 ? num + 1 : num
    }
  },
  getCurrentSubRange: function (currentItem, itemsPerSubRange) {
    return window._.floor(currentItem / itemsPerSubRange);
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
    var first = window._.max([0, pageNum * itemsPerPage]);
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
  }
}
