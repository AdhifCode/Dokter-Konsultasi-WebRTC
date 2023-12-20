<template>
  <div>
    <v-footer v-bind="localAttrs" :padless="padless"
      ><div class="d-flex justify-space-between align-center">
        <div>
          <v-img
            :src="require('~/assets/img/Logo_02.png')"
            :max-width="$vuetify.breakpoint.smAndDown ? '100' : '200'"
          ></v-img>
        </div>
        <div class="d-flex align-center">
          <v-btn
            v-for="(Socials, index) in social"
            :key="index"
            class="mx-2"
            fab
            dark
            small
            color="primary"
          >
            <v-icon dark> {{ Socials.icons }} </v-icon>
          </v-btn>
        </div>
        <div>Nadhifal 2023 All Right Reserved</div>
      </div>
    </v-footer>
  </div>
</template>

<script>
export default {
  data() {
    return {
      social: [
        { icons: 'mdi-instagram', link: '' },
        { icons: 'mdi-github', link: '' },
        { icons: 'mdi-youtube', link: '' },
      ],
    }
  },
}
</script>
