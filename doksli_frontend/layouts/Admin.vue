<template>
  <div>
    <v-app>
      <v-row>
        <v-col
          cols="2"
          class="btn-warnaku pr-0"
          style="background-color: #0c8ce9"
        >
          <div class="py-4 pl-4 pr-0">
            <div class="py-5 pl-5 pr-0 pt-8 rounded-lg" style="height: 100%">
              <v-img
                :src="require('~/assets/img/logo_02.png')"
                class="mb-15"
                :max-width="$vuetify.breakpoint.smAndDown ? '100' : '150'"
              >
              </v-img>
              <div v-for="(rute, i) in routersku" :key="i">
                <v-btn
                  text
                  depressed
                  @click="$router.push(rute.rt)"
                  width="100%"
                  style="
                    border-top-left-radius: 30px !important;
                    border-bottom-left-radius: 30px !important;
                  "
                  :class="[
                    'font-weight-medium rounded-0 rounded-ts-xl rounded-bs-xl d-flex align-items my-4 justify-start py-6 text-capitalize',
                    'sidebtn-' + i,
                    isaktif(rute.rt)
                      ? 'font-weight-medium white  color '
                      : 'font-weight-regular white--text',
                  ]"
                >
                  <v-icon
                    large
                    :color="isaktif(rute.rt) ? '#2f432d' : '#ffffff'"
                    class="mr-2"
                    >{{ rute.icon }}</v-icon
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
          <div class="container">
            <div class="d-flex align-center justify-end">
              <!-- <v-menu
                v-model="menu"
                :close-on-content-click="false"
                :nudge-width="200"
                offset-x
              >
                <template v-slot:activator="{ on, attrs }">
                  <v-btn
                    elevation="2"
                    fab
                    v-bind="attrs"
                    v-on="on"
                    color="#ffffff"
                    :small="$vuetify.breakpoint.smAndDown ? true : false"
                  >
                    <v-avatar
                      class="foto-profilan bg-greyku"
                      :size="$vuetify.breakpoint.smAndDown ? '35' : '50'"
                    >
                      <v-img
                        :src="
                          'http://127.0.0.1:8000/storage/' +
                          datauserLoginKu?.foto_profil
                        "
                      />
                    </v-avatar>
                  </v-btn>
                </template>
                <v-card tag="a" class="pa-4">
                  <div class="text-body-1 text-center mb-4 font-weight-medium">
                    Yakin ingin keluar?
                  </div>
                  <div class="d-flex justify-center">
                    <v-btn
                      class="text-capitalize px-8 text-body-1 font-weight-medium rounded-lg white--text"
                      color="#2f432d"
                      @click="logout"
                      >Logout</v-btn
                    >
                  </div>
                </v-card>
              </v-menu> -->
              <v-menu open-on-hover offset-y>
                <template v-slot:activator="{ on, attrs }">
                  <v-card
                    color="transparent"
                    flat
                    class="d-flex"
                    v-bind="attrs"
                    v-on="on"
                  >
                    <v-avatar class="foto-profilan mt-1" size="40">
                      <v-img
                        :src="
                          dataprofil.image
                            ? `http://127.0.0.1:8000/storage/${dataprofil.image}`
                            : require('@/assets/img/unknown.jpeg')
                        "
                      />
                    </v-avatar>
                  </v-card>
                </template>
                <v-list>
                  <v-list-item v-for="(menu, index) in menus" :key="index">
                    <v-list-item-icon>
                      <v-icon>{{ menu.icon }}</v-icon>
                    </v-list-item-icon>
                    <v-list-item-title
                      ><v-btn
                        class="text-capitalize"
                        plain
                        :ripple="false"
                        @click="handleMenuClick(menu)"
                      >
                        {{ menu.title }}
                      </v-btn>
                    </v-list-item-title>
                  </v-list-item>
                </v-list>
              </v-menu>
            </div>
          </div>
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
      datauserLoginKu: [],
      showMenu: false,
      fav: true,
      menu: false,
      message: false,
      hints: true,
      dataprofil: {
        name: null,
        email: null,
        phone: null,
      },
      userid: null,
      routersku: [
        {
          nama: 'Produk',
          rt: '/halaman-admin/management-produk',
          icon: 'mdi-store-plus',
        },
        {
          nama: 'User',
          rt: '/halaman-admin/management-user',
          icon: 'mdi-package-variant-closed-plus',
        },
        {
          nama: 'Log Aktivitas',
          rt: '/halaman-admin/management-logactivity',
          icon: 'mdi-store-check-outline',
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
    if (usid) {
      this.userid = usid.data?.data.id
      this.dataprofil = usid.data?.data
      console.log(usid)
    } else {
      this.userid = null
      this.dataprofil = {
        name: null,
        email: null,
        phone: null,
      }
    }
  },
}
</script>
  