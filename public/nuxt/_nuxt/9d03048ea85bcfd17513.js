(window.webpackJsonp=window.webpackJsonp||[]).push([[18],{737:function(t,e,n){"use strict";n(11),n(8),n(7),n(4),n(9);var r=n(1),o=(n(34),n(3)),l=n(35);function c(object,t){var e=Object.keys(object);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(object);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(object,t).enumerable}))),e.push.apply(e,n)}return e}var v={data:function(){return{mainPhone:this.$auth.user.mainPhone,setMainPhoneLoading:!1}},watch:{mainPhone:function(t){var e=this;return Object(o.a)(regeneratorRuntime.mark((function n(){var r;return regeneratorRuntime.wrap((function(n){for(;;)switch(n.prev=n.next){case 0:if(r=t.id,!e.$auth.user.mainPhone||r!==e.$auth.user.mainPhone.id){n.next=3;break}return n.abrupt("return");case 3:return e.setMainPhoneLoading=!0,n.next=6,e.setMainPhone({id:r});case 6:e.mainPhone=e.$auth.user.mainPhone,e.setMainPhoneLoading=!1;case 8:case"end":return n.stop()}}),n)})))()},"$auth.user.phones":function(){this.mainPhone=this.$auth.user.mainPhone}},methods:function(t){for(var i=1;i<arguments.length;i++){var source=null!=arguments[i]?arguments[i]:{};i%2?c(Object(source),!0).forEach((function(e){Object(r.a)(t,e,source[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(source)):c(Object(source)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(source,e))}))}return t}({getFlag:n(15).a},Object(l.b)("profileSettings",["setMainPhone"]))},m=n(32),h=n(38),d=n.n(h),f=n(100),_=n(704),y=n(665),component=Object(m.a)(v,(function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("v-select",{staticClass:"mt-2",staticStyle:{"max-width":"300px"},attrs:{items:t.$auth.user.phones,loading:t.setMainPhoneLoading,"item-text":"numberFormated","prepend-icon":"phone","hide-details":"","return-object":""},scopedSlots:t._u([{key:"selection",fn:function(e){var r=e.item;return[n("img",{staticClass:"country-flag",staticStyle:{"max-width":"30px"},attrs:{src:t.getFlag(r.country)}}),t._v(" "),n("span",{staticClass:"ml-2"},[t._v(t._s(r.numberFormated))]),t._v(" "),n("span",{staticClass:"ml-1"},[r.verified?n("v-tooltip",{attrs:{bottom:""}},[n("v-icon",{attrs:{color:"success"}},[t._v("check_circle")]),t._v(" "),n("span",[t._v("Подтвержденный адрес")])],1):n("v-tooltip",{attrs:{bottom:""}},[n("v-icon",{attrs:{color:"error"}},[t._v("error")]),t._v(" "),n("span",[t._v("Неподтвержденный адрес")])],1)],1)]}},{key:"item",fn:function(e){var r=e.item;return[n("img",{staticClass:"country-flag",staticStyle:{"max-width":"30px"},attrs:{src:t.getFlag(r.country)}}),t._v(" "),n("span",{staticClass:"ml-2"},[t._v(t._s(r.numberFormated))]),t._v(" "),n("span",{staticClass:"ml-2"},[r.verified?n("v-tooltip",{attrs:{bottom:""}},[n("v-icon",{attrs:{color:"success"}},[t._v("check_circle")]),t._v(" "),n("span",[t._v("Подтвержденный адрес")])],1):n("v-tooltip",{attrs:{bottom:""}},[n("v-icon",{attrs:{color:"error"}},[t._v("error")]),t._v(" "),n("span",[t._v("Неподтвержденный адрес")])],1)],1)]}}]),model:{value:t.mainPhone,callback:function(e){t.mainPhone=e},expression:"mainPhone"}})}),[],!1,null,null,null);e.a=component.exports;d()(component,{VIcon:f.a,VSelect:_.a,VTooltip:y.a})},957:function(t,e,n){"use strict";n.r(e);n(11),n(8),n(7),n(4),n(9),n(34);var r=n(3),o=n(1),l=n(35),c=n(15);function v(object,t){var e=Object.keys(object);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(object);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(object,t).enumerable}))),e.push.apply(e,n)}return e}var m={transition:"slide-y-transition",scrollToTop:!0,mixins:[],components:{MainPhoneSelect:n(737).a},data:function(){return{mainPhone:this.$auth.user.mainPhone,setMainPhoneLoading:!1,notifications:!0,sound:!1,video:!1,invites:!0,headers:[{text:"События",align:"left",value:"event"},{text:"На сайт",value:"site"},{text:"На почту",value:"email"}],items:[{event:"Личное сообщение",site:!0,email:!1,disabled:["site"]},{event:"Добавление в друзья",site:!0,email:!0},{event:"Вас лайкнули",site:!1,email:!1},{event:"Подписка",site:!1,email:!0}]}},methods:function(t){for(var i=1;i<arguments.length;i++){var source=null!=arguments[i]?arguments[i]:{};i%2?v(Object(source),!0).forEach((function(e){Object(o.a)(t,e,source[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(source)):v(Object(source)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(source,e))}))}return t}({getFlag:c.a},Object(l.b)("profileSettings",["setMainPhone"])),watch:{mainPhone:function(t){var e=this;return Object(r.a)(regeneratorRuntime.mark((function n(){var r;return regeneratorRuntime.wrap((function(n){for(;;)switch(n.prev=n.next){case 0:if((r=t.id)!==e.$auth.user.mainPhone.id){n.next=3;break}return n.abrupt("return");case 3:return e.setMainPhoneLoading=!0,n.next=6,e.setMainPhone({id:r});case 6:e.mainPhone=e.$auth.user.mainPhone,e.setMainPhoneLoading=!1;case 8:case"end":return n.stop()}}),n)})))()}}},h=n(32),d=n(38),f=n.n(d),_=n(666),y=n(101),P=n(648),O=n(938),x=n(944),j=n(654),w=n(100),k=n(677),$=n(167),component=Object(h.a)(m,(function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",[n("div",{staticClass:"text-xs-center headline my-4"},[t._v("\n    На сайт и на главный эл. адрес "),t.$auth.user.mainEmail?n("span",[t._v("("+t._s(t.$auth.user.mainEmail.email)+")")]):t._e()]),t._v(" "),n("v-layout",{attrs:{"justify-center":""}},[n("v-alert",{staticClass:"mb-3",attrs:{value:!t.$auth.user.mainEmail,type:"error"}},[t._v('\n      У Вас нет почты, уведомления с раздела "На почту" для Вас не работают.'),n("br"),t._v(" "),n("v-btn",{attrs:{to:"/profile/settings/emails"}},[n("v-icon",{attrs:{left:""}},[t._v("add")]),t._v("\n        Добавить\n      ")],1)],1),t._v(" "),n("v-alert",{staticClass:"mb-3",attrs:{value:t.$auth.user.mainEmail&&!t.$auth.user.mainEmail.verified,type:"warning"}},[t._v('\n      У Вас не подтвержденна почта, уведомления с раздела "На почту" для Вас не работают.'),n("br")])],1),t._v(" "),n("v-layout",{attrs:{"justify-center":""}},[n("v-data-table",{staticClass:"elevation-1 mb-5",staticStyle:{"max-width":"100%"},attrs:{headers:t.headers,items:t.items,"hide-actions":"","disable-initial-sort":""},scopedSlots:t._u([{key:"items",fn:function(e){return[n("td",[t._v(t._s(e.item.event))]),t._v(" "),n("td",[n("v-checkbox",{attrs:{disabled:e.item.disabled&&e.item.disabled.includes("site"),"hide-details":""},model:{value:e.item.site,callback:function(n){t.$set(e.item,"site",n)},expression:"props.item.site"}})],1),t._v(" "),n("td",[n("v-checkbox",{attrs:{disabled:e.item.disabled&&e.item.disabled.includes("email"),color:t.$auth.user.mainEmail?t.$auth.user.mainEmail.verified?void 0:"warning":"error","hide-details":""},model:{value:e.item.email,callback:function(n){t.$set(e.item,"email",n)},expression:"props.item.email"}})],1)]}}])})],1),t._v(" "),n("v-divider"),t._v(" "),n("div",{staticClass:"text-xs-center headline my-4"},[t._v("\n    Смс уведомления\n  ")]),t._v(" "),n("v-layout",{attrs:{"justify-center":""}},[n("v-alert",{staticClass:"mb-3",attrs:{value:!t.$auth.user.phones.length,type:"warning"}},[t._v("\n      У вас нет телефонов, смс уведомления не доступны."),n("br")])],1),t._v(" "),t.$auth.user.phones.length?n("v-layout",{attrs:{"justify-center":""}},[n("v-card",[n("div",{staticClass:"px-4 py-2"},[n("main-phone-select"),t._v(" "),n("v-alert",{staticClass:"mt-3",attrs:{value:t.$auth.user.mainPhone&&!t.$auth.user.mainPhone.verified,type:"warning"}},[t._v("\n          Телефон не подтвержден."),n("br")])],1),t._v(" "),t.$auth.user.mainPhone&&t.$auth.user.mainPhone.verified?n("v-list",{attrs:{"two-line":"",subheader:""}},[n("v-list-tile",{attrs:{avatar:""}},[n("v-list-tile-action",[n("v-checkbox",{model:{value:t.notifications,callback:function(e){t.notifications=e},expression:"notifications"}})],1),t._v(" "),n("v-list-tile-content",[n("v-list-tile-title",[t._v("Мне ответили")]),t._v(" "),n("v-list-tile-sub-title",[t._v("Когда Вам ответил пользователь")])],1)],1),t._v(" "),n("v-list-tile",{attrs:{avatar:""}},[n("v-list-tile-action",[n("v-checkbox",{model:{value:t.sound,callback:function(e){t.sound=e},expression:"sound"}})],1),t._v(" "),n("v-list-tile-content",[n("v-list-tile-title",[t._v("Добавление в друзья")]),t._v(" "),n("v-list-tile-sub-title",[t._v("Когда кто-то захотел стать другом")])],1)],1),t._v(" "),n("v-list-tile",{attrs:{avatar:""}},[n("v-list-tile-action",[n("v-checkbox",{model:{value:t.video,callback:function(e){t.video=e},expression:"video"}})],1),t._v(" "),n("v-list-tile-content",[n("v-list-tile-title",[t._v("Личное сообщение")]),t._v(" "),n("v-list-tile-sub-title",[t._v("Когда пришло личное сообщение")])],1)],1),t._v(" "),n("v-list-tile",{attrs:{avatar:""}},[n("v-list-tile-action",[n("v-checkbox",{model:{value:t.invites,callback:function(e){t.invites=e},expression:"invites"}})],1),t._v(" "),n("v-list-tile-content",[n("v-list-tile-title",[t._v("День рождения друга")]),t._v(" "),n("v-list-tile-sub-title",[t._v("Напоминать о днях рождения")])],1)],1)],1):t._e()],1)],1):t._e()],1)}),[],!1,null,null,null);e.default=component.exports;f()(component,{VAlert:_.a,VBtn:y.a,VCard:P.a,VCheckbox:O.a,VDataTable:x.a,VDivider:j.a,VIcon:w.a,VLayout:k.a,VList:$.a})}}]);