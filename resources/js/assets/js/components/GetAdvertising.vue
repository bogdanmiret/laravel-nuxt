<template>
    <div v-html="content"></div>
</template>

<script>
    export default {
        props: ['api_endpoint', 'slug', 'type'],
        data() {
            return {
                content: ''
            }
        },
        mounted() {
            this.get_advertising();
        },
        methods: {
            get_advertising() {
                let md = new MobileDetect(window.navigator.userAgent);

                if (md.mobile()) {

                }
            },
            activate_google(content) {

                if (content === 'adpushup') {

                    let adpush = '<ins class="adPushupAds" data-adpControl="07vf1" data-ver="2" data-siteId="40559" data-ac="PHNjcmlwdCBhc3luYyBzcmM9Imh0dHBzOi8vcGFnZWFkMi5nb29nbGVzeW5kaWNhdGlvbi5jb20vcGFnZWFkL2pzL2Fkc2J5Z29vZ2xlLmpzIj48L3NjcmlwdD4KPCEtLSByZXNwb25zaXZlIGJhc2ljIC0tPgo8aW5zIGNsYXNzPSJhZHNieWdvb2dsZSIKICAgICBzdHlsZT0iZGlzcGxheTpibG9jayIKICAgICBkYXRhLWFkLWNsaWVudD0iY2EtcHViLTM3MDQ4MTAwNjE1MDU3NDQiCiAgICAgZGF0YS1hZC1zbG90PSI2Njc0NDk4MTE4IgogICAgIGRhdGEtYWQtZm9ybWF0PSJhdXRvIgogICAgIGRhdGEtZnVsbC13aWR0aC1yZXNwb25zaXZlPSJ0cnVlIj48L2lucz4KPHNjcmlwdD4KICAgICAoYWRzYnlnb29nbGUgPSB3aW5kb3cuYWRzYnlnb29nbGUgfHwgW10pLnB1c2goe30pOwo8L3NjcmlwdD4="></ins>';
                    this.content = adpush;

                    setTimeout(() => {
                        (function (w, d) { for (var i = 0, j = d.getElementsByTagName("ins"), k = j[i]; i < j.length; k = j[++i]){
                            if(k.className == "adPushupAds" && k.getAttribute("data-push") != "1") {
                                ((w.adpushup = w.adpushup || {}).control = (w.adpushup.control || [])).push(k);
                                k.setAttribute("data-push", "1");
                            }
                        }
                        })(window, document);
                    }, 150);

                } else {
                    this.content = content;
                    setTimeout(() => {
                        (adsbygoogle = window.adsbygoogle || []).push({});
                    }, 150);

                }


            }
        }
    }
    // if ($('#ads').length === 1) {
</script>

