<template>
  <div>
    <v-dialog v-model="dialogLogin" width="875" style="margin: 0">
      <LoginPage />
    </v-dialog>
    <v-app-bar
      app
      :style="{ backgroundColor: navBarBackgroundColor }"
      :elevation="navBarElevation"
    >
      <v-container class="d-flex justify-space-between align-center">
        <v-img
          :src="currentLogo"
          :max-width="$vuetify.breakpoint.smAndDown ? '100' : '200'"
        ></v-img>
        <div class="d-flex align-center">
          <v-btn
            v-for="(item, index) in items"
            :key="index"
            class="text-capitalize"
            plain
            :ripple="false"
            >{{ item.title }}</v-btn
          >
        </div>
        <div v-if="!hasCookie">
          <v-btn
            color="blue lighten-2"
            dark
            v-bind="attrs"
            v-on="on"
            @click="dialogLogin = true"
            class="text-capitalize"
            >Login
          </v-btn>
        </div>
        <div v-else>
          <v-menu open-on-hover offset-y>
            <template v-slot:activator="{ on, attrs }">
              <v-card
                color="transparent"
                flat
                class="d-flex"
                v-bind="attrs"
                v-on="on"
              >
                <div class="ma-4">{{ dataprofil.name }}</div>
                <v-avatar
                  class="foto-profilan mt-1"
                  :size="$vuetify.breakpoint.smAndDown ? '35' : '50'"
                >
                  <v-img :src="require('@/assets/img/profile.jpg')" />
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
      </v-container>
    </v-app-bar>
  </div>
</template>

<script>
import LoginPage from '../components/LoginPage.vue'

export default {
  components: { LoginPage },
  data() {
    return {
      dialogLogin: false,
      items: [
        {
          title: 'Home',
          link: '',
        },
        {
          title: 'Benefit',
          link: '',
        },
        {
          title: 'Became A Doctor',
          link: '',
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
      dataprofil: {
        name: null,
        email: null,
        phone: null,
      },
      userid: null,
      navBarBackgroundColor: 'transparent',
      currentLogo: require('~/assets/img/Logo_02.png'),
      navBarElevation: 0,
    }
  },
  methods: {
    handleScroll() {
      const scrollPosition =
        window.scrollY || document.documentElement.scrollTop

      const newBackgroundColor = scrollPosition > 100 ? '#fff' : 'transparent'
      const newLogo =
        scrollPosition > 100
          ? require('~/assets/img/Logo_01.png')
          : require('~/assets/img/Logo_02.png')

      const newElevation = scrollPosition > 100 ? 3 : 0
      this.navBarBackgroundColor = newBackgroundColor
      this.currentLogo = newLogo
      this.navBarElevation = newElevation
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
  computed: {
    hasCookie() {
      if (process.client) {
        return document.cookie.includes('loginCookie=')
      }
      return false
    },
  },
  mounted() {
    window.addEventListener('scroll', this.handleScroll)
    setTimeout(() => {
      if (!this.hasCookie) {
        this.dialogLogin = true
      }
    }, 2000)
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
    // this.cekuserrole = usid.role_id
  },
  getuser() {
    axios
      .get('http://127.0.0.1:8000/api/getuserlogin/' + this.userid)
      .then((respon) => {
        this.dataprofil = respon.data?.data
      })
  },
}
</script>
