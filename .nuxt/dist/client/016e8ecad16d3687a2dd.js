(window.webpackJsonp=window.webpackJsonp||[]).push([[9],{239:function(e,t,r){"use strict";r.d(t,"a",(function(){return c})),r.d(t,"b",(function(){return f})),r.d(t,"c",(function(){return m}));var n=r(648),o=r(0),c=Object(o.i)("v-card__actions"),l=Object(o.i)("v-card__subtitle"),f=Object(o.i)("v-card__text"),m=Object(o.i)("v-card__title");n.a},676:function(e,t,r){"use strict";r(175);var n=r(176);t.a=Object(n.a)("flex")},677:function(e,t,r){"use strict";r(175);var n=r(176);t.a=Object(n.a)("layout")},682:function(e,t,r){var content=r(707);"string"==typeof content&&(content=[[e.i,content,""]]),content.locals&&(e.exports=content.locals);(0,r(13).default)("16844bd4",content,!0,{sourceMap:!1})},706:function(e,t,r){"use strict";var n=r(682);r.n(n).a},707:function(e,t,r){(t=r(12)(!1)).push([e.i,'.hr-sect[data-v-118076ca]{display:flex;flex-basis:100%;align-items:center;color:rgba(0,0,0,.35);margin:28px 0}.hr-sect[data-v-118076ca]:after,.hr-sect[data-v-118076ca]:before{content:"";flex-grow:1;height:1px;font-size:0;line-height:0;margin:0 8px}.hr-sect[data-v-118076ca]:before{background:-webkit-gradient(linear,0 0,100% 100,from(#fff),to(#fff),color-stop(50%,grey))}.hr-sect[data-v-118076ca]:after{background:-webkit-gradient(linear,0 100,100% 0,from(#fff),to(#fff),color-stop(50%,grey))}',""]),e.exports=t},738:function(e,t,r){"use strict";r(11),r(8),r(7),r(4),r(9);var n=r(55),o=(r(34),r(3)),c=r(1),l=r(35),f=r(15);function m(object,e){var t=Object.keys(object);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(object);e&&(r=r.filter((function(e){return Object.getOwnPropertyDescriptor(object,e).enumerable}))),t.push.apply(t,r)}return t}function d(e){for(var i=1;i<arguments.length;i++){var source=null!=arguments[i]?arguments[i]:{};i%2?m(Object(source),!0).forEach((function(t){Object(c.a)(e,t,source[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(source)):m(Object(source)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(source,t))}))}return e}var h=null,v={props:{value:Object,handler:Function,validateOnStart:Boolean,socAuth:Boolean,captchaLoaded:Boolean},mixins:[],data:function(){return{form:{email:null,nickname:null,password:null},loading:!1,passwordShow:!1,nicknameUnique:null,nicknameCheckLoading:!1,passwordVariantAuthChoose:null,enterEmail:null}},methods:d({submit:function(){var e=this;return Object(o.a)(regeneratorRuntime.mark((function t(){var r,n,o;return regeneratorRuntime.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return r=e.form,!1===e.passwordVariantAuthChoose&&(n=e.form.nickname,r={nickname:n}),t.next=4,e.validateByMixin(r);case 4:if(!t.sent){t.next=15;break}if(o=null,!window.grecaptcha){t.next=11;break}if(o=window.grecaptcha.getResponse()){t.next=11;break}return f.c.error("Подтвердите что Вы не робот."),t.abrupt("return");case 11:return e.loading=!0,t.next=14,e.handler(o);case 14:e.loading=!1;case 15:case"end":return t.stop()}}),t)})))()},checkNickname:function(){var e=this;return Object(o.a)(regeneratorRuntime.mark((function t(){return regeneratorRuntime.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:e.nicknameCheckLoading=!0,clearTimeout(h),h=setTimeout(Object(o.a)(regeneratorRuntime.mark((function t(){return regeneratorRuntime.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return t.next=2,e.checkNicknameUnique(e.form.nickname);case 2:e.nicknameUnique=t.sent,e.nicknameCheckLoading=!1;case 4:case"end":return t.stop()}}),t)}))),1e3);case 3:case"end":return t.stop()}}),t)})))()},input:function(){this.$emit("input",{data:this.form,btnDisabled:this.btnDisabled,passwordVariantAuthChoose:this.passwordVariantAuthChoose})},setFormFromValue:function(){this.form=d({},this.form,{},this.value.data)}},Object(l.b)("auth",["checkNicknameUnique"])),computed:{nicknameErrors:function(){var e=this.errors.collect("nickname");return!1===this.nicknameUnique&&e.push("Занятый"),e},btnDisabled:function(){return!(![].concat(Object(n.a)(this.errors.items),Object(n.a)(this.nicknameErrors)).length&&!this.nicknameCheckLoading)}},watch:{form:{deep:!0,handler:function(){this.input()}},errors:function(){this.input()},btnDisabled:function(){this.input()},passwordVariantAuthChoose:function(){this.input()}},mounted:function(){return Object(o.a)(regeneratorRuntime.mark((function e(){return regeneratorRuntime.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:case"end":return e.stop()}}),e)})))()}},w=r(32),k=r(38),O=r.n(k),x=r(666),y=r(101),j=r(677),_=r(165),C=r(652),component=Object(w.a)(v,(function(){var e=this,t=e.$createElement,r=e._self._c||t;return r("div",{staticClass:"px-3"},[r("v-text-field",{attrs:{"error-messages":e.nicknameErrors,loading:e.nicknameCheckLoading,"data-vv-name":"nickname","prepend-icon":"person",label:"Ник",required:""},on:{input:e.checkNickname},model:{value:e.form.nickname,callback:function(t){e.$set(e.form,"nickname",t)},expression:"form.nickname"}}),e._v(" "),e.form.nickname&&!e.errors.has("nickname")?[r("v-alert",{attrs:{value:e.nicknameCheckLoading,color:"info",icon:"info",outline:""}},[e._v("\n      Checking...\n      "),r("v-progress-circular",{attrs:{color:"primary",size:20,indeterminate:""}})],1),e._v(" "),e.nicknameCheckLoading?e._e():[r("v-alert",{attrs:{value:e.nicknameUnique,color:"success",icon:"check_circle",outline:""}},[e._v("\n        Nickname is available\n      ")])]]:e._e(),e._v(" "),e.socAuth?e._e():r("v-text-field",{directives:[{name:"validate",rawName:"v-validate",value:"required",expression:"'required'"}],attrs:{label:"Email",type:"email","error-messages":e.errors.collect("email"),"data-vv-name":"email",required:""},model:{value:e.form.email,callback:function(t){e.$set(e.form,"email",t)},expression:"form.email"}}),e._v(" "),e.socAuth?e._e():r("v-text-field",{attrs:{"error-messages":e.errors.collect("password"),type:e.passwordShow?"text":"password","append-icon":e.passwordShow?"visibility_off":"visibility","data-vv-name":"password",label:"Password",required:""},on:{"click:append":function(t){e.passwordShow=!e.passwordShow}},model:{value:e.form.password,callback:function(t){e.$set(e.form,"password",t)},expression:"form.password"}}),e._v(" "),e.socAuth?e._e():r("v-layout",{staticClass:"mt-4",attrs:{"justify-center":""}}),e._v(" "),r("v-btn",{staticClass:"mt-5",attrs:{color:"primary",loading:e.loading,disabled:e.btnDisabled,large:"",block:""},on:{click:e.submit}},[e._v("\n    Submit\n  ")])],2)}),[],!1,null,null,null);t.a=component.exports;O()(component,{VAlert:x.a,VBtn:y.a,VLayout:j.a,VProgressCircular:_.a,VTextField:C.a})},739:function(e,t,r){"use strict";var n={props:["text"]},o=(r(706),r(32)),component=Object(o.a)(n,(function(){var e=this.$createElement,t=this._self._c||e;return t("div",{staticClass:"hr-sect"},[t("span",{staticClass:"subheading"},[this._v("\n    "+this._s(this.text)+"\n  ")])])}),[],!1,null,"118076ca",null);t.a=component.exports},951:function(e,t,r){"use strict";r.r(t);r(11),r(8),r(7),r(4),r(9),r(34);var n=r(3),o=r(1),c=r(35),l=r(739),f=r(738);function m(object,e){var t=Object.keys(object);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(object);e&&(r=r.filter((function(e){return Object.getOwnPropertyDescriptor(object,e).enumerable}))),t.push.apply(t,r)}return t}var d={middleware:"guest",components:{AppHrText:l.a,SignupForm:f.a},data:function(){return{showForm:!1,form:{},captchaLoaded:!1,captchaKey:"6LcpH18UAAAAAJ7IxDGeA4TtQdBDTYA4xL4QSkvA"}},methods:function(e){for(var i=1;i<arguments.length;i++){var source=null!=arguments[i]?arguments[i]:{};i%2?m(Object(source),!0).forEach((function(t){Object(o.a)(e,t,source[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(source)):m(Object(source)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(source,t))}))}return e}({submit:function(e){var t=this;return Object(n.a)(regeneratorRuntime.mark((function r(){var data;return regeneratorRuntime.wrap((function(r){for(;;)switch(r.prev=r.next){case 0:return data=t.form.data,r.next=3,t.signup({form:data,captchaResponse:e});case 3:case"end":return r.stop()}}),r)})))()}},Object(c.b)("auth",["signup"])),beforeMount:function(){window.onloadCallback=function(){}},head:function(){return{title:"Страница регестрации",meta:[{content:"Это страница регестрации",name:"description",hid:"description"}]}}},h=r(32),v=r(38),w=r.n(v),k=r(666),O=r(101),x=r(648),y=r(239),j=r(669),_=r(676),C=r(677),A=r(44),component=Object(h.a)(d,(function(){var e=this,t=e.$createElement,r=e._self._c||t;return r("v-container",{attrs:{"fill-height":""}},[r("v-layout",{attrs:{"fill-height":"","justify-center":"","align-center":""}},[r("v-flex",{attrs:{xs12:"",sm8:"",md6:"",lg4:"",xl3:""}},[r("v-card",{staticClass:"elevation-10 app-border-all-round"},[r("v-toolbar",{attrs:{flat:"",prominent:""}},[r("v-layout",{staticClass:"display-1",attrs:{"justify-center":""}},[e._v("\n              Sign up\n          ")])],1),e._v(" "),r("v-card-text",[r("v-layout",{staticClass:"display-1",attrs:{"justify-center":""}},[r("v-btn",{directives:[{name:"show",rawName:"v-show",value:!e.showForm,expression:"!showForm"}],attrs:{color:"primary"},on:{click:function(t){e.showForm=!0}}},[e._v("\n              Via mail\n            ")])],1),e._v(" "),r("form",{directives:[{name:"show",rawName:"v-show",value:e.showForm,expression:"showForm"}],attrs:{autocomplete:"on"}},[r("v-alert",{attrs:{value:!0,type:"warning"}},[e._v("\n              This option requires mandatory confirmation of mail to activate your account!\n            ")]),e._v(" "),r("signup-form",{attrs:{handler:e.submit,"captcha-loaded":e.captchaLoaded},model:{value:e.form,callback:function(t){e.form=t},expression:"form"}})],1)],1),e._v(" "),r("div",{staticClass:"text-xs-center pb-3"},[r("nuxt-link",{attrs:{to:"/auth/signin"}},[e._v("\n            Already registered?\n          ")])],1)],1)],1)],1)],1)}),[],!1,null,null,null);t.default=component.exports;w()(component,{VAlert:k.a,VBtn:O.a,VCard:x.a,VCardText:y.b,VContainer:j.a,VFlex:_.a,VLayout:C.a,VToolbar:A.a})}}]);