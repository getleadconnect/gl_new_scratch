/******/
(function (modules) { // webpackBootstrap
    /******/ 	// The module cache
    /******/
    var installedModules = {};
    /******/
    /******/ 	// The require function
    /******/
    function __webpack_require__(moduleId) {
        /******/
        /******/ 		// Check if module is in cache
        /******/
        if (installedModules[moduleId]) {
            /******/
            return installedModules[moduleId].exports;
            /******/
        }
        /******/ 		// Create a new module (and put it into the cache)
        /******/
        var module = installedModules[moduleId] = {
            /******/            i: moduleId,
            /******/            l: false,
            /******/            exports: {}
            /******/
        };
        /******/
        /******/ 		// Execute the module function
        /******/
        modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
        /******/
        /******/ 		// Flag the module as loaded
        /******/
        module.l = true;
        /******/
        /******/ 		// Return the exports of the module
        /******/
        return module.exports;
        /******/
    }

    /******/
    /******/
    /******/ 	// expose the modules object (__webpack_modules__)
    /******/
    __webpack_require__.m = modules;
    /******/
    /******/ 	// expose the module cache
    /******/
    __webpack_require__.c = installedModules;
    /******/
    /******/ 	// define getter function for harmony exports
    /******/
    __webpack_require__.d = function (exports, name, getter) {
        /******/
        if (!__webpack_require__.o(exports, name)) {
            /******/
            Object.defineProperty(exports, name, {enumerable: true, get: getter});
            /******/
        }
        /******/
    };
    /******/
    /******/ 	// define __esModule on exports
    /******/
    __webpack_require__.r = function (exports) {
        /******/
        if (typeof Symbol !== 'undefined' && Symbol.toStringTag) {
            /******/
            Object.defineProperty(exports, Symbol.toStringTag, {value: 'Module'});
            /******/
        }
        /******/
        Object.defineProperty(exports, '__esModule', {value: true});
        /******/
    };
    /******/
    /******/ 	// create a fake namespace object
    /******/ 	// mode & 1: value is a module id, require it
    /******/ 	// mode & 2: merge all properties of value into the ns
    /******/ 	// mode & 4: return value when already ns object
    /******/ 	// mode & 8|1: behave like require
    /******/
    __webpack_require__.t = function (value, mode) {
        /******/
        if (mode & 1) value = __webpack_require__(value);
        /******/
        if (mode & 8) return value;
        /******/
        if ((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
        /******/
        var ns = Object.create(null);
        /******/
        __webpack_require__.r(ns);
        /******/
        Object.defineProperty(ns, 'default', {enumerable: true, value: value});
        /******/
        if (mode & 2 && typeof value != 'string') for (var key in value) __webpack_require__.d(ns, key, function (key) {
            return value[key];
        }.bind(null, key));
        /******/
        return ns;
        /******/
    };
    /******/
    /******/ 	// getDefaultExport function for compatibility with non-harmony modules
    /******/
    __webpack_require__.n = function (module) {
        /******/
        var getter = module && module.__esModule ?
            /******/            function getDefault() {
                return module['default'];
            } :
            /******/            function getModuleExports() {
                return module;
            };
        /******/
        __webpack_require__.d(getter, 'a', getter);
        /******/
        return getter;
        /******/
    };
    /******/
    /******/ 	// Object.prototype.hasOwnProperty.call
    /******/
    __webpack_require__.o = function (object, property) {
        return Object.prototype.hasOwnProperty.call(object, property);
    };
    /******/
    /******/ 	// __webpack_public_path__
    /******/
    __webpack_require__.p = "";
    /******/
    /******/
    /******/ 	// Load entry module and return exports
    /******/
    return __webpack_require__(__webpack_require__.s = "../demo3/src/js/pages/features/cards/draggable.js");
    /******/
})
    /************************************************************************/
    /******/ ({

    /***/ "../demo3/src/js/pages/features/cards/draggable.js":
    /*!*********************************************************!*\
      !*** ../demo3/src/js/pages/features/cards/draggable.js ***!
      \*********************************************************/
    /*! no static exports found */
    /***/ (function (module, exports, __webpack_require__) {

        "use strict";


        var KTCardDraggable = function () {

            return {
                //main function to initiate the module
                init: function () {
                    var containers = document.querySelectorAll('.draggable-zone');

                    if (containers.length === 0) {
                        return false;
                    }

                    var swappable = new Sortable.default(containers, {
                        draggable: '.draggable',
                        handle: '.draggable .draggable-handle',
                        mirror: {
                            //appendTo: selector,
                            appendTo: 'body',
                            constrainDimensions: true
                        },

                    });
                    swappable.on('sortable:start', function () {
                        //  console.log("'swappable:start'");
                    });
                    swappable.on('sortable:stop', function (event, ui) {
                        // console.log(event);
                        var newStatusId = event.newContainer.getAttribute('data-status-id');
                        var enqId = event.dragEvent.originalSource.getAttribute('data-id');
                        var url = window.location.origin + '/user/add-timeline-status';
                        var divIndex = event.newContainer.getAttribute('data-div_index');
                        var stickyCard = event.newContainer.getAttribute('data-lead_count');
                        var oldContainerIndex = event.oldContainer.getAttribute('data-div_index');
                        var va = $('#div_zone_' + oldContainerIndex).children().length;

                        if (stickyCard == 1) {
                            var c = '<div class="card-body leads sticky-card " data-sticky_id="' + divIndex + '" id="sticky_id_"+divIndex+>' +
                                '<div class=" ">' +
                                '<div class="d-flex ">' +
                                '<div class="d-flex flex-column flex-grow-1">' +
                                '<a href="#" class="font-weight-bold text-dark-75 text-hover-primary font-size-lg mb-1 leads-service text-center">Add </a>\n' +
                                '<div class="text-center pr-0 pt-1">' +
                                '<a href="/user/enquiries/create" class=""><span class="svg-icon"><img src="http://127.0.0.1:8000/backend/media/svg/custom/user.svg" height="25px"></span> </a>\n' +
                                '</div>' +
                                '</div>' +
                                '</div>' +
                                '</div>' +
                                '</div>';


                            $('#div_zone_' + oldContainerIndex).append(c);
                        }


                        if (enqId != null && newStatusId != null) {
                            $.ajax({
                                url: url,
                                dataType: 'html',
                                type: 'post',
                                data: {
                                    _csrf: $("input[name=_csrf]").val(),
                                    status: newStatusId,
                                    id: enqId,
                                },
                                success: function (result) {
                                    $('#sticky_id_' + divIndex).remove();
                                    console.log('Success');
                                    //toastr.success("The order was saved", "updated");
                                },
                                error: function (result) {
                                    // console.log(result);
                                }
                            });
                        }


                    })

                }
            };
        }();

        jQuery(document).ready(function () {
            KTCardDraggable.init();

        });


        /***/
    })

    /******/
});
//# sourceMappingURL=draggable.js.map