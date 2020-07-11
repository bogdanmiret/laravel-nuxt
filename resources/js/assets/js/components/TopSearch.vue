<template>
    <div class="search_block">
        <div class="">
            <div class="col-sm-7 col-xs-11 nopadding">
                <input type="text"
                       class="form-control main-enter"
                       id="company-top"
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

                        <div class="col-md-6 suggested_search_holder" v-for="suggested_search in suggested_searches"
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

            <div class="col-sm-4 col-xs-11 nopadding">
                <HereAddressLookup
                        @address="getAddressData"
                        :placeholder="formatted_placeholder.length ? formatted_placeholder : city_placeholder_trans"
                        :id="'topSearch'"
                        ref="city_input"
                        :app_id="here_maps_id"
                        :app_code="here_maps_code"
                        :app_key="here_maps_key"
                />
            </div>

            <div class="col-sm-1 nopadding">
                <button class="btn btn-primary search_btn_top"
                        type="button"
                        @click="MakeSearch">
                    <i class="lnr lnr-magnifier" style="margin-right: 1px;"></i>
                </button>
            </div>
        </div>
    </div>
</template>


<script>
    import VueGoogleAutocomplete from 'vue-google-autocomplete'
    import HereAddressLookup from '../components/HereAddressLookup.vue'


    export default {
        components: {VueGoogleAutocomplete,HereAddressLookup},

        props: [
            'button_trans',
            'not_location_selected',
            'keyword_placeholder_trans',
            'city_placeholder_trans',
            'suggested_searches_php',
            'suggested_searches_trans',
            'auto_complete_route',
            'no_suggestions_trans',
            'build_search_route',
            'trans_restaurant_reset',
            'trans_map',
            'php_search_keyword',
            'php_search_city',
            'city_session',
            'here_maps_id',
            'here_maps_code',
            'here_maps_key'
    ],

        data()
        {
            return {
                address: '',
                keywords: false,
                selected_keyword: '',
                suggested_searches: [],
                delay: 100,
                menu_open: false,
                show_suggestions: true,
                keywords_loading: false,
                placeResultData: '',
                formatted_placeholder: '',
                formatted_value: '',
            }
        }
        ,

        created: function () {
            this.getKeywordAutoComplete = debounce(this.getKeywordAutoComplete, this.delay);

            this.eventHub.$on('reset_search', () => {
                this.selected_keyword = "";
                this.address = '';
                this.$refs.city_input_top.clear();
            });
        }
        ,
        watch: {
            selected_keyword()
            {
                this.show_suggestions = !this.selected_keyword.length ? true : false
            }
        }
        ,
        mounted()
        {
            this.suggested_searches = JSON.parse(this.suggested_searches_php);
            this.selected_keyword = this.php_search_keyword;

            if (this.php_search_city.length) {
                this.format_placeholder(this.php_search_city ? JSON.parse(this.php_search_city) : '');
            }

            if (this.city_session.length) {
                this.format_placeholder(this.city_session ? JSON.parse(this.city_session) : '');
            }

        }
        ,
        methods: {
            MakeSearch()
            {
                let VueInstance = this;


                if(typeof VueInstance.php_search_city !== null && VueInstance.php_search_city && typeof VueInstance.address === 'string')
                {
                    VueInstance.address =  JSON.parse(VueInstance.php_search_city);
                }

                if (VueInstance.city_session.length && !VueInstance.address.locality) {
                    VueInstance.address = JSON.parse(VueInstance.city_session);
                }

                if (VueInstance.selected_keyword.length || VueInstance.address) {
                    let search_obj = {
                        'company_search': VueInstance.selected_keyword,
                        'search_city': VueInstance.address ? VueInstance.address : '',
                        // 'placeResultData': VueInstance.placeResultData
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
                            } else {
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
            }
            ,
            getAddressData: function (addressData, placeResultData) {
                this.address = addressData;
                this.placeResultData = placeResultData;
            }
            ,
            format_placeholder (address_object)
            {

                if (address_object.locality.length) {
                    let string = '';
                    address_object.locality ? string += address_object.locality : null;

                    address_object.route ? string += ", " + address_object.route : null;


                    this.formatted_placeholder = string;

//                    $('#map').val( this.formatted_placeholder);


                } else {
                    return '';
                }
            },
            OpenMenu()
            {
                this.menu_open = true;
            }
            ,
            CloseMenu()
            {
                let VueInstance = this;
                setTimeout(function () {
                    VueInstance.menu_open = false;
                }, 100);
            }
            ,
            SetKeyword(keyword, menu_open)
            {
                this.menu_open = menu_open;
                this.selected_keyword = keyword;
                this.$refs.keyword_input.focus();
                this.getKeywordAutoComplete();
            }
            ,
            getKeywordAutoComplete()
            {
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
            }
        }

    }
</script>


<style>

    .search_btn_top {
        width: 100%;
        line-height: 1.58;
    }

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
        z-index: 90;
        position: absolute !important;
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


</style>
