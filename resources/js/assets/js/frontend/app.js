require('./bootstrap');


const eventHub = new Vue();

Vue.mixin({
    data: function () {
        return {
            eventHub: eventHub
        };
    }
});

Vue.prototype.$http = axios;

import StarRating from 'vue-star-rating';
import ReadMore from 'vue-read-more';

Vue.component('paginator', require('../components/Paginator.vue'));

Vue.component('star-rating', StarRating);

Vue.component('search-terms', require('../components/SearchTerms.vue'));

Vue.component('search-amenities', require('../components/RestaurantSearchAmenities.vue'));

Vue.component('home-search', require('../components/HomeSearch.vue'));

Vue.component('restaurant-search', require('../components/RestaurantSearch.vue'));

Vue.component('top-search', require('../components/TopSearch.vue'));

Vue.component('restaurant-questions', require('../components/RestaurantQuestions.vue'));

Vue.component('dishes-search', require('../components/DishesSearch.vue'));

Vue.component('dishes-search-filters', require('../components/DishesSearchFilters.vue'));

Vue.component('restaurant-list-dishes', require('../components/ListDishesOnRestaurant.vue'));

Vue.component('feedback', require('../components/Feedback.vue'));

Vue.component('restaurant-categories', require('../components/RestaurantCategories.vue'));

Vue.component('restaurant-user-actions', require('../components/RestaurantUserActions.vue'));

Vue.component('get-advertising', require('../components/GetAdvertising.vue'));

Vue.component('favourites', require('../components/Favourites.vue'));

Vue.component('user-avatar', require('../components/UserAvatar.vue'));

Vue.component('modal-trigger', require('../components/ModalTrigger.vue'));

Vue.component('restaurant-map', require('../components/RestaurantMap.vue'));

Vue.component('dish-map', require('../components/DishesCompaniesMap.vue'));

Vue.component('restaurants-listing-map', require('../components/RestaurantsListingMap.vue'));

Vue.component('vote', require('../components/Vote.vue'));

Vue.component('search-rating', require('../components/RestaurantSearchRating.vue'));

Vue.component('search-company', require('../components/SearchCompany.vue'));

Vue.component('here-address-lookup', require('../components/HereAddressLookup.vue'));

Vue.component('search-range', require('../components/RestaurantSearchRange.vue'));

Vue.use(ReadMore);

var VueInst = new Vue({
    components: {
        StarRating
    },
    el: '#vue_id'
});

window.oldBtnContent = '';
window.spinner = '<div class="spinner"></div>';

window.enableSpinner = function enableSpinner(selector) {
    selector.attr('disabled', true);
    window.oldBtnContent = selector.html();
    selector.css('width', selector.outerWidth());
    selector.html(window.spinner);
};

window.disabledSpinner = function disabledSpinner(selector) {
    selector.attr('disabled', false);
    selector.html(window.oldBtnContent);
    window.oldBtnContent = '';
};








