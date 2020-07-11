<template>
  <div :class="{'current-user-profile__info elevation-1': true, 'current-user-profile__info_sticky': $vuetify.breakpoint.mdAndUp }">
    <v-list style="border-radius: 5px;" two-line dense>

      <v-list-tile>
        <v-list-tile-avatar>
           <v-icon color="indigo">access_time</v-icon>
         </v-list-tile-avatar>
        <v-list-tile-content>
          <v-list-tile-title class="indigo--text">Заренестрирован</v-list-tile-title>
          <v-list-tile-sub-title>
            <time>{{ new Date(user.createdAt).toLocaleDateString() }}</time>
          </v-list-tile-sub-title>
        </v-list-tile-content>
      </v-list-tile>

      <v-list-tile>
        <v-list-tile-avatar></v-list-tile-avatar>
        <v-list-tile-content>
          <v-list-tile-title>Email</v-list-tile-title>
          <v-list-tile-sub-title>{{ user.email }}</v-list-tile-sub-title>
        </v-list-tile-content>
      </v-list-tile>

    </v-list>
  </div>
</template>

<script>
import { getFlag } from '~/tools/helpers'

export default {
  props: ['user'],
  computed: {
  },
  methods: {
    getFlag
  }
}
</script>

<style>
  .current-user-profile__info_sticky {
    position: sticky;
    z-index: 1;
    /* 230 */
    top: 100px;
  }
</style>
<!-- <no-ssr>
  <table style="width: 100%;">
    <tr>
      <td>
        <v-icon class="pr-2">person</v-icon>
        <b>Имя:</b>
      </td>
      <td class="text-xs-right">{{ user.fullName || 'не указано' }}</td>
    </tr>
    <tr>
      <td>
        <v-icon class="pr-2">access_time</v-icon>
        <b>Заренестрирован:</b>
      </td>
      <td class="text-xs-right"><time>{{ new Date(user.createdAt).toLocaleDateString() }}</time></td>
    </tr>
    <tr>
      <td>
        <v-icon class="pr-2">contact_mail</v-icon>
        <b>Эл. адреса:</b>
      </td>
      <td class="text-xs-right">
        todo
      </td>
    </tr>
  </table>
</no-ssr> -->
