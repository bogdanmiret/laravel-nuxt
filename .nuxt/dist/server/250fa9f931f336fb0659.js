exports.ids=[7],exports.modules={482:function(t,e,n){"use strict";n(76);var r=n(77);e.a=Object(r.a)("layout")},602:function(t,e,n){"use strict";n.r(e);var r=n(11),o={data:()=>({}),methods:{...Object(r.mapActions)("auth",["verifyEmail"])},computed:{token(){return this.$route.query.token}},async mounted(){console.log(this.token),await this.verifyEmail(this.token)}},l=n(10),c=n(12),h=n.n(c),f=n(477),d=n(478),v=n(482),component=Object(l.a)(o,(function(){var t=this.$createElement,e=this._self._c||t;return e("v-container",{attrs:{"fill-height":""}},[e("v-layout",{attrs:{"fill-height":"","justify-center":"","align-center":""}},[e("v-alert",{staticClass:"display-1 text-xs-center",attrs:{value:!0,type:"info"}},[this._v("\n\n      Обрабатываем...\n\n    ")])],1)],1)}),[],!1,null,null,"5158dde3");e.default=component.exports;h()(component,{VAlert:f.a,VContainer:d.a,VLayout:v.a})}};