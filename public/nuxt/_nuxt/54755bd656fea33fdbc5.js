(window.webpackJsonp=window.webpackJsonp||[]).push([[11],{677:function(t,e,r){"use strict";r(175);var n=r(176);e.a=Object(n.a)("layout")},949:function(t,e,r){"use strict";r.r(e);r(11),r(8),r(7),r(4),r(9),r(34);var n=r(3),o=r(1),c=r(35);function l(object,t){var e=Object.keys(object);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(object);t&&(r=r.filter((function(t){return Object.getOwnPropertyDescriptor(object,t).enumerable}))),e.push.apply(e,r)}return e}var f={data:function(){return{}},methods:function(t){for(var i=1;i<arguments.length;i++){var source=null!=arguments[i]?arguments[i]:{};i%2?l(Object(source),!0).forEach((function(e){Object(o.a)(t,e,source[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(source)):l(Object(source)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(source,e))}))}return t}({},Object(c.b)("auth",["verifyEmail"])),computed:{token:function(){return this.$route.query.token}},mounted:function(){var t=this;return Object(n.a)(regeneratorRuntime.mark((function e(){return regeneratorRuntime.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return console.log(t.token),e.next=3,t.verifyEmail(t.token);case 3:case"end":return e.stop()}}),e)})))()}},O=r(32),y=r(38),h=r.n(y),j=r(666),v=r(669),w=r(677),component=Object(O.a)(f,(function(){var t=this.$createElement,e=this._self._c||t;return e("v-container",{attrs:{"fill-height":""}},[e("v-layout",{attrs:{"fill-height":"","justify-center":"","align-center":""}},[e("v-alert",{staticClass:"display-1 text-xs-center",attrs:{value:!0,type:"info"}},[this._v("\n\n      Обрабатываем...\n\n    ")])],1)],1)}),[],!1,null,null,null);e.default=component.exports;h()(component,{VAlert:j.a,VContainer:v.a,VLayout:w.a})}}]);