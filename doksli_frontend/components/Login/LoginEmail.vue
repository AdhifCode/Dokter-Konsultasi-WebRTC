<template>
  <div>
    <v-btn elevation="0" @click="goBack"
      ><v-icon left> mdi-arrow-left </v-icon>Back</v-btn
    >
    <h1>Continue with your email</h1>
    <div class="pt-4 pb-2">
      <div>Email</div>
      <v-text-field
        class="rounded-lg"
        type="email"
        outlined
        v-model="email"
      ></v-text-field>
      <div>Password</div>
      <v-text-field
        outlined
        v-model="password"
        :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
        :type="showPassword ? 'text' : 'password'"
        name="input-10-2"
        class="input-group--focused rounded-lg"
        @click:append="showPassword = !showPassword"
      ></v-text-field>
      <p>Forgot password?</p>
    </div>
    <v-btn
      height="50"
      class="rounded-lg text-capitalize white--text"
      block
      color="#0C8CE9"
      depressed
      @click="continueWithEmail"
      >Continue</v-btn
    >
  </div>
</template>

<script>
export default {
  data() {
    return {
      email: 'admin@gmail.com',
      password: '12345678',
      showPassword: false,
    }
  },
  methods: {
    goBack() {
      this.$emit('switchToChoose')
    },
    async continueWithEmail() {
      try {
        const response = await this.$axios.post('/auth/login', {
          email: this.email,
          password: this.password,
        })

        if (response.status === 200) {
          console.log('Login successful')
          this.$cookies.set('loginCookie', 'Berhasil')
          this.$store.dispatch('users/login', response)
          console.log(response.data)
        } else {
          console.error('Login failed')
          console.error(response.data)
        }
      } catch (error) {
        console.error('An error occurred:', error)
      }
    },
  },
  computed: {
    loggedIn() {
      return this.$store.state.auth.loggedIn
    },
    user() {
      return this.$store.state.auth.user
    },
  },
}
</script>
