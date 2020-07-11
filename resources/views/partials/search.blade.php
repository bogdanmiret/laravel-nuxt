<div class="header-search hidden-sm">

    <div class="search_holder_top">

        <div is="companies-search-top"></div>

    </div>
    {{--<input type="text" class="form-control" placeholder="Search for...">                   --}}

</div><!-- /.header-search -->

@push('scripts')
    <script type="text/x-template" id="companies-search-top">

        <div class="row">

            <div class="input-group">
                <div class="col-md-5 nopadding">

                    <input type="text"
                           class="form-control main-enter"
                           id="company-top"
                           name="company"
                           placeholder="{{ trans('home.search-placeholder') }}"
                           v-model="selected_company"
                           @keyup="getCompanyAutoComplete"
                           @blur="DisableShowCompaniesResults"
                           @keydown.down="CompanyDown"
                           @keydown.up="CompanyUp"
                           @keydown.space="ResetCompanyApiCall"
                           @keydown.delete="ResetCompanyApiCall"
                           @keydown.enter="MakeSearch"
                    >

                </div>

                <div class="col-md-5 nopadding top-s ">
                    <input type="text"
                           class="form-control main-enter"
                           id="location-top"
                           placeholder="{{ trans('home.city-placeholder') }}"
                           v-model="selected_location"
                           @keyup="getLocationAutoComplete"
                           @blur="DisableShowLocationResults"
                           @keydown.down="LocationDown"
                           @keydown.up="LocationUp"
                           @keydown.space="ResetLocationApiCall"
                           @keydown.delete="ResetLocationApiCall"
                           @keydown.enter="MakeSearch"

                    >
                </div>

                <div class="col-md-2 top-search-button-np">
                    <button class="btn btn-primary"
                            type="button"
                            @click="MakeSearch"
                    >{{ trans('home.search-button') }}</button>
                </div>

                <div class="row">
                    <div class="col-md-5">
                        <transition name="fade">
                            <ul v-show="showCompaniesResults" class="companies-results" style="margin-top:-13px; width: 95%;"
                            >
                                <li v-for="company in companies" class="single-company-result-custom"
                                    :id="'single-item-'+companies.indexOf(company)" @click="
                SetCompanySearchValue(company)"> <span v-html="highlight(company, selected_company)"></span>  </li>

                            </ul>
                        </transition>
                    </div>

                    <div class="col-md-5 ">
                        <transition name="fade">
                            <ul v-show="showLocationResults" class="companies-results"
                                style="width: 96%; margin-left: -15px; margin-top:-13px">
                                <li v-for="location in locations" class="single-company-result"
                                    :id="'single-location-'+locations.indexOf(location)" @click="
                SetLocationSearchValue(location)"> <span v-html="highlight(location, selected_location)"></span>  </li>

                            </ul>
                        </transition>
                    </div>
                </div>

            </div><!-- /.row -->
        </div>

    </script>

    <script>


        Vue.component('companies-search-top', {
            template: '#companies-search-top',

            props: ['lang'],
            data: function () {
                return {
                    companies: [],
                    selected_company: '{!! isset($search_keyword)  ? $search_keyword : '' !!}',
                    locations: [],
                    selected_location: '{!! isset($search_city)  ? $search_city->name : '' !!}',
                    categories: [],
                    selected_category: '{!! isset($search_category)  ? $search_category : '' !!}',
                    delay: 100,
                    showCompaniesResults: false,
                    showLocationResults: false,
                    showCategoriesResults: false,
                    company_field_loading: false,
                    location_field_loading: false,
                    category_field_loading: false,
                    current_company_position: 0,
                    current_location_position: 0,
                    current_category_position: 0,
                    make_company_api_call: 1,
                    make_location_api_call: 1,
                    make_category_api_call: 1,
                    isActive: false,
                }

            },


            created: function () {
                this.getLocationAutoComplete = debounce(this.getLocationAutoComplete, this.delay);
                this.getCompanyAutoComplete = debounce(this.getCompanyAutoComplete, this.delay);
                this.getCategoryAutoComplete = debounce(this.getCategoryAutoComplete, this.delay);
            },
//        mounted : function() {
//            this.ApplyRel();
//        },

            methods: {

//            ApplyRel : function() {
//                $('#loadMore .pagination a').attr('rel', 'nofollow');
//            },

                highlight: function (line, word) {
                    var regex = new RegExp('(' + word + ')', 'gi');
                    return line.replace(regex, "<b>$1</b>");
                },
                getCompanyAutoComplete: function () {

                    if (this.make_company_api_call == 1) {
                        var CompanyField = this;
                        CompanyField.company_field_loading = true;

                        axios.post('{{ route('trans.companies.auto.complete') }}', {
                            search_query: CompanyField.selected_company
                        })
                            .then(function (response) {

                                CompanyField.companies = response.data.companies;
                                CompanyField.current_company_position = 0;
                                CompanyField.company_field_loading = false;


                                if (response.data.hasResults) {
                                    CompanyField.showCompaniesResults = true;
                                }
                            })
                            .catch(function (error) {
                            });
                    }
                },
                getLocationAutoComplete: function () {
                    if (this.make_location_api_call == 1) {
                        var LocationField = this;

                        LocationField.location_field_loading = true;
                        axios.post('{{ route('trans.cities.auto.complete') }}', {
                            search_query: LocationField.selected_location
                        })
                            .then(function (response) {

                                LocationField.locations = response.data.locations;
                                LocationField.current_location_position = 0;
                                LocationField.location_field_loading = false;

                                if (response.data.hasResults) {
                                    LocationField.showLocationResults = true;
                                }
                            })
                            .catch(function (error) {
                            });
                    }
                },

                getCategoryAutoComplete: function () {
                    if (this.make_category_api_call == 1) {
                        var CategoryField = this;
                        CategoryField.category_field_loading = true;

                        axios.post('/company_categories_auto_complete', {
                            search_query: CategoryField.selected_category
                        })
                            .then(function (response) {
                                CategoryField.categories = response.data.categories;
                                CategoryField.current_category_position = 0;
                                CategoryField.category_field_loading = false;

                                if (response.data.hasResults) {
                                    CategoryField.showCategoriesResults = true;
                                }
                            })
                            .catch(function (error) {
                            });
                    }
                },

                SetLocationSearchValue: function (location) {
                    var LocationField = this;
                    LocationField.selected_location = location;
                    LocationField.showLocationResults = false;
                },
                SetCompanySearchValue: function (company) {
                    var CompanyField = this;
                    CompanyField.selected_company = company;
                    CompanyField.showCompaniesResults = false;
                },
                SetCategorySearchValue: function (category) {
                    var CategoryField = this;
                    CategoryField.selected_category = category;
                    CategoryField.showCategoriesResults = false;
                },
                DisableShowCompaniesResults: function () {
                    var CompanyField = this;
                    setTimeout(function () {
                        CompanyField.showCompaniesResults = false;
                    }, 100);

                },
                DisableShowLocationResults: function () {
                    var LocationField = this;
                    setTimeout(function () {
                        LocationField.showLocationResults = false;
                    }, 100);

                },
                DisableShowCategoryResults: function () {
                    var CategoryField = this;
                    setTimeout(function () {
                        CategoryField.showCategoriesResults = false;
                    }, 100);
                },
                ResetCompanyApiCall: function () {
                    this.make_company_api_call = 1;
                    $(".companies-results>li.is_in_focus").removeClass("is_in_focus");
                },
                ResetLocationApiCall: function () {
                    this.make_location_api_call = 1;
                    $(".locations-results>li.is_in_focus").removeClass("is_in_focus");
                },
                ResetCategoryApiCall: function () {
                    this.make_category_api_call = 1;
                    $(".categories-results>li.is_in_focus").removeClass("is_in_focus");
                },
                CompanyDown: function () {
                    this.make_company_api_call = 0;
                    if (this.current_company_position < this.companies.length) {
                        this.selected_company = this.companies[this.current_company_position];
                        $("#single-item-" + (this.current_company_position)).addClass('is_in_focus');
                        $("#single-item-" + (this.current_company_position - 1)).removeClass('is_in_focus');
                        if (this.current_company_position == this.companies.length) {
                            $("#single-item-" + (this.companies.length - 1)).removeClass('is_in_focus');
                            this.current_company_position = 0;
                        }
                        this.current_company_position++;
                    } else {
                        this.current_company_position = 0;
                        $("#single-item-" + (this.companies.length - 1)).removeClass('is_in_focus');
                        $("#single-item-" + (this.current_company_position)).addClass('is_in_focus');
                        this.selected_company = this.companies[this.current_company_position];
                        this.current_company_position++;
                    }
                },
                LocationDown: function () {
                    this.make_location_api_call = 0;
                    if (this.current_location_position < this.locations.length) {
                        this.selected_location = this.locations[this.current_location_position];
                        $("#single-location-" + (this.current_location_position)).addClass('is_in_focus');
                        $("#single-location-" + (this.current_location_position - 1)).removeClass('is_in_focus');
                        if (this.current_location_position == this.locations.length) {
                            $("#single-location-" + (this.locations.length - 1)).removeClass('is_in_focus');
                            this.current_location_position = 0;
                        }
                        this.current_location_position++;
                    } else {
                        this.current_location_position = 0;
                        $("#single-location-" + (this.locations.length - 1)).removeClass('is_in_focus');
                        $("#single-location-" + (this.current_location_position)).addClass('is_in_focus');
                        this.selected_location = this.locations[this.current_location_position];
                        this.current_location_position++;
                    }
                },
                CategoryDown: function () {
                    this.make_category_api_call = 0;
                    if (this.current_category_position < this.categories.length) {

                        this.selected_category = this.categories[this.current_category_position];
                        $("#single-category-" + (this.current_category_position)).addClass('is_in_focus');
                        $("#single-category-" + (this.current_category_position - 1)).removeClass('is_in_focus');
                        this.current_category_position++;
                    } else {
                        this.current_category_position = 0;
                        $("#single-category-" + (this.categories.length - 1)).removeClass('is_in_focus');
                        $("#single-category-" + (this.current_category_position)).addClass('is_in_focus');
                        this.selected_category = this.categories[this.current_category_position];
                    }
                },
                CompanyUp: function () {
                    this.make_company_api_call = 0;
                    if (this.current_company_position > 0) {
                        this.current_company_position--;
                        this.selected_company = this.companies[this.current_company_position];
                        $("#single-item-" + (this.current_company_position)).addClass('is_in_focus');
                        $("#single-item-" + (this.current_company_position + 1)).removeClass('is_in_focus');
                    } else if (this.current_company_position === -1) {
                        this.current_company_position = this.companies.length - 1;
                        this.selected_company = this.companies[this.current_company_position];
                        $("#single-item-" + (this.current_company_position)).addClass('is_in_focus');
                        $("#single-item-" + (this.current_company_position - 1)).removeClass('is_in_focus');
                    } else {
                        this.current_company_position = 0;
                        $("#single-item-" + (this.companies.length - 1)).removeClass('is_in_focus');
                    }
                },

                LocationUp: function () {
                    this.make_location_api_call = 0;
                    if (this.current_location_position > 0) {
                        this.current_location_position--;
                        this.selected_location = this.locations[this.current_location_position];
                        $("#single-location-" + (this.current_location_position)).addClass('is_in_focus');
                        $("#single-location-" + (this.current_location_position + 1)).removeClass('is_in_focus');
                    } else if (this.current_location_position === -1) {
                        this.current_location_position = this.locations.length - 1;
                        this.selected_location = this.locations[this.current_location_position];
                        $("#single-location-" + (this.current_location_position)).addClass('is_in_focus');
                        $("#single-location-" + (this.current_location_position - 1)).removeClass('is_in_focus');
                    } else {
                        this.current_location_position = 0;
                        $("#single-location-" + (this.locations.length - 1)).removeClass('is_in_focus');
                    }
                },
                CategoryUp: function () {
                    this.make_category_api_call = 0;
                    if (this.current_category_position > 0) {
                        this.current_category_position--;
                        this.selected_category = this.categories[this.current_category_position];
                        $("#single-category-" + (this.current_category_position)).addClass('is_in_focus');
                        $("#single-category-" + (this.current_category_position + 1)).removeClass('is_in_focus');
                    } else if (this.current_category_position === -1) {
                        this.current_category_position = this.categories.length - 1;
                        this.selected_category = this.categories[this.current_category_position];
                        $("#single-category-" + (this.current_category_position)).addClass('is_in_focus');
                        $("#single-category-" + (this.current_category_position - 1)).removeClass('is_in_focus');
                    } else {
                        this.current_category_position = 0;
                        $("#single-category-" + (this.categories.length - 1)).removeClass('is_in_focus');
                    }
                },
                MakeSearch: function () {

                    var Search = this;
                    var company_search = Search.selected_company;
                    var location_search = Search.selected_location;


                    if(company_search.length || location_search.length)
                    {
                        axios.post("{{route('trans.build_restaurants_search')}}", {
                            company_search : company_search,
                            location_search : location_search,

                        })
                            .then(function (response) {
                                if (response.data.status == 'success') {
//										console.log(response.data.url);
                                    window.location.href = response.data.url;
                                }
                            })
                            .catch(function (error) {
                                console.log(error);
                            });
                    }

                }
            }
        });

        new Vue({

            el: '.search_holder_top'
        });
    </script>


@endpush