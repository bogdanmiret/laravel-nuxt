<template>

    <div class="col-md-12">
        <div class="box">
            <div class="box-body">
                <table v-if="structs.length" class="table">
                    <thead>
                    <tr>
                        <th>Isocode</th>
                        <th>landing_id</th>
                        <th>intervals</th>
                    </tr>
                    </thead>

                    <tbody>

                    <tr v-for="(struct, struct_index) in structs" class="single_iso" :id="'struct_'+struct_index">
                        <td class="vertical_center">
                            <button class="btn" @click.prevent="RemoveIso(struct_index)">Remove from LIST</button>
                            &nbsp;&nbsp;&nbsp;
                            <button class="btn btn-warning" @click.prevent="RemoveSQL(struct.landing_id, struct_index)"><i
                                    class="fa fa-times danger-times "></i> DELETE SQL
                            </button>
                            &nbsp;&nbsp;&nbsp;
                            <v-select
                                    :class="isocodes.indexOf(struct.isocode) !== -1 ? 'item_exists' : 'new_item' "
                                    v-model="struct.isocode"
                                    :id="'select_id_' + struct_index"
                                    :options="filtered_iso_list"
                            ></v-select>
                        </td>
                        <td>
                            <v-select
                                    :class="landing_ids.indexOf(struct.landing_id) !== -1 ? 'item_exists' : 'new_item'"
                                    taggable v-model="struct.landing_id" :options="filtered_landing_ids"></v-select>
                        </td>
                        <td>
                            <div v-for="(interval, interval_index) in struct.intervals" class="vertical_center"
                                 :id="'int_'+interval_index">
                                <small class="label  bg-orange" v-text="interval.from + ' >> '"></small> &nbsp;

                                <v-select
                                        :options="landing_lists_ids"
                                        class="item_exists"
                                        v-model="interval.to"
                                ></v-select>
                                <!--<input type="text" v-model="interval.to">-->
                            </div>


                        </td>
                    </tr>

                    </tbody>
                </table>
                <div v-else class="text-center"><b>Silence is golden</b></div>
                <br>

                <div class="btn btn-default text-center" @click="PushNewISO()"><i class="fa fa-plus"></i></div>
                <div class="btn btn-warning pull-right" @click="SyncAll()" v-if="structs.length"><i
                        class="fa fa-refresh"></i> SYNC
                </div>


                Legend : Green - New Item / Orange - Item exists

            </div>


        </div>

    </div>


</template>

<script>

    import vSelect from 'vue-select'


    export default {
        components: {vSelect},
        props: {
            sync_url: {},
            parent_id: {},
            landing_ids_php: {},
            isocodes_php: {},
            landing_lists_ids_php: {},
            empty_struct_php: {},
            children_struct: {},
            delete_sql_route: {},
            parent_isocode : {}

        },
        data() {
            return {
                structs: JSON.parse(this.children_struct),
                empty_struct: JSON.parse(this.empty_struct_php),
                landing_ids: JSON.parse(this.landing_ids_php),
                isocodes: JSON.parse(this.isocodes_php),
                landing_lists_ids: JSON.parse(this.landing_lists_ids_php),
            }
        },
        mounted()
        {
            this.SyncAll();
        },
        computed: {
            filtered_iso_list: function () {
                let used_isocodes = this.structs.map(function(a) {return a.isocode;});

                used_isocodes.push(used_isocodes, JSON.parse(this.parent_isocode));

                return this.isocodes.filter(function(x) { return used_isocodes.indexOf(x) < 0 });
            },

            filtered_landing_ids : function() {
                let used_ids = this.structs.map(function(a) {return a.landing_id;});
                used_ids.push(used_ids, this.parent_id);

                return this.landing_ids.filter(function(x) { return used_ids.indexOf(x) < 0 });
            },



        },
        filters: {}
        ,
        methods: {
            PushNewISO()
            {
                let empty = JSON.parse(JSON.stringify(this.empty_struct));
                this.structs.push(Object.assign({}, empty));
                iziToast.show({
                    title: 'New Landing added',
                    color: 'green'  // blue, red, green, yellow
                });
            },
            SyncAll()
            {
                $('body').plainOverlay('show');
                let VueInstance = this;
                axios.post(VueInstance.sync_url, {
                    structs: VueInstance.structs,
                    parent_id: VueInstance.parent_id,
                })
                    .then(function (response) {
                        $('body').plainOverlay('hide');
                        iziToast.show({
                            title: 'Sync complete ! Big success',
                            color: 'green'  // blue, red, green, yellow
                        });
                    })
                    .catch(function (error) {
                        $('body').plainOverlay('hide');
                        iziToast.show({
                            title: 'A wild error ccurred',
                            color: 'red'  // blue, red, green, yellow
                        });
                    });
            },
            RemoveIso(struct_index)
            {
                this.structs.splice(struct_index, 1);
                iziToast.show({
                    title: 'Iso country removed',
                    color: 'green'  // blue, red, green, yellow
                });
            },

            RemoveSQL(landing_id, struct_index)
            {
                if(landing_id.length === 0) {
                    iziToast.show({
                        title: 'Click remove from LIST. This item is not in the database yet.',
                        color: 'red'  // blue, red, green, yellow
                    });
                    return;
                }


                if (window.confirm("Are you sure?")) {
                    let VueInstance = this;


                    axios.post(VueInstance.delete_sql_route, {
                        id: landing_id,
                        parent_id: VueInstance.parent_id,
                    })
                        .then(function (response) {
                            VueInstance.structs.splice(struct_index, 1);
                            iziToast.show({
                                title: 'Landing ' + landing_id + ' has been deleted !',
                                color: 'green'  // blue, red, green, yellow
                            });
                        })
                        .catch(function (error) {
                            console.log(error);
                        });
                }


            }
        }
    }
</script>

<style>

    .danger-times {
        color: #851a17;
    }

    .danger-times:hover {
        cursor: pointer;
        color: red;
    }

    .single_iso:hover {
        background: #ebebeb;
    }

    .item_exists {
        background: rgba(255, 165, 0, 0.54);
    }

    .new_item {
        background: rgba(0, 141, 0, 0.29);
    }

    .critical_error {
        background: rgba(255, 45, 35, 0.75);
    }

    .vertical_center {
        display: flex;
        align-items: center;
    }
</style>