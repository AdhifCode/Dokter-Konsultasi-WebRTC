<template>
  <v-container>
    <v-btn icon @click="goBack" :ripple="false">
      <v-icon left> mdi-arrow-left </v-icon>
    </v-btn>
    <div class="d-flex">
      <v-avatar height="200px" width="200px">
        <img
          :src="require('@/assets/img/profile.jpg')"
          alt="John"
          style="object-fit: cover"
        />
      </v-avatar>
      <div>
        <div>Hallo</div>
        <div>Dokter Kejang</div>
        <div class="d-flex"><v-icon>mdi-briefcase</v-icon>4 Tahun</div>
      </div>
    </div>
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