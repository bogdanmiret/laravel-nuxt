<template>
    <div class="row" id="all_feedback">
        <div class="col-md-12">
            <div class="review" style="min-height: 100px; margin-bottom: 15px;" v-for="(review,index) in dataSet.data" v-bind:key="index">
                <div class="review-image no-overflow flex-wrap-v-center">
                    <img :src="review.user.image" alt="" class="review_image">
                    <span class="user_name text-center">{{ review.user.full_name}}</span>
                    <star-rating v-if="review.rating" active-color="#739745" :read-only="true"
                                 :rating="review.rating" :show-rating="false" :inline="true"
                                 :star-size="15"></star-rating>
                </div>

                <div class="review-inner">
                    <div class="review-content-wrapper">
                        <div class="review-content">
                            <div class="flex flex-wrap justify-between mb-4" v-if="review.title">

                                <strong v-if="review.company">
                                    <a :href="review.company.url">{{ review.company.name }}, {{ review.company.city.name
                                        }} </a>
                                </strong>

                            </div>

                                <read-more :more-str="open_read_more" :text="review.comment !== null ? review.comment : review.old_comment" link="#"
                                           :less-str="close_read_more" :max-chars="250"></read-more>

                                <p class="mt-3 pt-3" :id="'original-'+index" style="border-top: 1px solid #eeeeee" hidden></p>

                            <hr>

                            <div class="float-right">
                                <a class="open-report" v-if="auth" @click="show(review)">Report</a>
                            </div>

<!--                            <a class="open-report" v-if="review.locale !== get_locale && review.onlineTrans !== 1" @click="translateReview(review, index)">Translate</a>-->
<!--                            <span v-if="review.translated === 2">This comment was translated automatically.</span> <br>-->
<!--                            <a class="open-report" v-if="review.locale === get_locale && review.translated !== 3" :id="'translated-'+index" @click="showOriginal(review, index)">Show original</a>-->

                            <div class="row" v-if="review.custom_properties !== null">

                                <div class="col-md-6"
                                     v-for="(rating, rating_name) in JSON.parse(review.custom_properties)"
                                     v-bind:key="rating_name">
                                    <span v-text="rating_translations[rating_name]"></span>
                                    <br>
                                    <star-rating active-color="#808080" :read-only="true" :rating="parseFloat(rating)"
                                                 :show-rating="false" :inline="true" :star-size="12"></star-rating>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12 text-center">
            <paginator :dataSet="dataSet" @changed="fetch"></paginator>
        </div>

        <modal :height="'auto'" name="report-modal">
            <img class="close-report" @click="hide" :src="close_button"/>
            <div class="text-center" style="padding: 40px 100px;">
                <h3 class="text-bold">{{this.report_title}}</h3>
                <br>
                <p>
                    {{this.report_body}}
                </p>

                    <div class="form-group">
                        <textarea rows="8" v-model="body" name="report" class="form-control"></textarea>
                    </div>
                    <button @click="sendReport(review.id, restaurant_id)" class="btn btn-primary">{{this.report_button}}</button>
            </div>
        </modal>
    </div>
</template>

<script>

    export default {

        props: [
            "fetch_endpoint",
            "rating_translations",
            "open_read_more",
            "close_read_more",
            "close_button",
            "auth",
            "report_route",
            "restaurant_id",
            "owner_restaurant",
            "report_message",
            "report_title",
            "report_body",
            "report_button",
            "report_confirmation",
            "translate_route",
            "original_route",
            "get_locale"
        ],

        data() {
            return {
                dataSet: false,
                showModal: false,
                review: {},
                body: '',
            };
        },
        created() {
            this.fetch();
        },

        methods: {
            show (review) {
                if (this.owner_restaurant) {
                    this.review = review;
                    this.$modal.show('report-modal');
                } else {
                    iziToast.show({
                        title: this.report_message,
                        color: 'red'
                    });
                }
            },

            hide () {
                this.review = {};
                this.body = '';
                this.$modal.hide('report-modal');
            },

            sendReport(review_id, restaurant_id) {
                let Vue = this;
                Vue.showOverlay();

                    axios.post(Vue.report_route, {
                        'review_id': review_id,
                        'restaurant_id': restaurant_id,
                        'body': Vue.body,
                    })
                        .then(function (response){

                            if(response.data === 'success') {
                                iziToast.show({
                                    title: Vue.report_confirmation,
                                    color: 'green'
                                })
                            }

                        });

                Vue.hideOverlay();
                Vue.hide();

            },

            fetch(page) {
                axios.get(this.url(page)).then(this.refresh);

                if (page && $("#all_feedback")) {
                    $("html, body").animate(
                        {
                            scrollTop: $("#all_feedback").offset().top
                        },
                        500
                    );
                    $('#original-0, #original-1, #original-2').hide();
                    $('#translated-0, #translated-1, #translated-2').show();
                }
            },

            refresh({data}) {
                this.dataSet = data;
            },

            url(page) {
                if (!page) {
                    let query = location.search.match(/page=(\d+)/);

                    page = query ? query[1] : 1;
                }

                return this.fetch_endpoint + "?page=" + page;
            },

            getReviewComment(review) {
                if (review.comment && review.comment.length > 250) {
                    return review.comment.substring(0, 250) + "...";
                } else {
                    return review.comment;
                }
            },

            showOverlay: function () {
                $('body').plainOverlay('show');
            },

            hideOverlay: function () {
                $('body').plainOverlay('hide');
            },

            translateReview(review, index) {

                let Vue = this;

                axios.post(Vue.translate_route, {
                    feedback: review
                })
                    .then(function(data) {
                        Vue.dataSet.data[index].comment = data.data.feedback.comment;
                        Vue.dataSet.data[index].onlineTrans = 1;
                    })
                .catch(function(data) {
                    iziToast.show({
                        title: "Something went wrong.",
                        color: 'red'  // blue, red, green, yellow
                    });
                });

            },

            showOriginal(review, index) {

                let Vue = this;

                axios.post(Vue.original_route, {
                    feedback: review
                })
                    .then(function(data) {
                        let p = $('#original-'+index);
                        p.text(data.data.feedback.comment);
                        p.show();
                        Vue.dataSet.data[index].onlineShow = 1;
                        $('#translated-'+index).hide();
                    })
                    .catch(function(data) {
                        iziToast.show({
                            title: "Something went wrong.",
                            color: 'red'  // blue, red, green, yellow
                        });
                    });

            }

        }
    };
</script>

<style>
    .no-overflow {
        overflow: inherit !important;
    }

    .review-image img {
        width: 60px !important;
        height: 60px !important;
    }

    .user_name {
        font-weight: 600;
        /* line-height: 0.529; */
    }

    .review_image {
        margin-bottom: 10px;
    }

    .review-content {
        padding: 0!important;
    }
    .close-report {
        position: absolute;
        right: 10px;
        top: 10px;
        cursor: pointer;
    }
    .open-report {
        color: #8B8B8B;
        cursor: pointer;
    }
    .open-report:hover {
        color: #7f9741;
    }
</style>

