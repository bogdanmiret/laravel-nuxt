<template>
  <div>
    <div v-if="models && models.length > 0" class="flex flex-wrap">
      <div v-for="(model, index) in models" v-bind:key="'fav_model'+index" class="w-1/2 md:w-1/3 lg:w-1/4 flex flex-col justify-between p-2 border border-dashed">

        <div class="text-center overflow-hidden fixed_fav_height" v-if="model.new_slug">
          <img class="fixed_fav_height w-auto"  :src="model.media_company_image ? model.media_company_image : '/assets/img/tmp/placeholder_restaurant.jpg'" :alt="model.name" :title="model.name">
        </div>

        <div v-else  class="text-center overflow-hidden fixed_fav_height">
           <img class="fixed_fav_height w-auto" :src="model.main_image_sql ? model.main_image_sql : '/assets/img/tmp/food_default.jpg'" :alt="model.name" :title="model.name">
        </div>

        <a class="text-center" :href="model.url">{{ model.name}} {{ model.city ? '-' + model.city.name : ''}}</a>

        <button @click="removeFavorite(model)" class="tw-btn tw-btn-alert w-full">
          {{ remove_btn_trans }}
        </button>

      </div>
    </div>

    <div v-else class="miaumiau">
      <h4>{{ no_models_trans }}</h4>
    </div>
  </div>
</template>

<script>
export default {
  props: [
    "toggle_endpoint",
    "php_models",
    "no_models_trans",
    "remove_btn_trans"
  ],

  data() {
    return {
      models: false
    };
  },

  mounted() {
    this.models = this.php_models;
  },
  methods: {
    removeFavorite(model) {

      axios
        .post(this.toggle_endpoint, {
          model_id: model.id
        })
        .then(response => {
          this.models = _.filter(this.models, function(currentObject) {
            return currentObject.id !== model.id;
          });

          iziToast.success({
            title: response.data.message
          });
        })
        .catch(function(error) {
          console.log(error);
        });
    }
  }
};
</script>

<style>
.fixed_fav_height {
  height: 130px;
}
</style>


