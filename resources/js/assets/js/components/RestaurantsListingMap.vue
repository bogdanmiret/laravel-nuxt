<template>
    <modal
            name="restaurants-listing-map"
            height="auto"
            width="90%"
            @opened="modalOpened"
            classes="bg-white rounded-none shadow-inner"
    >
        <div slot="top-right" @click="$modal.hide('restaurants-listing-map')"><strong class="green">X</strong></div>
        <div id="mapdiv"></div>
    </modal>
</template>

<style>
    .v--modal-top-right {
        right: 3.6%!important;
        top: 9%!important;
        z-index: 999;
        font-size: 19px;
        background-color: #fff;
        padding: 0 8px;
        cursor: pointer;
        border-radius: 50%;
        line-height: 1.5;
    }
    #mapdiv {
        width: 100%;
        height:80vh;
    }
    .green {
        color: #7F9741;
    }
    @media (min-width: 320px) and (max-width: 480px) {
        .H_ib_content {
            width:200px !important;
        }
        .H_ib_body {
            margin-right: -12em !important;
        }
    }

    .H_ib_content {
        width:300px;
        font-size: 12px;
        margin: 0px;
        padding: 2px 10px;
    }

    .H_ib_body {
        margin-right: -17em;
        margin-bottom: 13px;
    }

    .H_ib_tail::after {
        bottom: 9px;
    }
    .H_ib_tail::before {
        bottom: 9px;
    }

</style>

<script>

    export default {
        props: {
            zoom_lvl: {type: String},
            country_lat: {},
            country_lng: {},
            companies_php : {},
            apikey: {type: String},
        },
        methods: {
            async modalOpened() {
                setTimeout(async () => {

                    let platform = new H.service.Platform({
                        apikey: this.apikey
                    });
                    let defaultLayers = platform.createDefaultLayers();

                    let map = false;
                        map = new H.Map(document.getElementById('mapdiv'),
                        defaultLayers.vector.normal.map,{
                            center: {lat:this.country_lat, lng:this.country_lng},
                            zoom: 4,
                            pixelRatio: window.devicePixelRatio || 1
                        });
                    window.addEventListener('resize', () => map.getViewPort().resize());

                    map.addEventListener('pointermove', function (event) {
                        if (event.target instanceof H.map.Marker) {
                            map.getViewPort().element.style.cursor = 'pointer';
                        } else {
                            map.getViewPort().element.style.cursor = 'auto';
                        }
                    }, false);

                    let behavior = new H.mapevents.Behavior(new H.mapevents.MapEvents(map));

                    let ui = H.ui.UI.createDefault(map, defaultLayers);

                    let group = new H.map.Group();

                    map.addObject(group);

                    map.addEventListener('tap', function (evt) {

                        $('.H_el').remove();
                        //
                        if (typeof evt.target.data == "object" && typeof evt.target.data.g == "undefined") {
                            let bubble =  new H.ui.InfoBubble(evt.target.getGeometry(), {
                                content: evt.target.getData().a.data
                            });
                            ui.addBubble(bubble);
                        }
                    });

                    this.startClustering(map,this.companies_php);

                }, 100)
            },
            startClustering(map, data) {

                let dataPoints = Object.keys(data).map(function (item) {
                    let details = `<a href="${data[item].url}">${data[item].name}</a> <br> ${data[item].extended_address}`;
                    return new H.clustering.DataPoint(data[item]['location'].lat, data[item]['location'].lon, null, details);
                });

                let clusteredDataProvider = new H.clustering.Provider(dataPoints, {
                    clusteringOptions: {
                        eps: 32,
                        minWeight: 2
                    }
                });

                let clusteringLayer = new H.map.layer.ObjectLayer(clusteredDataProvider);

                map.addLayer(clusteringLayer);
            }

        }
    }
</script>
