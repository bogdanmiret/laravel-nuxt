<template>
    <div class="search_block">
        <div class="">
            <div class="col-sm-6 col-xs-12 nopadding" >
                <input type="text"
                       class="form-control centered-input-resp main-enter"
                       id="company-video"
                       name="company"
                       autocomplete="off"
                       :placeholder="keyword_placeholder_trans"
                       v-model="selected_keyword"
                        @blur="CloseMenu()"
                       ref="keyword_input"
                       v-on:click="OpenMenu(); getKeywordAutoComplete()"
                       @keyup.enter="MakeSearch()"
                       @keyup="getKeywordAutoComplete()"
                >

                <div class="col-md-12 search_results resp_absolute" v-if="menu_open" style="z-index: 99;">

                    <div v-if="menu_open && show_suggestions">
                        <div class="col-md-12 separator_text">
                            <h4 class="ui horizontal divider header">
                                {{ suggested_searches_trans }}
                            </h4>
                        </div>

                        <div class="col-md-4 col-sm-6 col-xs-6 suggested_search_holder" v-for="suggested_search in suggested_searches" @click="SetKeyword(suggested_search.key, true)">
                            <div class="col-md-12 suggested_search" @click="SetKeyword(suggested_search.key, true)">
                                {{ suggested_search.key }}
                            </div>
                        </div>
                    </div>
                    <div v-else-if="keywords_loading" class="text-center">
                        <img src="/assets/img/ball-triangle.svg" class="svg-loader" style=""/>
                    </div>
                    <div v-else-if="!keywords_loading && keywords.length">
                        <div class="row single_search_result" v-for="keyword in keywords"
                             @click="SetKeyword(keyword, false); ">
                            {{ keyword }}
                        </div>
                    </div>
                    <div v-else-if="!keywords_loading && !keywords.length">
                        <h5 class="text-center no_results_message">{{ no_suggestions_trans }}</h5>
                    </div>
                </div>

            </div>

            <div class="col-sm-4 col-xs-12 nopadding" style="border-left: 1px solid #cccccc;">
                <div class="relative">
                    <HereAddressLookup
                            @address="getAddressData"
                            :placeholder="city_placeholder_trans"
                            :id="'homeSearch'"
                            ref="vuegoogle"
                            :app_id="here_maps_id"
                            :app_code="here_maps_code"
                            :app_key="here_maps_new_key"
                    />

                    <!--                    <vue-google-autocomplete-->
<!--                            ref="vuegoogle"-->
<!--                            id="google_map_home"-->
<!--                            classname="form-control centered-input-resp main-enter"-->
<!--                            :placeholder="city_placeholder_trans"-->
<!--                            v-on:placechanged="getAddressData"-->
<!--                            @keypress="test"-->
<!--                            :enable-geolocation=false-->
<!--                            :types="'geocode'"-->
<!--                    >-->
<!--                    </vue-google-autocomplete>-->
                    <div class="absolute current-location" @click="getCurrentLocation" title="Current location"></div>
                </div>

            </div>

            <div class="col-sm-2 nopadding">
                <button class="btn btn-primary" style="width: 100%"
                        type="button"
                        @click="MakeSearch"
                ><i class="lnr lnr-magnifier"></i> {{ button_trans }}</button>
            </div>
        </div>
    </div>
</template>

<style>
    .current-location {
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        width: 22px;
        height: 22px;
        background-image: url(~/images/flaticons/aim.svg);
        cursor: pointer;
    }
</style>

<script>
    import VueGoogleAutocomplete from 'vue-google-autocomplete'
    import HereAddressLookup from '../components/HereAddressLookup.vue'


    export default {
        components: {VueGoogleAutocomplete,HereAddressLookup},

        props: {
            'button_trans': {},
            'not_location_selected': {},
            'keyword_placeholder_trans': {},
            'city_placeholder_trans': {},
            'suggested_searches_php': {},
            'suggested_searches_trans': {},
            'auto_complete_route': {},
            'no_suggestions_trans': {},
            'build_search_route' : {},
            'here_maps_id': '',
            'here_maps_code': '',
            'here_maps_key': '',
            'here_maps_new_key': ''

        },
        data() {
            return {
                address: '',
                keywords: false,
                selected_keyword: '',
                suggested_searches: [],
                delay: 250,
                menu_open: false,
                show_suggestions: true,
                keywords_loading: false,
                placeResultData : '',
                vuegoogle: ''
            }
        },

        created: function () {
            this.getKeywordAutoComplete = debounce(this.getKeywordAutoComplete, this.delay);
        },
        watch: {
            selected_keyword () {
                this.show_suggestions = !this.selected_keyword.length ? true : false
            }
        },
        mounted() {
            this.suggested_searches = JSON.parse(this.suggested_searches_php);

            let VueInstance = this;
            setTimeout(function(){

            }, 5000);


        },
        methods: {
            MakeSearch() {
                let VueInstance = this;

                if (!VueInstance.address.locality && VueInstance.address.locality !== undefined) {
                    VueInstance.address['locality'] = VueInstance.placeResultData.name;
                }

                if(VueInstance.selected_keyword.length || VueInstance.address.locality)
                {

                    let search_obj = {
                        'company_search': VueInstance.selected_keyword,
                        'search_city': VueInstance.address,
                        'placeResultData' : VueInstance.placeResultData
                    };

                    Object.keys(VueInstance.suggested_searches).forEach(function(key) {
                        if(VueInstance.suggested_searches[key].key.toUpperCase() === VueInstance.selected_keyword.toUpperCase()) {
                            if(VueInstance.suggested_searches[key].url.length > 0)
                            {
                                search_obj["json_url"] = VueInstance.suggested_searches[key].url;
                            }
                        }
                    });

                    axios.post(VueInstance.build_search_route, search_obj)
                        .then(function (response) {
                            if (response.data.status === 'success') {
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

                } else {

                    iziToast.show({
                        title: this.not_location_selected,
                        color: 'red' // blue, red, green, yellow
                    });

                }
            },
            test(data, addressData) {
              console.log(data, addressData);
            },
            getAddressData: function (addressData, placeResultData) {
                this.address = addressData;
                this.placeResultData = placeResultData;
            },
            OpenMenu() {
                this.menu_open = true;
            },
            CloseMenu() {
                let VueInstance = this;
                setTimeout(function () {
                    VueInstance.menu_open = false;
                }, 100);
            },
            SetKeyword(keyword, menu_open) {
                this.menu_open = menu_open;
                this.selected_keyword = keyword;
                this.$refs.keyword_input.focus();
                this.getKeywordAutoComplete();
            },
            getKeywordAutoComplete() {
                let VueInstance = this;
                VueInstance.keywords_loading = true;

                axios.post(VueInstance.auto_complete_route, {
                    search_query: VueInstance.selected_keyword
                })
                    .then(function (response) {
                        VueInstance.keywords = response.data.companies;

                        VueInstance.keywords_loading = false;
                    })
                    .catch(function (error) {
                    });
            },
            getCurrentLocation() {
                let _this = this;
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function (position) {
                        let platform = new H.service.Platform({
                            'apikey': _this.here_maps_key
                        });
                        let geocoder = platform.getGeocodingService();
                        let latlng = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude
                        };

                        geocoder.reverseGeocode(
                            {
                                mode: "retrieveAddresses",
                                maxresults: 1,
                                prox: latlng.lat + "," + latlng.lng
                            }, data => {
                                let city = data.Response.View[0]['Result'][0]['Location']['Address']['City'];
                                _this.address = {
                                    'locality': city,
                                    'latitude': position.coords.latitude,
                                    'longitude': position.coords.longitude
                                };

                                $('#homeSearch').val(city);
                            }, error => {
                                console.error(error);
                            }
                        );

                    });
                } else {
                    alert("Geolocation is not supported by this browser.");
                }

            }
        }

    }
</script>


<style>
    .suggested_search {
        height: 25px;
        display: flex;
        align-items: center;
        background: rgba(189, 189, 189, 0.13);
        color: #696969;
        -webkit-transition-timing-function: linear; /* Safari and Chrome */
        transition-timing-function: linear;
        transition: all 0.5s ease-in-out;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.18);
    }

    .suggested_search:hover {
        background: rgba(130, 130, 130, 0.15);
        cursor: pointer;
    }

    .suggested_search_holder {
        margin-bottom: 10px;

    }

    .suggested_search_holder:last-child {
        margin-bottom: 20px;
    }

    .search_results {
        background: white;
        border-top: 1px solid rgb(204, 204, 204);

        position: absolute;
    }

    .separator_text {
        margin-bottom: 10px;
        margin-top: 10px;
    }

    .single_search_result {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        padding-left: 15px;
        padding-bottom: 5px;
        padding-top: 5px;
    }

    .single_search_result:hover {
        background: rgba(130, 130, 130, 0.15);
        cursor: pointer;
    }

    .no_results_message {
        margin-top: 20px !important;
        margin-bottom: 20px !important;
    }

    .svg-loader {
        width: 50px;
        margin-top: 20px;
        margin-bottom: 20px;
    }


    @media (max-width: 767px) {
        .nopadding {
            position:inherit!important;
        }

        .resp_absolute {
            left: 15px;
            right: 15px;
        }
    }


</style>
