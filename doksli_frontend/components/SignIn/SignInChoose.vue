<template>
  <v-container>
    <template v-if="showLoginChoose">
      <h1>Create a new account?</h1>
      <h3>
        Already have an account?
        <a href="#">Log In</a>
      </h3>
      <div>
        <v-btn class="rounded-lg" outlined block @click="switchToPhone">
          <v-icon left>mdi-cellphone</v-icon>Continue with Phone
        </v-btn>
        <v-btn class="rounded-lg" outlined block @click="switchToEmail">
          <v-icon left>mdi-email-outline</v-icon>Continue with Email
        </v-btn>
      </div>
      <div>OR</div>
      <div class="d-flex">
        <v-btn
          class="rounded-lg mr-2"
          outlined
          style="width: 49%"
          @click="socialLogin('google')"
        >
          <v-icon left>mdi-google</v-icon>Google
        </v-btn>
        <v-btn
          class="rounded-lg"
          outlined
          style="width: 49%"
          @click="socialLogin('github')"
        >
          <v-icon left>mdi-github</v-icon>Github
        </v-btn>
      </div>
      <p>
        By joining, you agree to the Doksli
        <a href="#">Terms of Service</a> and to occasionally receive emails from
        us. Please read our Privacy Policy to learn how we use your personal
        data.
      </p>
    </template>
    <template v-if="showSignInPhone">
      <SignInPhone @switchToChoose="switchToChoose" />
    </template>
    <template v-if="showSignInEmail">
      <SignInEmail @switchToChoose="switchToChoose" />
    </template>
  </v-container>
</template>
  
  <script>
import SignInPhone from '../SignIn/SignInPhone.vue'
import SignInEmail from '../SignIn/SignInEmail.vue'

export default {
  components: { SignInPhone, SignInEmail },
  name: 'IndexPage',
  data() {
    return {
      showLoginChoose: true,
      showSignInPhone: false,
      showSignInEmail: false,
    }
  },
  methods: {
    async socialLogin(provider) {
      window.location.href = `${process.env.BASE_API_URL}/auth/${provider}`
    },

    switchToPhone() {
      this.showLoginChoose = false
      this.showSignInPhone = true
      this.showSignInEmail = false
    },
    switchToEmail() {
      this.showLoginChoose = false
      this.showSignInPhone = false
      this.showSignInEmail = true
    },
    switchToChoose() {
      this.showLoginChoose = true
      this.showSignInPhone = false
      this.showSignInEmail = false
    },
  },
}
</script>
  