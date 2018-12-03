
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
require('./bootstrap')
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

if (window.jQuery('#masterPagination').length > 0) {
  window.Laravel.page.masterPaginator = new window.Vue(
    {
      el: '#masterPagination',
      template: '<boot-pagination v-bind="pagingData" @paging-event="doPaging"></boot-pagination>',
      data: {
        urlObj: window.url.parse(window.Laravel.thisUrl),
        paging: JSON.parse(window.Laravel.pagination),
        pageAsync: false,
        pagingFunc: function (pUrl) {
          // for now, no-op..
          return pUrl;
        }
      },
      computed: {
        pagingFor: function () {
          if (this.paging !== undefined && window._.size(this.paging) > 0) {
            return this.paging.pagingFor;
          } else {
            return '';
          }
        },
        pagingData: function () {
          return window.myUtils.getPagingData(this.paging);
        }
      },
      methods: {
        genUrl: function (pageNum, viewNum) {
          return window.myUtils.genUrl(
            this.urlObj, pageNum, viewNum, this.pagingFor
          );
        },
        doPaging: function (pageNum, viewNum) {
          var pUrl = this.genUrl(pageNum, viewNum);
          if (this.pageAsync) {
            // for now, no-op..
            this.pagingFunc(pUrl);
          } else {
            window.location.assign(pUrl);
          }
        }
      }
    }
  );
}
