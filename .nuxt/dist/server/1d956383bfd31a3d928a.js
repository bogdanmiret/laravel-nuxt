exports.ids=[5],exports.modules={101:function(t,e,n){"use strict";n.d(e,"a",(function(){return c})),n.d(e,"b",(function(){return d})),n.d(e,"c",(function(){return m}));var r=n(457),o=n(0);const c=Object(o.i)("v-card__actions"),l=Object(o.i)("v-card__subtitle"),d=Object(o.i)("v-card__text"),m=Object(o.i)("v-card__title");r.a},482:function(t,e,n){"use strict";n(76);var r=n(77);e.a=Object(r.a)("layout")},484:function(t,e,n){"use strict";n(76);var r=n(77);e.a=Object(r.a)("flex")},495:function(t,e,n){var content=n(523);"string"==typeof content&&(content=[[t.i,content,""]]),content.locals&&(t.exports=content.locals);var r=n(5).default;t.exports.__inject__=function(t){r("16844bd4",content,!0,t)}},522:function(t,e,n){"use strict";n.r(e);var r=n(495),o=n.n(r);for(var c in r)"default"!==c&&function(t){n.d(e,t,(function(){return r[t]}))}(c);e.default=o.a},523:function(t,e,n){(e=n(4)(!1)).push([t.i,'.hr-sect[data-v-118076ca]{display:flex;flex-basis:100%;align-items:center;color:rgba(0,0,0,.35);margin:28px 0}.hr-sect[data-v-118076ca]:after,.hr-sect[data-v-118076ca]:before{content:"";flex-grow:1;height:1px;font-size:0;line-height:0;margin:0 8px}.hr-sect[data-v-118076ca]:before{background:-webkit-gradient(linear,0 0,100% 100,from(#fff),to(#fff),color-stop(50%,grey))}.hr-sect[data-v-118076ca]:after{background:-webkit-gradient(linear,0 100,100% 0,from(#fff),to(#fff),color-stop(50%,grey))}',""]),t.exports=e},538:function(t,e,n){"use strict";var r=n(11),o=n(7);let c=null;var l={props:{value:Object,handler:Function,validateOnStart:Boolean,socAuth:Boolean,captchaLoaded:Boolean},mixins:[],data:()=>({form:{email:null,nickname:null,password:null},loading:!1,passwordShow:!1,nicknameUnique:null,nicknameCheckLoading:!1,passwordVariantAuthChoose:null,enterEmail:null}),methods:{async submit(){let t=this.form;if(!1===this.passwordVariantAuthChoose){const{nickname:e}=this.form;t={nickname:e}}if(await this.validateByMixin(t)){let t=null;if(window.grecaptcha&&(t=window.grecaptcha.getResponse(),!t))return void o.c.error("Подтвердите что Вы не робот.");this.loading=!0,await this.handler(t),this.loading=!1}},async checkNickname(){this.nicknameCheckLoading=!0,clearTimeout(c),c=setTimeout(async()=>{this.nicknameUnique=await this.checkNicknameUnique(this.form.nickname),this.nicknameCheckLoading=!1},1e3)},input(){this.$emit("input",{data:this.form,btnDisabled:this.btnDisabled,passwordVariantAuthChoose:this.passwordVariantAuthChoose})},setFormFromValue(){this.form={...this.form,...this.value.data}},...Object(r.mapActions)("auth",["checkNicknameUnique"])},computed:{nicknameErrors(){let t=this.errors.collect("nickname");return!1===this.nicknameUnique&&t.push("Занятый"),t},btnDisabled(){return!(![...this.errors.items,...this.nicknameErrors].length&&!this.nicknameCheckLoading)}},watch:{form:{deep:!0,handler(){this.input()}},errors(){this.input()},btnDisabled(){this.input()},passwordVariantAuthChoose(){this.input()}},async mounted(){}},d=n(10),m=n(12),h=n.n(m),f=n(477),v=n(37),k=n(482),_=n(69),w=n(462),component=Object(d.a)(l,(function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"px-3"},[n("v-text-field",{attrs:{"error-messages":t.nicknameErrors,loading:t.nicknameCheckLoading,"data-vv-name":"nickname","prepend-icon":"person",label:"Ник",required:""},on:{input:t.checkNickname},model:{value:t.form.nickname,callback:function(e){t.$set(t.form,"nickname",e)},expression:"form.nickname"}}),t._ssrNode(" "),t.form.nickname&&!t.errors.has("nickname")?[n("v-alert",{attrs:{value:t.nicknameCheckLoading,color:"info",icon:"info",outline:""}},[t._v("\n      Checking...\n      "),n("v-progress-circular",{attrs:{color:"primary",size:20,indeterminate:""}})],1),t._ssrNode(" "),t.nicknameCheckLoading?t._e():[n("v-alert",{attrs:{value:t.nicknameUnique,color:"success",icon:"check_circle",outline:""}},[t._v("\n        Nickname is available\n      ")])]]:t._e(),t._ssrNode(" "),t.socAuth?t._e():n("v-text-field",{directives:[{name:"validate",rawName:"v-validate",value:"required",expression:"'required'"}],attrs:{label:"Email",type:"email","error-messages":t.errors.collect("email"),"data-vv-name":"email",required:""},model:{value:t.form.email,callback:function(e){t.$set(t.form,"email",e)},expression:"form.email"}}),t._ssrNode(" "),t.socAuth?t._e():n("v-text-field",{attrs:{"error-messages":t.errors.collect("password"),type:t.passwordShow?"text":"password","append-icon":t.passwordShow?"visibility_off":"visibility","data-vv-name":"password",label:"Password",required:""},on:{"click:append":function(e){t.passwordShow=!t.passwordShow}},model:{value:t.form.password,callback:function(e){t.$set(t.form,"password",e)},expression:"form.password"}}),t._ssrNode(" "),t.socAuth?t._e():n("v-layout",{staticClass:"mt-4",attrs:{"justify-center":""}}),t._ssrNode(" "),n("v-btn",{staticClass:"mt-5",attrs:{color:"primary",loading:t.loading,disabled:t.btnDisabled,large:"",block:""},on:{click:t.submit}},[t._v("\n    Submit\n  ")])],2)}),[],!1,null,null,"4b6cebdb");e.a=component.exports;h()(component,{VAlert:f.a,VBtn:v.a,VLayout:k.a,VProgressCircular:_.a,VTextField:w.a})},539:function(t,e,n){"use strict";var r={props:["text"]},o=n(10);var component=Object(o.a)(r,(function(){var t=this.$createElement;return(this._self._c||t)("div",{staticClass:"hr-sect"},[this._ssrNode('<span class="subheading" data-v-118076ca>'+this._ssrEscape("\n    "+this._s(this.text)+"\n  ")+"</span>")])}),[],!1,(function(t){var e=n(522);e.__inject__&&e.__inject__(t)}),"118076ca","8c1d47fa");e.a=component.exports},603:function(t,e,n){"use strict";n.r(e);var r=n(11),o=n(539),c=n(538),l={middleware:"guest",components:{AppHrText:o.a,SignupForm:c.a},data:()=>({showForm:!1,form:{},captchaLoaded:!1,captchaKey:"6LcpH18UAAAAAJ7IxDGeA4TtQdBDTYA4xL4QSkvA"}),methods:{async submit(t){let{data:data}=this.form;await this.signup({form:data,captchaResponse:t})},...Object(r.mapActions)("auth",["signup"])},beforeMount(){window.onloadCallback=()=>{}},head:()=>({title:"Страница регестрации",meta:[{content:"Это страница регестрации",name:"description",hid:"description"}]})},d=n(10),m=n(12),h=n.n(m),f=n(477),v=n(37),k=n(457),_=n(101),w=n(478),x=n(484),y=n(482),C=n(15),component=Object(d.a)(l,(function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("v-container",{attrs:{"fill-height":""}},[n("v-layout",{attrs:{"fill-height":"","justify-center":"","align-center":""}},[n("v-flex",{attrs:{xs12:"",sm8:"",md6:"",lg4:"",xl3:""}},[n("v-card",{staticClass:"elevation-10 app-border-all-round"},[n("v-toolbar",{attrs:{flat:"",prominent:""}},[n("v-layout",{staticClass:"display-1",attrs:{"justify-center":""}},[t._v("\n              Sign up\n          ")])],1),t._v(" "),n("v-card-text",[n("v-layout",{staticClass:"display-1",attrs:{"justify-center":""}},[n("v-btn",{directives:[{name:"show",rawName:"v-show",value:!t.showForm,expression:"!showForm"}],attrs:{color:"primary"},on:{click:function(e){t.showForm=!0}}},[t._v("\n              Via mail\n            ")])],1),t._v(" "),n("form",{directives:[{name:"show",rawName:"v-show",value:t.showForm,expression:"showForm"}],attrs:{autocomplete:"on"}},[n("v-alert",{attrs:{value:!0,type:"warning"}},[t._v("\n              This option requires mandatory confirmation of mail to activate your account!\n            ")]),t._v(" "),n("signup-form",{attrs:{handler:t.submit,"captcha-loaded":t.captchaLoaded},model:{value:t.form,callback:function(e){t.form=e},expression:"form"}})],1)],1),t._v(" "),n("div",{staticClass:"text-xs-center pb-3"},[n("nuxt-link",{attrs:{to:"/auth/signin"}},[t._v("\n            Already registered?\n          ")])],1)],1)],1)],1)],1)}),[],!1,null,null,"5d93081a");e.default=component.exports;h()(component,{VAlert:f.a,VBtn:v.a,VCard:k.a,VCardText:_.b,VContainer:w.a,VFlex:x.a,VLayout:y.a,VToolbar:C.a})}};