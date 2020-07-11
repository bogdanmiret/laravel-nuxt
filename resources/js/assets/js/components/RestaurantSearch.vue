<template>
    <div class="search_block" id="searchRow">
        <div class="row filter" id="searchRestaurants">
            <div class="col-sm-6 col-xs-12 nopadding">
                <input type="text"
                       class="form-control main-enter"
                       id="company-video"
                       name="company"
                       :placeholder="keyword_placeholder_trans"
                       v-model="selected_keyword"
                       @blur="CloseMenu()"
                       ref="keyword_input"
                       v-on:click="OpenMenu(); getKeywordAutoComplete()"
                       @keyup.enter="MakeSearch()"
                       @keyup="getKeywordAutoComplete()"

                >

                <div class="col-md-12 search_results p-l-r-0" v-if="menu_open">

                    <div v-if="menu_open && show_suggestions">
                        <div class="col-md-12 separator_text">
                            <h4 class="ui horizontal divider header">
                                {{ suggested_searches_trans }}
                            </h4>
                        </div>

                        <div class="col-md-4 col-xs-6 suggested_search_holder"
                             v-for="suggested_search in suggested_searches"
                             @click="SetKeyword(suggested_search.key, true)">
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

            <div class="col-sm-6 col-xs-12 nopadding">

                <HereAddressLookup
                        @address="getAddressData"
                        :placeholder="formatted_placeholder.length ? formatted_placeholder : city_placeholder_trans"
                        :id="'citySelect'"
                        ref="city_input"
                        :app_id="here_maps_id"
                        :app_code="here_maps_code"
                        :app_key="here_maps_api_key"
                        value=""
                />

            </div>

            <br>
            <br>
            <hr>
            <div class="row">
                <div class="col-sm-12">
                    <div class="filter-actions">
                        <div class="col-lg-5 col-md-6 col-sm-12 col-xs-12 flex-wrap p-0 align-items-center">
<!--                            <modal-trigger :modal_name="'restaurants-listing-map'" :holder_classes="'cursor-pointer '">-->
<!--                                <a href="javascript:void(0)" id="mobile_show_map">-->
<!--                                    <i class="lnr lnr-earth"></i> {{ trans_map }}-->
<!--                                </a>-->
<!--                            </modal-trigger>-->

                            <button class="btn btn-white mr-5 media-100 d-none" style="height: 37px" id="filtersToggle">{{filters}} <i class="fa fa-sort-down ml-3 mr-0"></i></button>
                            <a href="javascript:void(0)"  class="mb-0" @click="ClearFields()"><i
                                    class="lnr lnr-cross-circle"></i> {{ trans_restaurant_reset }}</a>

                            <a href="#filters" class="visible-xs visible-sm"><i class="fa fa-filter"></i>
                                {{ filters_trans }}</a>
                        </div>
                        <div class="pull-right">
                            <div class="toggle-switch mr-5  mt-1" id="div-show-map" style="display: inline-flex; justify-content: center; align-items: center;">
                                <lable for="show-map" class="mr-3">{{show_map}}</lable>
                                <input type="checkbox" class="toggleCheckbox" id="show-map" name="showmap" /><label class="toggleLabel pull-right mb-0" for="show-map">Show Map</label>
                            </div>


                            <button type="button" @click="MakeSearch" class="btn btn-primary media-100">
                                <i class="lnr lnr-magnifier" style="color: #fff;"></i> {{ button_trans }}
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import HereAddressLookup from '../components/HereAddressLookup.vue'


    export default {
        components: {HereAddressLookup},

        props: {
            'button_trans': {},
            'not_location_selected': {},
            'keyword_placeholder_trans': {},
            'city_placeholder_trans': {},
            'suggested_searches_php': {},
            'suggested_searches_trans': {},
            'auto_complete_route': {},
            'no_suggestions_trans': {},
            'build_search_route': {},
            'trans_restaurant_reset': {},
            'trans_map': {},
            'php_search_keyword': {},
            'php_search_city': {},
            'filters_trans': {},
            'city_session_php': {},
            'city_session_route': {},
            'here_maps_id': '',
            'here_maps_code': '',
            'here_maps_api_key': '',
            'range_error_location': '',
            'show_map': '',
            'filters': '',
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
                placeResultData: '',
                search_terms: [],
                search_amenities: [],
                formatted_placeholder: '',
                formatted_value: '',
                city_session: '',
                search_rating: '',
                search_range: ''
            }
        },

        created: function () {
            this.getKeywordAutoComplete = debounce(this.getKeywordAutoComplete, this.delay);

            this.eventHub.$on('rating-ticked', rating => {
                if (rating.length) {
                    this.search_rating = rating;
                }
            });

            this.eventHub.$on('range-ticked', range => {
                this.search_range = range;
            });

            this.eventHub.$on('term-ticked', terms => {
                this.search_terms = terms;
            });

            this.eventHub.$on('amenity-ticked', amenities => {
                this.search_amenities = amenities;
            });

            this.eventHub.$on('execute-search', form_type => {
                this.MakeSearch(form_type);
            });

        },
        watch: {
            selected_keyword() {
                this.show_suggestions = !this.selected_keyword.length ? true : false
            }
        },
        mounted() {
            this.suggested_searches = JSON.parse(this.suggested_searches_php);
            this.selected_keyword = this.php_search_keyword;
            this.city_session = this.city_session_php;

            if (this.php_search_city.length) {
                this.format_placeholder(this.php_search_city ? JSON.parse(this.php_search_city) : '');
            }
            if (this.city_session.length) {
                this.format_placeholder(this.city_session ? JSON.parse(this.city_session) : '');
            }
        },
        methods: {
            MakeSearch(form_type) {
                let VueInstance = this;

                if (typeof VueInstance.php_search_city !== null && VueInstance.php_search_city && typeof VueInstance.address === 'string') {
                    VueInstance.address = JSON.parse(VueInstance.php_search_city);
                }

                if (VueInstance.city_session.length && VueInstance.city_session && !VueInstance.address.locality) {
                    VueInstance.address = JSON.parse(VueInstance.city_session);
                }

                if (VueInstance.selected_keyword.length
                    || VueInstance.address.locality
                    || VueInstance.search_terms.length
                    || VueInstance.search_rating.length
                    || VueInstance.search_amenities.length
                    || (VueInstance.search_range.length && VueInstance.address.locality)) {

                    if (VueInstance.search_range.length && typeof VueInstance.address.locality === "undefined") {
                        return iziToast.show({
                            title: this.range_error_location,
                            color: 'red' // blue, red, green, yellow
                        });
                    }

                    let search_obj = {
                        'company_search': VueInstance.selected_keyword,
                        'search_city': VueInstance.address,
                        'search_terms': VueInstance.search_terms,
                        'search_amenities': VueInstance.search_amenities,
                        'search_rating': VueInstance.search_rating,
                        'search_range': VueInstance.search_range,
                    };

                    Object.keys(VueInstance.suggested_searches).forEach(function (key) {
                        if (VueInstance.suggested_searches[key].key.toUpperCase() === VueInstance.selected_keyword.toUpperCase()) {
                            if (VueInstance.suggested_searches[key].url.length > 0) {
                                search_obj["json_url"] = VueInstance.suggested_searches[key].url;
                            }
                        }
                    });

                    axios.post(VueInstance.build_search_route, search_obj)
                        .then(function (response) {
                            if (response.data.status === 'success') {
                                window.location.href = response.data.url;
                            } else if (response.data.status === 'not_supported') {
                                iziToast.show({
                                    title: response.data.message,
                                    color: response.data.code  // blue, red, green, yellow
                                });
                            }
                        })
                        .catch(function (error) {
                            console.log(error);
                        });
                } else {

                    if (form_type != 'amenities' && form_type != 'terms') {
                        iziToast.show({
                            title: this.not_location_selected,
                            color: 'red' // blue, red, green, yellow
                        });
                    }
                }
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
            format_placeholder(address_object) {

                if (address_object.locality.length) {
                    let string = '';
                    address_object.locality ? string += address_object.locality : null;
                    address_object.route ? string += ", " + address_object.route : null;
                    this.formatted_placeholder = string;
                    this.formatted_value = string;
                } else {
                    return '';
                }
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

            ClearFields: function () {
                let VueInstance = this;

                VueInstance.address = [];
                VueInstance.search_terms = [];
                VueInstance.search_amenities = [];
                VueInstance.selected_keyword = '';
                $('#citySelect').prop('value', '');
                VueInstance.formatted_placeholder = VueInstance.city_placeholder_trans;
                VueInstance.city_session = '';
                VueInstance.search_rating = [];
                VueInstance.search_range = '';
                VueInstance.city_input = '';

                $('.stars-3, .stars-4, .stars-5').removeClass('active');

                axios.post(VueInstance.city_session_route)
                    .then(function (response) {
                        this.eventHub.$emit('reset_search');
                    })
                    .catch(function (error){
                });
            },
        }
    }
</script>

<style>
    .suggested_search {
        height: 40px;
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
        z-index: 99;
        left: 0 !important;
        right: 0 !important;
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
        margin-left: 0;
        margin-right: 0;
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

    @media (max-width: 1740px) {
        #div-show-map {
            display: none !important;
        }
    }

</style>
