<template>
    <div class="post-meta-date" style="display: inline; width-min: 60px;">
            <button  v-if="data_vote_up"  @click="vote(1)" disabled style="color: greenyellow"><span class="lnr lnr-thumbs-up" style="text-shadow: 0 0 black;" ></span></button>
            <button  v-else @click="vote(1)" ><span class="lnr lnr-thumbs-up" style="text-shadow: 0 0 black;"></span></button>
        {{ data_score }}
            <button v-if="data_vote_down" style="color: red" @click="vote(-1)" type="submit" disabled><span class="lnr lnr-thumbs-down" style="text-shadow: 0 0 black;" ></span></button>
            <button v-else @click="vote(-1)" type="submit" ><span class="lnr lnr-thumbs-down" style="text-shadow: 0 0 black;"></span></button>
    </div>
</template>

<script>

    export default {

        props: {
            article_id: this.article_id,
            type: this.type,
            check_voteup: this.check_voteup,
            check_votedown: this.check_votedown,
            check_auth: this.check_auth,
            register: '',
            login: ''
        },

        data() {
            return {
                data_vote_up: this.check_voteup,
                data_vote_down: this.check_votedown,
                data_score: this.voteScore(),
            }
        },
        methods: {
            vote(voteType) {
                if(this.check_auth){
                    if(voteType > 0){
                        this.data_vote_up = true;
                        this.data_vote_down = false;
                    }else{
                        this.data_vote_up = false;
                        this.data_vote_down = true;
                    }

                    axios.post(`/vote/${this.type}/${this.article_id}`,{
                        vote: voteType
                    })
                    .then(data => {
                        this.voteScore();
                        iziToast.success({
                            title: data.data.message
                        });
                    })
                    .catch(err => {
                        console.log(err);
                    });
                }else{
                    iziToast.error({
                        title: `You need to <a href="${this.register}">register</a> or <a href="${this.login}">login</a> to vote.`
                    });
                }


            },
            voteScore(){
                axios.get(`/score/${this.type}/${this.article_id}`)
                .then(data => {
                    this.data_score = data.data.score;
                })
                .catch(err => {
                    console.log(err);
                });
            }
        }
    }
</script>
