import url from 'url';
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
      res = _.concat(res, func(i));
    }
    return res;
  }
}