<template>
  <div>
    <v-btn plain @click="goBack"
      ><v-icon left> mdi-arrow-left </v-icon>Back</v-btn
    >
    <h1>Continue with your email or username</h1>
    <div class="pt-4 pb-2">
      <div>Username</div>
      <v-text-field class="rounded-lg" outlined v-model="name"></v-text-field>
      <div>Email</div>
      <v-text-field
        class="rounded-lg"
        outlined
        v-model="email"
        type="email"
      ></v-text-field>
      <div>Password</div>
      <v-text-field
        class="rounded-lg"
        outlined
        v-model="password"
        :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
        :type="showPassword ? 'text' : 'password'"
        @click:append="showPassword = !showPassword"
      ></v-text-field>
    </div>
    <v-btn
      height="50"
      class="rounded-lg text-capitalize white--text"
      block
      color="#0C8CE9"
      depressed
      @click="registNow"
      >Register</v-btn
    >
  </div>
</template>

<script>
export default {
  data() {
    return {
      name: '',
      email: '',
      password: '',
      showPassword: false,
    }
  },
  methods: {
    goBack() {
      this.$emit('switchToChoose')
    },
    async registNow() {
      try {
        const response = await this.$axios.post('/register', {
          name: this.name,
          email: this.email,
          password: this.password,
          password_confirmation: this.password,
        })

        console.log(response)

        if (response.status === 201) {
          console.log('Register successful')
          this.$cookies.set('loginCookie', 'Berhasil')
          this.$store.dispatch('users/login', response)
          console.log(response.data)
        } else {
          console.error('Register failed')
          console.error(response.data)
        }
      } catch (error) {
        console.error('An error occurred:', error)
      }
    },
  },
}
</script>
