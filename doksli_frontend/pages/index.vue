<template>
  <div>
    <NavbarLanding ref="navbarLanding" />
    <div class="LandingFirst">
      <v-container
        ><v-row>
          <v-col class="col-7" style="padding-top: 200px">
            <h2 style="font-size: 48px; color: white">
              Your Health, Our Priority. Expert Care at Your Fingertips with
              Online Doksli!
            </h2>
            <p style="font-size: 12px; color: white">
              Discover a new era of healthcare convenience with our cutting-edge
              website dedicated to expert medical consultations. At the click of
              a button, connect with qualified doctors who prioritize your
              well-being. Our user-friendly platform ensures seamless
              navigation, allowing you to seek medical advice and assistance
              from the comfort of your own space
            </p>
            <div class="pt-8">
              <v-btn
                class="text-capitalize white"
                width="140px"
                height="55px"
                @click="goToConsule()"
                style="border-radius: 15px; color: #0c8ce9"
                elevation="0"
                >Consule Now</v-btn
              >
            </div>

            <div class="d-flex pt-16" style="gap: 40px">
              <v-card
                v-for="(status, index) in statusItem"
                :key="index"
                outlined
                style="
                  border-radius: 15px;
                  box-shadow: 0 4px 8px rgba(12, 140, 233, 0.1);
                "
                width="125px"
                height="160px"
              >
                <v-card-actions class="justify-center my-1">
                  <v-btn
                    fab
                    depressed
                    :ripple="false"
                    dark
                    large
                    :color="status.iconBackground"
                  >
                    <v-icon dark x-large>{{ status.icon }}</v-icon>
                  </v-btn>
                </v-card-actions>
                <v-card-title
                  class="pa-0 justify-center"
                  :style="{ color: status.iconBackground }"
                >
                  {{ status.Counts }}
                </v-card-title>
                <v-card-title
                  class="pa-0 justify-center"
                  :style="{ color: status.iconBackground }"
                >
                  {{ status.title }}
                </v-card-title>
              </v-card>
            </div>
          </v-col>
          <v-col class="col-5 d-flex align-center">
            <v-img
              :src="require('@/assets/img/Doctor_1_Edited.png')"
              style="border-radius: 20px 0px 0px 20px"
            ></v-img>
          </v-col> </v-row
      ></v-container>
      <div class="custom-shape-divider-bottom-1701135255">
        <svg
          data-name="Layer 1"
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 1200 120"
          preserveAspectRatio="none"
        >
          <path
            d="M600,112.77C268.63,112.77,0,65.52,0,7.23V120H1200V7.23C1200,65.52,931.37,112.77,600,112.77Z"
            class="shape-fill"
            style="fill: #ffffff"
            height="269px"
          ></path>
        </svg>
      </div>
    </div>
    <div>
      <v-container
        ><v-row justify="center" align="center"
          ><v-col>
            <v-img
              :src="require('@/assets/img/Landing_Img_3.png')"
              height="645"
              width="440"
              style="border-radius: 20px 0px 0px 20px"
            >
            </v-img> </v-col
          ><v-col>
            <h1 style="font-size: 32px">
              Benefit from our online <br />consultation
            </h1>
            <div
              class="mt-4"
              v-for="(benefit, index) in benefitItems"
              :key="index"
            >
              <div class="d-flex">
                <v-btn
                  class="mx-2"
                  fab
                  dark
                  small
                  depressed
                  :color="benefit.color"
                  :ripple="false"
                >
                  <v-icon dark>{{ benefit.icon }}</v-icon>
                </v-btn>
                <div>
                  <h3>{{ benefit.title }}</h3>
                  <p>{{ benefit.text }}</p>
                </div>
              </div>
            </div>
          </v-col>

          <div></div></v-row
      ></v-container>
    </div>
    <div class="mt-6">
      <v-container
        ><div class="text-center">
          <h1>Our Popular Consults</h1>
          <p>Ini adalah doctor yang cocok, cocok oe hantamm</p>
        </div>
        <div class="d-flex justify-space-between">
          <div
            v-for="(cardItem, index) in top3"
            :key="index"
            class="custom-shadow"
            style="border-radius: 20px; box-shadow: 3px 3px 5px 1px #0c8ce9"
          >
            <v-card
              elevation="0"
              style="border-radius: 20px"
              width="360"
              height="auto"
            >
              <v-img
                v-if="$vuetify.breakpoint.xs"
                height="250px"
                style="display: none"
              ></v-img>

              <v-img
                v-else
                :src="`http://127.0.0.1:8000/storage/${cardItem.room_image}`"
                height="250px"
                style="border-radius: 20px"
              ></v-img>

              <v-card-actions>
                <v-list-item class="grow">
                  <v-list-item-avatar color="grey darken-3">
                    <v-img
                      :src="
                        cardItem.user_image
                          ? `http://127.0.0.1:8000/storage/${cardItem.user_image}`
                          : require('@/assets/img/unknown.jpeg')
                      "
                      class="elevation-6"
                      alt=""
                    ></v-img>
                  </v-list-item-avatar>

                  <v-list-item-content>
                    <v-list-item-title class="text-capitalize">{{
                      cardItem.name
                    }}</v-list-item-title>
                  </v-list-item-content>
                  <v-row align="center" justify="end">
                    <v-btn icon depressed color="red">
                      <v-icon>mdi-heart</v-icon>
                    </v-btn>
                    <span class="subheading mr-2">{{
                      cardItem.total_favorites
                    }}</span>
                  </v-row>
                </v-list-item>
              </v-card-actions>

              <v-card-title>{{ cardItem.title }}</v-card-title>

              <v-card-actions>
                <v-list-item class="grow">
                  <v-list-item-content>
                    <v-list-item-title>{{
                      cardItem.experience
                    }}</v-list-item-title>
                  </v-list-item-content>

                  <div
                    style="
                      background-color: #0c8ce9;
                      border-radius: 5px;
                      color: white;
                    "
                  >
                    <span
                      class="subheading mx-2 text-capitalize font-weight-medium"
                      >{{ cardItem.specialist }}</span
                    >
                  </div>
                </v-list-item>
              </v-card-actions>
            </v-card>
          </div>
        </div></v-container
      >
    </div>
    <div class="mt-10">
      <v-container
        ><v-row justify="center" align="center">
          <v-col>
            <h2>Become an specialist</h2>
            <p>
              Discover a new era of healthcare convenience with our cutting-edge
              website dedicated to expert medical consultations. At the click of
              a button, connect with qualified doctors who prioritize your
              well-being. Our user-friendly platform ensures seamless
              navigation, allowing you to seek medical advice and assistance
              from the comfort of your own space
            </p>
            <v-btn
              style="
                font-size: 14px;
                letter-spacing: 1px;
                border-radius: 10px;
                box-shadow: -2px 2px 4px 0px #0c8ce9;
              "
              class="text-capitalize white font-weight-bold"
              rounded-lg
              height="45px"
              @click="goToJoin()"
              >Click Here To Apply</v-btn
            >
          </v-col>
          <v-col
            ><v-img
              :src="require('@/assets/img/join.png')"
              height="auto"
              width="600"
              style="border-radius: 20px 0px 0px 20px"
            >
            </v-img>
          </v-col>
        </v-row>
      </v-container>
    </div>
    <Footer />
  </div>
</template>

<script>
import NavbarLanding from '../components/NavbarLanding.vue'
import Footer from '../components/Footer.vue'
import axios from 'axios'

export default {
  components: { NavbarLanding, Footer },
  // middleware: ['authenticated'],
  data() {
    return {
      top3: [],
      benefitItems: [
        {
          icon: 'mdi-phone',
          title: 'Convenience and Accessibility',
          text: 'Access healthcare from home, ideal for remote locations or those with mobility challenges.',
          color: '#31C9A2',
        },
        {
          icon: 'mdi-sun-clock-outline',
          title: 'Time Efficiency',
          text: 'Save time by eliminating travel and waiting, making consultations more efficient.',
          color: '#9675F7',
        },
        {
          icon: 'mdi-doctor',
          title: 'Enhanced Doctor-Patient Communication',
          text: 'Real-time video calls improve symptom understanding and enable personalized advice for better healthcare.',
          color: '#FF6B82',
        },
      ],
      statusItem: [],
      patientCount: 0,
      doctorCount: 0,
      orderCount: 0,
    }
  },
  methods: {
    getRoleCounts() {
      axios
        .get(`http://localhost:8000/api/user-role-counts`)
        .then((response) => {
          this.patientCount =
            response.data.role_counts.find((role) => role.role_id === 4)
              ?.count || 0
          this.doctorCount =
            response.data.role_counts.find((role) => role.role_id === 3)
              ?.count || 0

          this.statusItem = [
            {
              title: 'Doctors',
              Counts: this.doctorCount,
              icon: 'mdi-doctor',
              iconBackground: '#31C9A2',
            },
            {
              title: 'Patients',
              Counts: this.patientCount,
              icon: 'mdi-account-injury',
              iconBackground: '#FF6B82',
            },
            {
              title: 'Consults',
              Counts: this.orderCount,
              icon: 'mdi-information',
              iconBackground: '#9675F7',
            },
          ]

          console.log(this.orderCount)
        })
        .catch((error) => {
          console.error('Error fetching user role counts:', error)
        })
    },

    getOrderCount() {
      axios
        .get(`http://localhost:8000/api/total-order-count`)
        .then((response) => {
          console.log('API Response (getOrderCount):', response)
          this.orderCount = response.data.order_count
        })
        .catch((error) => {
          console.error('Error fetching total order count:', error)
        })
    },

    getPopularData() {
      axios
        .get(`http://localhost:8000/api/top3`)
        .then((response) => {
          console.log('API Response:', response)
          this.top3 = response.data?.top_rooms
        })
        .catch((error) => {
          console.error('Error fetching user data:', error)
        })
    },

    goToConsule() {
      const cookieExists = this.$cookies.get('loginCookie')
      if (!cookieExists) {
        const navbarLanding = this.$refs.navbarLanding
        if (navbarLanding) {
          navbarLanding.dialogLogin = true
        } else {
          console.error('NavbarLanding component not found.')
        }
      } else {
        this.$router.push('/MainPage')
      }
    },

    goToJoin() {
      const cookieExists = this.$cookies.get('loginCookie')
      if (!cookieExists) {
        const navbarLanding = this.$refs.navbarLanding
        if (navbarLanding) {
          navbarLanding.dialogLogin = true
        } else {
          console.error('NavbarLanding component not found.')
        }
      } else {
        this.$router.push('/join-doctor')
      }
    },
  },
  mounted() {
    this.getOrderCount()
    this.getRoleCounts()
    this.getPopularData()
  },
}
</script>

<style scoped>
.LandingFirst {
  background-color: #0c8ce9;
}

.custom-shape-divider-bottom-1701135255 {
  bottom: 0;
  left: 0;
  width: 174%;
  overflow: hidden;
  line-height: 0;
}
</style>
