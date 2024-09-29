/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./resources/js/modules/ModuleApp.js":
/*!*******************************************!*\
  !*** ./resources/js/modules/ModuleApp.js ***!
  \*******************************************/
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

window.UserModule = __webpack_require__(/*! ./setting/user/UserModule.js */ "./resources/js/modules/setting/user/UserModule.js");
window.RoleModule = __webpack_require__(/*! ./setting/role/RoleModule.js */ "./resources/js/modules/setting/role/RoleModule.js");

var ModuleApp = function () {
  return {
    getName: function getName() {
      return $('.content .content-page').data('module-name');
    },
    // Auto call on reload page or content replaced
    init: function init() {
      var moduleName = this.getName();

      switch (moduleName) {
        case 'backend_setting_user':
          // script nodule scope
          UserModule.init();
          break;

        case 'backend_setting_role':
          // script nodule scope
          RoleModule.init();
          break;
      }
    },
    // Auto call on reload page
    documentEvent: function documentEvent() {
      UserModule.documentEvent();
      RoleModule.documentEvent();
    }
  };
}(); // webpack support


if ( true && typeof module.exports !== 'undefined') {
  module.exports = ModuleApp;
}

$(function () {
  ModuleApp.init();
  ModuleApp.documentEvent();
});

/***/ }),

/***/ "./resources/js/modules/setting/role/RoleModule.js":
/*!*********************************************************!*\
  !*** ./resources/js/modules/setting/role/RoleModule.js ***!
  \*********************************************************/
/***/ ((module) => {

var RoleModule = function () {
  var moduleName = 'backend_setting_role';
  var moduleScope = '[data-module-name="backend_setting_role"]';
  return {
    init: function init() {
      if (ModuleApp.getName() === moduleName) {
        console.log('Run your script/function module here.'); // Example call base functions
        // BaseApp.test();
      }
    },
    documentEvent: function documentEvent() {
      $(document).on('click', moduleScope + ' .btn-test', function () {
        // Example call base functions
        BaseApp.test();
      });
    }
  };
}(); // webpack support


if ( true && typeof module.exports !== 'undefined') {
  module.exports = RoleModule;
}

/***/ }),

/***/ "./resources/js/modules/setting/user/UserModule.js":
/*!*********************************************************!*\
  !*** ./resources/js/modules/setting/user/UserModule.js ***!
  \*********************************************************/
/***/ ((module) => {

var UserModule = function () {
  var moduleName = 'backend_setting_user';
  var moduleScope = '[data-module-name="backend_setting_user"]';
  return {
    init: function init() {
      if (ModuleApp.getName() == moduleName) {
        console.log('Run your script/function module here.');
      }
    },
    documentEvent: function documentEvent() {
      $(document).on('click', moduleScope + ' .btn-test', function () {
        // Example call Base functions
        BaseApp.test();
      });
    }
  };
}(); // webpack support


if ( true && typeof module.exports !== 'undefined') {
  module.exports = UserModule;
}

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
(() => {
/*!***************************************!*\
  !*** ./resources/js/modules/index.js ***!
  \***************************************/
window.ModuleApp = __webpack_require__(/*! ./ModuleApp.js */ "./resources/js/modules/ModuleApp.js");
})();

/******/ })()
;