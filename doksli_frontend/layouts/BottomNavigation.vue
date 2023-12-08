<template>
  <v-container>
    <div>
      <nuxt />
    </div>

    <v-bottom-navigation
      elevation="6"
      fixed
      style="border-radius: 25px"
      max-width="240px"
      v-model="value"
      :input-value="ButtonActive"
    >
      <v-btn
        v-for="(item, index) in navigationItems"
        :key="index"
        class="d-flex"
        :ripple="false"
        plain
        :color="color"
        dark
        @click="pushing(item.slug)"
        style="justify-content: center"
      >
        <v-icon
          v-if="
            !['.jpg', '.png', '.jpeg'].some((ext) => item.icon.endsWith(ext))
          "
        >
          {{ item.icon }}
        </v-icon>
        <div v-else>
          <v-avatar width="32px" height="32px">
            <img
              v-if="
                item.icon !== null &&
                item.icon !== undefined &&
                item.icon !== ''
              "
              :src="require(`~/assets/img/${item.icon}`)"
              alt="John"
              style="object-fit: cover"
            />
            <img
              v-else
              :src="require(`~/assets/img/unknown.jpeg`)"
              alt="John"
              style="object-fit: cover"
            />
          </v-avatar>
        </div>
      </v-btn>
    </v-bottom-navigation>
  </v-container>
</template>

<script>
export default {
  data: () => ({
    value: 1,
    ButtonActive: true,
    navigationItems: [
      { icon: 'mdi-account', slug: '/schedule' },
      { icon: 'mdi-magnify', slug: '/search' },
      { icon: 'profile.jpg', slug: '/profile' },
    ],
  }),
  methods: {
    pushing(data) {
      this.$router.push('/MainPage/action' + data)
    },
  },

  computed: {
    color() {
      switch (this.value) {
        case 0:
          return '#FF6B82'
        case 1:
          return '#31C9A2'
        default:
          return '#0C8CE9'
      }
    },
  },
}
</script>