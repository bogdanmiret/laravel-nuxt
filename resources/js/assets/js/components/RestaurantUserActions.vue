<template>
    <div class="flex-grow margin-1">
        <button id="edit-restaurant" class="btn btn-primary btn-book " @click="OpenModal"><i class="lnr lnr-pencil"
                                                                        aria-hidden="true"></i> {{ edit_button_trans }}
        </button>

        <sweet-modal ref="actions_modal" overlay-theme="dark" :title="modal_title_trans">
            <div class="col-md-12 w-100 text-center">
                {{ owner_text_trans }} <a href="javascript:void(0)" @click="company_session(company_id)" class="tw-btn">{{
                claim_trans}}</a>
            </div>
            <div class="clearfix"></div>
            <hr>

            <div class="col-md-12 flex-column-start">
                <div v-for="(attribute,index) in attributes" class="w-100" v-bind:key="'key_'+index">
                    <input type="checkbox" :id="'checkbox-'+index" :value="attribute.slug" v-model="selected_checkboxes"
                           style="display:none">
                    <label :for="'checkbox-'+index">{{ attribute.name }}</label>
                    <transition name="fade">
                        <input type="text" :placeholder="suggest_trans"
                               v-if="selected_checkboxes.includes(attribute.slug) && attribute.allows_edit"
                               class="form-control m-b-10"
                               :class="{ 'google_restaurant_address': attribute.slug === 'extended_address' }"
                               v-model="attribute.suggest"
                        >
                    </transition>
                </div>

                <div class="row text-center w-100" v-if="auth_check !== '1' && selected_checkboxes.length">
                    <br>
                    <input type="text" :placeholder="email_placeholder"
                           class="form-control m-b-10"
                           :class="valid_email ? 'has-success' : 'has-warning' "
                           v-model="email"
                    >
                </div>

                <div class="row text-center w-100">
                    <button class="huge ui speisekarte button" @click="send_report()"
                            :disabled="!selected_checkboxes.length || !valid_email">{{ submit_trans }}
                    </button>
                </div>
            </div>


        </sweet-modal>
    </div>
</template>

<script>

    import {SweetModal, SweetModalTab} from 'sweet-modal-vue'

    export default {
        components: {
            SweetModal,
            SweetModalTab
        },
        props: [
            'owner_text_trans',
            'claim_trans',
            'suggest_trans',
            'modal_title_trans',
            'submit_trans',
            'fetch_questions_url',
            'company_id',
            'for_companies_url',
            'redirect_claim',
            'store_suggestion_route',
            'report_received',
            'edit_button_trans',
            'auth_check',
            'email_placeholder',
        ],

        data() {
            return {
                selected_checkboxes: [],
                attributes: [],
                email: '',
                valid_email: true
            }
        },
        created() {

        },
        mounted() {
            let self = this;

            let options = {
                details: ".restaurant_details_wrapper_updated"
            };

            $(document).on('change', '#checkbox-3', function () {
                if ($('#checkbox-3').is(":checked")) {
                    setTimeout(function () {
                        $(".google_restaurant_address").geocomplete(options).bind('geocode:result', function (event, result) {
                            self.attributes[3].suggest = result.formatted_address
                        });
                    }, 300)
                } else {
                    $(".google_restaurant_address").geocomplete('destroy');
                }
            })
        },
        watch: {
            email() {
                this.valid_email = this.validateEmail(this.email)
            }
        },
        methods: {
            validateEmail(string) {
                let re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                if (string.length === 0) {
                    return true;
                }
                return re.test(string);
            },
            OpenModal() {
                this.$nextTick(function () {
                    let VueInstance = this;
                    if (VueInstance.attributes.length === 0) {
                        axios.get(VueInstance.fetch_questions_url)
                            .then(function (response) {
                                response.data.forEach(function (question) {
                                    VueInstance.attributes.push(question);
                                });
                            })
                            .catch(function (error) {
                                console.log(error);
                            });

                        VueInstance.$refs.actions_modal.open()
                    } else {
                        VueInstance.$refs.actions_modal.open()
                    }
                });
            },

            company_session() {
                    let VueInstance = this;

                    axios.post(VueInstance.for_companies_url, {
                        company_id: VueInstance.company_id,
                    })
                        .then(function (response) {
                            window.location.href = VueInstance.redirect_claim;
                        })
                        .catch(function (error) {
                            console.log(error);
                        });
            },

            send_report() {
                let VueInstance = this;

                if (VueInstance.selected_checkboxes.length === 0) {
                    VueInstance.$refs.actions_modal.close();
                    iziToast.show({
                        title: "Mhm. Try again.",
                        color: 'red'  // blue, red, green, yellow
                    });
                    return;
                }

                setTimeout(function () {
                    $('.sweet-modal').plainOverlay('show');
                }, 100);

                axios.post(VueInstance.store_suggestion_route, {
                    company_id: VueInstance.company_id,
                    selected_checkboxes: VueInstance.selected_checkboxes,
                    attributes_array: VueInstance.attributes,
                    email: VueInstance.email
                })
                    .then(function (response) {
                        $('.sweet-modal').plainOverlay('hide');
                        VueInstance.selected_checkboxes = [];
                        VueInstance.$refs.actions_modal.close();
                        iziToast.show({
                            title: VueInstance.report_received,
                            color: 'green'  // blue, red, green, yellow
                        });

                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            }
        }
    }
</script>
<style>
    .flex-column-start {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }

    .sweet-modal .sweet-title > h2 {
        margin-top: 1em !important;
        border-bottom: none;

    }

    .sweet-modal .sweet-box-actions .sweet-action-close:hover {
        background: #739745 !important;
    }

    .m-b-10 {
        margin-bottom: 10px;
    }

    .w-100 {
        width: 100%;
    }

    .fade-enter-active, .fade-leave-active {
        transition: opacity 0.25s ease-out;
    }

    .fade-enter, .fade-leave-to {
        opacity: 0;
    }

    .ui.speisekarte.button {
        background-color: #739745;
        color: #FFFFFF;
        text-shadow: none;
        background-image: none;
        -webkit-box-shadow: 0px 0em 0px 0px rgba(34, 36, 38, 0.15) inset;
        box-shadow: 0px 0em 0px 0px rgba(34, 36, 38, 0.15) inset;
    }

    .ui.speisekarte.button:hover {
        background-color: #3a4f26;
    }

    .pac-container {
        z-index: 99999;
    }
</style>
