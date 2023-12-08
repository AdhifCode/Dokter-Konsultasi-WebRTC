<template>
  <v-container
    ><v-dialog v-model="dialogLogin" width="875" style="margin: 0">
      <template v-slot:activator="{ on, attrs }">
        <v-btn color="red lighten-2" dark v-bind="attrs" v-on="on">
          Click Me
        </v-btn>
      </template>
      <LoginPage /> </v-dialog
  ></v-container>
</template>

<script>
import LoginPage from '../components/LoginPage.vue'

export default {
  components: { LoginPage },
  data() {
    return {
      dialogLogin: false,
    }
  },
  created() {
    setTimeout(() => {
      this.dialogLogin = true
    }, 2000)
  },
}
</script>