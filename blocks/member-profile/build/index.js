(window["webpackJsonp_wawp_addon_member_directory"] = window["webpackJsonp_wawp_addon_member_directory"] || []).push([["style-index"],{

/***/ "./blocks/member-profile/src/style.scss":
/*!**********************************************!*\
  !*** ./blocks/member-profile/src/style.scss ***!
  \**********************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ })

}]);

/******/ (function(modules) { // webpackBootstrap
/******/ 	// install a JSONP callback for chunk loading
/******/ 	function webpackJsonpCallback(data) {
/******/ 		var chunkIds = data[0];
/******/ 		var moreModules = data[1];
/******/ 		var executeModules = data[2];
/******/
/******/ 		// add "moreModules" to the modules object,
/******/ 		// then flag all "chunkIds" as loaded and fire callback
/******/ 		var moduleId, chunkId, i = 0, resolves = [];
/******/ 		for(;i < chunkIds.length; i++) {
/******/ 			chunkId = chunkIds[i];
/******/ 			if(Object.prototype.hasOwnProperty.call(installedChunks, chunkId) && installedChunks[chunkId]) {
/******/ 				resolves.push(installedChunks[chunkId][0]);
/******/ 			}
/******/ 			installedChunks[chunkId] = 0;
/******/ 		}
/******/ 		for(moduleId in moreModules) {
/******/ 			if(Object.prototype.hasOwnProperty.call(moreModules, moduleId)) {
/******/ 				modules[moduleId] = moreModules[moduleId];
/******/ 			}
/******/ 		}
/******/ 		if(parentJsonpFunction) parentJsonpFunction(data);
/******/
/******/ 		while(resolves.length) {
/******/ 			resolves.shift()();
/******/ 		}
/******/
/******/ 		// add entry modules from loaded chunk to deferred list
/******/ 		deferredModules.push.apply(deferredModules, executeModules || []);
/******/
/******/ 		// run deferred modules when all chunks ready
/******/ 		return checkDeferredModules();
/******/ 	};
/******/ 	function checkDeferredModules() {
/******/ 		var result;
/******/ 		for(var i = 0; i < deferredModules.length; i++) {
/******/ 			var deferredModule = deferredModules[i];
/******/ 			var fulfilled = true;
/******/ 			for(var j = 1; j < deferredModule.length; j++) {
/******/ 				var depId = deferredModule[j];
/******/ 				if(installedChunks[depId] !== 0) fulfilled = false;
/******/ 			}
/******/ 			if(fulfilled) {
/******/ 				deferredModules.splice(i--, 1);
/******/ 				result = __webpack_require__(__webpack_require__.s = deferredModule[0]);
/******/ 			}
/******/ 		}
/******/
/******/ 		return result;
/******/ 	}
/******/
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// object to store loaded and loading chunks
/******/ 	// undefined = chunk not loaded, null = chunk preloaded/prefetched
/******/ 	// Promise = chunk loading, 0 = chunk loaded
/******/ 	var installedChunks = {
/******/ 		"index": 0
/******/ 	};
/******/
/******/ 	var deferredModules = [];
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
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
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
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	var jsonpArray = window["webpackJsonp_wawp_addon_member_directory"] = window["webpackJsonp_wawp_addon_member_directory"] || [];
/******/ 	var oldJsonpFunction = jsonpArray.push.bind(jsonpArray);
/******/ 	jsonpArray.push = webpackJsonpCallback;
/******/ 	jsonpArray = jsonpArray.slice();
/******/ 	for(var i = 0; i < jsonpArray.length; i++) webpackJsonpCallback(jsonpArray[i]);
/******/ 	var parentJsonpFunction = oldJsonpFunction;
/******/
/******/
/******/ 	// add entry module to deferred list
/******/ 	deferredModules.push(["./blocks/member-profile/src/index.js","style-index"]);
/******/ 	// run deferred modules when ready
/******/ 	return checkDeferredModules();
/******/ })
/************************************************************************/
/******/ ({

/***/ "./blocks/components/ContactFields.js":
/*!********************************************!*\
  !*** ./blocks/components/ContactFields.js ***!
  \********************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
// TODO: get filter data here
const getContactFields = async () => {
  const CF_API_URL = "/wp-json/wawp/v1/contacts/fields/";
  const resp = await fetch(CF_API_URL, {
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json'
    }
  });
  const data = await resp.text();
  var result_1 = JSON.parse(data);
  return result_1;
};

class ContactFields {
  constructor() {
    this.init();
  }

  init() {
    if (this.data == undefined) {
      this.data = [];
      this.system = [];
      this.member = [];
      this.common = [];
    }

    if (this.data.length == 0) {
      this.populateFieldData();
    }
  }

  async populateFieldData() {
    const data = await getContactFields();
    data.forEach(field => {
      let cat = '';

      if (field.IsSystem) {
        cat = 'system';
        this.system.push({
          id: field.SystemCode,
          name: field.FieldName
        });
      } else if (field.MemberOnly) {
        cat = 'member';
        this.member.push({
          id: field.SystemCode,
          name: field.FieldName
        });
      } else {
        cat = 'common';
        this.common.push({
          id: field.SystemCode,
          name: field.FieldName
        });
      }

      this.data[field.SystemCode] = {
        name: field.FieldName,
        type: field.Type,
        allowed_values: field.AllowedValues,
        access: field.Access,
        category: cat,
        support_search: field.SupportSearch,
        order: field.Order,
        system_code: field.SystemCode
      };
    });
  }

  async getFieldName(system_code) {
    if (this.data[system_code] == undefined) {
      this.populateFieldData().then(() => {
        return this.data[system_code].name;
      });
    }

    return this.data[system_code].name;
  }

  getSystemFields() {
    return this.system;
  }

  getCommonFields() {
    return this.common;
  }

  getMemberFields() {
    return this.member;
  }

  getData() {
    return this.data;
  }

  isMultiple(system_code) {
    return this.data[system_code].type == 'MultipleChoice';
  }

}

const contactFields = new ContactFields();
/* harmony default export */ __webpack_exports__["default"] = (contactFields);

/***/ }),

/***/ "./blocks/components/Field.js":
/*!************************************!*\
  !*** ./blocks/components/Field.js ***!
  \************************************/
/*! exports provided: Field */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "Field", function() { return Field; });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__);



class Field extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      attributes: props.attributes,
      setAttributes: props.setAttributes,
      field: props.field,
      // field must be in the format {id: SystemCode, name: Name}
      profile: props.profile
    };
  }

  render() {
    return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__["PanelRow"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(FieldCheckbox, {
      field: this.state.field,
      attributes: this.state.attributes,
      setAttributes: this.state.setAttributes,
      profile: this.state.profile,
      key: this.state.field.name
    }));
  }

}

function FieldCheckbox(props) {
  let arr = [];

  if (props.profile) {
    arr = props.attributes.profile_fields;
  } else {
    arr = props.attributes.fields_applied;
  }

  let exists = contains(arr, props.field) == -1 ? false : true;
  const [isChecked, setChecked] = Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["useState"])(exists);
  Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["useEffect"])(() => {
    let in_array = contains(arr, props.field); // if the item is checked but not in the field array, add it

    if (isChecked && in_array == -1) {
      arr.push(props.field); // add it to fields applied
    } else if (!isChecked && in_array != -1) {
      arr.splice(in_array, 1); // remove it
    }

    if (props.profile) {
      props.setAttributes({
        profile_fields: arr
      });
    } else {
      props.setAttributes({
        fields_applied: arr
      });
    }
  });
  return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__["CheckboxControl"], {
    label: props.field.name,
    checked: isChecked,
    onChange: setChecked
  });
}

function contains(array, item) {
  for (var i = 0; i < array.length; i++) {
    if (array[i].id == item.id) {
      return i;
    }
  }

  return -1;
}

/***/ }),

/***/ "./blocks/components/FilterControls.js":
/*!*********************************************!*\
  !*** ./blocks/components/FilterControls.js ***!
  \*********************************************/
/*! exports provided: FieldControls, ProfileFieldControls */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "FieldControls", function() { return FieldControls; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "ProfileFieldControls", function() { return ProfileFieldControls; });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _Field__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./Field */ "./blocks/components/Field.js");
/* harmony import */ var _ContactFields__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./ContactFields */ "./blocks/components/ContactFields.js");
/* harmony import */ var _SavedSearches__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./SavedSearches */ "./blocks/components/SavedSearches.js");
/* harmony import */ var _ProfileFields__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./ProfileFields */ "./blocks/components/ProfileFields.js");









class FieldControls extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      system: _ContactFields__WEBPACK_IMPORTED_MODULE_3__["default"].getSystemFields(),
      common: _ContactFields__WEBPACK_IMPORTED_MODULE_3__["default"].getCommonFields(),
      member: _ContactFields__WEBPACK_IMPORTED_MODULE_3__["default"].getMemberFields(),
      attributes: props.attributes,
      setAttributes: props.setAttributes
    };
  }

  render() {
    return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__["Panel"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__["PanelBody"], {
      title: "System Fields",
      initialOpen: false
    }, this.state.system.map(field => {
      return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_Field__WEBPACK_IMPORTED_MODULE_2__["Field"], {
        attributes: this.state.attributes,
        setAttributes: this.state.setAttributes,
        field: field,
        profile: false
      });
    })), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__["PanelBody"], {
      title: "Common Fields",
      initialOpen: false
    }, this.state.common.map(field => {
      return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_Field__WEBPACK_IMPORTED_MODULE_2__["Field"], {
        attributes: this.state.attributes,
        setAttributes: this.state.setAttributes,
        field: field,
        profile: false
      });
    })), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__["PanelBody"], {
      title: "Member Fields",
      initialOpen: false
    }, this.state.member.map(field => {
      return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_Field__WEBPACK_IMPORTED_MODULE_2__["Field"], {
        attributes: this.state.attributes,
        setAttributes: this.state.setAttributes,
        field: field,
        profile: false
      });
    })), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(SavedSearchControl, {
      attributes: this.state.attributes,
      setAttributes: this.state.setAttributes
    }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(SearchControl, {
      attributes: this.state.attributes,
      setAttributes: this.state.setAttributes
    }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(ProfileLinkControl, {
      attributes: this.state.attributes,
      setAttributes: this.state.setAttributes
    }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_ProfileFields__WEBPACK_IMPORTED_MODULE_5__["ProfileFields"], {
      attributes: this.state.attributes,
      setAttributes: this.state.setAttributes,
      data: [...this.state.system, ...this.state.common, ...this.state.member]
    }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(PageSizeControl, {
      attributes: this.state.attributes,
      setAttributes: this.state.setAttributes
    }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(RestrictedFieldsControl, {
      attributes: this.state.attributes,
      setAttributes: this.state.setAttributes
    }));
  }

}
class ProfileFieldControls extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      system: _ContactFields__WEBPACK_IMPORTED_MODULE_3__["default"].getSystemFields(),
      common: _ContactFields__WEBPACK_IMPORTED_MODULE_3__["default"].getCommonFields(),
      member: _ContactFields__WEBPACK_IMPORTED_MODULE_3__["default"].getMemberFields(),
      attributes: props.attributes,
      setAttributes: props.setAttributes
    };
    console.log(this.state.system);
  }

  render() {
    return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__["Panel"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__["PanelBody"], {
      title: "System Fields",
      initialOpen: false
    }, this.state.system.map(field => {
      return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_Field__WEBPACK_IMPORTED_MODULE_2__["Field"], {
        attributes: this.state.attributes,
        setAttributes: this.state.setAttributes,
        field: field,
        profile: false
      });
    })), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__["PanelBody"], {
      title: "Common Fields",
      initialOpen: false
    }, this.state.common.map(field => {
      return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_Field__WEBPACK_IMPORTED_MODULE_2__["Field"], {
        attributes: this.state.attributes,
        setAttributes: this.state.setAttributes,
        field: field,
        profile: false
      });
    })), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__["PanelBody"], {
      title: "Member Fields",
      initialOpen: false
    }, this.state.member.map(field => {
      return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_Field__WEBPACK_IMPORTED_MODULE_2__["Field"], {
        attributes: this.state.attributes,
        setAttributes: this.state.setAttributes,
        field: field,
        profile: false
      });
    })), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(UserIdControl, {
      attributes: this.state.attributes,
      setAttributes: this.state.setAttributes
    }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(RestrictedFieldsControl, {
      attributes: this.state.attributes,
      setAttributes: this.state.setAttributes
    }));
  }

}

const SavedSearchControl = props => {
  const [search, setSearch] = Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["useState"])(props.attributes.saved_search);
  return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__["PanelBody"], {
    title: "Filters",
    initialOpen: false
  }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__["PanelRow"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__["SelectControl"], {
    label: "Saved search",
    value: search,
    options: _SavedSearches__WEBPACK_IMPORTED_MODULE_4__["savedSearches"].getSearchOptions(),
    onChange: newSearch => {
      setSearch(newSearch);
      props.setAttributes({
        saved_search: Number(newSearch)
      });
    }
  })));
};

function SearchControl(props) {
  let attributes = props.attributes;
  let setAttributes = props.setAttributes;
  const [isChecked, setChecked] = Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["useState"])(attributes.enable_search);
  Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["useEffect"])(() => {
    var new_val;

    if (isChecked && !attributes.enable_search) {
      new_val = true;
    } else if (!isChecked && attributes.enable_search) {
      new_val = false;
    }

    setAttributes({
      enable_search: new_val
    });
  });
  return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__["PanelBody"], {
    title: "Enable Search",
    initialOpen: true
  }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__["PanelRow"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__["ToggleControl"], {
    label: "Enable search",
    checked: isChecked,
    onChange: setChecked
  })));
}

function ProfileLinkControl(props) {
  const [isChecked, setChecked] = Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["useState"])(props.attributes.profile_link);
  Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["useEffect"])(() => {
    props.setAttributes({
      profile_link: isChecked
    });
  });
  return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__["PanelBody"], {
    title: "Profile Link",
    initialOpen: true
  }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__["PanelRow"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__["ToggleControl"], {
    label: "Profile link",
    checked: isChecked,
    onChange: setChecked
  })));
}

function PageSizeControl(props) {
  let attributes = props.attributes;
  let setAttributes = props.setAttributes;
  const [value, setValue] = Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["useState"])(attributes.page_size);
  Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["useEffect"])(() => {
    setAttributes({
      page_size: Number(value)
    });
  });
  return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__["PanelBody"], {
    title: "Page Size",
    initialOpen: false
  }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__["PanelRow"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__["__experimentalNumberControl"], {
    label: "Number of members per page",
    isShiftStepEnabled: true,
    onChange: setValue,
    shiftStep: 5,
    value: value
  })));
}

function UserIdControl(props) {
  let attributes = props.attributes;
  let setAttributes = props.setAttributes;
  const [value, setValue] = Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["useState"])(attributes.user_id);
  Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["useEffect"])(() => {
    setAttributes({
      user_id: value
    });
  });
  return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__["PanelBody"], {
    title: "User"
  }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__["__experimentalInputControl"], {
    value: value,
    onChange: nextValue => setValue(nextValue),
    label: "User ID"
  }));
}

function RestrictedFieldsControl(props) {
  const [isChecked, setChecked] = Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["useState"])(props.attributes.hide_restricted_fields);
  Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["useEffect"])(() => {
    props.setAttributes({
      hide_restricted_fields: isChecked
    });
  });
  return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__["PanelBody"], {
    title: "Hide Restricted Fields",
    initialOpen: true
  }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__["PanelRow"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__["ToggleControl"], {
    label: "Hide restricted fields",
    checked: isChecked,
    onChange: setChecked
  })));
}

/***/ }),

/***/ "./blocks/components/ProfileFields.js":
/*!********************************************!*\
  !*** ./blocks/components/ProfileFields.js ***!
  \********************************************/
/*! exports provided: ProfileFields */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "ProfileFields", function() { return ProfileFields; });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _Field__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./Field */ "./blocks/components/Field.js");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__);




class ProfileFields extends react__WEBPACK_IMPORTED_MODULE_1___default.a.Component {
  constructor(props) {
    super(props);
    this.state = {
      data: props.data,
      attributes: props.attributes,
      setAttributes: props.setAttributes
    };
  }

  render() {
    return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__["PanelBody"], {
      title: "User Profile Fields",
      initialOpen: false
    }, this.state.data.map(field => {
      return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_Field__WEBPACK_IMPORTED_MODULE_2__["Field"], {
        attributes: this.state.attributes,
        setAttributes: this.state.setAttributes,
        field: field,
        profile: true
      });
    }));
  }

}

/***/ }),

/***/ "./blocks/components/SavedSearches.js":
/*!********************************************!*\
  !*** ./blocks/components/SavedSearches.js ***!
  \********************************************/
/*! exports provided: SavedSearches, savedSearches */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "SavedSearches", function() { return SavedSearches; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "savedSearches", function() { return savedSearches; });
const getSavedSearches = async () => {
  const CF_API_URL = "/wp-json/wawp/v1/savedsearches/";
  const resp = await fetch(CF_API_URL, {
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json'
    }
  });
  const data = await resp.text();
  var result_1 = JSON.parse(data);
  return result_1;
};

class SavedSearches {
  constructor() {
    this.data = [];
    this.populateSavedSearches();
  }

  populateSavedSearches() {
    getSavedSearches().then(data => {
      this.data = data;
    });
  }

  getSearchOptions() {
    var options = [];
    options.push(SavedSearches.getNullSearchOption());
    this.data.forEach(search => {
      options.push({
        label: search.Name,
        value: search.Id
      });
    });
    return options;
  }

  getFirstSearchOption() {
    var opt = this.data[0];
    return {
      label: opt.Name,
      value: opt.Id
    };
  }

  static getNullSearchOption() {
    return {
      value: 0,
      label: 'Select a saved search'
    };
  }

}
const savedSearches = new SavedSearches();


/***/ }),

/***/ "./blocks/member-profile/src/edit.js":
/*!*******************************************!*\
  !*** ./blocks/member-profile/src/edit.js ***!
  \*******************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return Edit; });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _components_FilterControls__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../components/FilterControls */ "./blocks/components/FilterControls.js");
/* harmony import */ var _index__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./index */ "./blocks/member-profile/src/index.js");
/* harmony import */ var _editor_scss__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./editor.scss */ "./blocks/member-profile/src/editor.scss");


/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-i18n/
 */

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-block-editor/#useBlockProps
 */




/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */


/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#edit
 *
 * @return {WPElement} Element to render.
 */

function Edit({
  attributes,
  setAttributes
}) {
  return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("div", Object(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2__["useBlockProps"])(), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2__["InspectorControls"], {
    key: "setting"
  }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_components_FilterControls__WEBPACK_IMPORTED_MODULE_3__["ProfileFieldControls"], {
    attributes: attributes,
    setAttributes: setAttributes
  })), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("div", _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2__["useBlockProps"].save(), Object(_index__WEBPACK_IMPORTED_MODULE_4__["renderHiddenFields"])(attributes), Object(_index__WEBPACK_IMPORTED_MODULE_4__["generateShortcode"])(attributes)));
}

/***/ }),

/***/ "./blocks/member-profile/src/editor.scss":
/*!***********************************************!*\
  !*** ./blocks/member-profile/src/editor.scss ***!
  \***********************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./blocks/member-profile/src/index.js":
/*!********************************************!*\
  !*** ./blocks/member-profile/src/index.js ***!
  \********************************************/
/*! exports provided: renderHiddenFields, generateShortcode */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "renderHiddenFields", function() { return renderHiddenFields; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "generateShortcode", function() { return generateShortcode; });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/blocks */ "@wordpress/blocks");
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_blocks__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./style.scss */ "./blocks/member-profile/src/style.scss");
/* harmony import */ var _edit__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./edit */ "./blocks/member-profile/src/edit.js");
/* harmony import */ var _save__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./save */ "./blocks/member-profile/src/save.js");


/**
 * Registers a new block provided a unique name and an object defining its behavior.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/
 */

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * All files containing `style` keyword are bundled together. The code used
 * gets applied both to the front of your site and to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */


/**
 * Internal dependencies
 */


 // attributes needed
// - fields
// the user id: enter it or grab it from the current logged in user
// if showing logged in user; do user-id=current and in php code:
// if user-id=current then grab the logged in id. else do nothing

/**
 * Every block starts by registering a new block type definition.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/
 */

Object(_wordpress_blocks__WEBPACK_IMPORTED_MODULE_1__["registerBlockType"])('wawp-member-addons/member-profile', {
  attributes: {
    fields_applied: {
      type: 'array',
      default: [],
      source: 'query',
      selector: 'li.filter',
      query: {
        id: {
          type: 'string',
          source: 'attribute',
          attribute: 'data-system-code'
        },
        name: {
          type: 'string',
          source: 'attribute',
          attribute: 'data-name'
        }
      }
    },
    user_id: {
      // add separate name and id attributes
      type: 'string',
      default: '',
      selector: '#user_id',
      attribute: 'data-user-id'
    },
    hide_restricted_fields: {
      type: 'boolean',
      default: true,
      selector: '#hide_restricted_fields',
      attribute: 'data-hide-restricted-fields'
    }
  },

  /**
   * @see ./edit.js
   */
  edit: _edit__WEBPACK_IMPORTED_MODULE_3__["default"],

  /**
   * @see ./save.js
   */
  save: _save__WEBPACK_IMPORTED_MODULE_4__["default"]
});
function renderHiddenFields(attributes) {
  return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("div", {
    className: "hidden"
  }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("ul", null, attributes.fields_applied.map(field => {
    return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("li", {
      className: "filter",
      key: field.id,
      "data-system-code": field.id,
      "data-name": field.name
    }, field.name);
  })), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("div", {
    id: "user_id",
    "data-user-id": attributes.user_id
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("div", {
    id: "hide_restricted_fields",
    "data-hide-restricted-fields": attributes.hide_restricted_fields
  }, attributes.hide_restricted_fields));
}
function generateShortcode(attributes) {
  var shortcode_str = '[wa-profile ';
  var len = attributes.fields_applied.length;

  for (let i = 0; i < len; i++) {
    shortcode_str += '\'' + attributes.fields_applied[i].name + '\'';
    shortcode_str += ' ';
  }

  shortcode_str += ' user-id=' + attributes.user_id;

  if (attributes.hide_restricted_fields) {
    shortcode_str += ' hide_restricted_fields';
  }

  shortcode_str += ']';
  return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("p", null, shortcode_str);
}

/***/ }),

/***/ "./blocks/member-profile/src/save.js":
/*!*******************************************!*\
  !*** ./blocks/member-profile/src/save.js ***!
  \*******************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return save; });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _index__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./index */ "./blocks/member-profile/src/index.js");


/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-i18n/
 */

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-block-editor/#useBlockProps
 */



/**
 * The save function defines the way in which the different attributes should
 * be combined into the final markup, which is then serialized by the block
 * editor into `post_content`.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#save
 *
 * @return {WPElement} Element to render.
 */

function save({
  attributes
}) {
  return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("div", _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2__["useBlockProps"].save(), Object(_index__WEBPACK_IMPORTED_MODULE_3__["renderHiddenFields"])(attributes), Object(_index__WEBPACK_IMPORTED_MODULE_3__["generateShortcode"])(attributes));
}

/***/ }),

/***/ "@wordpress/block-editor":
/*!*************************************!*\
  !*** external ["wp","blockEditor"] ***!
  \*************************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = window["wp"]["blockEditor"]; }());

/***/ }),

/***/ "@wordpress/blocks":
/*!********************************!*\
  !*** external ["wp","blocks"] ***!
  \********************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = window["wp"]["blocks"]; }());

/***/ }),

/***/ "@wordpress/components":
/*!************************************!*\
  !*** external ["wp","components"] ***!
  \************************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = window["wp"]["components"]; }());

/***/ }),

/***/ "@wordpress/element":
/*!*********************************!*\
  !*** external ["wp","element"] ***!
  \*********************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = window["wp"]["element"]; }());

/***/ }),

/***/ "@wordpress/i18n":
/*!******************************!*\
  !*** external ["wp","i18n"] ***!
  \******************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = window["wp"]["i18n"]; }());

/***/ }),

/***/ "react":
/*!************************!*\
  !*** external "React" ***!
  \************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = window["React"]; }());

/***/ })

/******/ });
//# sourceMappingURL=index.js.map