<template>
    <div v-if="this.length">
        <div class="background-white my-4">

            <div class="ui p20 background-white hidden-sm hidden-xs">
                <h2 ><span class="lnr lnr-menu"></span> {{categories_trans}}</h2>
                <div class="ui list ">
                    <div class="item" v-for="(category, index) in categories" v-bind:key="index">
                        <a href="javascript:void(0)" @click="LoadAllDishes(category)"> {{ category}} </a>
                    </div>
                </div>
            </div>

            <div class="background-white p20 visible-xs-block visible-sm" id="menu">
                <div class="dropup">
                    <button class="btn btn-default dropdown-toggle wide-button" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        {{ categories_trans }}
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-wide" aria-labelledby="dropdownMenu1">
                        <li v-for="(category, index) in categories" v-bind:key="index">
                            <a href="javascript:void(0)" @click="LoadAllDishes(category)">
                                {{ category }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
export default {
  props: ["categories", "categories_trans"],
    data() {
      return {
          length: '',
      }
    },

  methods: {
    LoadAllDishes(category) {
      this.eventHub.$emit("load-all-dishes", category);
    },
  },
    mounted() {
        this.length = Object.keys(this.categories).length;
    }
};
</script>
