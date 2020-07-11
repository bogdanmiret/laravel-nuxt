(window.webpackJsonp=window.webpackJsonp||[]).push([[16],{239:function(t,e,n){"use strict";n.d(e,"a",(function(){return l})),n.d(e,"b",(function(){return d})),n.d(e,"c",(function(){return v}));var r=n(648),o=n(0),l=Object(o.i)("v-card__actions"),c=Object(o.i)("v-card__subtitle"),d=Object(o.i)("v-card__text"),v=Object(o.i)("v-card__title");r.a},676:function(t,e,n){"use strict";n(175);var r=n(176);e.a=Object(r.a)("flex")},695:function(t,e,n){var content=n(696);"string"==typeof content&&(content=[[t.i,content,""]]),content.locals&&(t.exports=content.locals);(0,n(13).default)("2065bca8",content,!0,{sourceMap:!1})},696:function(t,e,n){(e=n(12)(!1)).push([t.i,".v-dialog{border-radius:4px;margin:24px;overflow-y:auto;pointer-events:auto;transition:.3s cubic-bezier(.25,.8,.25,1);width:100%;z-index:inherit;box-shadow:0 11px 15px -7px rgba(0,0,0,.2),0 24px 38px 3px rgba(0,0,0,.14),0 9px 46px 8px rgba(0,0,0,.12)}.v-dialog:not(.v-dialog--fullscreen){max-height:90%}.v-dialog>*{width:100%}.v-dialog>.v-card>.v-card__title{font-size:1.25rem;font-weight:500;letter-spacing:.0125em;padding:16px 24px 10px}.v-dialog>.v-card>.v-card__subtitle,.v-dialog>.v-card>.v-card__text{padding:0 24px 20px}.v-dialog__content{align-items:center;display:flex;height:100%;justify-content:center;left:0;pointer-events:none;position:fixed;top:0;transition:.2s cubic-bezier(.25,.8,.25,1),z-index 1ms;width:100%;z-index:6;outline:none}.v-dialog__container{display:none}.v-dialog__container--attached{display:inline}.v-dialog--animated{-webkit-animation-duration:.15s;animation-duration:.15s;-webkit-animation-name:animate-dialog;animation-name:animate-dialog;-webkit-animation-timing-function:cubic-bezier(.25,.8,.25,1);animation-timing-function:cubic-bezier(.25,.8,.25,1)}.v-dialog--fullscreen{border-radius:0;margin:0;height:100%;position:fixed;overflow-y:auto;top:0;left:0}.v-dialog--fullscreen>.v-card{min-height:100%;min-width:100%;margin:0!important;padding:0!important}.v-dialog--scrollable,.v-dialog--scrollable>form{display:flex}.v-dialog--scrollable>.v-card,.v-dialog--scrollable>form>.v-card{display:flex;flex:1 1 100%;flex-direction:column;max-height:100%;max-width:100%}.v-dialog--scrollable>.v-card>.v-card__actions,.v-dialog--scrollable>.v-card>.v-card__title,.v-dialog--scrollable>form>.v-card>.v-card__actions,.v-dialog--scrollable>form>.v-card>.v-card__title{flex:0 0 auto}.v-dialog--scrollable>.v-card>.v-card__text,.v-dialog--scrollable>form>.v-card>.v-card__text{-webkit-backface-visibility:hidden;backface-visibility:hidden;flex:1 1 auto;overflow-y:auto}@-webkit-keyframes animate-dialog{0%{transform:scale(1)}50%{transform:scale(1.03)}to{transform:scale(1)}}@keyframes animate-dialog{0%{transform:scale(1)}50%{transform:scale(1.03)}to{transform:scale(1)}}",""]),t.exports=e},727:function(t,e,n){var content=n(879);"string"==typeof content&&(content=[[t.i,content,""]]),content.locals&&(t.exports=content.locals);(0,n(13).default)("6892dd22",content,!0,{sourceMap:!1})},767:function(t,e,n){"use strict";n(11),n(8),n(7),n(4),n(9),n(28),n(30);var r=n(1),o=(n(16),n(695),n(647)),l=n(77),c=n(82),d=n(117),v=n(182),m=n(181),f=n(180),h=n(27),x=n(113),_=n(5),y=n(10),w=n(0);function k(object,t){var e=Object.keys(object);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(object);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(object,t).enumerable}))),e.push.apply(e,n)}return e}function O(t){for(var i=1;i<arguments.length;i++){var source=null!=arguments[i]?arguments[i]:{};i%2?k(Object(source),!0).forEach((function(e){Object(r.a)(t,e,source[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(source)):k(Object(source)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(source,e))}))}return t}var D=Object(_.a)(l.a,c.a,d.a,v.a,m.a,f.a,h.a);e.a=D.extend({name:"v-dialog",directives:{ClickOutside:x.a},props:{dark:Boolean,disabled:Boolean,fullscreen:Boolean,light:Boolean,maxWidth:{type:[String,Number],default:"none"},noClickAnimation:Boolean,origin:{type:String,default:"center center"},persistent:Boolean,retainFocus:{type:Boolean,default:!0},scrollable:Boolean,transition:{type:[String,Boolean],default:"dialog-transition"},width:{type:[String,Number],default:"auto"}},data:function(){return{activatedBy:null,animate:!1,animateTimeout:-1,isActive:!!this.value,stackMinZIndex:200}},computed:{classes:function(){var t;return t={},Object(r.a)(t,"v-dialog ".concat(this.contentClass).trim(),!0),Object(r.a)(t,"v-dialog--active",this.isActive),Object(r.a)(t,"v-dialog--persistent",this.persistent),Object(r.a)(t,"v-dialog--fullscreen",this.fullscreen),Object(r.a)(t,"v-dialog--scrollable",this.scrollable),Object(r.a)(t,"v-dialog--animated",this.animate),t},contentClasses:function(){return{"v-dialog__content":!0,"v-dialog__content--active":this.isActive}},hasActivator:function(){return Boolean(!!this.$slots.activator||!!this.$scopedSlots.activator)}},watch:{isActive:function(t){t?(this.show(),this.hideScroll()):(this.removeOverlay(),this.unbind())},fullscreen:function(t){this.isActive&&(t?(this.hideScroll(),this.removeOverlay(!1)):(this.showScroll(),this.genOverlay()))}},created:function(){this.$attrs.hasOwnProperty("full-width")&&Object(y.d)("full-width",this)},beforeMount:function(){var t=this;this.$nextTick((function(){t.isBooted=t.isActive,t.isActive&&t.show()}))},beforeDestroy:function(){"undefined"!=typeof window&&this.unbind()},methods:{animateClick:function(){var t=this;this.animate=!1,this.$nextTick((function(){t.animate=!0,window.clearTimeout(t.animateTimeout),t.animateTimeout=window.setTimeout((function(){return t.animate=!1}),150)}))},closeConditional:function(t){var e=t.target;return!(this._isDestroyed||!this.isActive||this.$refs.content.contains(e)||this.overlay&&e&&!this.overlay.$el.contains(e))&&this.activeZIndex>=this.getMaxZIndex()},hideScroll:function(){this.fullscreen?document.documentElement.classList.add("overflow-y-hidden"):v.a.options.methods.hideScroll.call(this)},show:function(){var t=this;!this.fullscreen&&!this.hideOverlay&&this.genOverlay(),this.$nextTick((function(){t.$refs.content.focus(),t.bind()}))},bind:function(){window.addEventListener("focusin",this.onFocusin)},unbind:function(){window.removeEventListener("focusin",this.onFocusin)},onClickOutside:function(t){this.$emit("click:outside",t),this.persistent?this.noClickAnimation||this.animateClick():this.isActive=!1},onKeydown:function(t){if(t.keyCode===w.v.esc&&!this.getOpenDependents().length)if(this.persistent)this.noClickAnimation||this.animateClick();else{this.isActive=!1;var e=this.getActivator();this.$nextTick((function(){return e&&e.focus()}))}this.$emit("keydown",t)},onFocusin:function(t){if(t&&this.retainFocus){var e=t.target;if(e&&![document,this.$refs.content].includes(e)&&!this.$refs.content.contains(e)&&this.activeZIndex>=this.getMaxZIndex()&&!this.getOpenDependentElements().some((function(t){return t.contains(e)}))){var n=this.$refs.content.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');n.length&&n[0].focus()}}},genContent:function(){var t=this;return this.showLazyContent((function(){return[t.$createElement(o.a,{props:{root:!0,light:t.light,dark:t.dark}},[t.$createElement("div",{class:t.contentClasses,attrs:O({role:"document",tabindex:t.isActive?0:void 0},t.getScopeIdAttrs()),on:{keydown:t.onKeydown},style:{zIndex:t.activeZIndex},ref:"content"},[t.genTransition()])])]}))},genTransition:function(){var content=this.genInnerContent();return this.transition?this.$createElement("transition",{props:{name:this.transition,origin:this.origin,appear:!0}},[content]):content},genInnerContent:function(){var data={class:this.classes,ref:"dialog",directives:[{name:"click-outside",value:this.onClickOutside,args:{closeConditional:this.closeConditional,include:this.getOpenDependentElements}},{name:"show",value:this.isActive}],style:{transformOrigin:this.origin}};return this.fullscreen||(data.style=O({},data.style,{maxWidth:"none"===this.maxWidth?void 0:Object(w.g)(this.maxWidth),width:"auto"===this.width?void 0:Object(w.g)(this.width)})),this.$createElement("div",data,this.getContentSlot())}},render:function(t){return t("div",{staticClass:"v-dialog__container",class:{"v-dialog__container--attached":""===this.attach||!0===this.attach||"attach"===this.attach},attrs:{role:"dialog"}},[this.genActivator(),this.genContent()])}})},878:function(t,e,n){"use strict";var r=n(727);n.n(r).a},879:function(t,e,n){(e=n(12)(!1)).push([t.i,".profile-settings__emails-toolbar{border-top-left-radius:15px;border-top-right-radius:15px}",""]),t.exports=e},950:function(t,e,n){"use strict";n.r(e);n(11),n(8),n(7),n(4),n(9),n(34);var r=n(3),o=n(1),l=n(35);function c(object,t){var e=Object.keys(object);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(object);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(object,t).enumerable}))),e.push.apply(e,n)}return e}var d={transition:"slide-y-transition",scrollToTop:!0,mixins:[],data:function(){return{repeatVerificationMailLoadingItemId:!1,deleteDialogItem:null,deleteDialog:!1,deleteDialogLoadingBtn:!1,createDialog:!1,createDialogLoadingBtn:!1,createDialogForm:{email:null,label:null,public:!1,main:!this.$auth.user.emails.length},mainEmail:this.$auth.user.mainEmail,setMainEmailLoading:!1,headers:[{text:"Адрес",align:"left",value:"email"},{text:"Метка",value:"label"},{text:"Подтвержденный",value:"verified",align:"center"},{text:"Виден всем?",value:"public"},{text:"Отправлялось сообщение для подтверждения?",value:"verificationToken",align:"center"},{text:"Удалить",value:-1}]}},methods:function(t){for(var i=1;i<arguments.length;i++){var source=null!=arguments[i]?arguments[i]:{};i%2?c(Object(source),!0).forEach((function(e){Object(o.a)(t,e,source[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(source)):c(Object(source)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(source,e))}))}return t}({createDialogSaveEmail:function(){var t=this;return Object(r.a)(regeneratorRuntime.mark((function e(){return regeneratorRuntime.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,t.validateByMixin(t.createDialogForm);case 2:if(!e.sent){e.next=9;break}return t.createDialogLoadingBtn=!0,e.next=6,t.saveEmail(t.createDialogForm);case 6:t.setInputMainEmail(),t.createDialogLoadingBtn=t.createDialog=!1,t.setCreateDialogFormMain();case 9:case"end":return e.stop()}}),e)})))()},deleteDialogDeleteEmail:function(){var t=this;return Object(r.a)(regeneratorRuntime.mark((function e(){return regeneratorRuntime.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return t.deleteDialogLoadingBtn=!0,e.next=3,t.deleteEmail({id:t.deleteDialogItem});case 3:t.deleteDialogLoadingBtn=t.deleteDialog=!1,t.setCreateDialogFormMain();case 5:case"end":return e.stop()}}),e)})))()},clickRepeatVerificationMail:function(t){var e=this;return Object(r.a)(regeneratorRuntime.mark((function n(){return regeneratorRuntime.wrap((function(n){for(;;)switch(n.prev=n.next){case 0:return e.repeatVerificationMailLoadingItemId=t,n.next=3,e.repeatVerificationMail(t);case 3:e.repeatVerificationMailLoadingItemId=null;case 4:case"end":return n.stop()}}),n)})))()},setCreateDialogFormMain:function(){this.createDialogForm.main=!this.$auth.user.emails.length},setInputMainEmail:function(){this.mainEmail=this.$auth.user.mainEmail}},Object(l.b)("auth",["repeatVerificationMail"]),{},Object(l.b)("profileSettings",["saveEmail","deleteEmail","setMainEmail","changePublicStateEmail"])),watch:{mainEmail:function(t){var e=this;return Object(r.a)(regeneratorRuntime.mark((function n(){var r;return regeneratorRuntime.wrap((function(n){for(;;)switch(n.prev=n.next){case 0:if(r=t.id,!e.$auth.user.mainEmail||r!==e.$auth.user.mainEmail.id){n.next=3;break}return n.abrupt("return");case 3:return e.setMainEmailLoading=!0,n.next=6,e.setMainEmail({id:r});case 6:e.setInputMainEmail(),e.setMainEmailLoading=!1;case 8:case"end":return n.stop()}}),n)})))()}}},v=(n(878),n(32)),m=n(38),f=n.n(m),h=n(101),x=n(648),_=n(239),y=n(938),w=n(669),k=n(944),O=n(767),D=n(676),j=n(100),E=n(677),C=n(704),$=n(674),S=n(724),M=n(652),V=n(44),F=n(110),I=n(665),component=Object(v.a)(d,(function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticStyle:{width:"100%"}},[t.$auth.user.emails.length?n("v-container",{attrs:{fluid:""}},[n("v-layout",{attrs:{row:"",wrap:"","justify-center":""}},[n("v-flex",{attrs:{xs12:"",md6:"",xl4:""}},[n("v-subheader",[t._v("Главный адрес")]),t._v(" "),n("span",{staticClass:"grey--text"},[t._v("\n          Используется для\n          "),n("router-link",{attrs:{to:"/profile/settings/notifications"}},[t._v("уведомлений")]),t._v("\n          и функций сайта, например, для сброса пароля.\n        ")],1)],1),t._v(" "),n("v-flex",{attrs:{xs12:"",md6:"",xl4:""}},[n("v-select",{attrs:{loading:t.setMainEmailLoading,items:t.$auth.user.emails,"prepend-icon":"mail","item-text":"email","return-object":""},scopedSlots:t._u([{key:"selection",fn:function(e){var r=e.item;return[n("span",{staticClass:"mr-1"},[r.verified?n("v-tooltip",{attrs:{bottom:""}},[n("v-icon",{attrs:{slot:"activator",color:"success"},slot:"activator"},[t._v("check_circle")]),t._v(" "),n("span",[t._v("Подтвержденный адрес")])],1):n("v-tooltip",{attrs:{bottom:""}},[n("v-icon",{attrs:{slot:"activator",color:"error"},slot:"activator"},[t._v("error")]),t._v(" "),n("span",[t._v("Неподтвержденный адрес")])],1)],1),t._v("\n            "+t._s(r.email)+"\n          ")]}},{key:"item",fn:function(e){var r=e.item;return[n("span",{staticClass:"mr-3"},[r.verified?n("v-tooltip",{attrs:{bottom:""}},[n("v-icon",{attrs:{slot:"activator",color:"success"},slot:"activator"},[t._v("check_circle")]),t._v(" "),n("span",[t._v("Подтвержденный адрес")])],1):n("v-tooltip",{attrs:{bottom:""}},[n("v-icon",{attrs:{slot:"activator",color:"error"},slot:"activator"},[t._v("error")]),t._v(" "),n("span",[t._v("Неподтвержденный адрес")])],1)],1),t._v("\n            "+t._s(r.email)+"\n          ")]}}],null,!1,2096652862),model:{value:t.mainEmail,callback:function(e){t.mainEmail=e},expression:"mainEmail"}})],1)],1)],1):t._e(),t._v(" "),n("v-layout",{attrs:{"justify-center":"",wrap:""}},[n("v-flex",{attrs:{xs12:""}},[n("v-toolbar",{staticClass:"profile-settings__emails-toolbar mt-3",attrs:{color:"primary",dark:""}},[n("v-btn",{attrs:{color:"success",fab:"",top:"",right:"",absolute:""},on:{click:function(e){t.createDialog=!0}}},[n("v-icon",[t._v("add")])],1),t._v(" "),n("v-toolbar-title",{staticClass:"white--text"},[t._v("\n          Мои электронные адреса\n          "),n("v-tooltip",{attrs:{bottom:""}},[n("v-btn",{attrs:{slot:"activator",icon:""},slot:"activator"},[n("v-icon",[t._v("help")])],1),t._v(" "),n("span",[t._v("\n              Служат как данные для входа, сброса пароля, а также как контактные данные(только публичные)\n            ")])],1)],1)],1)],1),t._v(" "),n("v-flex",{attrs:{xs12:""}},[n("v-data-table",{staticClass:"elevation-1 mb-5",staticStyle:{"max-width":"100%"},attrs:{headers:t.headers,items:t.$auth.user.emails,"hide-actions":"","disable-initial-sort":""},scopedSlots:t._u([{key:"items",fn:function(e){return[n("tr",{attrs:{active:t.$auth.user.mainEmail&&t.$auth.user.mainEmail.id===e.item.id}},[n("td",{class:{"success--text":e.item.verified,"error--text":!e.item.verified}},[t._v("\n              "+t._s(e.item.email)),n("br"),t._v(" "),t.$auth.user.mainEmail&&t.$auth.user.mainEmail.id===e.item.id?n("b",[t._v("\n                (главный)\n              ")]):t._e()]),t._v(" "),n("td",[t._v(t._s(e.item.label))]),t._v(" "),n("td",{staticClass:"text-xs-center"},[e.item.verified?n("v-icon",{attrs:{color:"success"}},[t._v("check")]):n("v-icon",{attrs:{color:"error"}},[t._v("error")])],1),t._v(" "),n("td",[n("v-checkbox",{staticClass:"ml-4",attrs:{"input-value":e.item.public,"hide-details":""},on:{change:function(n){return t.changePublicStateEmail(e.item)}}})],1),t._v(" "),n("td",{staticClass:"text-xs-center"},[e.item.verified?n("span",[t._v("\n                Почта подтверждена\n              ")]):t._e(),t._v(" "),e.item.verificationToken?n("span",[t._v("\n                Да\n                "),e.item.verificationToken?n("v-btn",{staticClass:"ml-4",attrs:{loading:t.repeatVerificationMailLoadingItemId===e.item.id,color:"primary",small:""},on:{click:function(n){return t.clickRepeatVerificationMail(e.item.id)}}},[n("v-icon",{attrs:{left:""}},[t._v("email")]),t._v("\n                  Отправить повторно\n                ")],1):t._e()],1):t._e()]),t._v(" "),n("td",[n("v-btn",{attrs:{icon:""},on:{click:function(n){t.deleteDialog=!0,t.deleteDialogItem=e.item.id}}},[n("v-icon",{attrs:{color:"error"}},[t._v("delete")])],1)],1)])]}}])})],1)],1),t._v(" "),n("v-dialog",{attrs:{"max-width":"290"},model:{value:t.deleteDialog,callback:function(e){t.deleteDialog=e},expression:"deleteDialog"}},[n("v-card",[n("v-card-title",{staticClass:"headline"},[t._v("Удалить этот адрес?")]),t._v(" "),n("v-card-actions",[n("v-spacer"),t._v(" "),n("v-btn",{attrs:{color:"blue darken-1",text:""},on:{click:function(e){t.deleteDialog=!1}}},[t._v("\n          Нет\n        ")]),t._v(" "),n("v-btn",{attrs:{loading:t.deleteDialogLoadingBtn,color:"error",text:""},on:{click:t.deleteDialogDeleteEmail}},[t._v("\n          Да\n        ")])],1)],1)],1),t._v(" "),n("v-dialog",{attrs:{"max-width":"400px"},model:{value:t.createDialog,callback:function(e){t.createDialog=e},expression:"createDialog"}},[n("v-card",[n("v-card-title",[n("span",{staticClass:"headline"},[t._v("\n          Добавление почтового адреса\n        ")])]),t._v(" "),n("v-card-text",[n("v-text-field",{directives:[{name:"validate",rawName:"v-validate",value:"required",expression:"'required'"}],attrs:{type:"email",label:"Почта","error-messages":t.errors.collect("email"),"data-vv-name":"email","prepend-icon":"mail",required:""},model:{value:t.createDialogForm.email,callback:function(e){t.$set(t.createDialogForm,"email",e)},expression:"createDialogForm.email"}}),t._v(" "),n("v-text-field",{attrs:{label:"Метка","error-messages":t.errors.collect("emailLabel"),"data-vv-name":"emailLabel","prepend-icon":"label",required:""},model:{value:t.createDialogForm.label,callback:function(e){t.$set(t.createDialogForm,"label",e)},expression:"createDialogForm.label"}}),t._v(" "),n("v-layout",{attrs:{"justify-start":""}},[n("v-checkbox",{attrs:{label:"Публичный?"},model:{value:t.createDialogForm.public,callback:function(e){t.$set(t.createDialogForm,"public",e)},expression:"createDialogForm.public"}}),t._v(" "),n("v-tooltip",{attrs:{bottom:""}},[n("v-btn",{staticClass:"mt-3",attrs:{slot:"activator",color:"primary",text:"",icon:""},slot:"activator"},[n("v-icon",[t._v("info")])],1),t._v(" "),n("span",[t._v("Виден всем пользователям в ваших контактах")])],1)],1),t._v(" "),n("v-checkbox",{attrs:{label:"Сделать главным?",disabled:!t.$auth.user.emails.length},model:{value:t.createDialogForm.main,callback:function(e){t.$set(t.createDialogForm,"main",e)},expression:"createDialogForm.main"}})],1),t._v(" "),n("v-card-actions",[n("v-spacer"),t._v(" "),n("v-btn",{attrs:{color:"blue darken-1",text:""},nativeOn:{click:function(e){t.createDialog=!1}}},[t._v("\n          Отмена\n        ")]),t._v(" "),n("v-btn",{attrs:{color:"blue darken-1",loading:t.createDialogLoadingBtn,disabled:!!this.errors.items.length,text:""},nativeOn:{click:function(e){return t.createDialogSaveEmail(e)}}},[t._v("\n          Сохранить\n        ")])],1)],1)],1)],1)}),[],!1,null,null,null);e.default=component.exports;f()(component,{VBtn:h.a,VCard:x.a,VCardActions:_.a,VCardText:_.b,VCardTitle:_.c,VCheckbox:y.a,VContainer:w.a,VDataTable:k.a,VDialog:O.a,VFlex:D.a,VIcon:j.a,VLayout:E.a,VSelect:C.a,VSpacer:$.a,VSubheader:S.a,VTextField:M.a,VToolbar:V.a,VToolbarTitle:F.b,VTooltip:I.a})}}]);