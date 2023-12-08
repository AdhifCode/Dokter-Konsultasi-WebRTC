<template>
  <h1>Tunggu Sebentar {{ token }}</h1>
</template>

<script>
export default {
  data() {
    return {
      token: this.$route.query.token ? this.$route.query.token : null,
    }
  },
  methods: {
    async handleLogin() {
      try {
        const response = await this.$auth.loginWithToken(this.token)
        this.$store.commit('SET_AUTH', {
          loggedIn: true,
          user: response.data.data.user,
          token: response.data.token,
        })
        // const cookies = new Cookies()
        // cookies.set('authToken', response.data.token)
      } catch (error) {
        return this.$router.push(
          '/?error=Your token appeared to be invalid, please try again'
        )
      }
    },
  },
  mounted() {
    this.handleLogin()
  },
}
</script>
