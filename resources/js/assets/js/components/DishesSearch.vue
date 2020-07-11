<template>
    <div class="row filter">
        <div class="col-sm-12 col-md-7">
            <div class="form-group">
                <input type="text"
                       class="form-control centered-input-resp"
                       :placeholder="dish_search_placeholder"
                       v-model="selected_keyword"
                       @keyup.enter="MakeSearch()"
                >
            </div><!-- /.form-group -->
        </div><!-- /.col-* -->

        <div class="col-sm-12 col-md-5">
            <div class="form-group">
                <div class="form-group">
                    <div class="restaurant-search">
                        <HereAddressLookup
                                @address="getAddressData"
                                :placeholder="city_placeholder_trans"
                                :id="'dishCitySelect'"
                                ref="city_input"
                                :app_id="here_maps_id"
                                :app_code="here_maps_code"
                        />

<!--                        <vue-google-autocomplete-->
<!--                                id="dishes_search"-->
<!--                                classname="form-control centered-input-resp main-enter"-->
<!--                                :placeholder="city_placeholder_trans"-->
<!--                                v-on:placechanged="getAddressData"-->

<!--                                :enable-geolocation=false-->
<!--                                :types="'geocode'"-->
<!--                                ref="city_input"-->

<!--                        >-->
<!--                        </vue-google-autocomplete>-->
                    </div>

                </div><!-- /.form-group -->
            </div><!-- /.form-group -->
        </div><!-- /.col-* -->

        <hr>
        <br>

        <div class="col-sm-8">
            <div class="filter-actions">
                <a href="javascript:void(0)" @click="ResetForm()"><i class="lnr lnr-cross-circle"></i> {{ reset_trans }}</a>

            </div><!-- /.filter-actions -->
        </div><!-- /.col-* -->

        <div class="col-sm-4">
            <button id="submit-search" type="button" class="btn btn-primary pull-right redefine-dishes-center" v-text="search_button_trans" @click="MakeSearch()"></button>
        </div><!-- /.col-* -->
    </div>
</template>

<script>
    import VueGoogleAutocomplete from 'vue-google-autocomplete'
    import HereAddressLookup from '../components/HereAddressLookup.vue'

    export default {
        components: {VueGoogleAutocomplete,HereAddressLookup},

        props: {
            dish_search_placeholder : {},
            city_placeholder_trans: {},
            reset_trans : {},
            search_button_trans: {},
            build_search_route : {},
            php_search_keyword : {},
            php_search_city : {},
            here_maps_id: '',
            here_maps_code: '',


        },

        data () {
            return {
                address: '',
                placeResultData : '',
                selected_keyword: '',
                filters : []
            }
        },
        mounted() {

            this.selected_keyword = this.php_search_keyword;

            if(this.php_search_city.length) {
                this.address = JSON.parse(this.php_search_city);
                let recreated = this.address.locality;

                this.address.hasOwnProperty('route') ? recreated += ", "+ this.address.route : "";

                // this.$refs.city_input.update(recreated);
                $('#dishCitySelect').val(recreated);

            }
        },
        created() {
            this.eventHub.$on('toggle-filter', filters => {
                this.filters = filters;
            });

            this.eventHub.$on('search-event', () => {
                this.MakeSearch();
            });
        },

        methods: {
            getAddressData: function (addressData, placeResultData) {
                this.address = addressData;
                this.placeResultData = placeResultData;
            },

            ResetForm() {
                this.eventHub.$emit('reset_search');
                this.address = '';
                this.placeResultData = '';
                this.selected_keyword = "";
                // this.$refs.city_input.clear();
                $('#dishCitySelect').attr('placeholder', '');

            },
            MakeSearch() {

                let VueInstance = this;

                if (VueInstance.selected_keyword.length || VueInstance.address.locality ||  VueInstance.filters.length) {
                    axios.post(VueInstance.build_search_route, {
                        dish_search: VueInstance.selected_keyword,
                        search_city: VueInstance.address,
                        placeResultData: VueInstance.placeResultData,
                        search_filters : VueInstance.filters
                    })
                        .then(function (response) {
                            if (response.data.status === 'success') {
//										console.log(response.data.url);
                                window.location.href = response.data.url;
                            }else if (response.data.status === 'not_supported') {
                                iziToast.show({
                                    title:  response.data.message,
                                    color:  response.data.code  // blue, red, green, yellow
                                });
                            }
                        })
                        .catch(function (error) {
                            console.log(error);
                        });
                }

            }
        }

    }

</script>

<style>

</style>
