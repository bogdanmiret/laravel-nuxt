<template>
    <div class="background-white p20">
        <div>
            <div :id="'taboola-before-menu'"></div>
            {{taboolaAds('Before Menu ', 'taboola-before-menu')}}
        </div>
        <div v-for="(category, index) in dishes" :key="'category_'+index" class="mb-6">
            <h3 class="my-6 content_header_h3"><span :id="slugify(category.title)"></span> {{ category.title }} </h3>
            <div v-for="(dish, index) in category.dishes">
            <div class="row restaurant-dishes-holder md-flex-col" :key="'dish_'+index" style="display: flex; align-items: center;">
                <div class="col-xs-12 col-sm-2 restaurant-food-img">
                    <a :href="dish.url" :style="`background-image: url('${dish.restaurant_image}'); width: 150px; height: 65px; background-size: contain; background-repeat: no-repeat; background-position: 50% 50%;`">
                    </a>
                </div>

                <div class="col-xs-12 col-sm-8 ">
                    <div class="col-md-10 flex justify-center py-3 md:justify-start" >
                        <a :href="dish.url" style="font-size: 15px;"> <b>{{ dish.name }}</b></a>
                    </div>
                    <div class="col-md-2 flex justify-center md:justify-start">
                        <star-rating v-if="dish.rating" active-color="#739745" :read-only="true" :rating="dish.rating" :show-rating="false" :inline="true" :star-size="15"></star-rating>
                    </div>
                    
                    <div class="col-md-12 flex justify-center md:justify-start">
                      {{ dish.details }}
                    </div>
                </div>

                <div class="col-sm-2 col-xs-12 flex flex-col md:items-end items-center">

                    <a v-if="dish.comment_number > 0" :href="dish.url" style="    font-size: 1.2em;">
                        <i class="fa fa-commenting-o " aria-hidden="true"></i> {{ dish.comment_number }}
                    </a>

                    <a v-else :href="dish.url+'#reviews'" style="  font-size: 1.2em;" class="js_hover">
                        <i class="fa fa-commenting-o " aria-hidden="true"></i> {{ comment_action_trans }}
                    </a>

                    <h6 v-if="dish.converted_price && typeof dish.converted_price !== 'object'" style="color:#739745">
                        {{ dish.converted_price }} </h6>

                </div>
            </div>

                <div v-if="(index + 1) > 1 && (index + 1) % 5 === 0">
                    <div :id="'taboola-fifth-item-'+convertAdsId(dish.name)"></div>
                    {{taboolaAds('Fifth Item '+dish.name, 'taboola-fifth-item-'+dish.name)}}
                </div>


                    <get-advertising :api_endpoint="mobile_ads20"
                                     :type="'mobile'"
                                     class="ewoiplnx-weknm mobile-dishes-listing-from-restaurant-page ewoiplnx-mobile-dishes-listing-from-restaurant-page hidden-lg hidden-md"
                                     style="margin-bottom: 30px;"
                                     v-if="check_ads && checkAdsPosition(index/19) && index !== 0"
                    ></get-advertising>
                    <get-advertising :api_endpoint="desktop_ads20"
                                     :type="'dekstop'"
                                     class="ewoiplnx-weknm desktop-dishes-listing-from-restaurant-page ewoiplnx-desktop-dishes-listing-from-restaurant-page hidden-sm hidden-xs"
                                     style="margin-bottom: 30px;"
                                     v-if="check_ads && checkAdsPosition(index/19) && index !== 0"
                    ></get-advertising>
            </div>
            <get-advertising :api_endpoint="mobile_ads"
                             :type="'mobile'"
                             class="ewoiplnx-weknm mobile-mobile-restaurant-dishes-feed-listing ewoiplnx-mobile-restaurant-dishes-feed-listing hidden-lg hidden-md"
                             style="margin-bottom: 30px;"
                             v-if="check_ads && checkSize(category.dishes) > 9"
            ></get-advertising>
            <get-advertising :api_endpoint="desktop_ads"
                             :type="'dekstop'"
                             class="ewoiplnx-weknm desktop-dish-listing-from-restaurant ewoiplnx-desktop-dish-listing-from-restaurant hidden-sm hidden-xs"
                             style="margin-bottom: 30px;"
                             v-if="check_ads && checkSize(category.dishes) > 9"
            ></get-advertising>
        </div>



        <div v-if="load_state" class="text-center"><img src="/assets/img/ball-triangle.svg" class="svg-loader" style=""/></div>
        <div class="text-center">
            <button @click="fetch" v-if="!load_state & load_more" class="btn btn-primary" >{{load_more_trans}}</button>
        </div>
    </div>
</template>

<style>
    @media (min-width: 992px) {
        .restaurant-food-img {
            border: 1px solid #dcdcdc;
        }
    }
    @media (max-width: 992px) {
        .md-flex-col {
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
            -ms-flex-direction: column;
            flex-direction: column;
        }
    }
</style>

<script>
export default {
  props: [
    "php_offset",
    "php_limit",
    "restaurant_id",
    "comments_trans",
    "comment_action_trans",
    "php_dishes",
    "all_dishes_endpoint",
    "check_ads",
    "mobile_ads",
    "desktop_ads",
    "mobile_ads20",
    "desktop_ads20",
    "load_more_trans",
    "taboola_ads"
  ],

  data() {
    return {
      dishes: [],
      offset: this.php_offset,
      limit: this.php_limit,
      load_state: false,
      in_viewport: false,
      load_more: true,
      arrayAds: []
    };
  },
  created() {
    let VueInstance = this;
    $.each(this.php_dishes, function(category, dishes) {
      VueInstance.dishes.push({ dishes: dishes, title: category });
    });
    // window.addEventListener("scroll", this.handleScroll);

    this.eventHub.$on("load-all-dishes", category => {
      VueInstance.FetchAllDishes(category);
    });
  },
  mounted() {
    this.refreshSticky();
  },
  destroyed() {
    // window.removeEventListener("scroll", this.handleScroll);
  },
  watch: {
    in_viewport() {
      this.fetch();
    }
  },

  methods: {
      checkSize($list){
          return $list.length;
      },
    fetch() {
      let VueInstance = this;
      if (!this.load_state && this.load_more) {
        VueInstance.load_state = true;
        axios
          .get(
            "/api/restaurant/" +
              VueInstance.restaurant_id +
              "/dishes/" +
              VueInstance.offset +
              "/" +
              VueInstance.limit
          )
          .then(function(response) {
            if (Object.keys(response.data).length) {
              $.each(response.data, function(category, dishes) {
                let id = null;
                let obj = VueInstance.dishes.filter(function(obj, index) {
                  if (obj.title === category) {
                    id = index;
                    return obj;
                  } else {
                    return false;
                  }
                })[0];

                if (obj && id !== null) {
                  $.each(dishes, function(index, dish) {
                    VueInstance.dishes[id].dishes.push(dish);
                  });
                } else {
                  VueInstance.dishes.push({ dishes: dishes, title: category });
                }
              });
            } else {
              VueInstance.load_more = false;
            }

            VueInstance.refreshSticky();
            VueInstance.load_state = false;
            VueInstance.offset += VueInstance.limit;
          })
          .catch(function(error) {
            console.log(error);
          });
      }
    },
    handleScroll() {
      let top_of_element = $("#load_anchor").offset().top;
      let bottom_of_element =
        $("#load_anchor").offset().top + $("#load_anchor").outerHeight();
      let bottom_of_screen = $(window).scrollTop() + $(window).height();
      let top_of_screen = $(window).scrollTop();

      if (
        bottom_of_screen > top_of_element &&
        top_of_screen < bottom_of_element
      ) {
        this.in_viewport = 1;
      } else {
        this.in_viewport = 0;
      }
    },

    refreshSticky() {
      setTimeout(function() {
        $(".ui.sticky").sticky({
          context: "#example1"
        });
      }, 20);
    },

    FetchAllDishes(category) {
      let VueInstance = this;
      if (!VueInstance.load_more) {
        VueInstance.animateToCategory(category);
      } else {
        axios
          .post(VueInstance.all_dishes_endpoint, {
            restaurant_id: VueInstance.restaurant_id
          })
          .then(function(response) {
            VueInstance.dishes = [];
            $.each(response.data, function(category, dishes) {
              VueInstance.dishes.push({ dishes: dishes, title: category });
            });

            VueInstance.refreshSticky();
            VueInstance.load_more = false;
            VueInstance.animateToCategory(category);
          })
          .catch(function(error) {
            console.log(error);
          });
      }
    },

    slugify(string) {
      return string
        .toLowerCase()
        .replace(/[^\w ]+/g, "")
        .replace(/ +/g, "-");
    },

    animateToCategory(category) {
      let VueInstance = this;
      setTimeout(function() {
        $("html, body").animate(
          {
            scrollTop: $("#" + VueInstance.slugify(category)).offset().top
          },
          500
        );
      }, 100);
    },

    checkAdsPosition(index){
        return index === index && index === (index|0);
    },

      taboolaAds(string, id) {

           id = this.convertAdsId(id);

           if (!this.inArray(id, this.arrayAds)) {

               this.arrayAds.push(id);
               window._taboola = window._taboola || [];
               _taboola.push({
                   mode: 'thumbnails-b',
                   container: id,
                   placement: string,
                   target_type: 'mix'
               });

           }

      },

      convertAdsId(id){
          return id.replace(/ /g,"-").toLowerCase();
      },

      inArray(needle, haystack) {
          let i = 0;
          let found = false;

          for (i; i < haystack.length; i++) {

              found = haystack[i] === needle;

              if (found) {
                  break;
              }

          }

          return found;
      },

  }
};

</script>

<style>
.restaurant-dishes-holder {
  margin-bottom: 10px;
  padding-bottom: 10px;
  background: linear-gradient(
      to left,
      rgb(255, 255, 255) 0%,
      rgb(115, 151, 69) 12%,
      rgb(73, 151, 17) 47%,
      rgb(255, 255, 255) 100%
    )
    left bottom no-repeat;
  background-size: 100% 1px; /* if linear-gradient, we need to resize it */
}

.no-stretch {
  width: auto !important;
}

.restaurant-food-img {
  display: flex;
  justify-content: center;
}
</style>
