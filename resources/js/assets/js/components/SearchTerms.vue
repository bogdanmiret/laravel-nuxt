<template>
    <div class="widget" id="categories">
        <h2 class="widgettitle" style="margin-top:20px">{{ terms_translate }}</h2>
        <div class="background-white p20" id="categoriesList">

                <div class="form-group" id="categoriesGroup">
                    <div v-for="(term,index) in terms" class="checkbox termsCheckBox" id="termsGroup">
                        <input type="checkbox" :id="'term'+index" @change="EmitEvent" :name="term.key" :value="term.slug" v-model="selected_terms" style="display:none"><label :for="'term'+index">{{ term.key }}</label>
                    </div>

                </div>

        </div>
    </div><!-- /.wifget -->

</template>

<script>
    export default {

        props: {
            php_terms: {},
            php_selected_terms: {},
            terms_translate: {},
            filter_btn_trans : {}

        },
        data() {
            return {
                terms: '',
                selected_terms: ''
            }
        },
        created: function () {
            this.eventHub.$on('reset_search', () => {
                this.selected_terms = [];
            });
        },
        mounted() {
            this.terms = JSON.parse(this.php_terms);
            this.selected_terms = JSON.parse(this.php_selected_terms);

            let instance = this;

            setTimeout(function () {
                instance.EmitEvent();
            }, 200);
        },
        methods: {
            EmitEvent()
            {
                this.eventHub.$emit('term-ticked', this.selected_terms)
            },
            emitSearch()
            {
                this.eventHub.$emit('execute-search', 'terms')
            }
        }
    }
</script>


<style>

    .left-right-20 {
        padding-left: 20px;
        padding-right: 20px;
    }

    .flex-center {
        display: flex;
        justify-content: center;
    }

    .flex-vertical-center {
        display: flex;
        align-items: center;
    }


</style>
