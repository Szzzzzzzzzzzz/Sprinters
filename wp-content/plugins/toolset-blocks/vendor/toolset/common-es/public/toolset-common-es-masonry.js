var toolsetCommonEs=function(e){var t={};function r(n){if(t[n])return t[n].exports;var i=t[n]={i:n,l:!1,exports:{}};return e[n].call(i.exports,i,i.exports,r),i.l=!0,i.exports}return r.m=e,r.c=t,r.d=function(e,t,n){r.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:n})},r.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},r.t=function(e,t){if(1&t&&(e=r(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var n=Object.create(null);if(r.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var i in e)r.d(n,i,function(t){return e[t]}.bind(null,i));return n},r.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return r.d(t,"a",t),t},r.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},r.p="",r(r.s="./utils/masonry/frontend.js")}({"./utils/masonry/frontend.js":function(e,t,r){"use strict";r.r(t);var n=r("./utils/masonry/index.js");r.d(t,"Masonry",function(){return n.a})},"./utils/masonry/index.js":function(e,t,r){"use strict";function n(e,t){for(var r=0;r<t.length;r++){var n=t[r];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(e,n.key,n)}}var i=function(){function e(t){var r=this;if(function(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}(this,e),t){this.CHROME_MAX_NUMBER_ROWS=1e3,this.CHROME_MAX_ROW_SPAN=1e3,this.root=t,this.resizeAllGridItems();var n=Array.from(this.root.querySelectorAll("img")),i=n.length,o=0;n.forEach(function(e){e.addEventListener("load",function(){++o===i&&r.resizeAllGridItems()})}),window.addEventListener("resize",this.resizeAllGridItems.bind(this)),window.addEventListener("load",this.resizeAllGridItems.bind(this))}}var t,r,i;return t=e,(r=[{key:"resizeGridItem",value:function(e,t){var r=window.getComputedStyle(this.root),n=parseInt(r.getPropertyValue("grid-row-gap")),i=e.querySelector(".tb-brick__content");if(i){var o=Math.ceil(i.getBoundingClientRect().height+n),l=Math.ceil(o/t);e.style.gridRowEnd="span ".concat(l)}}},{key:"getMaxItemsHeight",value:function(e){return e.children.length?Array.from(e.children).reduce(function(e,t){return Math.max(e||0,(t.querySelector(".tb-brick__content")||{}).offsetHeight)}):0}},{key:"getMultiplier",value:function(e){var t=window.getComputedStyle(e).gridTemplateColumns.split(" ").length,r=Math.ceil(e.children.length/t),n=this.getMaxItemsHeight(e);if(n>this.CHROME_MAX_ROW_SPAN||r*n>this.CHROME_MAX_NUMBER_ROWS){var i=Math.floor(n*r/this.CHROME_MAX_ROW_SPAN)+1;return e.style.gridAutoRows="".concat(i,"px"),i}return e.style.gridAutoRows="1px",1}},{key:"resizeAllGridItems",value:function(){var e=this;if(this.root){var t=this.getMultiplier(this.root);Array.from(this.root.children).forEach(function(r){return e.resizeGridItem(r,t)})}}}])&&n(t.prototype,r),i&&n(t,i),e}();t.a=i}});