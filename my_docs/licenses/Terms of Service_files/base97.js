try { jQuery = django.jQuery, $ = jQuery; LANG = 'en'; I18N = { 'delete': 'Really delete?' }; } catch(e) {}

// globals
var ua = navigator.userAgent.toLowerCase(),
    mobile_os = /windows phone/.test(ua) && 'Windows' || /android/.test(ua) && 'Android' || /ipad|iphone|ipod/.test(ua) && !window.MSStream && 'iOS' || null,
    is_ie = ~ua.indexOf('msie ') || ~ua.indexOf('trident/'),
    is_safari = ~ua.indexOf('safari') && !~ua.indexOf('chrome'),
    ac_xhr, hover_media, hover_media_to, is_human;

// Form 3.51.0-2014.06.20
!function(e){"use strict";"function"==typeof define&&define.amd?define(["jquery"],e):e("undefined"!=typeof jQuery?jQuery:window.Zepto)}(function(e){"use strict";function t(t){var r=t.data;t.isDefaultPrevented()||(t.preventDefault(),e(t.target).ajaxSubmit(r))}function r(t){var r=t.target,a=e(r);if(!a.is("[type=submit],[type=image]")){var n=a.closest("[type=submit]");if(0===n.length)return;r=n[0]}var i=this;if(i.clk=r,"image"==r.type)if(void 0!==t.offsetX)i.clk_x=t.offsetX,i.clk_y=t.offsetY;else if("function"==typeof e.fn.offset){var o=a.offset();i.clk_x=t.pageX-o.left,i.clk_y=t.pageY-o.top}else i.clk_x=t.pageX-r.offsetLeft,i.clk_y=t.pageY-r.offsetTop;setTimeout(function(){i.clk=i.clk_x=i.clk_y=null},100)}function a(){if(e.fn.ajaxSubmit.debug){var t="[jquery.form] "+Array.prototype.join.call(arguments,"");window.console&&window.console.log?window.console.log(t):window.opera&&window.opera.postError&&window.opera.postError(t)}}var n={};n.fileapi=void 0!==e("<input type='file'/>").get(0).files,n.formdata=void 0!==window.FormData;var i=!!e.fn.prop;e.fn.attr2=function(){if(!i)return this.attr.apply(this,arguments);var e=this.prop.apply(this,arguments);return e&&e.jquery||"string"==typeof e?e:this.attr.apply(this,arguments)},e.fn.ajaxSubmit=function(t){function r(r){var a,n,i=e.param(r,t.traditional).split("&"),o=i.length,s=[];for(a=0;o>a;a++)i[a]=i[a].replace(/\+/g," "),n=i[a].split("="),s.push([decodeURIComponent(n[0]),decodeURIComponent(n[1])]);return s}function o(a){for(var n=new FormData,i=0;i<a.length;i++)n.append(a[i].name,a[i].value);if(t.extraData){var o=r(t.extraData);for(i=0;i<o.length;i++)o[i]&&n.append(o[i][0],o[i][1])}t.data=null;var s=e.extend(!0,{},e.ajaxSettings,t,{contentType:!1,processData:!1,cache:!1,type:u||"POST"});t.uploadProgress&&(s.xhr=function(){var r=e.ajaxSettings.xhr();return r.upload&&r.upload.addEventListener("progress",function(e){var r=0,a=e.loaded||e.position,n=e.total;e.lengthComputable&&(r=Math.ceil(a/n*100)),t.uploadProgress(e,a,n,r)},!1),r}),s.data=null;var c=s.beforeSend;return s.beforeSend=function(e,r){r.data=t.formData?t.formData:n,c&&c.call(this,e,r)},e.ajax(s)}function s(r){function n(e){var t=null;try{e.contentWindow&&(t=e.contentWindow.document)}catch(r){a("cannot get iframe.contentWindow document: "+r)}if(t)return t;try{t=e.contentDocument?e.contentDocument:e.document}catch(r){a("cannot get iframe.contentDocument: "+r),t=e.document}return t}function o(){function t(){try{var e=n(g).readyState;a("state = "+e),e&&"uninitialized"==e.toLowerCase()&&setTimeout(t,50)}catch(r){a("Server abort: ",r," (",r.name,")"),s(k),j&&clearTimeout(j),j=void 0}}var r=f.attr2("target"),i=f.attr2("action"),o="multipart/form-data",c=f.attr("enctype")||f.attr("encoding")||o;w.setAttribute("target",p),(!u||/post/i.test(u))&&w.setAttribute("method","POST"),i!=m.url&&w.setAttribute("action",m.url),m.skipEncodingOverride||u&&!/post/i.test(u)||f.attr({encoding:"multipart/form-data",enctype:"multipart/form-data"}),m.timeout&&(j=setTimeout(function(){T=!0,s(D)},m.timeout));var l=[];try{if(m.extraData)for(var d in m.extraData)m.extraData.hasOwnProperty(d)&&l.push(e.isPlainObject(m.extraData[d])&&m.extraData[d].hasOwnProperty("name")&&m.extraData[d].hasOwnProperty("value")?e('<input type="hidden" name="'+m.extraData[d].name+'">').val(m.extraData[d].value).appendTo(w)[0]:e('<input type="hidden" name="'+d+'">').val(m.extraData[d]).appendTo(w)[0]);m.iframeTarget||v.appendTo("body"),g.attachEvent?g.attachEvent("onload",s):g.addEventListener("load",s,!1),setTimeout(t,15);try{w.submit()}catch(h){var x=document.createElement("form").submit;x.apply(w)}}finally{w.setAttribute("action",i),w.setAttribute("enctype",c),r?w.setAttribute("target",r):f.removeAttr("target"),e(l).remove()}}function s(t){if(!x.aborted&&!F){if(M=n(g),M||(a("cannot access response document"),t=k),t===D&&x)return x.abort("timeout"),void S.reject(x,"timeout");if(t==k&&x)return x.abort("server abort"),void S.reject(x,"error","server abort");if(M&&M.location.href!=m.iframeSrc||T){g.detachEvent?g.detachEvent("onload",s):g.removeEventListener("load",s,!1);var r,i="success";try{if(T)throw"timeout";var o="xml"==m.dataType||M.XMLDocument||e.isXMLDoc(M);if(a("isXml="+o),!o&&window.opera&&(null===M.body||!M.body.innerHTML)&&--O)return a("requeing onLoad callback, DOM not available"),void setTimeout(s,250);var u=M.body?M.body:M.documentElement;x.responseText=u?u.innerHTML:null,x.responseXML=M.XMLDocument?M.XMLDocument:M,o&&(m.dataType="xml"),x.getResponseHeader=function(e){var t={"content-type":m.dataType};return t[e.toLowerCase()]},u&&(x.status=Number(u.getAttribute("status"))||x.status,x.statusText=u.getAttribute("statusText")||x.statusText);var c=(m.dataType||"").toLowerCase(),l=/(json|script|text)/.test(c);if(l||m.textarea){var f=M.getElementsByTagName("textarea")[0];if(f)x.responseText=f.value,x.status=Number(f.getAttribute("status"))||x.status,x.statusText=f.getAttribute("statusText")||x.statusText;else if(l){var p=M.getElementsByTagName("pre")[0],h=M.getElementsByTagName("body")[0];p?x.responseText=p.textContent?p.textContent:p.innerText:h&&(x.responseText=h.textContent?h.textContent:h.innerText)}}else"xml"==c&&!x.responseXML&&x.responseText&&(x.responseXML=X(x.responseText));try{E=_(x,c,m)}catch(y){i="parsererror",x.error=r=y||i}}catch(y){a("error caught: ",y),i="error",x.error=r=y||i}x.aborted&&(a("upload aborted"),i=null),x.status&&(i=x.status>=200&&x.status<300||304===x.status?"success":"error"),"success"===i?(m.success&&m.success.call(m.context,E,"success",x),S.resolve(x.responseText,"success",x),d&&e.event.trigger("ajaxSuccess",[x,m])):i&&(void 0===r&&(r=x.statusText),m.error&&m.error.call(m.context,x,i,r),S.reject(x,"error",r),d&&e.event.trigger("ajaxError",[x,m,r])),d&&e.event.trigger("ajaxComplete",[x,m]),d&&!--e.active&&e.event.trigger("ajaxStop"),m.complete&&m.complete.call(m.context,x,i),F=!0,m.timeout&&clearTimeout(j),setTimeout(function(){m.iframeTarget?v.attr("src",m.iframeSrc):v.remove(),x.responseXML=null},100)}}}var c,l,m,d,p,v,g,x,y,b,T,j,w=f[0],S=e.Deferred();if(S.abort=function(e){x.abort(e)},r)for(l=0;l<h.length;l++)c=e(h[l]),i?c.prop("disabled",!1):c.removeAttr("disabled");if(m=e.extend(!0,{},e.ajaxSettings,t),m.context=m.context||m,p="jqFormIO"+(new Date).getTime(),m.iframeTarget?(v=e(m.iframeTarget),b=v.attr2("name"),b?p=b:v.attr2("name",p)):(v=e('<iframe name="'+p+'" src="'+m.iframeSrc+'" />'),v.css({position:"absolute",top:"-1000px",left:"-1000px"})),g=v[0],x={aborted:0,responseText:null,responseXML:null,status:0,statusText:"n/a",getAllResponseHeaders:function(){},getResponseHeader:function(){},setRequestHeader:function(){},abort:function(t){var r="timeout"===t?"timeout":"aborted";a("aborting upload... "+r),this.aborted=1;try{g.contentWindow.document.execCommand&&g.contentWindow.document.execCommand("Stop")}catch(n){}v.attr("src",m.iframeSrc),x.error=r,m.error&&m.error.call(m.context,x,r,t),d&&e.event.trigger("ajaxError",[x,m,r]),m.complete&&m.complete.call(m.context,x,r)}},d=m.global,d&&0===e.active++&&e.event.trigger("ajaxStart"),d&&e.event.trigger("ajaxSend",[x,m]),m.beforeSend&&m.beforeSend.call(m.context,x,m)===!1)return m.global&&e.active--,S.reject(),S;if(x.aborted)return S.reject(),S;y=w.clk,y&&(b=y.name,b&&!y.disabled&&(m.extraData=m.extraData||{},m.extraData[b]=y.value,"image"==y.type&&(m.extraData[b+".x"]=w.clk_x,m.extraData[b+".y"]=w.clk_y)));var D=1,k=2,A=e("meta[name=csrf-token]").attr("content"),L=e("meta[name=csrf-param]").attr("content");L&&A&&(m.extraData=m.extraData||{},m.extraData[L]=A),m.forceSync?o():setTimeout(o,10);var E,M,F,O=50,X=e.parseXML||function(e,t){return window.ActiveXObject?(t=new ActiveXObject("Microsoft.XMLDOM"),t.async="false",t.loadXML(e)):t=(new DOMParser).parseFromString(e,"text/xml"),t&&t.documentElement&&"parsererror"!=t.documentElement.nodeName?t:null},C=e.parseJSON||function(e){return window.eval("("+e+")")},_=function(t,r,a){var n=t.getResponseHeader("content-type")||"",i="xml"===r||!r&&n.indexOf("xml")>=0,o=i?t.responseXML:t.responseText;return i&&"parsererror"===o.documentElement.nodeName&&e.error&&e.error("parsererror"),a&&a.dataFilter&&(o=a.dataFilter(o,r)),"string"==typeof o&&("json"===r||!r&&n.indexOf("json")>=0?o=C(o):("script"===r||!r&&n.indexOf("javascript")>=0)&&e.globalEval(o)),o};return S}if(!this.length)return a("ajaxSubmit: skipping submit process - no element selected"),this;var u,c,l,f=this;"function"==typeof t?t={success:t}:void 0===t&&(t={}),u=t.type||this.attr2("method"),c=t.url||this.attr2("action"),l="string"==typeof c?e.trim(c):"",l=l||window.location.href||"",l&&(l=(l.match(/^([^#]+)/)||[])[1]),t=e.extend(!0,{url:l,success:e.ajaxSettings.success,type:u||e.ajaxSettings.type,iframeSrc:/^https/i.test(window.location.href||"")?"javascript:false":"about:blank"},t);var m={};if(this.trigger("form-pre-serialize",[this,t,m]),m.veto)return a("ajaxSubmit: submit vetoed via form-pre-serialize trigger"),this;if(t.beforeSerialize&&t.beforeSerialize(this,t)===!1)return a("ajaxSubmit: submit aborted via beforeSerialize callback"),this;var d=t.traditional;void 0===d&&(d=e.ajaxSettings.traditional);var p,h=[],v=this.formToArray(t.semantic,h);if(t.data&&(t.extraData=t.data,p=e.param(t.data,d)),t.beforeSubmit&&t.beforeSubmit(v,this,t)===!1)return a("ajaxSubmit: submit aborted via beforeSubmit callback"),this;if(this.trigger("form-submit-validate",[v,this,t,m]),m.veto)return a("ajaxSubmit: submit vetoed via form-submit-validate trigger"),this;var g=e.param(v,d);p&&(g=g?g+"&"+p:p),"GET"==t.type.toUpperCase()?(t.url+=(t.url.indexOf("?")>=0?"&":"?")+g,t.data=null):t.data=g;var x=[];if(t.resetForm&&x.push(function(){f.resetForm()}),t.clearForm&&x.push(function(){f.clearForm(t.includeHidden)}),!t.dataType&&t.target){var y=t.success||function(){};x.push(function(r){var a=t.replaceTarget?"replaceWith":"html";e(t.target)[a](r).each(y,arguments)})}else t.success&&x.push(t.success);if(t.success=function(e,r,a){for(var n=t.context||this,i=0,o=x.length;o>i;i++)x[i].apply(n,[e,r,a||f,f])},t.error){var b=t.error;t.error=function(e,r,a){var n=t.context||this;b.apply(n,[e,r,a,f])}}if(t.complete){var T=t.complete;t.complete=function(e,r){var a=t.context||this;T.apply(a,[e,r,f])}}var j=e("input[type=file]:enabled",this).filter(function(){return""!==e(this).val()}),w=j.length>0,S="multipart/form-data",D=f.attr("enctype")==S||f.attr("encoding")==S,k=n.fileapi&&n.formdata;a("fileAPI :"+k);var A,L=(w||D)&&!k;t.iframe!==!1&&(t.iframe||L)?t.closeKeepAlive?e.get(t.closeKeepAlive,function(){A=s(v)}):A=s(v):A=(w||D)&&k?o(v):e.ajax(t),f.removeData("jqxhr").data("jqxhr",A);for(var E=0;E<h.length;E++)h[E]=null;return this.trigger("form-submit-notify",[this,t]),this},e.fn.ajaxForm=function(n){if(n=n||{},n.delegation=n.delegation&&e.isFunction(e.fn.on),!n.delegation&&0===this.length){var i={s:this.selector,c:this.context};return!e.isReady&&i.s?(a("DOM not ready, queuing ajaxForm"),e(function(){e(i.s,i.c).ajaxForm(n)}),this):(a("terminating; zero elements found by selector"+(e.isReady?"":" (DOM not ready)")),this)}return n.delegation?(e(document).off("submit.form-plugin",this.selector,t).off("click.form-plugin",this.selector,r).on("submit.form-plugin",this.selector,n,t).on("click.form-plugin",this.selector,n,r),this):this.ajaxFormUnbind().bind("submit.form-plugin",n,t).bind("click.form-plugin",n,r)},e.fn.ajaxFormUnbind=function(){return this.unbind("submit.form-plugin click.form-plugin")},e.fn.formToArray=function(t,r){var a=[];if(0===this.length)return a;var i,o=this[0],s=this.attr("id"),u=t?o.getElementsByTagName("*"):o.elements;if(u&&!/MSIE [678]/.test(navigator.userAgent)&&(u=e(u).get()),s&&(i=e(':input[form="'+s+'"]').get(),i.length&&(u=(u||[]).concat(i))),!u||!u.length)return a;var c,l,f,m,d,p,h;for(c=0,p=u.length;p>c;c++)if(d=u[c],f=d.name,f&&!d.disabled)if(t&&o.clk&&"image"==d.type)o.clk==d&&(a.push({name:f,value:e(d).val(),type:d.type}),a.push({name:f+".x",value:o.clk_x},{name:f+".y",value:o.clk_y}));else if(m=e.fieldValue(d,!0),m&&m.constructor==Array)for(r&&r.push(d),l=0,h=m.length;h>l;l++)a.push({name:f,value:m[l]});else if(n.fileapi&&"file"==d.type){r&&r.push(d);var v=d.files;if(v.length)for(l=0;l<v.length;l++)a.push({name:f,value:v[l],type:d.type});else a.push({name:f,value:"",type:d.type})}else null!==m&&"undefined"!=typeof m&&(r&&r.push(d),a.push({name:f,value:m,type:d.type,required:d.required}));if(!t&&o.clk){var g=e(o.clk),x=g[0];f=x.name,f&&!x.disabled&&"image"==x.type&&(a.push({name:f,value:g.val()}),a.push({name:f+".x",value:o.clk_x},{name:f+".y",value:o.clk_y}))}return a},e.fn.formSerialize=function(t){return e.param(this.formToArray(t))},e.fn.fieldSerialize=function(t){var r=[];return this.each(function(){var a=this.name;if(a){var n=e.fieldValue(this,t);if(n&&n.constructor==Array)for(var i=0,o=n.length;o>i;i++)r.push({name:a,value:n[i]});else null!==n&&"undefined"!=typeof n&&r.push({name:this.name,value:n})}}),e.param(r)},e.fn.fieldValue=function(t){for(var r=[],a=0,n=this.length;n>a;a++){var i=this[a],o=e.fieldValue(i,t);null===o||"undefined"==typeof o||o.constructor==Array&&!o.length||(o.constructor==Array?e.merge(r,o):r.push(o))}return r},e.fieldValue=function(t,r){var a=t.name,n=t.type,i=t.tagName.toLowerCase();if(void 0===r&&(r=!0),r&&(!a||t.disabled||"reset"==n||"button"==n||("checkbox"==n||"radio"==n)&&!t.checked||("submit"==n||"image"==n)&&t.form&&t.form.clk!=t||"select"==i&&-1==t.selectedIndex))return null;if("select"==i){var o=t.selectedIndex;if(0>o)return null;for(var s=[],u=t.options,c="select-one"==n,l=c?o+1:u.length,f=c?o:0;l>f;f++){var m=u[f];if(m.selected){var d=m.value;if(d||(d=m.attributes&&m.attributes.value&&!m.attributes.value.specified?m.text:m.value),c)return d;s.push(d)}}return s}return e(t).val()},e.fn.clearForm=function(t){return this.each(function(){e("input,select,textarea",this).clearFields(t)})},e.fn.clearFields=e.fn.clearInputs=function(t){var r=/^(?:color|date|datetime|email|month|number|password|range|search|tel|text|time|url|week)$/i;return this.each(function(){var a=this.type,n=this.tagName.toLowerCase();r.test(a)||"textarea"==n?this.value="":"checkbox"==a||"radio"==a?this.checked=!1:"select"==n?this.selectedIndex=-1:"file"==a?/MSIE/.test(navigator.userAgent)?e(this).replaceWith(e(this).clone(!0)):e(this).val(""):t&&(t===!0&&/hidden/.test(a)||"string"==typeof t&&e(this).is(t))&&(this.value="")})},e.fn.resetForm=function(){return this.each(function(){("function"==typeof this.reset||"object"==typeof this.reset&&!this.reset.nodeType)&&this.reset()})},e.fn.enable=function(e){return void 0===e&&(e=!0),this.each(function(){this.disabled=!e})},e.fn.selected=function(t){return void 0===t&&(t=!0),this.each(function(){var r=this.type;if("checkbox"==r||"radio"==r)this.checked=t;else if("option"==this.tagName.toLowerCase()){var a=e(this).parent("select");t&&a[0]&&"select-one"==a[0].type&&a.find("option").selected(!1),this.selected=t}})},e.fn.ajaxSubmit.debug=!1});
// Colorbox 1.6.4
(function(t,e,i){function n(i,n,o){var r=e.createElement(i);return n&&(r.id=Z+n),o&&(r.style.cssText=o),t(r)}function o(){return i.innerHeight?i.innerHeight:t(i).height()}function r(e,i){i!==Object(i)&&(i={}),this.cache={},this.el=e,this.value=function(e){var n;return void 0===this.cache[e]&&(n=t(this.el).attr("data-cbox-"+e),void 0!==n?this.cache[e]=n:void 0!==i[e]?this.cache[e]=i[e]:void 0!==X[e]&&(this.cache[e]=X[e])),this.cache[e]},this.get=function(e){var i=this.value(e);return t.isFunction(i)?i.call(this.el,this):i}}function h(t){var e=W.length,i=(A+t)%e;return 0>i?e+i:i}function a(t,e){return Math.round((/%/.test(t)?("x"===e?E.width():o())/100:1)*parseInt(t,10))}function s(t,e){return t.get("photo")||t.get("photoRegex").test(e)}function l(t,e){return t.get("retinaUrl")&&i.devicePixelRatio>1?e.replace(t.get("photoRegex"),t.get("retinaSuffix")):e}function d(t){"contains"in x[0]&&!x[0].contains(t.target)&&t.target!==v[0]&&(t.stopPropagation(),x.focus())}function c(t){c.str!==t&&(x.add(v).removeClass(c.str).addClass(t),c.str=t)}function g(e){A=0,e&&e!==!1&&"nofollow"!==e?(W=t("."+te).filter(function(){var i=t.data(this,Y),n=new r(this,i);return n.get("rel")===e}),A=W.index(_.el),-1===A&&(W=W.add(_.el),A=W.length-1)):W=t(_.el)}function u(i){t(e).trigger(i),ae.triggerHandler(i)}function f(i){var o;if(!G){if(o=t(i).data(Y),_=new r(i,o),g(_.get("rel")),!U){U=$=!0,c(_.get("className")),x.css({visibility:"hidden",display:"block",opacity:""}),I=n(se,"LoadedContent","width:0; height:0; overflow:hidden; visibility:hidden"),b.css({width:"",height:""}).append(I),j=T.height()+k.height()+b.outerHeight(!0)-b.height(),D=C.width()+H.width()+b.outerWidth(!0)-b.width(),N=I.outerHeight(!0),z=I.outerWidth(!0);var h=a(_.get("initialWidth"),"x"),s=a(_.get("initialHeight"),"y"),l=_.get("maxWidth"),f=_.get("maxHeight");_.w=Math.max((l!==!1?Math.min(h,a(l,"x")):h)-z-D,0),_.h=Math.max((f!==!1?Math.min(s,a(f,"y")):s)-N-j,0),I.css({width:"",height:_.h}),J.position(),u(ee),_.get("onOpen"),O.add(F).hide(),x.focus(),_.get("trapFocus")&&e.addEventListener&&(e.addEventListener("focus",d,!0),ae.one(re,function(){e.removeEventListener("focus",d,!0)})),_.get("returnFocus")&&ae.one(re,function(){t(_.el).focus()})}var p=parseFloat(_.get("opacity"));v.css({opacity:p===p?p:"",cursor:_.get("overlayClose")?"pointer":"",visibility:"visible"}).show(),_.get("closeButton")?B.html(_.get("close")).appendTo(b):B.appendTo("<div/>"),w()}}function p(){x||(V=!1,E=t(i),x=n(se).attr({id:Y,"class":t.support.opacity===!1?Z+"IE":"",role:"dialog",tabindex:"-1"}).hide(),v=n(se,"Overlay").hide(),L=t([n(se,"LoadingOverlay")[0],n(se,"LoadingGraphic")[0]]),y=n(se,"Wrapper"),b=n(se,"Content").append(F=n(se,"Title"),R=n(se,"Current"),P=t('<button type="button"/>').attr({id:Z+"Previous"}),K=t('<button type="button"/>').attr({id:Z+"Next"}),S=t('<button type="button"/>').attr({id:Z+"Slideshow"}),L),B=t('<button type="button"/>').attr({id:Z+"Close"}),y.append(n(se).append(n(se,"TopLeft"),T=n(se,"TopCenter"),n(se,"TopRight")),n(se,!1,"clear:left").append(C=n(se,"MiddleLeft"),b,H=n(se,"MiddleRight")),n(se,!1,"clear:left").append(n(se,"BottomLeft"),k=n(se,"BottomCenter"),n(se,"BottomRight"))).find("div div").css({"float":"left"}),M=n(se,!1,"position:absolute; width:9999px; visibility:hidden; display:none; max-width:none;"),O=K.add(P).add(R).add(S)),e.body&&!x.parent().length&&t(e.body).append(v,x.append(y,M))}function m(){function i(t){t.which>1||t.shiftKey||t.altKey||t.metaKey||t.ctrlKey||(t.preventDefault(),f(this))}return x?(V||(V=!0,K.click(function(){J.next()}),P.click(function(){J.prev()}),B.click(function(){J.close()}),v.click(function(){_.get("overlayClose")&&J.close()}),t(e).bind("keydown."+Z,function(t){var e=t.keyCode;U&&_.get("escKey")&&27===e&&(t.preventDefault(),J.close()),U&&_.get("arrowKey")&&W[1]&&!t.altKey&&(37===e?(t.preventDefault(),P.click()):39===e&&(t.preventDefault(),K.click()))}),t.isFunction(t.fn.on)?t(e).on("click."+Z,"."+te,i):t("."+te).live("click."+Z,i)),!0):!1}function w(){var e,o,r,h=J.prep,d=++le;if($=!0,q=!1,u(he),u(ie),_.get("onLoad"),_.h=_.get("height")?a(_.get("height"),"y")-N-j:_.get("innerHeight")&&a(_.get("innerHeight"),"y"),_.w=_.get("width")?a(_.get("width"),"x")-z-D:_.get("innerWidth")&&a(_.get("innerWidth"),"x"),_.mw=_.w,_.mh=_.h,_.get("maxWidth")&&(_.mw=a(_.get("maxWidth"),"x")-z-D,_.mw=_.w&&_.w<_.mw?_.w:_.mw),_.get("maxHeight")&&(_.mh=a(_.get("maxHeight"),"y")-N-j,_.mh=_.h&&_.h<_.mh?_.h:_.mh),e=_.get("href"),Q=setTimeout(function(){L.show()},100),_.get("inline")){var c=t(e).eq(0);r=t("<div>").hide().insertBefore(c),ae.one(he,function(){r.replaceWith(c)}),h(c)}else _.get("iframe")?h(" "):_.get("html")?h(_.get("html")):s(_,e)?(e=l(_,e),q=_.get("createImg"),t(q).addClass(Z+"Photo").bind("error."+Z,function(){h(n(se,"Error").html(_.get("imgError")))}).one("load",function(){d===le&&setTimeout(function(){var e;_.get("retinaImage")&&i.devicePixelRatio>1&&(q.height=q.height/i.devicePixelRatio,q.width=q.width/i.devicePixelRatio),_.get("scalePhotos")&&(o=function(){q.height-=q.height*e,q.width-=q.width*e},_.mw&&q.width>_.mw&&(e=(q.width-_.mw)/q.width,o()),_.mh&&q.height>_.mh&&(e=(q.height-_.mh)/q.height,o())),_.h&&(q.style.marginTop=Math.max(_.mh-q.height,0)/2+"px"),W[1]&&(_.get("loop")||W[A+1])&&(q.style.cursor="pointer",t(q).bind("click."+Z,function(){J.next()})),q.style.width=q.width+"px",q.style.height=q.height+"px",h(q)},1)}),q.src=e):e&&M.load(e,_.get("data"),function(e,i){d===le&&h("error"===i?n(se,"Error").html(_.get("xhrError")):t(this).contents())})}var v,x,y,b,T,C,H,k,W,E,I,M,L,F,R,S,K,P,B,O,_,j,D,N,z,A,q,U,$,G,Q,J,V,X={html:!1,photo:!1,iframe:!1,inline:!1,transition:"elastic",speed:300,fadeOut:300,width:!1,initialWidth:"600",innerWidth:!1,maxWidth:!1,height:!1,initialHeight:"450",innerHeight:!1,maxHeight:!1,scalePhotos:!0,scrolling:!0,opacity:.9,preloading:!0,className:!1,overlayClose:!0,escKey:!0,arrowKey:!0,top:!1,bottom:!1,left:!1,right:!1,fixed:!1,data:void 0,closeButton:!0,fastIframe:!0,open:!1,reposition:!0,loop:!0,slideshow:!1,slideshowAuto:!0,slideshowSpeed:2500,slideshowStart:"start slideshow",slideshowStop:"stop slideshow",photoRegex:/\.(gif|png|jp(e|g|eg)|bmp|ico|webp|jxr|svg)((#|\?).*)?$/i,retinaImage:!1,retinaUrl:!1,retinaSuffix:"@2x.$1",current:"image {current} of {total}",previous:"previous",next:"next",close:"close",xhrError:"This content failed to load.",imgError:"This image failed to load.",returnFocus:!0,trapFocus:!0,onOpen:!1,onLoad:!1,onComplete:!1,onCleanup:!1,onClosed:!1,rel:function(){return this.rel},href:function(){return t(this).attr("href")},title:function(){return this.title},createImg:function(){var e=new Image,i=t(this).data("cbox-img-attrs");return"object"==typeof i&&t.each(i,function(t,i){e[t]=i}),e},createIframe:function(){var i=e.createElement("iframe"),n=t(this).data("cbox-iframe-attrs");return"object"==typeof n&&t.each(n,function(t,e){i[t]=e}),"frameBorder"in i&&(i.frameBorder=0),"allowTransparency"in i&&(i.allowTransparency="true"),i.name=(new Date).getTime(),i.allowFullscreen=!0,i}},Y="colorbox",Z="cbox",te=Z+"Element",ee=Z+"_open",ie=Z+"_load",ne=Z+"_complete",oe=Z+"_cleanup",re=Z+"_closed",he=Z+"_purge",ae=t("<a/>"),se="div",le=0,de={},ce=function(){function t(){clearTimeout(h)}function e(){(_.get("loop")||W[A+1])&&(t(),h=setTimeout(J.next,_.get("slideshowSpeed")))}function i(){S.html(_.get("slideshowStop")).unbind(s).one(s,n),ae.bind(ne,e).bind(ie,t),x.removeClass(a+"off").addClass(a+"on")}function n(){t(),ae.unbind(ne,e).unbind(ie,t),S.html(_.get("slideshowStart")).unbind(s).one(s,function(){J.next(),i()}),x.removeClass(a+"on").addClass(a+"off")}function o(){r=!1,S.hide(),t(),ae.unbind(ne,e).unbind(ie,t),x.removeClass(a+"off "+a+"on")}var r,h,a=Z+"Slideshow_",s="click."+Z;return function(){r?_.get("slideshow")||(ae.unbind(oe,o),o()):_.get("slideshow")&&W[1]&&(r=!0,ae.one(oe,o),_.get("slideshowAuto")?i():n(),S.show())}}();t[Y]||(t(p),J=t.fn[Y]=t[Y]=function(e,i){var n,o=this;return e=e||{},t.isFunction(o)&&(o=t("<a/>"),e.open=!0),o[0]?(p(),m()&&(i&&(e.onComplete=i),o.each(function(){var i=t.data(this,Y)||{};t.data(this,Y,t.extend(i,e))}).addClass(te),n=new r(o[0],e),n.get("open")&&f(o[0])),o):o},J.position=function(e,i){function n(){T[0].style.width=k[0].style.width=b[0].style.width=parseInt(x[0].style.width,10)-D+"px",b[0].style.height=C[0].style.height=H[0].style.height=parseInt(x[0].style.height,10)-j+"px"}var r,h,s,l=0,d=0,c=x.offset();if(E.unbind("resize."+Z),x.css({top:-9e4,left:-9e4}),h=E.scrollTop(),s=E.scrollLeft(),_.get("fixed")?(c.top-=h,c.left-=s,x.css({position:"fixed"})):(l=h,d=s,x.css({position:"absolute"})),d+=_.get("right")!==!1?Math.max(E.width()-_.w-z-D-a(_.get("right"),"x"),0):_.get("left")!==!1?a(_.get("left"),"x"):Math.round(Math.max(E.width()-_.w-z-D,0)/2),l+=_.get("bottom")!==!1?Math.max(o()-_.h-N-j-a(_.get("bottom"),"y"),0):_.get("top")!==!1?a(_.get("top"),"y"):Math.round(Math.max(o()-_.h-N-j,0)/2),x.css({top:c.top,left:c.left,visibility:"visible"}),y[0].style.width=y[0].style.height="9999px",r={width:_.w+z+D,height:_.h+N+j,top:l,left:d},e){var g=0;t.each(r,function(t){return r[t]!==de[t]?(g=e,void 0):void 0}),e=g}de=r,e||x.css(r),x.dequeue().animate(r,{duration:e||0,complete:function(){n(),$=!1,y[0].style.width=_.w+z+D+"px",y[0].style.height=_.h+N+j+"px",_.get("reposition")&&setTimeout(function(){E.bind("resize."+Z,J.position)},1),t.isFunction(i)&&i()},step:n})},J.resize=function(t){var e;U&&(t=t||{},t.width&&(_.w=a(t.width,"x")-z-D),t.innerWidth&&(_.w=a(t.innerWidth,"x")),I.css({width:_.w}),t.height&&(_.h=a(t.height,"y")-N-j),t.innerHeight&&(_.h=a(t.innerHeight,"y")),t.innerHeight||t.height||(e=I.scrollTop(),I.css({height:"auto"}),_.h=I.height()),I.css({height:_.h}),e&&I.scrollTop(e),J.position("none"===_.get("transition")?0:_.get("speed")))},J.prep=function(i){function o(){return _.w=_.w||I.width(),_.w=_.mw&&_.mw<_.w?_.mw:_.w,_.w}function a(){return _.h=_.h||I.height(),_.h=_.mh&&_.mh<_.h?_.mh:_.h,_.h}if(U){var d,g="none"===_.get("transition")?0:_.get("speed");I.remove(),I=n(se,"LoadedContent").append(i),I.hide().appendTo(M.show()).css({width:o(),overflow:_.get("scrolling")?"auto":"hidden"}).css({height:a()}).prependTo(b),M.hide(),t(q).css({"float":"none"}),c(_.get("className")),d=function(){function i(){t.support.opacity===!1&&x[0].style.removeAttribute("filter")}var n,o,a=W.length;U&&(o=function(){clearTimeout(Q),L.hide(),u(ne),_.get("onComplete")},F.html(_.get("title")).show(),I.show(),a>1?("string"==typeof _.get("current")&&R.html(_.get("current").replace("{current}",A+1).replace("{total}",a)).show(),K[_.get("loop")||a-1>A?"show":"hide"]().html(_.get("next")),P[_.get("loop")||A?"show":"hide"]().html(_.get("previous")),ce(),_.get("preloading")&&t.each([h(-1),h(1)],function(){var i,n=W[this],o=new r(n,t.data(n,Y)),h=o.get("href");h&&s(o,h)&&(h=l(o,h),i=e.createElement("img"),i.src=h)})):O.hide(),_.get("iframe")?(n=_.get("createIframe"),_.get("scrolling")||(n.scrolling="no"),t(n).attr({src:_.get("href"),"class":Z+"Iframe"}).one("load",o).appendTo(I),ae.one(he,function(){n.src="//about:blank"}),_.get("fastIframe")&&t(n).trigger("load")):o(),"fade"===_.get("transition")?x.fadeTo(g,1,i):i())},"fade"===_.get("transition")?x.fadeTo(g,0,function(){J.position(0,d)}):J.position(g,d)}},J.next=function(){!$&&W[1]&&(_.get("loop")||W[A+1])&&(A=h(1),f(W[A]))},J.prev=function(){!$&&W[1]&&(_.get("loop")||A)&&(A=h(-1),f(W[A]))},J.close=function(){U&&!G&&(G=!0,U=!1,u(oe),_.get("onCleanup"),E.unbind("."+Z),v.fadeTo(_.get("fadeOut")||0,0),x.stop().fadeTo(_.get("fadeOut")||0,0,function(){x.hide(),v.hide(),u(he),I.remove(),setTimeout(function(){G=!1,u(re),_.get("onClosed")},1)}))},J.remove=function(){x&&(x.stop(),t[Y].close(),x.stop(!1,!0).remove(),v.remove(),G=!1,x=null,t("."+te).removeData(Y).removeClass(te),t(e).unbind("click."+Z).unbind("keydown."+Z))},J.element=function(){return t(_.el)},J.settings=X)})(jQuery,document,window);
// Autosize 1.18.15
!function(e){var t,o={className:"autosizejs",id:"autosizejs",append:"\n",callback:!1,resizeDelay:10,placeholder:!0},i='<textarea tabindex="-1" style="position:absolute; top:-999px; left:0; right:auto; bottom:auto; border:0; padding: 0; -moz-box-sizing:content-box; -webkit-box-sizing:content-box; box-sizing:content-box; word-wrap:break-word; height:0 !important; min-height:0 !important; overflow:hidden; transition:none; -webkit-transition:none; -moz-transition:none;"/>',n=["fontFamily","fontSize","fontWeight","fontStyle","letterSpacing","textTransform","wordSpacing","textIndent","whiteSpace"],a=e(i).data("autosize",!0)[0];a.style.lineHeight="99px","99px"===e(a).css("lineHeight")&&n.push("lineHeight"),a.style.lineHeight="",e.fn.autosize=function(i){return this.length?(i=e.extend({},o,i||{}),a.parentNode!==document.body&&e(document.body).append(a),this.each(function(){function o(){var t,o=window.getComputedStyle?window.getComputedStyle(u,null):!1;o?(t=u.getBoundingClientRect().width,(0===t||"number"!=typeof t)&&(t=parseFloat(o.width)),e.each(["paddingLeft","paddingRight","borderLeftWidth","borderRightWidth"],function(e,i){t-=parseFloat(o[i])})):t=p.width(),a.style.width=Math.max(t,0)+"px"}function s(){var s={};if(t=u,a.className=i.className,a.id=i.id,d=parseFloat(p.css("maxHeight")),e.each(n,function(e,t){s[t]=p.css(t)}),e(a).css(s).attr("wrap",p.attr("wrap")),o(),window.chrome){var r=u.style.width;u.style.width="0px";{u.offsetWidth}u.style.width=r}}function r(){var e,n;t!==u?s():o(),a.value=!u.value&&i.placeholder?p.attr("placeholder")||"":u.value,a.value+=i.append||"",a.style.overflowY=u.style.overflowY,n=parseFloat(u.style.height),a.scrollTop=0,a.scrollTop=9e4,e=a.scrollTop,d&&e>d?(u.style.overflowY="scroll",e=d):(u.style.overflowY="hidden",c>e&&(e=c)),e+=w,n!==e&&(u.style.height=e+"px",a.className=a.className,f&&i.callback.call(u,u),p.trigger("autosize.resized"))}function l(){clearTimeout(h),h=setTimeout(function(){var e=p.width();e!==g&&(g=e,r())},parseInt(i.resizeDelay,10))}var d,c,h,u=this,p=e(u),w=0,f=e.isFunction(i.callback),z={height:u.style.height,overflow:u.style.overflow,overflowY:u.style.overflowY,wordWrap:u.style.wordWrap,resize:u.style.resize},g=p.width(),y=p.css("resize");p.data("autosize")||(p.data("autosize",!0),("border-box"===p.css("box-sizing")||"border-box"===p.css("-moz-box-sizing")||"border-box"===p.css("-webkit-box-sizing"))&&(w=p.outerHeight()-p.height()),c=Math.max(parseFloat(p.css("minHeight"))-w||0,p.height()),p.css({overflow:"hidden",overflowY:"hidden",wordWrap:"break-word"}),"vertical"===y?p.css("resize","none"):"both"===y&&p.css("resize","horizontal"),"onpropertychange"in u?"oninput"in u?p.on("input.autosize keyup.autosize",r):p.on("propertychange.autosize",function(){"value"===event.propertyName&&r()}):p.on("input.autosize",r),i.resizeDelay!==!1&&e(window).on("resize.autosize",l),p.on("autosize.resize",r),p.on("autosize.resizeIncludeStyle",function(){t=null,r()}),p.on("autosize.destroy",function(){t=null,clearTimeout(h),e(window).off("resize",l),p.off("autosize").off(".autosize").css(z).removeData("autosize")}),r())})):this}}(jQuery||$);
// code.accursoft.com/caret/overview - set cursor position
!function(e){e.fn.caret=function(e){var t=this[0],n="true"===t.contentEditable;if(0==arguments.length){if(window.getSelection){if(n){t.focus();var o=window.getSelection().getRangeAt(0),r=o.cloneRange();return r.selectNodeContents(t),r.setEnd(o.endContainer,o.endOffset),r.toString().length}return t.selectionStart}if(document.selection){if(t.focus(),n){var o=document.selection.createRange(),r=document.body.createTextRange();return r.moveToElementText(t),r.setEndPoint("EndToEnd",o),r.text.length}var e=0,c=t.createTextRange(),r=document.selection.createRange().duplicate(),a=r.getBookmark();for(c.moveToBookmark(a);0!==c.moveStart("character",-1);)e++;return e}return 0}if(-1==e&&(e=this[n?"text":"val"]().length),window.getSelection)n?(t.focus(),window.getSelection().collapse(t.firstChild,e)):t.setSelectionRange(e,e);else if(document.body.createTextRange)if(n){var c=document.body.createTextRange();c.moveToElementText(t),c.moveStart("character",e),c.collapse(!0),c.select()}else{var c=t.createTextRange();c.move("character",e),c.select()}return n||t.focus(),e}}(jQuery);
// autoComplete (mod: width+17)
!function(e){e.fn.autoComplete=function(t){var o=e.extend({},e.fn.autoComplete.defaults,t);return"string"==typeof t?(this.each(function(){var o=e(this);"destroy"==t&&(e(window).off("resize.autocomplete",o.updateSC),o.off("blur.autocomplete focus.autocomplete keydown.autocomplete keyup.autocomplete"),o.data("autocomplete")?o.attr("autocomplete",o.data("autocomplete")):o.removeAttr("autocomplete"),e(o.data("sc")).remove(),o.removeData("sc").removeData("autocomplete"))}),this):this.each(function(){function t(e){var t=s.val();if(s.cache[t]=e,e.length&&t.length>=o.minChars){for(var a="",c=0;c<e.length;c++)a+=o.renderItem(e[c],t);s.sc.html(a),s.updateSC(0)}else s.sc.hide()}var s=e(this);s.sc=e('<div class="autocomplete-suggestions '+o.menuClass+'"></div>'),s.data("sc",s.sc).data("autocomplete",s.attr("autocomplete")),s.attr("autocomplete","off"),s.cache={},s.last_val="",s.updateSC=function(t,o){if(s.sc.css({top:s.offset().top+s.outerHeight(),left:s.offset().left,width:s.outerWidth()+17}),!t&&(s.sc.show(),s.sc.maxHeight||(s.sc.maxHeight=parseInt(s.sc.css("max-height"))),s.sc.suggestionHeight||(s.sc.suggestionHeight=e(".autocomplete-suggestion",s.sc).first().outerHeight()),s.sc.suggestionHeight))if(o){var a=s.sc.scrollTop(),c=o.offset().top-s.sc.offset().top;c+s.sc.suggestionHeight-s.sc.maxHeight>0?s.sc.scrollTop(c+s.sc.suggestionHeight+a-s.sc.maxHeight):0>c&&s.sc.scrollTop(c+a)}else s.sc.scrollTop(0)},e(window).on("resize.autocomplete",s.updateSC),s.sc.appendTo("body"),s.sc.on("mouseleave",".autocomplete-suggestion",function(){e(".autocomplete-suggestion.selected").removeClass("selected")}),s.sc.on("mouseenter",".autocomplete-suggestion",function(){e(".autocomplete-suggestion.selected").removeClass("selected"),e(this).addClass("selected")}),s.sc.on("mousedown",".autocomplete-suggestion",function(t){var a=e(this),c=a.data("val");return(c||a.hasClass("autocomplete-suggestion"))&&(s.val(c),o.onSelect(t,c,a),s.sc.hide()),!1}),s.on("blur.autocomplete",function(){try{over_sb=e(".autocomplete-suggestions:hover").length}catch(t){over_sb=0}over_sb?s.is(":focus")||setTimeout(function(){s.focus()},20):(s.last_val=s.val(),s.sc.hide(),setTimeout(function(){s.sc.hide()},350))}),o.minChars||s.on("focus.autocomplete",function(){s.last_val="\n",s.trigger("keyup.autocomplete")}),s.on("keydown.autocomplete",function(t){if((40==t.which||38==t.which)&&s.sc.html()){var a,c=e(".autocomplete-suggestion.selected",s.sc);return c.length?(a=40==t.which?c.next(".autocomplete-suggestion"):c.prev(".autocomplete-suggestion"),a.length?(c.removeClass("selected"),s.val(a.addClass("selected").data("val"))):(c.removeClass("selected"),s.val(s.last_val),a=0)):(a=40==t.which?e(".autocomplete-suggestion",s.sc).first():e(".autocomplete-suggestion",s.sc).last(),s.val(a.addClass("selected").data("val"))),s.updateSC(0,a),!1}if(27==t.which)s.val(s.last_val).sc.hide();else if(13==t.which||9==t.which){var c=e(".autocomplete-suggestion.selected",s.sc);c.length&&s.sc.is(":visible")&&(o.onSelect(t,c.data("val"),c),setTimeout(function(){s.sc.hide()},20))}}),s.on("keyup.autocomplete",function(a){if(!~e.inArray(a.which,[13,27,35,36,37,38,39,40])){var c=s.val();if(c.length>=o.minChars){if(c!=s.last_val){if(s.last_val=c,clearTimeout(s.timer),o.cache){if(c in s.cache)return void t(s.cache[c]);for(var l=1;l<c.length-o.minChars;l++){var i=c.slice(0,c.length-l);if(i in s.cache&&!s.cache[i].length)return void t([])}}s.timer=setTimeout(function(){o.source(c,t)},o.delay)}}else s.last_val=c,s.sc.hide()}})})},e.fn.autoComplete.defaults={source:0,minChars:3,delay:150,cache:1,menuClass:"",renderItem:function(e,t){t=t.replace(/[-\/\\^$*+?.()|[\]{}]/g,"\\$&");var o=new RegExp("("+t.split(" ").join("|")+")","gi");return'<div class="autocomplete-suggestion" data-val="'+e+'">'+e.replace(o,"<b>$1</b>")+"</div>"},onSelect:function(e,t,o){}}}(jQuery);
// flexImages
!function(t){function e(t,a,r,n){function o(t){r.maxRows&&d>r.maxRows||r.truncate&&t&&d>1?w[g][0].style.display="none":(w[g][4]&&(w[g][3].attr("src",w[g][4]),w[g][4]=""),w[g][0].style.width=l+"px",w[g][0].style.height=u+"px",w[g][0].style.display="block")}var g,l,s=1,d=1,f=t.width()-2,w=[],c=0,u=r.rowHeight;for(f||(f=t.width()-2),i=0;i<a.length;i++)if(w.push(a[i]),c+=a[i][2]+r.margin,c>=f){var m=w.length*r.margin;for(s=(f-m)/(c-m),u=Math.ceil(r.rowHeight*s),exact_w=0,l,g=0;g<w.length;g++)l=Math.ceil(w[g][2]*s),exact_w+=l+r.margin,exact_w>f&&(l-=exact_w-f),o();w=[],c=0,d++}for(g=0;g<w.length;g++)l=Math.floor(w[g][2]*s),h=Math.floor(r.rowHeight*s),o(!0);n||f==t.width()||e(t,a,r,!0)}t.fn.flexImages=function(a){var i=t.extend({container:".item",object:"img",rowHeight:180,maxRows:0,truncate:0},a);return this.each(function(){var a=t(this),r=t(a).find(i.container),n=[],o=(new Date).getTime(),h=window.getComputedStyle?getComputedStyle(r[0],null):r[0].currentStyle;for(i.margin=(parseInt(h.marginLeft)||0)+(parseInt(h.marginRight)||0)+(Math.round(parseFloat(h.borderLeftWidth))||0)+(Math.round(parseFloat(h.borderRightWidth))||0),j=0;j<r.length;j++){var g=r[j],l=parseInt(g.getAttribute("data-w")),s=l*(i.rowHeight/parseInt(g.getAttribute("data-h"))),d=t(g).find(i.object);n.push([g,l,s,d,d.data("src")])}e(a,n,i),t(window).off("resize.flexImages"+a.data("flex-t")),t(window).on("resize.flexImages"+o,function(){e(a,n,i)}),a.data("flex-t",o)})}}(jQuery);
// mod: https://github.com/luis-almeida/unveil
!function(t){t.fn.unveil=function(i){function e(){var i=u.filter(function(){var i=t(this);if(!i.is(":hidden")){var e=r.scrollTop(),n=e+r.height(),u=i.offset().top,o=u+i.height();return o>=e-s&&n+s>=u}});n=i.trigger("unveil"),u=u.not(n)}var n,r=t(window),s=i||0,u=this;return this.one("unveil",function(){var t=this.getAttribute("data-lazy"),i=this.getAttribute("data-lazy-srcset");t&&this.setAttribute("src",t),i&&this.setAttribute("srcset",i)}),r.on("scroll.unveil resize.unveil lookup.unveil",e),e(),this}}(window.jQuery);

String.prototype.rsplit = function(sep, maxsplit){var split=this.split(sep); return maxsplit?[split.slice(0, -maxsplit).join(sep)].concat(split.slice(-maxsplit)):split;}

function getCookie(k){return(document.cookie.match('(^|; )'+k+'=([^;]*)')||0)[2]}
function setCookie(k,v,d,s){var o=new Date;o.setTime(o.getTime()+864e5*d+1000*(s||0)),document.cookie=k+"="+v+";path=/;expires="+o.toGMTString()}

window.linkify=(function(){var f="[a-z\\d.-]+://",h="(?:(?:[0-9]|[1-9]\\d|1\\d{2}|2[0-4]\\d|25[0-5])\\.){3}(?:[0-9]|[1-9]\\d|1\\d{2}|2[0-4]\\d|25[0-5])",g="(?:(?:[^\\s!@#$%^&*()_=+[\\]{}\\\\|;:'\",.<>/?]+)\\.)+",m="(?:at|biz|com|ch|co|de|edu|es|eu|fr|gov|info|it|ly|me|mobi|nl|net|org|to|uk|us|ws)",c="(?:"+g+m+"|"+h+")",n="(?:[;/][^#?<>\\s]*)?",d="(?:\\?[^#<>\\s]*)?(?:#[^<>\\s]*)?",e="\\b"+f+"[^<>\\s]+",b="\\b"+c+n+d+"(?!\\w)",l="mailto:",i="(?:"+l+")?[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@"+c+d+"(?!\\w)",a=new RegExp("(?:"+e+"|"+b+"|"+i+")","ig"),j=new RegExp("^"+f,"i"),k={"'":"`",">":"<",")":"(","]":"[","}":"{","»":"«","›":"‹"};return function(u,D){D=D||{};var w,t,y,p,v="",s=[],r,C,B,o,q,A,z,x;while(w=a.exec(u)){y=w[0];C=a.lastIndex;B=C-y.length;if(/[\/:]/.test(u.charAt(B-1))){continue}do{o=y;x=y.substr(-1);z=k[x];if(z){q=y.match(new RegExp("\\"+z+"(?!$)","g"));A=y.match(new RegExp("\\"+x,"g"));if((q?q.length:0)<(A?A.length:0)){y=y.substr(0,y.length-1);C--}}y=y.replace(/(?:[!?.,:;'"]|(?:&|&amp;)(?:lt|gt|quot|apos|raquo|laquo|rsaquo|lsaquo);)$/,function(E){C-=E.length;return""})}while(y.length&&y!==o);p=y;if(!j.test(p)){p=(p.indexOf("@")!==-1?(!p.indexOf(l)?"":l):!p.indexOf("irc.")?"irc://":!p.indexOf("ftp.")?"ftp://":"http://")+p}if(r!=B){s.push([u.slice(r,B)]);r=C}s.push([y,p])}s.push([u.substr(r)]);for(t=0;t<s.length;t++){text=s[t][0];p=s[t][1];target=text.indexOf(window.location.hostname)>=0?"":'target="_blank" ';v+=(p?"<a "+target+'href="'+p+'">'+text+"</a>":text)}return v||u}})();


// detect bots
if (getCookie('is_human')) {
    is_human = true;
} else {
    $(document).one('keydown', 'input[type=text]', function(){ setCookie('is_human', 1, 8640000); is_human = true; });
    $(document).one('mousemove', function(){ setCookie('is_human', 1, 8640000); is_human = true; });
}


function wopen(url, w, h, target){
    target = target || ''; w = w || 640; h = h || 350;
    var left = (window.screenLeft ? window.screenLeft : window.screenX) + (ww/2)-(w/2);
    var top = (window.screenTop ? window.screenTop : window.screenY) + (wh/2)-(h/2)-100;
    if (top<=0) top = 20;
    window.open(url, target, 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,width='+w+',height='+h+',top='+top+',left='+left);
    return false;
}


var ww, wh, pure_menu;
if ('ontouchstart' in window) {
    is_human = true;
    $('.pure-dropdown>a').click(function(e){
        pure_menu = $(this).parent();
        var sub = pure_menu.toggleClass('pure-menu-open').find('.pure-menu-children').css('top', pure_menu.height());
        if (pure_menu.offset().left + sub.outerWidth() > ww && ww > sub.outerWidth())
            sub.css('left', pure_menu.width()-sub.outerWidth());
        else
            sub.css('left', 0);
        return false;
    });
    $(document).mousedown(function(e){
        if (pure_menu && !pure_menu.is(e.target) && !pure_menu.has(e.target).length) {
            $('.pure-dropdown').removeClass('pure-menu-open');
            pure_menu = false;
        }
    });
} else {
    $('.pure-dropdown>a').mouseenter(function(e){
        pure_menu = $(this).parent();
        var sub = pure_menu.toggleClass('pure-menu-open').find('.pure-menu-children').css('top', pure_menu.height());
        if (pure_menu.offset().left + sub.outerWidth() > ww && ww > sub.outerWidth())
            sub.css('left', pure_menu.width()-sub.outerWidth());
        else
            sub.css('left', 0);
        return false;
    });
    $('.pure-dropdown').mouseleave(function(){
        $(this).removeClass('pure-menu-open');
        pure_menu = false;
    });
}


// clone menu items for mobile menus
var mm = $('#mobile_menu'), mum = $('#mobile_user_menu');
$('.mm_inc').each(function(){ mm.append($(this).clone()); if ($(this).hasClass('mm_sep')) mm.append('<li class="pure-menu-separator"></li>'); });
$('.mum_inc').each(function(){ mum.append($(this).clone().removeClass('hide-xs hide-sm hide-md')); });


var ajax_anim = false, loadingBar = $('<div id="loadingBar"><dt></dt><dd></dd></div>');
loadingBar.appendTo('body').hide();
$.ajaxSetup({
    beforeSend: function() { if (ajax_anim) { loadingBar.css('width', 0); loadingBar.show(); } },
    error: function(){ if (ajax_anim) { loadingBar.css('width', '102%').addClass('error').fadeOut(2000, function(){ loadingBar.removeClass('error').hide(); }); } },
    complete: function(xhr, status) {if (status!='error') loadingBar.hide(); ajax_anim = false; },
    xhr: function(){
        var xhr = new window.XMLHttpRequest();
        try { // IE
            xhr.upload.addEventListener('progress', function(e) { // upload
                if (e.lengthComputable) loadingBar.css('width', 2+100*e.loaded/e.total+'%');
            }, false);
            xhr.addEventListener('progress', function(e) { // download
                if (e.lengthComputable) loadingBar.css('width', 2+100*e.loaded/e.total+'%');
            }, false);
        } catch(e) {}
        return xhr;
    }
});


$(document).on('submit', '.ajax_form', function(e){
    e.preventDefault();
    var form = $(this), t = form.data('target') ? $(form.data('target')) : form;
    form.find('[type="submit"]').attr('disabled', true);
    ajax_anim = true;
    form.ajaxSubmit({
        success: function(re){
            form.find('[type="submit"]').removeAttr('disabled');
            if (re.substring(0,7) == 'script:') eval(re.substring(7));
            else if (form.data('replace')) t.replaceWith(re);
            else t.html(re);
            resized();
        },
        error: function(re){
            form.find('[type="submit"]').removeAttr('disabled');
            $('#loadingBar').css('width', '102%').addClass('error').fadeOut(2000, function(){ loadingBar.removeClass('error').hide(); });
            t.html('Server error '+re.status);
            resized();
        }
    });
});


// responsive Colorbox + resize events
var resizeTimer, cboxOptions = {
    width: '95%', height: '95%', maxWidth: 640, maxHeight: 0, speed: 0, fadeOut: 0, transition: 'none', reposition: false,
    closeButton: false, scrolling: false, trapFocus: false, opacity: .5,
    onCleanup: function(){ $.extend($.colorbox.settings, cboxOptions); }
}
$.extend($.colorbox.settings, cboxOptions);
function resized(autosized){
    ww = $(window).innerWidth();
    wh = $(window).innerHeight();
    setCookie('client_width', ww, 365);
    var o = $.colorbox.settings;
    $.colorbox.resize({ width: ww > o.maxWidth ? o.maxWidth : o.width, height: wh > o.maxHeight ? o.maxHeight : o.height });

    // livequery replacement
    if (!autosized) { // prevent recursion
        $('.autosize').not('.autosized').addClass('autosized').autosize({placeholder: 0, callback: function(){ setTimeout(function(){ resized(true); }, 150); }});
        $('.autosize').trigger('autosize.resize');
        if (is_ie || is_safari) { // placeholder polyfill
            $('[placeholder]').not('.polyfilled').addClass('polyfilled')
            .focus(function(){ var i = $(this); if (i.hasClass('placeholder')) { i.caret(0); } })
            .click(function(){ var i = $(this); if (i.hasClass('placeholder')) { i.caret(0); } })
            .keydown(function(){ var i = $(this); if (i.hasClass('placeholder')) { i.val(''); i.removeClass('placeholder'); } })
            .blur(function(){ var i = $(this); if (i.val() == '' || i.val() == i.attr('placeholder')) { i.addClass('placeholder').val(i.attr('placeholder')); } })
            .blur()
            .parents('form').not('.polyfilled').addClass('polyfilled').submit(function() {
                $(this).find('[placeholder]').each(function(){ if ($(this).val() == $(this).attr('placeholder')) $(this).val(''); })
            });
        }
    }
}
$(window).resize(function(){ clearTimeout(resizeTimer); resizeTimer = setTimeout(resized, 200); });

// make Colorbox draggable
$(document).on({
    mousedown: function(e){ e.preventDefault(); var os = $('#colorbox').offset(), dx = e.pageX-os.left, dy = e.pageY-os.top; $(document).on('mousemove.drag',  function(e){ $('#colorbox').offset({top: e.pageY-dy, left: e.pageX-dx}); }); },
    mouseup: function(){ $(document).unbind('mousemove.drag'); }
}, '#colorbox h6');

$(document).on('click contextmenu', '.modal', function(){
    var that = $(this);
    if (that.data('confirm') == null || confirm(that.data('confirm')))
        $.get(that.data('href') || this.href, function(re){
            if (re.substring(0,7) == 'script:') eval(re.substring(7));
            else {
                if (that.data('w')) $.colorbox.settings.maxWidth = that.data('w');
                $.colorbox({ html: re, overlayClose: that.data('no-overlay-close') == null, height: 'auto', onComplete: function(){ setTimeout(resized, 100); $('#cboxContent h6').prepend('<a onclick="$.colorbox.close();">×</a>'); } });
            }
        });
    return false;
});


$(document).on('click', '.ajax', function(){
    var link = $(this);
    if (link.data('confirm') == null || confirm(link.data('confirm') || I18N['delete'])) {
        ajax_anim = true;
        $.post(this.href || $(this).data('href'), function(re){
            if (re.substring(0,7) == 'script:') eval(re.substring(7));
            else (link.data('target')?$(link.data('target')):link).html(re);
            if (link.data('resized') != 'false') resized();
        });
    }
    return false;
});


$(document).on('click dblclick', '.remove_link', function(e){
    if (($(this).data('dblclick') && e.type == 'click')) return false;
    if ($(this).data('confirm') == null || confirm($(this).data('confirm') || I18N['delete'])) {
        var obj = $($(this).data('target'));
        obj.fadeOut(200, function(){ obj.remove(); });
        $.post(this.href || $(this).data('href'), function(re){ if (re.substring(0,7) == 'script:') eval(re.substring(7)); });
    }
    return false;
});


// used for comment links in list views
$(document).on('click', '[data-location]', function(e){ location = $(this).data('location'); });


$(document).on('click', '.tab_menu li', function(e) { $(this).siblings().removeClass('selected').end().addClass('selected'); return false; });


$(document).on('click', '.tiny_search label', function(e){
    var input = $(this).next(), form = $(this).closest('form');
    if (!form.hasClass('active')) form.addClass('active');
    else { if (input.hasClass('dirty')) form.submit(); else form.removeClass('active'); }
});


$(document).on('click', 'a[data-confirm]:not(.ajax, .modal, .remove_link)', function(e) { if (!confirm($(this).data('confirm') || I18N['delete'])) return false; });


var dd_box, max_zindex = 99999;
$(document).on('click', '.dd_box', function(){
    max_zindex += 1;
    var button = $(this);
    dd_box = button.next();
    if (dd_box.is(':visible')) { dd_box.hide(); dd_box = false; }
    else {
        $('.dd_box+div').hide();
        var button_top = button.position().top, l = button.position().left;
        if (button.data('left') || (button.offset().left + dd_box.outerWidth() > ww && button.offset().left + button.outerWidth() - dd_box.outerWidth() > 0))
            l += button.outerWidth() - dd_box.outerWidth();

        if (button.data('up') || (button.offset().top + button.outerHeight() + dd_box.outerHeight() > $(document).scrollTop()+wh && button.offset().top - dd_box.outerHeight() > 0))
            var t = button.position().top - dd_box.outerHeight() - 5;
        else
            var t = button.position().top + button.outerHeight() + 5;
        dd_box.unbind().css({ position: 'absolute', top: t+'px', left: l+'px', zIndex: max_zindex }).show();
    }
    return false;
});
$(document).click(function(e){ if (dd_box && !dd_box.is(e.target) && !dd_box.has(e.target).length) { dd_box.hide(); dd_box = false; } });


$('.toggle_mobile_search').on('click touchstart', function(){ $('body').toggleClass('show_mobile_search'); setTimeout(function(){ $('.header_search [name="q"]').focus(); }, 200); });


function flexVideoGrid(el, rowHeight, maxRows, truncate) {
    el.flexImages({ rowHeight: rowHeight, maxRows: maxRows, truncate: truncate }).find('.media').hover(
        function(){
            hover_media = $(this);
            hover_media_to = setTimeout(function(){
                var mp4 = hover_media.data('mp4');
                hover_media.prepend('<video style="display:none" onloadeddata="$(this).addClass(\'playing\').show();" autoplay muted loop><source type="video/mp4" src="'+mp4+'">'+(hover_media.data('webm') ? '<source type="video/webm" src="'+(mp4.rsplit('.', 1)[0]+'.webm')+'">' : '')+'</video>');
            }, 300);
        },
        function(){ $(this).find('video').remove(); clearTimeout(hover_media_to); }
    );
}


function show_message(text, msg_class, t, prepend){
    $('.message_box').remove();
    var box = $('<div class="message_box" onclick="$(this).remove();"><span style="opacity:.8;float:right;margin-right:8px;cursor:pointer;font-size:20px;line-height:1">×</span>'+text+'</div>');
    if (msg_class) box.addClass(msg_class);
    if (prepend)
        box.css({ 'position': 'relative', 'margin': 0 }).prependTo('body');
    else
        box.appendTo('body');
    box.delay(t||4500).slideUp();
}


function show_support_overlay(){
    var el = $('#support_overlay'), img = $('#media_container > img');
    if (el.length && img.width() > 370 && img.height() > 180) {
        $('#img_click_overlay').hide();
        el.show();
        if (img.height() > 470) {
            (function(d, s, id) {var js, fjs = d.getElementsByTagName(s)[0];if (d.getElementById(id)) return;js = d.createElement(s); js.id = id;js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.5&appId=114593902037957";fjs.parentNode.insertBefore(js, fjs);}(document, 'script', 'facebook-jssdk'));
            !function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");
        } else
            $('.hide_on_small_img').hide();
        $('#media_show .overlay').fadeIn();
    } else
        $('#media_show .overlay').fadeOut();
}


var dl_menu;
$(function(){
    resized();

    // redirect "?q=query" to /query/ and cleanup GET params
    $('.media_search').submit(function(){
        var q = $(this).find('.q').removeAttr('name').val();
        if (q) {
            $(this).attr('action', $(this).attr('action')+q+'/');
            setCookie('manual_search', 1, 0, 2); // set flag for noindex meta tag
        }

        // remove honeypot field if empty
        if (!$(this).find('[name="hp"]').val()) { $(this).find('[name="hp"]').removeAttr('name'); }

        // remove unused fields / GET params
        if (!$(this).find('[name="image_type"]').val() || $(this).find('[name="image_type"]').val() == 'video') { $(this).find('[name="image_type"]').removeAttr('name'); }
        if (!$(this).find('[name="order"]').val()) { $(this).find('[name="order"]').removeAttr('name'); }
        if (!$(this).find('[name="cat"]').val()) { $(this).find('[name="cat"]').removeAttr('name'); }
        if (!$(this).find('[name="min_width"]').val()) { $(this).find('[name="min_width"]').removeAttr('name'); }
        if (!$(this).find('[name="min_height"]').val()) { $(this).find('[name="min_height"]').removeAttr('name'); }

        if (!$(this).formSerialize()){
            window.location = $(this).attr('action');
            return false;
        }
    });

    $('.media_search .select_image_type span').click(function(){
        var form = $(this).closest('form'), type = $(this).data('type');
        $('.media_search .image_type').html($(this).text());
        if (type == 'video')
            form.attr('action', form.attr('action').replace('/photos/', '/videos/list/'));
        else
            form.attr('action', form.attr('action').replace('/videos/list/', '/photos/'));
        dd_box.hide(); dd_box = false;
        form.find('[name="image_type"]').val(type);
        if (form.find('[name="q"]').val()) form.submit();
        else form.find('[name="q"]').focus();
    });

    // set autofocus, unless page was loaded from cache with scroll position and on mobile devices (to prevent opening of virtual keyboard)
    setTimeout(function(){ if (!('ontouchstart' in window || window.location.hash || $(document).scrollTop())) $('[data-autofocus]').last().focus(); }, 250);

    if (~$.inArray(LANG, ['de', 'en', 'es', 'fr', 'it', 'ja', 'ko', 'pt']))
        $('.media_search input[name="q"]').autoComplete({
            onSelect: function(e){ if (e.type != 'keydown') $('.media_search').submit(); },
            source: function(term, response){
                if (!is_human || term.length > 30 || term.indexOf('/') > -1) return;
                try { ac_xhr.abort(); } catch(e){}
                ac_xhr = $.ajax({
                    url: '/suggest_'+LANG+'/', type: 'POST', dataType: 'json', contentType: 'application/json',
                    data: JSON.stringify({ "suggest": { "search_term": { "prefix": term, "completion": { "field": "suggest_"+LANG, "size": 5 } } } }),
                    success: function(re){
                        var suggestions = [];
                        $.each(re.suggest.search_term[0].options, function(){ suggestions.push(this.text); });
                        response(suggestions);
                    }
                });
            }
        });


    $(document).on('mousedown', '[data-go]', function(){
        if (!is_human) return;
        $(this).attr('href', '/go/?t='+$(this).data('go'));
        if ($(this).data('go').indexOf(window.location.hostname)==-1 && $(this).data('go').charAt(0)!='/') $(this).attr('target', '_blank');
        return false;
    });

    $('#toTop').click(function(){ $('body,html').animate({scrollTop: 0}); });
    $(window).scroll(function(){ if ($(this).scrollTop() > 480) $('#toTop').addClass('show'); else $('#toTop').removeClass('show'); });

    $(document).on('click', '.translate', function(){
        var url = 'http://translate.google.de/#auto|'+LANG+'|'+encodeURIComponent($($(this).data('el')).text().substring(0,999));
        return wopen(url, 800, 500, 'translate');
    });

    $('.linkify').each(function(){ $(this).html(linkify($(this).html())); });

    if ($('.media_list').length) {
        $('#paginator_clone').html($('.paginator').clone());

        if ($('.flex_grid.video').length) {
            flexVideoGrid($('.flex_grid.video.search_results'), 280, 0, false, '');
            $('.flex_grid.video.sponsored_videos').each(function(){
                flexVideoGrid($(this), parseInt($(this).data('rowHeight')) || 280, parseInt($(this).data('rows')) || 1, false);
            });
        } else {
            $('.flex_grid.search_results').flexImages({ rowHeight: 300 });
            $('.flex_grid.sponsored_images').each(function(){ $(this).flexImages({ rowHeight: parseInt($(this).data('rowHeight')) || 180, maxRows: parseInt($(this).data('rows')) || 1 }); });
        }

        $('[data-lazy]').unveil(400);
    } else if ($('#media_show.photo').length) {
        $('#media_container img').click(function(e){
            $('#support_overlay').hide();
            var container = $('#img_click_overlay');
            container.show();
            if (!container.data('cloned')) {
                container.data('cloned', 1).find('#download_menu_clone').append($('.download_menu').clone(true, true))
                    .end().find('#like_buttons_clone').append($('.like_buttons').clone(true, true));
            }
            $('#media_show .overlay').fadeIn(100);
        });
        $('#media_show .overlay').click(function(e){
            if ($(e.target).is('#support_overlay .pure-button, #download_menu_clone *:not(.modal)')) { e.stopPropagation(); return; }
            else if ($(e.target).is('#like_buttons_clone *')) return;
            var support_overlay_click = $('#support_overlay').is(':visible');
            $(this).fadeOut(200, function(){ if (support_overlay_click) $('#support_overlay').remove(); });
        });
        $('.dl_btn, .view_btn').click(function(){
            if (dl_menu && dl_menu.is(':visible')) {
                show_support_overlay();
                // prevent race condition with download menu handler
                setTimeout(function(){ if (dl_menu) dl_menu.hide(); }, 50);
            }
        });
        $('.flex_grid.sidebar_thumbs').flexImages({ rowHeight: 160, maxRows: 3 });
        $('.flex_grid.content_thumbs').flexImages({ rowHeight: 160, maxRows: 1 });
    } else if ($('#media_show.video').length) {
        flexVideoGrid($('.flex_grid.video.related_videos'), 240, 4, true);
        flexVideoGrid($('.flex_grid.video.sponsored_videos'), 240, 2, true);
    } else if ($('.signup_form.new').length) // registration
        $.get('/'+LANG+'/accounts/register/ets/', function(ts){
            $('.signup_form .data').each(function(){ var n = 'f-'+ts+'-'+$(this).attr('name'); $(this).attr('name', n)||$(this).prop('name', n); });
            $('.signup_form.new').show();
        });
    if ($('#media_show').length) {
        setTimeout(function(){ $('#media_show .init').removeClass('init'); }, 5000);
        $('#media_show .init').mouseleave(function(){ $(this).removeClass('init'); });

        $('.download_menu span').on('click contextmenu', function(){
            dl_menu = $(this).next();
            if (dl_menu.is(':visible'))
                dl_menu.hide();
            else {
                if (wh - dl_menu.prev().offset().top + $(document).scrollTop() > 250)
                    dl_menu.removeClass('ne').addClass('se');
                else
                    dl_menu.removeClass('se').addClass('ne');
                dl_menu.width(dl_menu.prev().width()+22).show().find('.selected input').prop('checked', true);
            }
            return false;
        });
        $(document).click(function(e){ if (dl_menu && !dl_menu.is(e.target) && !dl_menu.has(e.target).length) { dl_menu.hide(); dl_menu = null; } });

        $('.download_menu tr').click(function(){
            $('.download_menu tr').removeClass('selected');
            var input = $(this).find('input').eq(0);
            input.prop('checked', true);
            $('.download_menu [value="'+input.val()+'"]').closest('tr').addClass('selected');
            var file = input.val(), perm = input.data('perm');
            if (file.indexOf('/') < 0) file = '/'+LANG+'/'+$('.download_menu').data('type')+'/download/'+file;
            if (show_captcha && perm || !auth_user && perm == 'auth') {
                $('.dl_btn, .view_btn').addClass('modal');
                $('.dl_btn').attr('href', file+'?attachment&modal');
                $('.view_btn').attr('href', file+'?modal');
            } else {
                $('.dl_btn, .view_btn').removeClass('modal');
                $('.dl_btn').attr('href', file+'?attachment');
                $('.view_btn').attr('href', file);
            }
            $('.dl_btn').attr('target', input.data('target') ? '_blank' : '');
        });
        $('.download_menu tr:not(.no_default)').last().click();
    }

    // toggle SafeSearch checkbox
    var g_rated = getCookie('g_rated');
    if (window.location.host.indexOf('safesearch') > -1 || g_rated == 'permanent') {
        g_rated = true;
        $('.toggle_g_rated').attr('disabled', true).parent().parent().attr('title', 'SafeSearch is enabled and locked.').click(function(){ alert(this.title); });
    } else
        $(document).on('click', '.toggle_g_rated', function(){
            g_rated = $(this).is(':checked'); setCookie('g_rated', g_rated?1:'', 3650);
            $('.toggle_g_rated').prop('checked', g_rated);
        });
    if (g_rated) {
        $('.toggle_g_rated').prop('checked', true);
    } else if (g_rated != '') {
        nsfw_placeholder = '\
            <b class="nsfw_placeholder modal" data-href="/service/safesearch/" style="display:table;position:absolute;width:100%;height:100%;background:#111;color:#fff;text-align:center;font-weight:normal;cursor:pointer">\
                <span style="display:table-cell;vertical-align:middle;line-height:1.5;padding:0 10px">\
                    <span style="font-size:18px;display:block;margin:0 0 14px">'+I18N.adult_content+'</span>\
                    <span style="font-size:14px;color:#aaa">'+I18N.safesearch+'</span>\
                </span>\
            </b>';
        $('.flex_grid .item.nsfw').each(function(){ $(this).prepend(nsfw_placeholder); });
    }

    if (location.pathname.length > 4 && mobile_os && !getCookie('seen_app_hint') && mobile_os != 'Windows') {
        var html = '<div id="app_hint" style="position:fixed;bottom:0;padding:10px;background:#444;background:rgba(0,0,0,.9);box-shadow:0 0 6px rgba(0,0,0,.5);text-align:center;left:0;right:0;color:#eee">\
                    <span class="hide-xs" style="vertical-align:middle;margin-right:10px;font-size:14px">The Pixabay Mobile App</span>\
                    <i class="times" style="position:absolute;right:20px;cursor:pointer;color:#ddd;font-size:22px;font-weight:normal;line-height:35px">×</i>';
        if (mobile_os == 'iOS') html += '<a href="//itunes.apple.com/app/id1178021455"><img style="vertical-align:middle" src="/static/img/app_badge_apple.png" height="36"></a>';
        else html += '<a href="//play.google.com/store/apps/details?id=com.pixabay.pixabayapp"><img style="vertical-align:middle" src="/static/img/app_badge_google.png" height="36"></a>';
        $('body').append(html+'</div>');
        $('#app_hint a, #app_hint .times').click(function(){ setCookie('seen_app_hint', 1, 14); $('#app_hint').fadeOut(); });
    }

    $('a.ga_track, .ga_track a').click(function(){
        var url = this.href, target = this.target;
        if (typeof ga) ga('send', 'event', 'outbound', 'click', url, { 'transport': 'beacon', 'hitCallback': function(){ if (!target) document.location = url; } });
    });
});

/* Google Analytics */
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
ga('create', 'UA-20223345-1', {'sampleRate': 10}); ga('set', 'anonymizeIp', true); ga('send', 'pageview');

// cookies hint
if (LANG == 'de' && !getCookie('cookies_ok')) {
    var cookies_hint = {
        'de': 'Cookies helfen uns bei der Bereitstellung unserer Dienste. Durch die Nutzung der Website erklärst Du dich damit einverstanden, <a href="/service/privacy/#cookies">wie wir Cookies einsetzen</a>.'
        // 'en': 'To provide our services this site uses cookies. By continuing to browse the site you are agreeing to <a href="/service/privacy/#cookies">our use of cookies</a>.'
    }
    $('<div class="message_box info" style="position:relative;margin:0;">'+(cookies_hint[LANG] || cookies_hint['en'])+' &nbsp; <span class="pure-button button-sm" onclick="setCookie(\'cookies_ok\', 1, 365*5);$(\'.message_box\').remove();">OK</span></div>').prependTo('body');
}
