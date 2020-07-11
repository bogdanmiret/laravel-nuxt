<template>
    <v-app id="inspire"
           :class="{
      'application--have-auth-background': $route.path.includes('/auth'),
      'application--have-settings-background': $route.path.includes('profile/settings')
    }"
    >
        <v-navigation-drawer
            v-model="drawer"
            app
            :clipped="$vuetify.breakpoint.lgAndUp"
            temporary
            overflow
        >

            <v-list>
                <v-list-item
                    v-for="(item, i) in items"
                    v-if="!item.children"
                    :key="i"
                    :to="item.to"
                    router
                    exact
                >
                    <v-list-item-icon>
                        <v-icon>{{ item.icon }}</v-icon>
                    </v-list-item-icon>
                    <v-list-item-title v-text="item.title"/>
                </v-list-item>
                <v-list-group
                    v-else
                    :key="item.title"
                    v-model="item.model"
                    :prepend-icon="item.model ? item.icon : item['icon-alt']"
                    append-icon=""
                >
                    <template v-slot:activator>
                        <v-list-item-title>{{ item.title }}</v-list-item-title>
                    </template>
                    <v-list-item
                        v-for="(child, i) in item.children"
                        :key="i"
                        link
                    >
                        <v-list-item-title>
                            {{ child.title }}
                        </v-list-item-title>
                        <v-list-item-action v-if="child.icon">
                            <v-icon>{{ child.icon }}</v-icon>
                        </v-list-item-action>
                    </v-list-item>
                </v-list-group>
            </v-list>

        </v-navigation-drawer>

        <v-app-bar
            app
            clipped-left
            color="blue darken-3"
            dark
        >
            <v-app-bar-nav-icon @click.stop="drawer = !drawer"/>
            <v-toolbar-title
                style="width: 300px"
                class="ml-0 pl-4"
            >
                <span class="hidden-sm-and-down">Baronet</span>
            </v-toolbar-title>
            <v-text-field
                text
                solo-inverted
                hide-details
                prepend-inner-icon="mdi-magnify"
                label="Search"
                class="hidden-sm-and-down"
            />
            <v-spacer/>

            <template v-if="$auth.loggedIn && $auth.user">

                <!-- <the-toolbar-user-notifications /> -->

                <v-divider vertical inset/>

                <the-toolbar-user-menu/>

            </template>
            <v-toolbar-items v-else>
                <v-btn to="/auth/signin" text>
                    <!-- account_box -->
                    <!-- <v-icon left>person</v-icon> -->
                    <!-- <v-icon left>account_box</v-icon> -->
                    <!-- large -->
                    Sign in
                </v-btn>

                <v-divider vertical/>

                <v-btn to="/auth/signup" text>
                    Sign up
                </v-btn>
            </v-toolbar-items>

        </v-app-bar>

        <v-content>
            <v-container
                class="fill-height"
                fluid
            >
                <v-scale-transition>
                    <email-verification-alert v-show="!$route.path.includes('/profile/settings')" under-toolbar/>
                </v-scale-transition>
                <nuxt/>
            </v-container>
        </v-content>

        <v-footer app padless>

            <v-row
                justify="center"
                no-gutters
            >
                <v-btn
                    v-for="link in links"
                    :key="link"
                    color="white"
                    text
                    class="my-2"
                >
                    {{ link }}
                </v-btn>
                <v-col
                    class="primary py-4 text-center white--text"
                    cols="12"
                >
                    <span><strong>Bogdan Miret</strong> &copy; {{ new Date().getFullYear() }}</span>
                </v-col>
            </v-row>
        </v-footer>
    </v-app>
</template>

<script>
    import FontAwesomeIcon from '@fortawesome/vue-fontawesome'
    import helperScrollMixin from '~/mixins/helpers/scroll'
    import mixinlayoutElements from '~/mixins/helpers/layoutElements'
    import TheToolbarUserNotifications from '~/components/layouts/default/TheToolbarUserNotifications'
    import TheToolbarUserMenu from '~/components/layouts/default/TheToolbarUserMenu'
    import EmailVerificationAlert from '~/components/auth/EmailVerificationAlert'
    import {mapActions, mapGetters} from "vuex"

    export default {
        mixins: [helperScrollMixin, mixinlayoutElements],
        components: {TheToolbarUserNotifications, TheToolbarUserMenu, EmailVerificationAlert, FontAwesomeIcon},
        props: {
            source: String,
        },
        // asyncData: async ({ store }) => ({
        //     categories: await store.dispatch('getCategories')
        // }),
        data: () => ({
            drawer: null,
            items: [
                {
                    icon: 'mdi-apps',
                    title: 'Welcome',
                    to: '/'
                },
                {
                    icon: 'mdi-chart-bubble',
                    title: 'Inspire',
                    to: '/inspire'
                },
                {
                    icon: 'mdi-chevron-up',
                    'icon-alt': 'mdi-chevron-down',
                    title: 'More',
                    to: '/',
                    model: false,
                    children: [
                        {title: 'Import'},
                        {title: 'Export'},
                        {title: 'Print'},
                        {title: 'Undo changes'},
                        {title: 'Other contacts'},
                    ],
                }
            ],
            links: [
                'Home',
                'About Us',
                'Team',
                'Services',
                'Blog',
                'Contact Us',
            ],
        }),
        computed: {
          ...mapGetters({
            categories: 'categories'
          })
        },
        created() {
            this.$vuetify.theme.dark = true;

            this.$auth.$storage.watchState('loggedIn', newValue => {
                const token = this.$auth.getToken('local')

                this.$axios.setToken(token)
                // this.$axios.setHeader('Authorization', localStorage['auth._token.local'])

                // document.location.reload()
                // this.$axios.setToken(this.$auth.getToken(this.$auth.strategy.name))
                // console.log(1, localStorage['auth._token.local'])
            })

            // if (this.$auth.$state.redirect === null) {
            // {headers: {
            //   'Authorization': `Bearer ${this.$auth.getToken()}`
            // }}
            // console.log(2, localStorage['auth._token.local'])
            // this.$axios.setHeader('Authorization', localStorage['auth._token.local'])
            // this.$axios.setToken(this.$auth.getToken(this.$auth.strategy.name))
            // document.location.reload()
            // }
        },
    }
</script>
