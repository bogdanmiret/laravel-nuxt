<template>
    <video :poster="poster_src" 
            :preload="preload"
            :autoplay="autoplay"
            :muted="muted"
            :loop="loop"
            :class="{hidden: !display_on_mobile}"
            ref="video"
            >
        <source :data-src="source" :src="src" :type="type">
    </video>
</template>

<script>
    export default {
        props: {
            poster_src: {type: String},
            preload: {type: String},
            autoplay: {type: String},
            muted : {type: String},
            loop: {type: String},
            source: {type: String},
            type: {type: String},
            display_on_mobile: {
                Type: Boolean,
                default: false
            }
        },
        data() {
            return {
                src: ''
            }
        },
        mounted() {
            this.build();
        },
        methods: {
            build() {
                if(!this.display_on_mobile) {
                    let md = new MobileDetect(window.navigator.userAgent);
    
                    if (!md.is('bot') && md.mobile() === null) {
                         this.loadVideoSource(this.source);
                    }
                } else {
                    this.loadVideoSource(this.source);
                }
            },
            loadVideoSource(src) {
                this.$data.src = src;
                // Force video load.
                let vid = this.$refs.video;
                vid.load();
            },
        }
    }
</script>

<style scoped>
@media (max-width: 767px) {
    .hidden {
        display: none;
    }
}
</style>