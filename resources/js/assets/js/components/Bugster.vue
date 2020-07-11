<template>
    <div class="box">
        <div class="box-header">
            <div class="col-md-3"><input type="text" v-model="keyword" placeholder="Keyword" class="form-control"
                                         @keyup.enter="filterData"/></div>
            <div class="col-md-1"><input type="text" v-model="status_code" placeholder="Status Code"
                                         class="form-control"/></div>
            <div class="col-md-1"><input type="text" v-model="env" placeholder="ENV"
                                         class="form-control"/></div>
            <div class="col-md-2"><input type="text" v-model="app_name" placeholder="App Name"
                                         class="form-control"/></div>

            <div class="col-md-3">
                <flat-pickr
                        v-model="date"
                        placeholder="Select date"
                        :required="true"
                        input-class="date-input"
                        name="date">
                </flat-pickr>
            </div>

    </div>

    <div class="box-body">
        <div class="col-md-12">
            <div class="row pull-right">
                {{ dataSet.from }} - {{dataSet.to }} of {{ dataSet.total }}
            </div>

            <div class="row">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Method / Path</th>
                        <th>Status code</th>
                        <th>ENV</th>
                        <th>App name</th>
                        <th>Reported</th>
                        <th>Created at</th>

                    </tr>
                    </thead>
                    <tbody>
                    <tr class="single_bug" v-for="(data, index) in dataSet.data" @click="show(data.id)">
                        <th scope="row" v-text="data.id"></th>
                        <td>{{ data.method}} / {{ data.path }}</td>
                        <td v-text="data.status_code"></td>
                        <td v-text="data.app_env"></td>
                        <td v-text="data.app_name"></td>
                        <td v-text="data.reported"></td>
                        <td v-text="data.created_at"></td>
                    </tr>
                    </tbody>
                </table>


                <div class="col-md-12 text-center">
                    <paginator :dataSet="dataSet" @changed="fetch"></paginator>
                </div>
            </div>
            <br>

        </div>

    </div>
    </div>


</template>

<script>
    import flatPickr from 'vue-flatpickr-component';
    import 'flatpickr/dist/flatpickr.css';
    import 'flatpickr/dist/themes/material_blue.css';

    export default {
        components: {
            flatPickr
        },
        props: ['show_endpoint', 'fetch_endpoint'],
        data() {
            return {
                dataSet: false,
                keyword: "",
                status_code: "",
                env : "",
                app_name : "",
                date: '',
            }
        },
        created() {
            this.fetch();
        },

        methods: {
            fetch(page) {
                axios.get(this.url(page)).then(this.refresh);
            },
            refresh({data})
            {
                this.dataSet = data;
            },
            url(page) {
                if (!page) {
                    let query = location.search.match(/page=(\d+)/);

                    page = query ? query[1] : 1;
                }
                return this.fetch_endpoint + "?page=" + page;
            },
            show(id)
            {
                window.location = this.show_endpoint + "/" + id;
            }
        }
    }
</script>

<style>
    .single_bug:hover {
        cursor: pointer;
    }
</style>