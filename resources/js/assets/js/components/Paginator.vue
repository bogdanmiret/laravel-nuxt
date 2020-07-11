<template>
    <ul class="pagination" v-if="shouldPaginate">
        <li v-show="prevUrl">
            <a href="javascript:void(0)" aria-label="Previous" rel="prev" @click.prevent="changePage('previous')">
                <span aria-hidden="true">&laquo; </span>
            </a>
        </li>

        <li v-show="nextUrl">
            <a href="javascript:void(0)" aria-label="Next" rel="next" @click.prevent="changePage('next')">
                <span aria-hidden="true"> &raquo;</span>
            </a>
        </li>
    </ul>
</template>

<script>
export default {
  props: ["dataSet"],

  data() {
    return {
      page: 1,
      prevUrl: false,
      nextUrl: false,
      loading: false
    };
  },
  watch: {
    dataSet() {
      this.page = this.dataSet.current_page;
      this.prevUrl = this.dataSet.prev_page_url;
      this.nextUrl = this.dataSet.next_page_url;
      this.loading = false;
    },

    page() {
      this.broadcast().updateUrl();
    }
  },
  computed: {
    shouldPaginate() {
      return !!this.prevUrl || !!this.nextUrl;
    }
  },
  methods: {
    broadcast() {
      return this.$emit("changed", this.page);
    },

    updateUrl() {
      history.pushState(null, null, "?page=" + this.page);
    },
    changePage(direction) {
      if (!this.loading) {
        this.loading = true;
        direction == "next" ? this.page++ : this.page--;
      }
    }
  }
};
</script>

<style>
    .pagination a {
        color : #739745!important;
    }
</style>