<template>
    <div class="widget" id="amenities">
        <h2 class="widgettitle" style="margin-top:20px">{{ amenities_translate }}</h2>
        <div class="background-white p20" id="amenitiesList">
            <div class="form-group" id="amenitiesGroup">
                <div v-for="(amenity,index) in amenities" class="checkbox amenitiesCheckBox">
                    <input type="checkbox" :id="'amenity-'+index" @change="EmitEvent" :name="amenity.key" :value="amenity.slug"
                           v-model="selected_amenities" style="display:none"><label :for="'amenity-'+index">{{ amenity.key }}</label>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            php_amenities: {},
            php_selected_amenities: {},
            amenities_translate: {},
            filter_btn_trans : {}

        },

        data() {
            return {
                amenities: '',
                selected_amenities: ''
            }
        },
        created() {
            this.eventHub.$on('reset_search', () => {
                this.selected_amenities = [];
            });
        },
        mounted() {
            this.amenities = JSON.parse(this.php_amenities);

            this.selected_amenities = JSON.parse(this.php_selected_amenities);

            let instance = this;
            setTimeout(function () {
                instance.EmitEvent();
            }, 200);
        },
        methods: {
            EmitEvent()
            {
                this.eventHub.$emit('amenity-ticked', this.selected_amenities)
            }
        }
    }
</script>

<style>

</style>
