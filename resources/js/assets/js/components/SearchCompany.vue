<template>
    <div>
        <div class="container">
            <div class="row text-center">
                <div class="col-md-6" style="margin-bottom: 20px">
                    <h1 class="text-white" style="margin-bottom: 13px">{{this.data_trans_create_company}}</h1>
                    <button  @click="newCompany" style="width:200px; background-color: #ED9E3C;" class="btn btn-primary btn-block">{{ this.data_trans_button }}</button>
                </div>
                <div class="col-md-6">
                    <h1 class="text-white" style="margin-bottom: 13px">{{this.data_trans_claim_company}}</h1>
                    <div class="company-block">
                        <div class="col-md-12">
                            <input type="text" :placeholder="data_trans_placeholder" class="search-restaurant-input" v-model="company_auto_complete"
                                   @keyup="getCompanyAutoComplete">
                        </div>
                        <div class="col-md-12 col-xs-12">
                            <transition name="fade">
                                <ul class="companies-results" v-if="showCompanyResults">
                                    <li class="single-company-result" v-for="company in companies" @click="SetSelectedCompany(company)">
                                        <strong>{{ company.name }} </strong> |
                                        <em>{{company.city.name  }}, {{ company.extended_address }} {{company.zipcode ? ', '+ company.zipcode : ''}}</em>
                                    </li>
                                </ul>
                            </transition>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        props: {
            autocomplete: '',
            trans_placeholder: '',
            redirect_claim: '',
            for_companies_url: '',
            trans_button: '',
            trans_create_company: '',
            trans_claim_company: '',
            auth_check: '',
            login_error: '',
        },
        data: function() {
            return {
                data_autocomplete: '',
                data_trans_placeholder: '',
                data_trans_button: '',
                company_auto_complete: '',
                showCompanyResults: false,
                data_trans_create_company: '',
                data_trans_claim_company: '',

            }
        },

        mounted: function () {
          this.data_trans_placeholder = this.trans_placeholder;
          this.data_autocomplete = this.autocomplete;
          this.data_trans_button = this.trans_button;
          this.data_trans_create_company = this.trans_create_company;
          this.data_trans_claim_company = this.trans_claim_company;
        },

        methods: {
            getCompanyAutoComplete: function () {
                var CompanyField = this;
                CompanyField.company_field_loading = true;

                if (CompanyField.company_auto_complete !== '') {
                    axios.post(this.data_autocomplete, {
                        search_query: CompanyField.company_auto_complete
                    })
                        .then(function (response) {
                            CompanyField.companies = response.data.companies;
                            CompanyField.company_field_loading = false;

                            if (response.data.hasResults) {
                                CompanyField.showCompanyResults = true;
                            }
                        })
                        .catch(function (error) {
                        });

                } else {
                    CompanyField.showCompanyResults = false;
                }

            },

            SetSelectedCompany: function (company) {

                this.showOverlay();

                let VueInstance = this;

                axios.post(VueInstance.for_companies_url, {
                    company_id: company.id,
                })
                    .then(function (response) {
                        window.location.href = VueInstance.redirect_claim;
                        this.hideOverlay();
                    })
                    .catch(function (error) {
                        console.log(error);
                        this.hideOverlay();
                    });

            },

            newCompany: function (){
                this.showOverlay();

                let VueInstance = this;

                axios.post(VueInstance.for_companies_url)
                    .then(function (response) {
                        window.location.href = VueInstance.redirect_claim;
                        this.hideOverlay();
                    })
                    .catch(function (error) {
                        console.log(error);
                        this.hideOverlay();
                    });
            },

            showOverlay: function () {
                $('body').plainOverlay('show');
            },

            hideOverlay: function () {
                $('body').plainOverlay('hide');
            },
        }
    }
</script>
<style scoped>
    .single-company-result{
        cursor: pointer;
    }
</style>
