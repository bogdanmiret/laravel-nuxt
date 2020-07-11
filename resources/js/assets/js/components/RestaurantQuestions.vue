<template>
    <div>
        <h2 class="single-dish-padding-top mb-4" style="margin-bottom: 15px;" :id="questions.length"><span class="lnr lnr-list"></span> {{ trans_restaurant_questions_header }}</h2>
        <div class="background-white p20 my-4" v-if="questions.length">
                <div class="row questions-wrapper">

                    <transition name="fade" v-for="(item, index) in questions" :key="index">
                        <div class="col-md-12 col-sm-6 col-xs-12">
                            <div class="single-question">

                                <div>
                                    <div class="question-sentence text-center">
                                        <b>{{ item.question }}</b>
                                    </div>
                                    <div class="row question_holder" style="">

                                        <button class="ui inverted green button" @click="Vote(item, '1')">{{ trans_vote_yes }}
                                        </button>

                                        <button class="ui inverted orange button" @click="Vote(item, '0')">{{ trans_vote_maybe }}
                                        </button>

                                        <button class="ui inverted red button" @click="Vote(item, '-1')">{{ trans_vote_no }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </transition>

                </div>
        </div>
    </div>
</template>

<script>
export default {
  props: {
    company_php: {},
    trans_restaurant_questions_header: {},
    trans_vote_yes: {},
    trans_vote_no: {},
    trans_vote_maybe: {}
  },

  data() {
    return {
      questions: [],
      delay: 1500,
      company_id: this.company_php
    };
  },
  watch: {},
  mounted() {
      document.onreadystatechange = () => {
          if (document.readyState == "complete") {
              this.getQuestions();
          }
      }
  },
  methods: {
    getQuestions: function() {
        let VueInstance = this;
        axios
            .post("/get_features_questions", {
                company_id: this.company_id
            })
            .then(function (response) {
                VueInstance.questions = response.data.questions;

                // After the questions are loaded, the sidebar height will have a static height. To fix it we set the height with auto after the questions are loaded
                $('#sidebar-restaurant').css('height', 'auto');
            })
            .catch(function (error) {
                console.log(error);
            });
    },
    Vote: function(item, vote) {
      let VueInstance = this;

      axios
        .post("/feedback/vote", {
          company_id: this.company_id,
          feature_slug: item.slug,
          value: vote
        })
        .then(function(response) {
          VueInstance.removeByAttr(VueInstance.questions, "slug", item.slug);
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    removeByAttr: function(arr, attr, value) {
      let i = arr.length;
      while (i--) {
        if (
          arr[i] &&
          arr[i].hasOwnProperty(attr) &&
          (arguments.length > 2 && arr[i][attr] === value)
        ) {
          arr.splice(i, 1);
        }
      }
      return arr;
    }
  }
};
</script>

<style>
.question_holder {
  display: flex;
  justify-content: space-around;
}
</style>
