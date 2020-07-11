<template>
    <div class="widget">
        <h2 class="widgettitle" v-text="widget_title_trans"></h2>

        <div class="filter-separator-parent">
            <h3 class="filter-separator" v-text="nutritional_trans"></h3>
        </div>
        <div class="row">
            <div class="col-md-12 filter-col-md-12">
                <div class="col-md-6 filter-box" v-for="filter in filters" @click="ToggleFilter(filter.slug)" v-if="filter.important == 1">

                    <div class="single-filter" :class="{'filter-active' : selected_filters.includes(filter.slug)}">
                        <div class="row top-inner-padding">
                            <div class="col-md-12"><img :src="base_url + filter.icon" alt="" class="filter-img" ></div>
                        </div>
                        <div class="row filter-text">
                            <div class="col-md-12" v-text="filter.name"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="filter-separator-parent">
            <h3 class="filter-separator p-top-10" v-text="general_trans"></h3>
        </div>
        <div class="row">
            <div class="col-md-12 filter-col-md-12">
                <div class="col-md-6 filter-box" v-for="filter in filters" @click="ToggleFilter(filter.slug)" v-if="filter.important == 0">

                    <div class="single-filter" :class="{'filter-active' : selected_filters.includes(filter.slug)}">
                        <div class="row top-inner-padding">
                            <div class="col-md-12"><img :src="base_url + filter.icon" alt="" class="filter-img" ></div>
                        </div>
                        <div class="row filter-text">
                            <div class="col-md-12" v-text="filter.name"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-md-12">
            <button id="under-filters-submit-search" type="button"
                    class="btn btn-primary pull-right redefine-dishes-center" v-text="search_btn_trans" @click="SendSearchEvent()">
            </button>
        </div><!-- /.col-* -->
    </div>
</template>

<script>
    export default {
        props: {
            php_selected_filters: {},
            php_filters: {},
            widget_title_trans: {},
            nutritional_trans: {},
            base_url : {},
            general_trans : {},
            search_btn_trans : {}
        },

        data() {
            return {
                filters: JSON.parse(this.php_filters),
                selected_filters: JSON.parse(this.php_selected_filters),
            }
        },



        mounted() {
            this.eventHub.$emit('toggle-filter', this.selected_filters);
        },
        watch : {
            selected_filters () {
                this.eventHub.$emit('toggle-filter', this.selected_filters);
            }
        },
        created () {
            this.eventHub.$on('reset_search', () => {
                this.selected_filters = [];
            });
        },

        methods: {
            ToggleFilter(slug) {

                let i = this.selected_filters.indexOf(slug);

                if (i === -1)
                    this.selected_filters.push(slug);
                else
                    this.selected_filters.splice(i, 1);
            },
            SendSearchEvent() {
                this.eventHub.$emit('search-event');
            }
        }
    }

</script>


<style>
    .widgettitle {
        color: rgb(127, 151, 65);
    }
    .filter-box {
        margin-bottom: 10px;

    }


    .single-filter:hover {
        cursor : pointer;


    }
    .filter-img, .single-filter {
        -webkit-touch-callout: none; /* iOS Safari */
        -webkit-user-select: none; /* Safari */
        -khtml-user-select: none; /* Konqueror HTML */
        -moz-user-select: none; /* Firefox */
        -ms-user-select: none; /* Internet Explorer/Edge */
        user-select: none; /* Non-prefixed version, currently
                                  supported by Chrome and Opera */
    }
    .p-top-10 {
        padding-top : 10px
    }
</style>