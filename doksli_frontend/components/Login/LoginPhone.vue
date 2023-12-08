<template>
  <div style="width: 400px">
    <v-btn plain @click="goBack">
      <v-icon left> mdi-arrow-left </v-icon>Back
    </v-btn>
    <h1>Continue with your phone</h1>
    <div>
      <div>Your username</div>
      <v-text-field
        class="rounded-lg"
        outlined
        v-model="emailOrUsername"
      ></v-text-field>
      <div>Phone</div>
      <vue-tel-input-vuetify
        block
        class="rounded-lg"
        v-model="myPhone"
        clearable
        outlined
        solo
        @input="onInput"
        :valid-characters-only="true"
        :max-len="getMaxLenForCountry()"
        defaultCountry="ID"
        :onlyCountries="['ID', 'MY', 'SG']"
        label=""
        placeholder=""
      ></vue-tel-input-vuetify>
      <v-btn
        height="50"
        class="rounded-lg text-capitalize white--text"
        block
        color="#0C8CE9"
        depressed
        @click="continueWithEmail"
        >Continue</v-btn
      >
    </div>

    <!-- <div>
      <div v-if="phone.number" style="color: #e83e8c">
        <span>
          Number:
          <strong>{{ phone.number }}</strong
          >,&nbsp;
        </span>
        <span>
          Is valid:
          <strong>{{ phone.valid }}</strong
          >,&nbsp;
        </span>
        <span>
          Country:
          <strong>{{ phone.country }}</strong>
        </span>
      </div>
    </div> -->
  </div>
</template>

<script>
export default {
  data() {
    return {
      myPhone: '',
      phone: {
        number: '',
        valid: false,
        country: undefined,
      },
      phoneInputAttrs: {
        inputmode: 'numeric',
      },
      numberRule: (val) => {
        if (val < 0) return 'Please enter a positive number'
        return true
      },
    }
  },
  methods: {
    goBack() {
      this.$emit('switchToChoose')
    },
    onInput(formattedNumber, { number, valid, country }) {
      this.phone.number = number.international
      this.phone.valid = valid
      this.phone.country = country && country.name
    },
    getMaxLenForCountry() {
      const selectedCountry = this.phone.country
      if (selectedCountry === 'Indonesia') {
        return 14
      } else if (selectedCountry === 'Malaysia') {
        return 10
      } else if (selectedCountry === 'Singapore') {
        return 8
      }
      return 15
    },
  },
}
</script>
