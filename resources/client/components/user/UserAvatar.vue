<template>
  <span>
    <template v-if="avatar">
      <!-- ref="avatar" -->
      <!-- TODO это можно оптимизировать -->
      <img
        v-if="sizeName === 'lg'"
        :src="avatar"
        :alt="alt"
        :width="sizes.lg"
        :class="{['user-avatar__img user-avatar__img_large elevation-3 ' + imgClass]: true, 'user-avatar__img--border-bottom-reset': resetBorderBottomRadius}"
      />
      <img
        v-if="sizeName === 'md'"
        :src="avatar"
        :alt="alt"
        :width="sizes.md"
        :class="{['user-avatar__img user-avatar__img_medium elevation-3 ' + imgClass]: true, 'user-avatar__img--border-bottom-reset': resetBorderBottomRadius}"
      />
      <img
        v-if="sizeName === 'sm'"
        :src="avatar"
        :alt="alt"
        :width="sizes.sm"
        :class="{['user-avatar__img user-avatar__img_small elevation-3 ' + imgClass]: true, 'user-avatar__img--border-bottom-reset': resetBorderBottomRadius}"
      />
      <v-avatar v-if="sizeName === 'circle'" size="auto" :class="{['user-avatar__img user-avatar__img_circle ' + imgClass]: true, 'user-avatar__img--border-bottom-reset': resetBorderBottomRadius}">
        <img :src="avatar" class="elevation-3" alt="Аватар" />
      </v-avatar>
    </template>
    <v-icon v-else large>person</v-icon>
  </span>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  props: {
    user: Object,
    url: String,
    resetBorderBottomRadius: Boolean,
    imgClass: String,
    sizeName: {
      type: String,
      'default': 'circle'
    }
  },
  data: () => ({
    alt: 'Аватар',
    sizes: process.env.avatarSizes
  }),
  computed: {
    userForAvatar () {
      return this.user || this.$auth.user
    },
    avatar () {
      return this.url
        ? this.url
        : this.userForAvatar.avatar
          ? this.userAvatar(this.userForAvatar, this.sizeName)
          : null
    },
    ...mapGetters('user', ['userAvatar'])
  }
}
</script>
