/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(1);
__webpack_require__(2);
module.exports = __webpack_require__(3);


/***/ }),
/* 1 */
/***/ (function(module, exports) {

throw new Error("Module build failed: Error: Duplicate plugin/preset detected.\nIf you'd like to use two separate instances of a plugin,\nthey need separate names, e.g.\n\n  plugins: [\n    ['some-plugin', {}],\n    ['some-plugin', {}, 'some unique name'],\n  ]\n    at assertNoDuplicates (C:\\xampp\\htdocs\\liberty-mini-mart\\node_modules\\@babel\\core\\lib\\config\\config-descriptors.js:205:13)\n    at createDescriptors (C:\\xampp\\htdocs\\liberty-mini-mart\\node_modules\\@babel\\core\\lib\\config\\config-descriptors.js:114:3)\n    at createPluginDescriptors (C:\\xampp\\htdocs\\liberty-mini-mart\\node_modules\\@babel\\core\\lib\\config\\config-descriptors.js:105:10)\n    at alias (C:\\xampp\\htdocs\\liberty-mini-mart\\node_modules\\@babel\\core\\lib\\config\\config-descriptors.js:63:49)\n    at cachedFunction (C:\\xampp\\htdocs\\liberty-mini-mart\\node_modules\\@babel\\core\\lib\\config\\caching.js:33:19)\n    at plugins.plugins (C:\\xampp\\htdocs\\liberty-mini-mart\\node_modules\\@babel\\core\\lib\\config\\config-descriptors.js:28:77)\n    at mergeChainOpts (C:\\xampp\\htdocs\\liberty-mini-mart\\node_modules\\@babel\\core\\lib\\config\\config-chain.js:319:26)\n    at C:\\xampp\\htdocs\\liberty-mini-mart\\node_modules\\@babel\\core\\lib\\config\\config-chain.js:283:7\n    at buildRootChain (C:\\xampp\\htdocs\\liberty-mini-mart\\node_modules\\@babel\\core\\lib\\config\\config-chain.js:68:29)\n    at loadPrivatePartialConfig (C:\\xampp\\htdocs\\liberty-mini-mart\\node_modules\\@babel\\core\\lib\\config\\partial.js:85:55)\n    at Object.loadPartialConfig (C:\\xampp\\htdocs\\liberty-mini-mart\\node_modules\\@babel\\core\\lib\\config\\partial.js:110:18)\n    at Object.<anonymous> (C:\\xampp\\htdocs\\liberty-mini-mart\\node_modules\\babel-loader\\lib\\index.js:140:26)\n    at Generator.next (<anonymous>)\n    at asyncGeneratorStep (C:\\xampp\\htdocs\\liberty-mini-mart\\node_modules\\babel-loader\\lib\\index.js:3:103)\n    at _next (C:\\xampp\\htdocs\\liberty-mini-mart\\node_modules\\babel-loader\\lib\\index.js:5:194)\n    at C:\\xampp\\htdocs\\liberty-mini-mart\\node_modules\\babel-loader\\lib\\index.js:5:364\n    at new Promise (<anonymous>)\n    at Object.<anonymous> (C:\\xampp\\htdocs\\liberty-mini-mart\\node_modules\\babel-loader\\lib\\index.js:5:97)\n    at Object._loader (C:\\xampp\\htdocs\\liberty-mini-mart\\node_modules\\babel-loader\\lib\\index.js:220:18)\n    at Object.loader (C:\\xampp\\htdocs\\liberty-mini-mart\\node_modules\\babel-loader\\lib\\index.js:56:18)\n    at Object.<anonymous> (C:\\xampp\\htdocs\\liberty-mini-mart\\node_modules\\babel-loader\\lib\\index.js:51:12)");

/***/ }),
/* 2 */
/***/ (function(module, exports) {

throw new Error("Module build failed: Error: Duplicate plugin/preset detected.\nIf you'd like to use two separate instances of a plugin,\nthey need separate names, e.g.\n\n  plugins: [\n    ['some-plugin', {}],\n    ['some-plugin', {}, 'some unique name'],\n  ]\n    at assertNoDuplicates (C:\\xampp\\htdocs\\liberty-mini-mart\\node_modules\\@babel\\core\\lib\\config\\config-descriptors.js:205:13)\n    at createDescriptors (C:\\xampp\\htdocs\\liberty-mini-mart\\node_modules\\@babel\\core\\lib\\config\\config-descriptors.js:114:3)\n    at createPluginDescriptors (C:\\xampp\\htdocs\\liberty-mini-mart\\node_modules\\@babel\\core\\lib\\config\\config-descriptors.js:105:10)\n    at alias (C:\\xampp\\htdocs\\liberty-mini-mart\\node_modules\\@babel\\core\\lib\\config\\config-descriptors.js:63:49)\n    at cachedFunction (C:\\xampp\\htdocs\\liberty-mini-mart\\node_modules\\@babel\\core\\lib\\config\\caching.js:33:19)\n    at plugins.plugins (C:\\xampp\\htdocs\\liberty-mini-mart\\node_modules\\@babel\\core\\lib\\config\\config-descriptors.js:28:77)\n    at mergeChainOpts (C:\\xampp\\htdocs\\liberty-mini-mart\\node_modules\\@babel\\core\\lib\\config\\config-chain.js:319:26)\n    at C:\\xampp\\htdocs\\liberty-mini-mart\\node_modules\\@babel\\core\\lib\\config\\config-chain.js:283:7\n    at buildRootChain (C:\\xampp\\htdocs\\liberty-mini-mart\\node_modules\\@babel\\core\\lib\\config\\config-chain.js:68:29)\n    at loadPrivatePartialConfig (C:\\xampp\\htdocs\\liberty-mini-mart\\node_modules\\@babel\\core\\lib\\config\\partial.js:85:55)\n    at Object.loadPartialConfig (C:\\xampp\\htdocs\\liberty-mini-mart\\node_modules\\@babel\\core\\lib\\config\\partial.js:110:18)\n    at Object.<anonymous> (C:\\xampp\\htdocs\\liberty-mini-mart\\node_modules\\babel-loader\\lib\\index.js:140:26)\n    at Generator.next (<anonymous>)\n    at asyncGeneratorStep (C:\\xampp\\htdocs\\liberty-mini-mart\\node_modules\\babel-loader\\lib\\index.js:3:103)\n    at _next (C:\\xampp\\htdocs\\liberty-mini-mart\\node_modules\\babel-loader\\lib\\index.js:5:194)\n    at C:\\xampp\\htdocs\\liberty-mini-mart\\node_modules\\babel-loader\\lib\\index.js:5:364\n    at new Promise (<anonymous>)\n    at Object.<anonymous> (C:\\xampp\\htdocs\\liberty-mini-mart\\node_modules\\babel-loader\\lib\\index.js:5:97)\n    at Object.loader (C:\\xampp\\htdocs\\liberty-mini-mart\\node_modules\\babel-loader\\lib\\index.js:56:18)\n    at Object.<anonymous> (C:\\xampp\\htdocs\\liberty-mini-mart\\node_modules\\babel-loader\\lib\\index.js:51:12)");

/***/ }),
/* 3 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ })
/******/ ]);