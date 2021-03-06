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
      <v-list dense>
        <template v-for="item in items">
          <v-row
            v-if="item.heading"
            :key="item.heading"
            align="center"
          >
            <v-col cols="6">
              <v-subheader v-if="item.heading">
                {{ item.heading }}
              </v-subheader>
            </v-col>
            <v-col
              cols="6"
              class="text-center"
            >
              <a
                href="#!"
                class="body-2 black--text"
              >EDIT</a>
            </v-col>
          </v-row>
          <v-list-group
            v-else-if="item.children"
            :key="item.text"
            v-model="item.model"
            :prepend-icon="item.model ? item.icon : item['icon-alt']"
            append-icon=""
          >
            <template v-slot:activator>
              <v-list-item-content>
                <v-list-item-title>
                  {{ item.text }}
                </v-list-item-title>
              </v-list-item-content>
            </template>
            <v-list-item
              v-for="(child, i) in item.children"
              :key="i"
              link
            >
              <v-list-item-action v-if="child.icon">
                <v-icon>{{ child.icon }}</v-icon>
              </v-list-item-action>
              <v-list-item-content>
                <v-list-item-title>
                  {{ child.text }}
                </v-list-item-title>
              </v-list-item-content>
            </v-list-item>
          </v-list-group>
          <v-list-item
            v-else
            :key="item.text"
            link
          >
            <v-list-item-action>
              <v-icon>{{ item.icon }}</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <v-list-item-title>
                {{ item.text }}
              </v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </template>
      </v-list>
    </v-navigation-drawer>

    <v-app-bar
      app
      clipped-left
      color="blue darken-3"
      dark
    >
      <v-app-bar-nav-icon @click.stop="drawer = !drawer" />
      <v-toolbar-title
        style="width: 300px"
        class="ml-0 pl-4"
      >
        <span class="hidden-sm-and-down">Baronet</span>
      </v-toolbar-title>
            <v-text-field
        flat
        solo-inverted
        hide-details
        prepend-inner-icon="mdi-magnify"
        label="Search"
        class="hidden-sm-and-down"
      />
      <v-spacer />
      <v-btn icon>
        <v-icon>mdi-apps</v-icon>
      </v-btn>
      <v-btn icon>
        <v-icon>mdi-bell</v-icon>
      </v-btn>
      <v-btn
        icon
        large
      >
        <v-avatar
          size="32px"
          item
        >
          <v-img
            src="https://cdn.vuetifyjs.com/images/logos/logo.svg"
            alt="Vuetify"
          /></v-avatar>
      </v-btn>
    </v-app-bar>

    <v-content>
      <v-container
        class="fill-height"
        fluid
      >
        <slot></slot>
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
    export default {
        props: {
            source: String,
        },
        data: () => ({
            drawer: null,
            items: [
              { icon: 'mdi-contacts', text: 'Contacts' },
              { icon: 'mdi-history', text: 'Frequently contacted' },
              { icon: 'mdi-content-copy', text: 'Duplicates' },
              {
                icon: 'mdi-chevron-up',
                'icon-alt': 'mdi-chevron-down',
                text: 'Labels',
                model: true,
                children: [
                  { icon: 'mdi-plus', text: 'Create label' },
                ],
              },
              {
                icon: 'mdi-chevron-up',
                'icon-alt': 'mdi-chevron-down',
                text: 'More',
                model: false,
                children: [
                  { text: 'Import' },
                  { text: 'Export' },
                  { text: 'Print' },
                  { text: 'Undo changes' },
                  { text: 'Other contacts' },
                ],
              },
              { icon: 'mdi-settings', text: 'Settings' },
              { icon: 'mdi-message', text: 'Send feedback' },
              { icon: 'mdi-help-circle', text: 'Help' },
              { icon: 'mdi-cellphone-link', text: 'App downloads' },
              { icon: 'mdi-keyboard', text: 'Go to the old version' },
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
        created () {
            this.$vuetify.theme.dark = true
        },
    }
</script>