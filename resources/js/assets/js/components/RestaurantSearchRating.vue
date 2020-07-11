<template>
    <div id="rating" class="widget">
        <h2 class="widgettitle" style="margin-top:20px">{{this.data_trans_title}}</h2>

        <div class="stars">

            <div class="form-group input-rating star-container stars-3" :title="hoverMessage(3)" @click="EmitEvent(3)">
                <div style="margin-left: -15px">
                    <input type="radio" name="rating-restaurant" id="rating-restaurant-1" disabled>
                    <label for="rating-restaurant-1" id="rating-1" class="marked"></label>
                    <input type="radio" name="rating-restaurant" id="rating-restaurant-2" disabled>
                    <label for="rating-restaurant-2" id="rating-2" class="marked"></label>
                    <input type="radio" name="rating-restaurant" id="rating-restaurant-3" disabled>
                    <label for="rating-restaurant-3" id="rating-3" class="marked"></label>
                    <input type="radio" name="rating-restaurant" id="rating-restaurant-4" disabled>
                    <label for="rating-restaurant-4" id="rating-4"></label>
                    <input type="radio" name="rating-restaurant" id="rating-restaurant-5" disabled>
                    <label for="rating-restaurant-5" id="rating-5"></label>
                </div>
            </div>

            <div class="form-group input-rating star-container stars-4" :title="hoverMessage(4)" @click="EmitEvent(4)">
                <div style="margin-left: -15px">
                    <input type="radio" name="rating-restaurant" id="rating-restaurant-1" disabled>
                    <label for="rating-restaurant-1" id="rating-1" class="marked"></label>
                    <input type="radio" name="rating-restaurant" id="rating-restaurant-2" disabled>
                    <label for="rating-restaurant-2" id="rating-2" class="marked"></label>
                    <input type="radio"  name="rating-restaurant" id="rating-restaurant-3" disabled>
                    <label for="rating-restaurant-3" id="rating-3" class="marked"></label>
                    <input type="radio" name="rating-restaurant" id="rating-restaurant-4" disabled>
                    <label for="rating-restaurant-4" id="rating-4" class="marked"></label>
                    <input type="radio" name="rating-restaurant" id="rating-restaurant-5" disabled>
                    <label for="rating-restaurant-5" id="rating-5"></label>
                </div>
            </div>

            <div class="form-group input-rating star-container stars-5" :title="hoverMessage(5)"  @click="EmitEvent(5)">
                <div style="margin-left: -15px">
                    <input type="radio" name="rating-restaurant" id="rating-restaurant-1" disabled>
                    <label for="rating-restaurant-1" id="rating-1" class="marked"></label>
                    <input type="radio" name="rating-restaurant" id="rating-restaurant-2" disabled>
                    <label for="rating-restaurant-2" id="rating-2" class="marked"></label>
                    <input type="radio" name="rating-restaurant" id="rating-restaurant-3" disabled>
                    <label for="rating-restaurant-3" id="rating-3" class="marked"></label>
                    <input type="radio" name="rating-restaurant" id="rating-restaurant-4" disabled>
                    <label for="rating-restaurant-4" id="rating-4" class="marked"></label>
                    <input type="radio" name="rating-restaurant" id="rating-restaurant-5" disabled>
                    <label for="rating-restaurant-5" id="rating-5" class="marked"></label>
                </div>
            </div>

        </div>

    </div>
</template>

<script>

    export default {

        props: {
            search_rating: '',
            trans_title: '',
            trans_hover: ''
        },

        data() {
            return {
                stars: [],
                rating: '',
                data_trans_title: '',
                data_trans_hover: ''
            }
        },

        mounted() {
          this.data_trans_title = this.trans_title;
          this.data_trans_hover = this.trans_hover;

            if (this.search_rating !== '') {
                this.rating = JSON.parse(this.search_rating);

                this.stars = this.rating.map(function(value) {
                    $('.stars-'+stars).addClass('active');
                    return parseInt(value);
                });

                let instance = this;
                setTimeout(function () {
                    instance.EmitEvent();
                }, 200);
            }


        },

        created: function () {
            this.eventHub.$on('reset_search', () => {
                this.stars = [];
            });
        },

        methods: {
            EmitEvent(stars) {

                if (typeof stars !== 'undefined') {
                    if (this.inArray(stars,this.stars)) {

                        console.log(this.stars.indexOf(stars));

                        this.stars.splice(this.stars.indexOf(stars) , 1);
                    } else {
                        this.stars.push(stars);
                    }

                    $('.stars-'+stars).toggleClass('active');
                }

                this.eventHub.$emit('rating-ticked', this.stars);

            },

            hoverMessage(stars){
                return this.data_trans_hover.replace(':number', stars);
            },

            inArray(needle, haystack) {
                var length = haystack.length;
                for(var i = 0; i < length; i++) {
                    if(haystack[i] == needle) return true;
                }
                return false;
            }

        },

    }

</script>

<style scoped>

.star-container{
    background: white;
    padding: 12px 0 12px 0;
    text-align:center;
    margin-bottom: 13px;
    cursor: pointer;
}

.star-container:hover{
    margin: -2px 0 11px 0;
    border: 2px #7F9741 solid;
}

.star-container:active{
    background: #7F9741;
}

.active {
    background: #7F9741;
    margin: -2px 0 11px 0;
    border: 2px #7F9741 solid;
}

label {
    width: 43.6px !important;
    margin-bottom: 0px;

}
label::before{
    font-size: 35px !important;
}


</style>
