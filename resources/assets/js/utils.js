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
    });
  },
  getPagingData: function (paging) {
    return {
      numPages: paging.totalNumPages,
      currentPage: paging.currentPage,
      pagesPerView: paging.numPerView,
      itemsPerPage: paging.numItemsPerPage,
      totalItems: paging.totalItems
    };
  },
  testData: function (data) {
    return data !== undefined && data !== null;
  },
  doOnArray: function (data, func) {
    var res = [];
    for (var i in data) {
      res = window._.concat(res, func(i));
    }
    return res;
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
    };
  },
  genNumSubRanges: function (numItems, itemsPerSubRange) {
      var ni = window._.floor(numItems);
      var ipsr = window._.floor(itemsPerSubRange);
      if (ni <= ipsr) {
          return 1;
      } else {
          var num = ni / ipsr;
          return ni % ipsr > 0 ? num + 1 : num;
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
    var first = window._.max([0, pageNum * numItemsPerPage]);
    if (first > numItems) {
      first -= numItems;
    }
    var last = first + numItemsPerPage;
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