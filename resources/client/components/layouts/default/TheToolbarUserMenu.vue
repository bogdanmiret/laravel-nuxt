<template>
  <!-- :nudge-width="200" -->
  <!-- :nudge-left="195" -->

  <!-- attach для фиксированйо позиции при скроле -->
  <v-menu
    offset-y
    left
    dark
    attach
  >

    <template v-slot:activator="{ on }">
      <v-btn class="ml-3" v-on="on" text>
        <span class="pr-3" style="text-transform: none !important;">
          {{ $auth.user.nickname }}
        </span>

        <user-avatar size="124px"></user-avatar>
      </v-btn>
    </template>

    <v-card> 
      <v-list>
        <v-list-item :to="'/profile/' + $auth.user.nickname">
          <v-list-item-title>Profile</v-list-item-title>
        </v-list-item>

        <v-list-item to="/profile/settings">
          <v-list-item-title>Settings</v-list-item-title>
        </v-list-item>

        <v-list-item @click.prevent="$auth.logout()">
          <v-list-item-title>Logout</v-list-item-title>
        </v-list-item>
      </v-list>
    </v-card>
  </v-menu>
</template>

<script>
// import { mapGetters } from 'vuex'
import UserAvatar from '~/components/user/UserAvatar'
import { mapGetters } from 'vuex'

export default {
  components: { UserAvatar },
  computed: {
    ...mapGetters('user', ['userFullName'])
  }
  // computed: {
  //   ...mapGetters('user', ['userAvatar'])
  // }
}
</script>

<style scoped>
  #logo {
    cursor: pointer;
    /* background-color: white;*/
    /* padding: 3px; */
    margin-left: 30px;
    /* border-radius: 14px; */
  }
</style>
