exports.ids=[17],exports.modules={482:function(t,e,n){"use strict";n(76);var o=n(77);e.a=Object(o.a)("layout")},484:function(t,e,n){"use strict";n(76);var o=n(77);e.a=Object(o.a)("flex")},486:function(t,e,n){"use strict";n(493);var o=n(6),r=n(2);e.a=Object(r.a)(o.a).extend({name:"v-subheader",props:{inset:Boolean},render(t){return t("div",{staticClass:"v-subheader",class:{"v-subheader--inset":this.inset,...this.themeClasses},attrs:this.$attrs,on:this.$listeners},this.$slots.default)}})},493:function(t,e,n){var content=n(494);"string"==typeof content&&(content=[[t.i,content,""]]),content.locals&&(t.exports=content.locals),n(5).default("e8b41e5e",content,!0)},494:function(t,e,n){(e=n(4)(!1)).push([t.i,".theme--light.v-subheader{color:rgba(0,0,0,.6)}.theme--dark.v-subheader{color:hsla(0,0%,100%,.7)}.v-subheader{align-items:center;display:flex;height:48px;font-size:.875rem;font-weight:400;padding:0 16px}.v-subheader--inset{margin-left:56px}",""]),t.exports=e},598:function(t,e,n){"use strict";n.r(e);var o=n(84),r=n.n(o),c=n(11),l=n(469),v=n.n(l),d={components:{FontAwesomeIcon:r.a},data:()=>({socAccounts:null,saveSocAccBtnIndexForLoading:null}),computed:{notUsedSocAccount(){return v()(this.socAccounts,this.$auth.user.socAccounts,"name")}},methods:{async saveSocAcc(t,e){this.saveSocAccBtnIndexForLoading=e,location.href=await this.getRedirectUrl(t),this.saveSocAccBtnIndexForLoading=null},...Object(c.mapActions)(["getSocialiteProviders"]),...Object(c.mapActions)("authSocialite",["deleteSocAcc","getRedirectUrl"])}},h=n(10),_=n(12),f=n.n(_),m=n(477),x=n(37),y=n(457),A=n(460),$=n(484),S=n(36),w=n(482),V=n(71),j=n(486),component=Object(h.a)(d,(function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("v-layout",{attrs:{"justify-center":""}},[n("v-card",{staticStyle:{"border-radius":"20px"}},[t.socAccounts?n("v-layout",{attrs:{"justify-center":"",row:"",wrap:""}},[n("v-flex",{staticClass:"px-2 pb-3",attrs:{sm10:""}},[n("v-subheader",[t._v("\n          Прикрепленные аккаунты\n        ")]),t._v(" "),t.$auth.user.socAccounts.length?n("v-list",{attrs:{"two-line":"",dense:""}},[t._l(t.$auth.user.socAccounts,(function(e,o){return[n("v-list-tile",{key:o},[n("v-list-tile-avatar",{style:"color: "+e.color},[n("font-awesome-icon",{attrs:{icon:["fab",e.icon],size:"3x"}})],1),t._v(" "),n("v-list-tile-content",{style:"color: "+e.color},[t._v("\n                "+t._s(e.name_for_human)+"\n              ")]),t._v(" "),n("v-list-tile-action",[t.$auth.user.password||!t.$auth.user.password&&t.$auth.user.socAccounts.length>1?n("v-btn",{attrs:{text:"",icon:"",large:""},on:{click:function(n){return t.deleteSocAcc({name:e.name,id:e.id})}}},[n("v-icon",{attrs:{color:"red",large:""}},[t._v("delete_forever")])],1):t._e()],1)],1),t._v(" "),n("small",{key:o,staticClass:"grey--text ml-4"},[t._v("\n              Прикреплено "+t._s(t._f("date")(e.pivot.created_at))+"\n              ("+t._s(t.$dayjs().to(t.$dayjs(e.pivot.created_at)))+")\n            ")]),t._v(" "),o+1<t.$auth.user.socAccounts.length?n("v-divider",{key:o}):t._e()]}))],2):t._e(),t._v(" "),n("v-alert",{attrs:{type:"info",value:!t.$auth.user.socAccounts.length}},[t._v("\n          Нет прикрепленных.\n        ")])],1),t._v(" "),n("v-flex",{staticClass:"px-2",attrs:{sm10:""}},[n("v-subheader",[t._v("\n          Можно прикрепить\n        ")]),t._v(" "),t.notUsedSocAccount.length?n("v-list",{attrs:{"two-line":"",dense:""}},[t._l(t.notUsedSocAccount,(function(e,o){return[n("v-list-tile",{key:o},[n("v-list-tile-avatar",{style:"color: "+e.color},[n("font-awesome-icon",{attrs:{icon:["fab",e.icon],size:"3x"}})],1),t._v(" "),n("v-list-tile-content",{style:"color: "+e.color},[t._v("\n                "+t._s(e.name_for_human)+"\n              ")]),t._v(" "),n("v-list-tile-action",[n("v-btn",{attrs:{loading:t.saveSocAccBtnIndexForLoading===o,text:"",icon:"",large:""},on:{click:function(n){return t.saveSocAcc(e.name,o)}}},[n("v-icon",{attrs:{color:"green",large:""}},[t._v("add")])],1)],1)],1),t._v(" "),o+1<t.notUsedSocAccount.length?n("v-divider",{key:o}):t._e()]}))],2):t._e(),t._v(" "),n("v-alert",{attrs:{type:"info",value:!t.notUsedSocAccount.length}},[t._v("\n          Все прикреплено.\n        ")])],1)],1):t._e()],1)],1)}),[],!1,null,null,"47d2f81e"),k=component.exports;f()(component,{VAlert:m.a,VBtn:x.a,VCard:y.a,VDivider:A.a,VFlex:$.a,VIcon:S.a,VLayout:w.a,VList:V.a,VSubheader:j.a});var O={transition:"slide-y-transition",scrollToTop:!0,components:{socAccounts:k},computed:{addOrVerifyEmailText(){return this.$auth.user.mainEmail?this.$auth.user.mainEmail.verified?void 0:"подтвердить почту":"добавить и подтвердить почту"}}},C=Object(h.a)(O,(function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",[1===t.$auth.user.socAccounts.length?[t.$auth.user.password?!t.$auth.user.activated||1!==t.$auth.user.emails.length||t.$auth.user.mainEmail&&t.$auth.user.mainEmail.verified?t._e():n("v-alert",{staticClass:"mb-3",attrs:{type:"info",value:!0}},[t._v("\n      Если Вы открепите последний аккаунт, то Ваш аккаунт будет не активирован.\n      Чтобы остаться активированным Вам нужно "+t._s(t.addOrVerifyEmailText)+".\n    ")]):n("v-alert",{staticClass:"mb-3",attrs:{type:"info",value:!0}},[t._v("\n      У вас не настроен\n      "),n("router-link",{attrs:{to:"/profile/settings/password"}},[t._v("\n        вход через пароль\n      ")]),t._v("\n      (с помощью почты или ника), это значит что "),n("b",[t._v("мы Вам запретили откреплять последний аккаунт")]),t._v(",\n      иначе Вы не смогли бы входить на сайт.\n    ")],1)]:t._e(),t._ssrNode(" "),n("soc-accounts")],2)}),[],!1,null,null,"9c8c680e");e.default=C.exports;f()(C,{VAlert:m.a})}};