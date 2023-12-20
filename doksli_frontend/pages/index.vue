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
                style="border-radius: 15px"
                width="125px"
                height="150px"
              >
                <v-card-actions class="justify-center">
                  <v-btn
                    fab
                    depressed
                    :ripple="false"
                    dark
                    large
                    :color="status.iconBackground"
                    ><v-icon dark x-large>{{ status.icon }}</v-icon></v-btn
                  ></v-card-actions
                ><v-card-title
                  class="pa-0 justify-center"
                  :style="{ color: status.iconBackground }"
                  >{{ status.Counts }}</v-card-title
                ><v-card-title
                  class="pa-1 justify-center"
                  :style="{ color: status.iconBackground }"
                  >{{ status.title }}</v-card-title
                ></v-card
              >
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
        ><v-row
          ><v-col>
            <v-img
              :src="require('@/assets/img/Landing_Img_3.png')"
              height="645"
              width="440"
              style="border-radius: 20px 0px 0px 20px"
            >
            </v-img> </v-col
          ><v-col
            ><h1 style="font-size: 32px">
              Benefit from our online <br />consule
            </h1>
            <div>
              <div class="d-flex">
                <div>
                  <v-icon>mdi-phone</v-icon>
                </div>
                <div>
                  <h3>Online Consultation</h3>
                  <p>Due tige empat lime, lu olang nampak macam buah delima</p>
                </div>
              </div>
              <div class="d-flex">
                <div>
                  <v-icon>mdi-phone</v-icon>
                </div>
                <div>
                  <h3>Short article</h3>
                  <p>Due tige empat lime, lu olang nampak macam buah delima</p>
                </div>
              </div>
              <div class="d-flex">
                <div>
                  <v-icon>mdi-phone</v-icon>
                </div>
                <div>
                  <h3>Consultate with experts</h3>
                  <p>Due tige empat lime, lu olang nampak macam buah delima</p>
                </div>
              </div>
            </div></v-col
          >
          <div></div></v-row
      ></v-container>
    </div>
    <div>
      <v-container
        ><div class="text-center">
          <h1>Our Popular Consultate</h1>
          <p>Ini adalah doctor yang cocok, cocok oe hantamm</p>
        </div>
        <div class="d-flex justify-space-between">
          <div>
            <v-card
              style="border-radius: 20px"
              elevation="4"
              width="360"
              height="360"
              ><v-img
                :src="require('@/assets/img/Landing_Orthopedi.jpg')"
                height="200px"
              ></v-img
              ><v-card-actions>
                <v-list-item class="grow">
                  <v-list-item-avatar color="grey darken-3">
                    <v-img
                      class="elevation-6"
                      alt=""
                      src="https://avataaars.io/?avatarStyle=Transparent&topType=ShortHairShortCurly&accessoriesType=Prescription02&hairColor=Black&facialHairType=Blank&clotheType=Hoodie&clotheColor=White&eyeType=Default&eyebrowType=DefaultNatural&mouthType=Default&skinColor=Light"
                    ></v-img>
                  </v-list-item-avatar>

                  <v-list-item-content>
                    <v-list-item-title>Evan You</v-list-item-title>
                  </v-list-item-content>
                  <div style="background-color: #0c8ce9; border-radius: 20px">
                    <v-icon class="mr-1"> mdi-star </v-icon>
                    <span class="subheading mr-2">5</span>
                  </div>
                </v-list-item> </v-card-actions
              ><v-card-title>Orthopedi: Help u with ur skeleton</v-card-title>
              <v-card-actions>
                <v-list-item class="grow">
                  <v-list-item-content>
                    <v-list-item-title>Evan You</v-list-item-title>
                  </v-list-item-content>
                  <div>
                    <v-icon class="mr-1"> mdi-camera </v-icon>
                    <span class="subheading mr-2">Video Call</span>
                  </div>
                </v-list-item>
              </v-card-actions></v-card
            >
          </div>
          <div>
            <v-card
              style="border-radius: 20px"
              elevation="4"
              width="360"
              height="360"
            ></v-card>
          </div>
          <div>
            <v-card
              style="border-radius: 20px"
              elevation="4"
              width="360"
              height="360"
            ></v-card>
          </div></div
      ></v-container>
    </div>
    <div>
      <v-container
        ><v-row>
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
              style="font-size: 14px"
              class="text-capitalize white"
              width="140px"
              height="55px"
              to="/join-doctor"
              >Click Here To Apply</v-btn
            >
          </v-col>
          <v-col
            ><v-img
              :src="require('@/assets/img/LoginPage_img.jpeg')"
              style="border-radius: 20px 0px 0px 20px"
            >
            </v-img
          ></v-col> </v-row
      ></v-container>
    </div>
    <Footer />
  </div>
</template>

<script>
import NavbarLanding from '../components/NavbarLanding.vue'
import Footer from '../components/Footer.vue'

export default {
  components: { NavbarLanding, Footer },
  // middleware: ['authenticated'],
  data() {
    return {
      statusItem: [
        {
          title: 'Doctors',
          Counts: 4,
          icon: 'mdi-doctor',
          iconBackground: '#31C9A2',
        },
        {
          title: 'Patients',
          Counts: 90,
          icon: 'mdi-account-injury',
          iconBackground: '#FF6B82',
        },
        {
          title: 'Consuls',
          Counts: 40,
          icon: 'mdi-information',
          iconBackground: '#9675F7',
        },
      ],
    }
  },
  methods: {
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
