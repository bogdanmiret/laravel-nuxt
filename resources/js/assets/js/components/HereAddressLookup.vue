<template>
    <div>
        <div>
            <input type="text"
                   :name="name"
                   class="form-control centered-input-resp main-enter"
                   autocomplete="off"
                   :placeholder="placeholder"
                   :id="id"
                   v-model="query"/>
        </div>

        <transition name="fade">
            <ul v-click-outside="clearResults" class="address-results" style="width: 100%;" v-if="showResults">
                <li :key="index" v-bind:class="{'active-address-result': currentItem === index}" class="single-address-result " v-for="(result, index) in results" v-if="result.city" @click="setAddress(result)">

                    <span v-if="result.street">
                        <strong>
                            {{result.street}}
                            {{ result.houseNumber ? result.houseNumber + ", " : '' }}
                        </strong>
                    </span>
                    <strong v-if="result.city">{{result.city}}</strong>
                    <em v-if="result.county">{{ "| " + result.county}} </em>
                    <em v-if="result.city">{{ "| " + result.country}} </em>

                </li>
            </ul>
        </transition>
    </div>
</template>

<script>
    export default {
        name: "HereAddressLookup",
        props: {
            placeholder: '',
            id: '',
            name: '',
            value: '',
            app_id: '',
            app_code: '',
            app_key: '',
            on_form: false,

        },
        data() {
            return {
                results: {},
                showResults: false,
                query: "",
                address: {},
                selectedValue: '',
                currentItem: 0,
                // app_id: 'mAhHHZUvY2esTolvzcxc',
                // app_code: 'L8vRS8EKGdEufAmWXkifsw',
                // app_key: '5UCg415XtyeAWu-kPiXPRT1gVO_d6hkG4rFIynZm5Z0'
            };
        },
        mounted() {
            this.query = this.value;
            this.showResults = false;
            document.addEventListener("keyup", this.keyPress);
        },

        watch: {
            query: function(value) {
                this.selectedValue = '';
                fetch(`https://autocomplete.geocoder.ls.hereapi.com/6.2/suggest.json?maxresults=5&apikey=${this.app_key}&query=${value}`)
                    .then(result => result.json())
                    .then(result => {
                        if(result.suggestions && result.suggestions.length > 0) {

                            this.query === this.value ? this.showResults = false : this.showResults = true;

                            let final_results = [];
                            let api_results = result.suggestions;
                            let item = '';
                            let itemAddress = '';

                            Object.keys(api_results).forEach(key => {

                                item = api_results[key];
                                itemAddress = item.address;

                                if (!this.inArray(itemAddress, final_results)) {

                                    if (itemAddress.city && typeof itemAddress.street === 'undefined') {
                                        final_results.push(JSON.parse(JSON.stringify({
                                            city: itemAddress.city,
                                            county: itemAddress.county,
                                            country: itemAddress.country,
                                            locationId: item.locationId,
                                            matchLevel: item.matchLevel,
                                            district: itemAddress.district ? itemAddress.district : false
                                        })));
                                    }

                                    if (typeof itemAddress.street !== 'undefined') {
                                        final_results.push(JSON.parse(JSON.stringify({
                                            street: itemAddress.street,
                                            houseNumber: itemAddress.houseNumber ? itemAddress.houseNumber : '',
                                            city: itemAddress.city,
                                            county: itemAddress.county,
                                            country: itemAddress.country,
                                            locationId: item.locationId,
                                            matchLevel: item.matchLevel,
                                            district: itemAddress.district ? itemAddress.district : false
                                        })));
                                    }

                                }
                            });


                            this.results = final_results;

                        } else {
                            this.clearResults();
                        }
                    }).catch((e) => {
                    console.log('e', e);
                });
            }
        },
        methods: {
            keyPress () {
                if (this.currentItem > -1) {
                    if (event.keyCode === 38 && this.currentItem > 0) {
                        this.currentItem--;

                    } else if (event.keyCode === 40 && this.currentItem < Object.keys(this.results).length) {
                        this.currentItem++;

                    } else if (event.keyCode === 13 && this.currentItem > -1) {
                        this.setAddress(this.results[this.currentItem]);

                    }
                }
            },
            fillAddress(result){
                let Vue = this;

                if (typeof result.street !== 'undefined') {
                    let houseNumber = result.houseNumber ?  " " + result.houseNumber + ", " : ', ';
                    Vue.selectedValue = result.street + houseNumber + result.city + ", " + result.country;
                } else {
                    Vue.selectedValue = result.city + ", " + result.country;
                }

                $('#'+this.id).val(Vue.selectedValue);

            },
            setAddress(result) {

                let Vue = this;

                fetch(`https://geocoder.ls.hereapi.com/6.2/geocode.json?locationid=${result.locationId}&jsonattributes=1&gen=9&apiKey=${this.app_key}`) // for dev
                    .then(result_location => result_location.json())
                    .then(result_location => {

                        if (typeof result_location.response.view[0].result[0] !== 'undefined') {

                            let location = result_location.response.view[0].result[0].location;
                            let city = location.address.city;

                            Vue.address = {
                                locality: city.replace('ü', 'ue').replace('ö', 'oe').replace('ä', 'ae'),
                                country: location.address.additionalData[0].value, // country
                                latitude: location.displayPosition.latitude,
                                longitude: location.displayPosition.longitude,
                                route: location.address.street ? location.address.street : '',
                                houseNumber: result.houseNumber ? result.houseNumber : '',
                            };

                            let countries = {
                                'ROU': 'ro',
                                'DEU': 'de',
                                'AUT': 'at',
                                'CHE': 'ch',
                                'GBR': 'gb',
                                'FRA': 'fr',
                                'ESP': 'es',
                                'PRT': 'pt',
                                'NGA': 'ng',
                                'AUS': 'au',
                                'IND': 'in',
                                'PHL': 'ph',
                                'HKG': 'cn',
                                'USA': 'us',
                                'CAN': 'ca',
                                'MEX': 'mx',
                                'COL': 'co',
                                'PER': 'pe',
                                'ARG': 'ar',
                                'BRA': 'br',
                            };

                            let country = (typeof countries[location.address.country] !== 'undefined') ? countries[location.address.country] : '';

                            if (Vue.on_form) {
                                $('#lat').val(Vue.address.latitude);
                                $('#lng').val(Vue.address.longitude);
                                $('#street_number').val(Vue.address.houseNumber);
                                $('#route').val(Vue.address.route);
                                $('#locality').val(Vue.address.locality);
                                $('#country_long').val(Vue.address.country);
                                $('#country').val(country);
                            }

                            Vue.$emit("address", Vue.address);
                            Vue.fillAddress(result);

                        } else {
                            iziToast.show({
                                title: "Something went wrong.",
                                color: 'red'  // blue, red, green, yellow
                            });
                        }

                    }).catch((e) => {
                        iziToast.show({
                            title: "Something went wrong.",
                            color: 'red'  // blue, red, green, yellow
                        });
                });

                Vue.clearResults();

            },
            clearResults() {
                this.showResults = false;
                this.results = {};
                this.currentItem = 0;
            },
            inArray(needle, haystack) {
                let i = 0;
                let found = false;

                for (i; i < haystack.length; i++) {

                    found = haystack[i].city === needle.city
                        && haystack[i].county === needle.county
                        && haystack[i].country === needle.country
                        && needle.district !== false;

                    if (found) {
                        break;
                    }

                }

                return found;
            },
        }
    }
</script>

<style scoped>
    .information {
        width: 50%;
        margin: 15px 0px;
    }
    .information input {
        width: 100%;
        padding: 5px;
        margin: 5px 0px;
    }

    .address-results {
        background: #fff;
        position: absolute;
        width: 96%;
        z-index: 999;
        margin-top: 5px;
        list-style-type: none;
        padding-left: 0;
        border: 1px solid #ccc;
        font: 400 14px Roboto,sans-serif;
        cursor: context-menu;
        text-align: left;
    }
    .single-address-result {
        padding: 8px 0 8px 20px;
        font: 400 14px Roboto,sans-serif;
        margin: 0 !important;
        overflow: visible;
    }
    .single-address-result:hover {
        background: #cccccc;
        cursor: pointer;
    }
    .active-address-result {
        background: #7F9741;
        color: white;
    }

</style>
