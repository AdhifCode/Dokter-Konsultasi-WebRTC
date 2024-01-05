<template>
  <v-container>
    <template v-if="showLoginChoose">
      <div class="pt-8">
        <div class="pb-8">
          <h1>Create a new account?</h1>
          <h3>
            Already have an account?
            <a @click.prevent="switchToLogIn">Log In</a>
          </h3>
        </div>
        <div class="pt-8 pb-2">
          <div>
            <div class="pb-4">
              <v-btn
                class="rounded-lg text-capitalize"
                outlined
                block
                @click="switchToPhone"
              >
                <v-icon left>mdi-cellphone</v-icon>Join with Phone
              </v-btn>
            </div>

            <v-btn
              class="rounded-lg text-capitalize"
              outlined
              block
              @click="switchToEmail"
            >
              <v-icon left>mdi-email-outline</v-icon>Join with Email
            </v-btn>
          </div>

          <div class="pt-4 pb-4" style="text-align: center">OR</div>
          <div class="d-flex">
            <v-btn
              class="rounded-lg mr-2 text-capitalize"
              outlined
              style="width: 49%"
              @click="socialLogin('google')"
            >
              <v-icon left>mdi-google</v-icon>Google
            </v-btn>
            <v-btn
              class="rounded-lg text-capitalize"
              outlined
              style="width: 49%"
              @click="socialLogin('github')"
            >
              <v-icon left>mdi-github</v-icon>Github
            </v-btn>
          </div>
        </div>
        <p style="margin-top: 160px; margin-bottom: 0px">
          By joining, you agree to the Doksli
          <a href="#">Terms of Service</a> and to occasionally receive emails
          from us. Please read our Privacy Policy to learn how we use your
          personal data.
        </p>
      </div>
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
      window.location.href = `${process.env.baseUrl}/auth/${provider}`
    },

    switchToLogIn() {
      this.$emit('nonActiveSignIn')
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
  