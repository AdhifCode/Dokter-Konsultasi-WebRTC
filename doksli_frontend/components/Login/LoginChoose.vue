<template>
  <v-container style="padding-bottom: 0px">
    <template v-if="showLoginChoose">
      <div class="pt-8">
        <div class="pb-8">
          <h1>Create a new account?</h1>
          <h3>
            Already have an account?
            <a @click.prevent="switchToSignIn">Sign in</a>
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
                <v-icon left>mdi-cellphone</v-icon>
                <div>Continue with Phone</div>
              </v-btn>
            </div>

            <v-btn
              class="rounded-lg text-capitalize"
              outlined
              block
              @click="switchToEmail"
            >
              <v-icon left>mdi-email-outline</v-icon>Continue with Email
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
    <template v-if="showLoginPhone">
      <LoginPhone @switchToChoose="switchToChoose" />
    </template>
    <template v-if="showLoginEmail">
      <LoginEmail @switchToChoose="switchToChoose" />
    </template>
  </v-container>
</template>

<script>
import LoginPhone from '../Login/LoginPhone.vue'
import LoginEmail from '../Login/LoginEmail.vue'

export default {
  components: { LoginPhone, LoginEmail },
  name: 'IndexPage',
  data() {
    return {
      showLoginChoose: true,
      showLoginPhone: false,
      showLoginEmail: false,
    }
  },
  methods: {
    async socialLogin(provider) {
      window.location.href = `${process.env.baseUrl}/auth/${provider}`
    },

    switchToSignIn() {
      this.$emit('activateSignIn')
    },
    switchToPhone() {
      this.showLoginChoose = false
      this.showLoginPhone = true
      this.showLoginEmail = false
    },
    switchToEmail() {
      this.showLoginChoose = false
      this.showLoginPhone = false
      this.showLoginEmail = true
    },
    switchToChoose() {
      this.showLoginChoose = true
      this.showLoginPhone = false
      this.showLoginEmail = false
    },
  },
}
</script>
