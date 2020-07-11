<template>
    <div>
        <modal
                name="restaurant-map"
                width="90%"
                height="80vh"
                @before-open="modalOpened"
                @closed="removePopup"
                classes="bg-white rounded-none shadow-inner"
                style="padding-top: 100px"
        >
            <div id="mapdiv" style="width: 100%; height:80vh; position: relative; overflow: visible; padding: 15px;">
                    <button class="button-x" @click="$modal.hide('restaurant-map')"><strong>X</strong></button>
                <div id="popup"></div>
            </div>
        </modal>


    </div>
</template>

<style>
    .button-x {
        right: 2px;
        position: absolute;
        top: 2px;
        z-index: 99;
        font-size: 19px;
        background-color: #7F9741;
        padding: 0 8px;
        cursor: pointer;
        border-radius: 50%;
        line-height: 1.5;
        color: #fff;
    }
</style>

<script>
    export default {
        props: {
            details: Object,
            apikey: String,
            get_route: String,
        },
        methods: {

             async modalOpened (){
                setTimeout(() => {

                    let platform = new H.service.Platform({
                        apikey: this.apikey
                    });
                    let defaultLayers = platform.createDefaultLayers();

                    let map = new H.Map(document.getElementById('mapdiv'),
                        defaultLayers.vector.normal.map,{
                            center: {lat:this.details.lat, lng:this.details.lng},
                            zoom: 13,
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

                    let parisPngIcon = new H.map.Icon("https://i.ibb.co/549yxc9/theme-pin.png", {size: {w: 56, h: 56}});

                    let group = new H.map.Group();

                    map.addObject(group);

                    map.addEventListener('tap', function (evt) {
                        $('.H_el').remove();
                        if (typeof evt.target.data == "string") {
                            let bubble = new H.ui.InfoBubble(evt.target.getGeometry(), {
                                content: evt.target.getData()
                            });
                            ui.addBubble(bubble);
                        }
                    });

                    let details = '<b>' + this.details.name + '</b> <br>' +
                        this.details.address + ' <br>' +
                        '<a target="_blank" class="btn btn-primary sm:flex-no-grow flex-grow px-5 py-1" href="http://maps.google.com/maps?q=loc:'+this.details.lat + ',' + this.details.lng +'">'+this.get_route+'</a>';

                    this.addMarkerToGroup(group, {lat:this.details.lat, lng:this.details.lng}, details, parisPngIcon);

                }, 200);
            },
            removePopup: function () {
                $('.popover').popover('destroy');
            },
            closeModal: function () {

            },
            addMarkerToGroup: function(group, coordinate, html, image) {

                let marker = new H.map.Marker(coordinate, {icon: image});
                marker.setData(html);
                group.addObject(marker);

            }
        }
    }
</script>
<style>
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
        margin-bottom: 68px;
    }

    .H_ib_tail::after {
        bottom: 63px;
    }
    .H_ib_tail::before {
        bottom: 63px;
    }


</style>
