<template>
  <v-layout justify-center>
    <v-card style="border-radius: 20px;">
      <v-layout
        v-if="socAccounts"
        justify-center
        row
        wrap
      >
        <!-- md8 lg4 xl3 -->
        <v-flex class="px-2 pb-3" sm10>
          <!-- <v-card> -->

          <v-subheader>
            Прикрепленные аккаунты
          </v-subheader>

          <v-list v-if="$auth.user.socAccounts.length" two-line dense>
            <template v-for="(account, index) in $auth.user.socAccounts">
              <v-list-tile :key="index">
                <v-list-tile-avatar :style="'color: ' + account.color">
                  <font-awesome-icon :icon="['fab', account.icon]" size="3x" />
                </v-list-tile-avatar>
                <v-list-tile-content :style="'color: ' + account.color">
                  {{ account.name_for_human }}
                </v-list-tile-content>
                <v-list-tile-action>
                  <v-btn
                    v-if="$auth.user.password || (!$auth.user.password && $auth.user.socAccounts.length > 1)"
                    text
                    icon
                    large
                    @click="deleteSocAcc({ name: account.name, id: account.id })"
                  >
                    <v-icon color="red" large>delete_forever</v-icon>
                  </v-btn>
                </v-list-tile-action>
              </v-list-tile>
              <small class="grey--text ml-4" :key="index">
                Прикреплено {{ account.pivot.created_at | date }}
                ({{ $dayjs().to($dayjs(account.pivot.created_at)) }})
              </small>
              <v-divider v-if="index + 1 < $auth.user.socAccounts.length" :key="index" />
            </template>
          </v-list>

          <v-alert type="info" :value="!$auth.user.socAccounts.length">
            Нет прикрепленных.
          </v-alert>

          <!-- </v-card> -->
        </v-flex>
        <v-flex class="px-2" sm10>
          <!-- <v-card> -->

          <v-subheader>
            Можно прикрепить
          </v-subheader>

          <v-list v-if="notUsedSocAccount.length" two-line dense>
            <!-- :key="account.id" -->
            <template v-for="(account, index) in notUsedSocAccount">
              <v-list-tile :key="index">
                <v-list-tile-avatar :style="'color: ' + account.color">
                  <font-awesome-icon :icon="['fab', account.icon]" size="3x" />
                </v-list-tile-avatar>
                <v-list-tile-content :style="'color: ' + account.color">
                  {{ account.name_for_human }}
                </v-list-tile-content>
                <v-list-tile-action>
                  <v-btn
                    @click="saveSocAcc(account.name, index)"
                    :loading="saveSocAccBtnIndexForLoading === index"
                    text
                    icon
                    large
                  >
                    <v-icon color="green" large>add</v-icon>
                  </v-btn>
                </v-list-tile-action>
              </v-list-tile>
              <v-divider v-if="index + 1 < notUsedSocAccount.length"  :key="index" />
            </template>
          </v-list>

          <v-alert type="info" :value="!notUsedSocAccount.length">
            Все прикреплено.
          </v-alert>

        </v-flex>
      </v-layout>
    </v-card>
  </v-layout>
</template>

<script>
import FontAwesomeIcon from '@fortawesome/vue-fontawesome'
import { mapActions } from 'vuex'
import differenceBy from 'lodash/differenceBy'

export default {
  components: { FontAwesomeIcon },
  data: () => ({
    socAccounts: null,
    saveSocAccBtnIndexForLoading: null
  }),
  computed: {
    notUsedSocAccount () {
      return differenceBy(this.socAccounts, this.$auth.user.socAccounts, 'name')
      // return this.socAccounts.filter(i1 => !this.$auth.user.socAccounts.some(i2 => i2.name === i1.name))
    }
    // ...mapState('authSocialite', ['socAccounts'])
  },
  methods: {
    async saveSocAcc (name, index) {
      this.saveSocAccBtnIndexForLoading = index
      location.href = await this.getRedirectUrl(name)
      this.saveSocAccBtnIndexForLoading = null
    },
    ...mapActions(['getSocialiteProviders']),
    ...mapActions('authSocialite', [
      'deleteSocAcc', 'getRedirectUrl' // 'loginSocialite', 'handleSocialite',
    ])
  }
}
</script>
