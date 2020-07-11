<template>
    <div>

        <hr>
        <div class="tabbable pills">
            <ul id="faq" class="nav nav-pills flex-center">
                <li v-for="language in languages" v-bind:class="['', language === default_locale ? 'active' : '' ]">
                    <a :href="'#'+language" data-toggle="tab">{{ language | capitalise }}</a>

                </li>


            </ul>
            <hr>
            <form method="POST" :action="form_submit_route">
                <input type="hidden" name="_method" value="POST">
                <input type="hidden" name="_token" :value="csrf_token">
                <input type="hidden" name="list_id" :value="list_id">

                <div class="tab-content clearfix">
                    <div v-for="language in languages" class="tab-pane fade in  "
                         v-bind:class="['tab-pane fade', language === default_locale ? 'active' : '' ]"
                         :id="language">

                        <hr>

                        <div class="col-md-4 left-right-20 flex-vertical-center flex-center m-b-10"
                             v-for="(object_details, index) in objects[language]">
                            <div class="col-md-10 json_item">

                                <div class="form-group" v-for="(field_type, field_name) in structure" :class="field_type.css">
                                    <label :for="object_details[field_name]">{{ field_name }}:</label>

                                    <textarea v-if="field_type.element === 'textarea'" class="form-control" rows="1"
                                              :class="field_name == 'language' && object_details[field_name] == language ? 'country_language ' : ''"
                                              :id="field_name + '_' + index"
                                              :name="'element[' + language + '][' + index + '][' + field_name +']'"
                                              cols="50">{{ object_details[field_name] }}</textarea>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <button class="btn btn-default" @click.prevent.submit="RemoveItem(language, index)">
                                    {{ index }} <i class="fa fa-times" aria-hidden="true"></i></button>
                            </div>
                            <hr>
                        </div>
                        <div class="col-md-12 text-center">
                            <button class="btn btn-primary "> Save All </button>
                            <button class="btn btn-success" @click.prevent.submit="addNewItem(language)"><i
                                    class="fa fa-plus " aria-hidden="true"></i></button>
                        </div>
                    </div>

                </div>
            </form>

            <hr>

        </div>
    </div>


</template>


<script>

    export default {

        props: {
            php_structure: {},
            php_languages: {},
            default_locale: {},
            form_submit_route: {},
            csrf_token: {},
            php_empty_structure: {},

            php_translates: {},
            list_id: {}
        },
        data() {
            return {
                structure: '',
                languages: '',
                slug: '',
                objects: [""],
                indice: '',
                parsed_empty_structure: JSON.parse(this.php_empty_structure)

            }
        },
        mounted() {

            this.structure = this.php_structure;

            this.languages = JSON.parse(this.php_languages);
            this.objects = this.php_translates;
        },
        filters: {
            capitalise: function (value) {
                return value.toUpperCase();
            }
        },
        methods: {

            addNewItem (language)
            {
                this.objects[language].push(JSON.parse(this.php_empty_structure));
            },

            RemoveItem(language, index)
            {
                this.objects[language].splice(index, 1);
            },
//            CopyToOtherLanguages(language)
//            {
//
//            }
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

    textarea {
        max-width: 100%;
        /*max-height: 100px;*/
    }

    .json_item {
        border: 1px solid #cccccc;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.18);
    }

    /*.json_item div:last-child {*/
    /*flex-grow: 1;*/
    /*}*/

    .m-b-10 {
        margin-bottom: 10px;
    }

    .country_language {
        background: red;
        color: white;
    }
</style>
