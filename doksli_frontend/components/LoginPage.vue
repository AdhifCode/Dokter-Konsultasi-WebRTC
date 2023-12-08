<template>
  <v-card
    class="d-flex"
    style="width: 875px; height: 645px; border-radius: 20px"
  >
    <div style="padding: 0">
      <v-img
        :src="require('@/assets/img/LoginPage_img.jpeg')"
        height="645"
        width="440"
        style="border-radius: 20px 0px 0px 20px"
      >
      </v-img>
    </div>
    <div class="ma-2">
      <template>
        <LoginChoose @activateSignIn="activateSignIn" />
      </template>
      <template>
        <SignInChoose v-if="showSignIn" />
      </template>
    </div>
  </v-card>
</template>

<script>
import LoginChoose from './Login/LoginChoose.vue'
import SignInChoose from './SignIn/SignInChoose.vue'

export default {
  components: { LoginChoose, SignInChoose },
  name: 'IndexPage',
  data() {
    return {
      showSignIn: false,
    }
  },
  methods: {
    activateSignIn() {
      this.showSignIn = true
    },
  },
}
</script>

<style scoped>
</style>
