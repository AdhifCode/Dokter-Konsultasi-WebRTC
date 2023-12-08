<template>
  <div>
    <v-btn plain @click="goBack"
      ><v-icon left> mdi-arrow-left </v-icon>Back</v-btn
    >
    <vue-tel-input-vuetify
      block
      class="rounded-lg"
      v-model="phone"
      clearable
      outlined
      solo
      :maxLen="15"
      defaultCountry="ID"
      :onlyCountries="['ID', 'MY', 'SG']"
    ></vue-tel-input-vuetify>
    <div><strong>Nilai Phone:</strong> {{ phone }}</div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      phone: '',
    }
  },
  methods: {
    goBack() {
      this.$emit('switchToChoose')
    },
  },
}
</script>
