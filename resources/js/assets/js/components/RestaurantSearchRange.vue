<template>
    <div class="widget" id="range">
        <h2 class="widgettitle" style="margin-top:20px">
            {{trans_title}}
            <div class="toggle-switch float-right" style="display: grid;">
                <input type="checkbox" name="type[12]" :checked="range_bool" id="type12" class="toggleCheckbox"
                       @click="range_bool = !range_bool; rangeBoolEvent()">
                <label for="type12" class="toggleLabel">On/Off</label>
            </div>

        </h2>
        <div class="background-white p0-20">
            <div class="row flex-center-space" style="background-color: white;">

                <div class="slider-box">
                    <vue-slider
                            :dotStyle="{ backgroundColor: '#FFD54F' }"
                            :railStyle="{backgroundColor: '#D6DBDE'}"
                            :processStyle="{backgroundColor: '#D6DBDE'}"
                            :tooltipStyle="{backgroundColor: 'red'}"
                            :min="10"
                            :max="50"
                            :dot-options="{ tooltip: 'none'}"
                            v-model="value"
                            @change="EmitEvent(value)"
                    />
                </div>
                <div class="float-right value-box" >
                    <p class="range-slider-value" :title="hoverMessage(this.value)">{{this.value}}<br> <span style="font-weight: normal; font-size: 12px;">Km</span></p>
                </div>
            </div>
        </div>

        <div class="text-center">
            <button class="btn btn-primary mt-10" id="rangeButton" @click="emitSearch">{{ filter_btn_trans }}</button>
        </div>

    </div>
</template>

<script>

    import VueSlider from 'vue-slider-component'
    import 'vue-slider-component/theme/default.css'
    export default {
        components: {
            VueSlider
        },

        props: {
            search_range: '',
            trans_title: '',
            trans_hover: '',
            filter_btn_trans: ''
        },

        data() {
            return {
                rating: '',
                value: 10,
                range_bool: false,
            }
        },

        mounted() {
            if (Number.isInteger(this.search_range)) {
                this.value = this.search_range;
                this.range_bool = true;
                this.eventHub.$emit('range-ticked', this.value);
            }
            $('.vue-slider-dot').mouseenter(function() {
                $(this).css('border','2px solid #7F9741');
                $(this).css('border-radius','30px');
                $(this).css('padding','3px');
            });
            $('.vue-slider-dot').mouseleave(function() {
                $(this).css('border','none');
                $(this).css('padding','none');
            });
            $('.vue-slider-dot-handle').mouseup(function() {
                $(this).css('background-color', '#FFD54F');

            });
            $('.vue-slider-dot-handle').mousedown(function() {
                $(this).css('background-color', '#7F9741');
            });

        },

        created: function () {
            this.eventHub.$on('reset_search', () => {
                this.search_range = '';
            });
        },

        methods: {
            EmitEvent() {

                this.range_bool = true;
                this.rangeBoolEvent();

            },

            hoverMessage(val) {
                return this.trans_hover.replace(':kms', val);
            },

            rangeBoolEvent() {
                let range = this.range_bool ? `${this.value}` : '';
                this.eventHub.$emit('range-ticked', range);
            },

            emitSearch() {
                this.eventHub.$emit('execute-search', 'full');
            }

        },

    }

</script>

<style scoped>
.value-box {
    padding: 0;
    height: 52px;
    width: 10%;
    margin-left: -90px;
}

.range-slider-value {
    display: inline-block;
    position: relative;
    width: 60px;
    color: #fff;
    font-weight: bold;
    font-size: 18px;
    line-height: 20px;
    text-align: center;
    background: #7F9741;
    margin-left: 8px;
    padding: 10px 0 0 0;
}
.range-slider-value:after {
    position: absolute;
    top: 16.5px;
    left: -10px;
    width: 0;
    height: 0;
    border-top: 9px solid transparent;
    border-right: 12px solid #7F9741;
    border-bottom: 9px solid transparent;
    content: '';
}
.p0-20{
    padding: 0 20px;
}

.vue-slider {
    padding: 7px 0;
    width: 80% !important;
    height: 4px;
    margin-left: -25px;
}

@media (min-width: 768px) and (max-width: 1024px) {

    .vue-slider {
        margin-left: 15px;
        width: 100% !important;
    }

    .value-box {
        margin-left: 13px;
    }

}

@media (min-width: 320px) and (max-width: 480px) {

    .vue-slider {
        width: 93% !important;
    }
    .value-box {
        margin-left: -61px !important;
    }

}

.vue-slider-dot-handle {
    border: 1px solid white !important;
}
.vue-slider-dot-handle:active {
    background-color: #7F9741 !important;
}
.slider-box{
    padding: 0; width: 80%; display: inline;
}
</style>
