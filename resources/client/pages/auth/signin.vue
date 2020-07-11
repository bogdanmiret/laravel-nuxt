<template>
  <v-container fill-height>
    <v-layout fill-height justify-center align-center>
      <v-flex xs12 sm8 md6 lg4 xl3>
        <v-card class="elevation-10 app-border-all-round">
          <v-toolbar flat prominent>
            <v-layout class="display-1" justify-center>
              Sign in
            </v-layout>
          </v-toolbar>
          <v-card-text>
            <!-- <div class="display-1 text-xs-center mb-3">
              Вход
            </div> -->

            <v-alert type="error" :value="$auth.$state.redirect">
              To visit <b>{{ $auth.$state.redirect }}</b> You need to login.
            </v-alert>

            <!-- <v-alert :value="error" type="error">
              {{ error }}
            </v-alert> -->

            <form @submit.prevent="submit" @keydown.enter="submit" autocomplete="on">
              <!-- <v-layout align-center justify-center> -->
                <v-radio-group v-model="emailOrNickname" row>
                  <v-layout justify-space-around>
                    <v-radio label="Via mail" value="email" class="mr-0" />
                    <v-radio label="Via nickname" value="nickname" class="mr-0" />
                  </v-layout>
                </v-radio-group>
              <!-- </v-layout> -->

              <v-text-field
                v-show="emailOrNickname === 'email'"
                v-model="form.email"
                type="email"
                label="Email"
                :error-messages="errors.collect('email')"
                v-validate="'required'"
                data-vv-name="email"
                required
                filled
              />

              <v-text-field
                v-show="emailOrNickname === 'nickname'"
                v-model="form.nickname"
                :error-messages="errors.collect('nickname')"
                data-vv-name="nickname"
                label="Nickname"
                required
                filled
              />

              <v-text-field
                v-model="form.password"
                :error-messages="errors.collect('password')"
                :type="passwordShow ? 'text' : 'password'"
                :append-icon="passwordShow ? 'mdi-eye-off' : 'mdi-eye'"
                @click:append="passwordShow = !passwordShow"
                data-vv-name="password"
                label="Password"
                required
                filled
              />

              <!-- <v-checkbox
                v-model="form.remember"
                label="Запомнить меня"
                type="checkbox"
              ></v-checkbox> -->
            </form>

          </v-card-text>
          <v-card-actions class="px-3 pb-3">

            <!-- class="ml-3" -->
            <!-- <v-spacer /> -->

            <v-btn
              @click="submit"
              :loading="loadingBtn"
              :disabled="btnDisabled"
              color="primary"
              large block
            >
              Submit
              <!-- vpn_key -->
            </v-btn>
          </v-card-actions>

          <div class="text-xs-center pb-3">
            <nuxt-link to="/auth/forgot-password">
              Forgot your password?
            </nuxt-link>

            <span class="mx-3"></span>

            <nuxt-link to="/auth/signup">
             Sign up
            </nuxt-link>
          </div>

        </v-card>

      </v-flex>
    </v-layout>
  </v-container>
</template>

<script>
import { mapActions } from 'vuex'
import socialiteButtons from '~/components/auth/SocialiteButtons'
import AppHrText from '~/components/AppHrText'

export default {
  middleware: 'guest',
  mixins: [],
  components: { socialiteButtons, AppHrText },
  data: () => ({
    form: {
      email: 'miretbogdan@gmail.com',
      nickname: 'bogdan',
      password: 'bogdan'
    },
    emailOrNickname: 'email',
    passwordShow: false,
    loadingBtn: false
    // error: false
  }),
  methods: {
    async submit () {
      const data = {
        password: this.form.password,
        [this.emailOrNickname]: this.form[this.emailOrNickname]
      }

      this.loadingBtn = true;

      await this.signin({ data });

      // try {
      //   let response = await this.$auth.loginWith('local', { data: data });
      //   // this.$auth.setUser(response.data.user);
      //   console.log(this.$auth.user);
      //   this.$router.push('/profile/' + this.$auth.user.nickname);
      // } catch (err) {
      //   console.log(err);
      // }

      this.loadingBtn = false;
    },
    ...mapActions('auth', [
      'signin'
    ])
  },
  computed: {
    formErrors () {
      return this.errors.items.filter(i => i.field === this.emailOrNickname || i.field === 'password')
    },
    btnDisabled () {
      return !!this.formErrors.length
    }
  },
  head () {
    return {
      title: 'Страница входа',
      meta: [
        { content: 'Это страница входа', name: 'description', hid: 'description' }
      ]
    }
  }
}
</script>
