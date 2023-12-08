<template>
  <div>
    <v-btn plain @click="goBack"
      ><v-icon left> mdi-arrow-left </v-icon>Back</v-btn
    >
    <h1>Continue with your email or username</h1>
    <div>
      <v-text-field v-model="name" label="Username"></v-text-field>
      <v-text-field v-model="email" label="Email"></v-text-field>
      <v-text-field
        v-model="password"
        label="Password"
        type="password"
      ></v-text-field>
      <v-text-field
        v-model="passwordConfirmation"
        label="Confirm Password"
        type="password"
      ></v-text-field>
      <p>Forgot password?</p>
    </div>
    <v-btn @click="registNow">Register</v-btn>
  </div>
</template>

<script>
export default {
  data() {
    return {
      name: '',
      email: '',
      password: '',
      passwordConfirmation: '',
    }
  },
  methods: {
    goBack() {
      this.$emit('switchToChoose')
    },
    async registNow() {
      try {
        if (this.password !== this.passwordConfirmation) {
          console.error('Password and Confirmation do not match')
          return
        }
        await this.$axios.post('/register', {
          name: this.name,
          email: this.email,
          password: this.password,
          password_confirmation: this.passwordConfirmation,
        })
      } catch (e) {
        return
      }
      // console.log(response.data)
    },
  },
}
</script>
