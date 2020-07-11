exports.ids=[15],exports.modules={482:function(t,e,r){"use strict";r(76);var o=r(77);e.a=Object(o.a)("layout")},484:function(t,e,r){"use strict";r(76);var o=r(77);e.a=Object(o.a)("flex")},601:function(t,e,r){"use strict";r.r(e);var o=r(11),n={transition:"slide-y-transition",scrollToTop:!0,mixins:[],asyncData:async({store:t})=>({passwordsHistory:await t.dispatch("profileSettings/getPasswordsHistory")}),data:()=>({password:null,currentPassword:null,newPassword:null,passwordShow:!0,loading:!1,dateFormat:process.env.dateFormats.main}),computed:{title(){return this.$auth.user.password?"Смена пароля":"Добавление пароля"},btnDisabled(){return!!this.errors.items.length}},methods:{async saveNewPassword(){const data=this.$auth.user.password?{currentPassword:this.currentPassword,newPassword:this.newPassword}:{password:this.password};await this.validateByMixin(data)&&(this.loading=!0,await this.setPassword(data),this.loading=!1,this.passwordsHistory=await this.getPasswordsHistory())},...Object(o.mapActions)("profileSettings",["setPassword","getPasswordsHistory"])}},l=r(10),d=r(12),w=r.n(d),c=r(477),v=r(37),y=r(484),m=r(36),f=r(482),h=r(462),component=Object(l.a)(n,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("div",[r("v-alert",{staticClass:"mb-3",attrs:{value:t.$auth.user.passwordLongTimeNotChange,type:"warning"}},[t._v("\n    Вы давно не меняли пароль, рекомендуется поменять на новый.\n  ")]),t._ssrNode(" "),r("v-alert",{staticClass:"mb-3",attrs:{value:!t.$auth.user.password,type:"warning"}},[t._v("\n    Если хотите входить через "),r("b",[t._v("ник")]),t._v(" или\n    "),r("b",[t._v("\n      почту\n      "),t.$auth.user.email?t._e():r("span",[t._v("(У Вас не введена почта)")])]),t._v("\n    , то добавьте пароль.\n    "),r("v-btn",{attrs:{color:"primary"},on:{click:function(e){return t.$refs.password.focus()}}},[t._v("\n      Добавить\n    ")])],1),t._ssrNode(" "),r("v-layout",{staticClass:"mt-5 mb-5",attrs:{"align-center":"","justify-center":"",wrap:""}},[r("v-flex",{staticClass:"mb-5 pr-5",attrs:{xs12:"",md8:"",lg6:"",xl6:""}},[r("form",{attrs:{autocomplete:"on"},on:{submit:function(e){return e.preventDefault(),t.saveNewPassword(e)},keydown:function(e){return!e.type.indexOf("key")&&t._k(e.keyCode,"enter",13,e.key,"Enter")?null:t.saveNewPassword(e)}}},[t.$auth.user.password?[r("v-text-field",{attrs:{"error-messages":t.errors.collect("currentPassword"),type:t.passwordShow?"text":"password","append-icon":t.passwordShow?"visibility_off":"visibility","data-vv-name":"currentPassword",label:"Текущий пароль",required:""},on:{"click:append":function(e){t.passwordShow=!t.passwordShow}},model:{value:t.currentPassword,callback:function(e){t.currentPassword=e},expression:"currentPassword"}}),t._v(" "),r("v-layout",{attrs:{"justify-center":""}},[r("v-icon",{staticClass:"mr-1"},[t._v("help")]),t._v(" "),r("nuxt-link",{attrs:{to:"/auth/forgot-password"}},[t._v("\n              Забыли пароль?\n            ")])],1),t._v(" "),r("v-text-field",{attrs:{"error-messages":t.errors.collect("newPassword"),type:t.passwordShow?"text":"password","append-icon":t.passwordShow?"visibility_off":"visibility","data-vv-name":"newPassword",label:"Новый пароль",required:""},on:{"click:append":function(e){t.passwordShow=!t.passwordShow}},model:{value:t.newPassword,callback:function(e){t.newPassword=e},expression:"newPassword"}})]:[r("v-text-field",{ref:"password",attrs:{"error-messages":t.errors.collect("password"),type:t.passwordShow?"text":"password","append-icon":t.passwordShow?"visibility_off":"visibility","data-vv-name":"password",label:"Какой пароль хотите?","prepend-icon":"security",required:""},on:{"click:append":function(e){t.passwordShow=!t.passwordShow}},model:{value:t.password,callback:function(e){t.password=e},expression:"password"}})],t._v(" "),r("v-btn",{staticClass:"mt-3 right",attrs:{loading:t.loading,disabled:t.btnDisabled,color:"primary",right:""},on:{click:t.saveNewPassword}},[r("v-icon",{attrs:{left:""}},[t._v("save")]),t._v("\n          Сохранить\n        ")],1)],2)]),t._v(" "),r("v-flex",{attrs:{xs12:"",md12:"",xl6:""}},[r("p",{staticClass:"title"},[t._v("\n        История изменений пароля\n      ")]),t._v(" "),t.passwordsHistory.length?r("v-alert",{attrs:{type:"info",value:!0}},[t._v("\n        Последнее изменение: "+t._s(t.$dayjs(t.passwordsHistory[t.passwordsHistory.length-1].created_at).format(t.dateFormat))+"\n      ")]):t._e(),t._v(" "),r("v-alert",{attrs:{type:"info",value:!t.passwordsHistory.length}},[t._v("\n        Вы не меняли пароль.\n      ")]),t._v(" "),r("ul",t._l(t.passwordsHistory.slice().reverse(),(function(e){return r("li",{key:e.id},[r("time",[t._v("\n            "+t._s(t.$dayjs(e.created_at).format(t.dateFormat))+"\n            ("+t._s(t.$dayjs().to(t.$dayjs(e.created_at)))+")\n          ")])])})),0)],1)],1),t._ssrNode(' <div><p class="title">Сочетайте разные символы</p> <p>Используйте одновременно буквы, цифры и специальные символы:</p> <ul><li>Заглавные (прописные) буквы, например A, E, R.</li> <li>Строчные буквы, например a, e, r.</li> <li>Цифры, например 2, 6, 7.</li> <li>Специальные символы, например !, @, &amp;, *.</li></ul> <p class="title mt-3">Следуйте рекомендациям</p> <p>Выберите слово или фразу и замените некоторые буквы цифрами или символами. В пароле нельзя использовать символы кириллицы, но можно заменить их похожими допустимыми символами или использовать транслит. Примеры:</p> <ul><li>Название праздника Хеллоуин можно записать как }{eJIJI0yI/IH.</li> <li>Прощание &quot;чао-какао&quot; можно записать как 4A0-|{aK@O.</li></ul> <p>Сократите фразу: используйте первые буквы каждого слова. Пример:</p> <ul><li>&quot;Если звезды зажигают, значит, это кому-нибудь нужно?&quot; можно записать как E*zZeKnN?</li></ul> <p>Чем длиннее пароль, тем он надежнее. Поскольку в паролях допустимы пробелы, можно использовать запоминающиеся фразы или слова из ваших любимых песен, стихов или цитат. Пример:</p> <ul><li>@ – DrOOG 4eloveka (Собака – друг человека).</li> <li>33 KoP0BbI – cBe}|{AR sTP0|&lt;A (Тридцать три коровы – свежая строка).</li> <li>Ya pomnyu chudnoe mgnovenye: Peredo mnoy yavilas ty (Я помню чудное мгновенье: передо мной явилась ты).</li></ul></div>')],2)}),[],!1,null,null,"05d9d028");e.default=component.exports;w()(component,{VAlert:c.a,VBtn:v.a,VFlex:y.a,VIcon:m.a,VLayout:f.a,VTextField:h.a})}};