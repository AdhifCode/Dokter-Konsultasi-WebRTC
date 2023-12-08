<template>
  <v-container>
    <v-btn icon @click="goBack" :ripple="false">
      <v-icon left> mdi-arrow-left </v-icon>
    </v-btn>
    <div><v-img :src="'@/assets/img/profile.jpg'"></v-img></div>
  </v-container>
</template>

<script>
export default {
  // layout: 'BottomNavigation' ,
  data() {
    return {}
  },
  methods: {
    goBack() {
      this.$router.push('../home')
    },
  },
}
</script>