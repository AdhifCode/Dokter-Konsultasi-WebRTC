<template>
  <div>
    <v-app>
      <v-row>
        <v-col
          cols="2"
          class="btn-warnaku pb-0 pr-0"
          style="background-color: #0c8ce9"
        >
          <div class="pt-8">
            <div class="d-flex flex-column align-center">
              <v-img
                :src="require('~/assets/img/logo_02.png')"
                class="mb-8"
                :max-width="$vuetify.breakpoint.smAndDown ? '75' : '100'"
              ></v-img>
              <v-card
                class="d-flex flex-column align-center"
                color="transparent"
                elevation="0"
              >
                <v-avatar class="foto-profilan" size="75">
                  <v-img
                    :src="
                      dataprofil.image
                        ? `http://127.0.0.1:8000/storage/${dataprofil.image}`
                        : require('@/assets/img/unknown.jpeg')
                    "
                    width="250"
                    height="auto"
                  ></v-img>
                </v-avatar>
                <v-card-title class="white--text">{{
                  dataprofil.name
                }}</v-card-title>
                <v-card-subtitle class="font-weight-regular white--text">
                  {{ displayRole(dataprofil.role_id) }}
                </v-card-subtitle>
              </v-card>
              <div v-for="(rute, i) in routersku" :key="i">
                <v-btn
                  text
                  depressed
                  @click="$router.push(rute.rt)"
                  width="100%"
                  plain
                  :ripple="false"
                  :class="[
                    'font-weight-medium d-flex align-items my-4 justify-start py-2 text-capitalize',
                    'sidebtn-' + i,
                    isaktif(rute.rt)
                      ? 'font-weight-medium white  color '
                      : 'font-weight-regular white--text',
                  ]"
                >
                  <div>
                    {{ rute.nama }}
                  </div>
                </v-btn>
              </div>
            </div>
          </div>
        </v-col>
        <v-col cols="10">
          <nuxt />
        </v-col>
      </v-row>
    </v-app>
  </div>
</template>
  
  <script>
export default {
  data() {
    return {
      showMenu: false,
      menu: false,
      dataprofil: {
        name: null,
        email: null,
        phone: null,
      },
      userid: null,
      routersku: [
        {
          nama: 'Dashboard',
          rt: '/admin',
        },
        {
          nama: 'User',
          rt: '/admin/manage-user',
        },
        {
          nama: 'Log Aktivitas',
          rt: '/admin/tes',
        },
      ],
      menus: [
        {
          title: 'Profile',
          icon: 'mdi-account',
        },
        {
          title: 'Log Out',
          icon: 'mdi-logout',
        },
      ],
    }
  },

  computed: {
    hasCookie() {
      if (process.client) {
        return document.cookie.includes('loginCookie=')
      }
      return false
    },
  },

  methods: {
    displayRole(roleId) {
      if (roleId === 1) {
        return 'Admin'
      } else if (roleId === 2) {
        return 'Operator'
      } else {
        return 'Unknown Role'
      }
    },
    logout() {
      this.$store.dispatch('users/logout')
    },
    isaktif(pathh) {
      return this.$route.path === pathh
    },
    logout() {
      this.$store.dispatch('users/logout')
    },
    handleMenuClick(menu) {
      if (menu.title === 'Log Out') {
        this.logout()
      } else {
        window.location.replace('/MainPage/action/profile')
      }
    },
    logout() {
      this.$store.dispatch('users/logout')
    },
  },
  created() {
    const usid = this.$cookies.get('loginCookie')
    this.userid = usid.data?.data.id
    this.dataprofil = usid.data?.data
  },
}
</script>
  