
window._ = require('lodash');

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
  // modified by shlomo.kalner@gmail.com to include this check for
  // jQuery having been previously loaded via script tag..
  if (window.$ === undefined || 
        window.jQuery === undefined || 
        $ === undefined || 
        jQuery === undefined) {
    // alert('hello from vue-bootstrap!!');
    window.$ = window.jQuery = require('jquery');
  }
  if (window.$ === undefined && $ !== undefined) {
    window.$ = $;
  } else if (window.$ !== undefined && $ === undefined) {
    $ = window.$;
  }
  if (window.jQuery === undefined && jQuery !== undefined) {
    window.jQuery = jQuery;
  } else if (window.jQuery !== undefined && jQuery === undefined) {
    jQuery = window.jQuery;  
  }

  // we are appearing to have some conflict between this and
  //  and our local import... so commenting this out..
  // require('bootstrap-sass'); 
} catch (e) {}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
// an addition by Shlomo Kallner, to preload axios's default baseURL.
window.axios.defaults.baseURL = window.Laravel.baseUrl; 

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo'

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: 'your-pusher-key'
// });
