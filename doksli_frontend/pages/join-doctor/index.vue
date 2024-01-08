<template>
  <v-container>
    <v-stepper v-model="e1">
      <v-stepper-header>
        <v-stepper-step :complete="e1 > 1" step="1">
          Identification
        </v-stepper-step>

        <v-divider></v-divider>

        <v-stepper-step :complete="e1 > 2" step="2">
          Costumizing Page
        </v-stepper-step>
      </v-stepper-header>

      <v-stepper-items>
        <v-stepper-content step="1">
          <v-card flat>
            <v-snackbar v-model="snackbar" absolute top right color="success">
              <span>Registration successful!</span>
              <v-icon dark>mdi-checkbox-marked-circle</v-icon>
            </v-snackbar>
            <v-form ref="form" @submit.prevent="submit">
              <v-container fluid>
                <v-row>
                  <v-col cols="12" sm="6">
                    <v-select
                      v-model="form.specialist_id"
                      :items="special"
                      :item-value="(specialist) => specialist.specialist_id"
                      :item-text="(specialist) => specialist.name"
                      :rules="rules.specialist_id"
                      color="pink"
                      label="Your Specialist"
                      required
                    >
                    </v-select>
                  </v-col>
                  <v-col cols="12" sm="6">
                    <v-text-field
                      v-model="form.experience"
                      :rules="rules.experience"
                      color="blue darken-2"
                      label="How long have you been in this specialist"
                      required
                    ></v-text-field>
                  </v-col>
                  <v-col cols="12">
                    <v-file-input
                      v-model="form.sip"
                      color="deep-purple accent-4"
                      accept="image/png, image/jpeg, image/bmp"
                      counter
                      :rules="rules.sip"
                      label="File input"
                      :multiple="false"
                      placeholder="Select your image"
                      prepend-icon="mdi-paperclip"
                      outlined
                      :show-size="1000"
                      @change="handleFileUploadSIP"
                    >
                      <template v-slot:selection="{ index, text }">
                        <v-chip
                          v-if="index < 2"
                          color="deep-purple accent-4"
                          dark
                          label
                          small
                        >
                          {{ text }}
                        </v-chip>

                        <span
                          v-else-if="index === 2"
                          class="text-overline grey--text text--darken-3 mx-2"
                        >
                          +{{ form.sip.length - 2 }} File(s)
                        </span>
                      </template>
                    </v-file-input>
                  </v-col>
                  <v-col cols="12">
                    <v-checkbox v-model="form.terms" color="green">
                      <template v-slot:label>
                        <div @click.stop="">
                          Do you accept the
                          <a href="#" @click.prevent="terms = true">terms</a>
                          and
                          <a href="#" @click.prevent="conditions = true"
                            >conditions?</a
                          >
                        </div>
                      </template>
                    </v-checkbox>
                  </v-col>
                </v-row>
              </v-container>
            </v-form>
            <v-dialog v-model="terms" width="70%">
              <v-card>
                <v-card-title class="text-h6"> Terms </v-card-title>
                <v-card-text v-for="n in 5" :key="n">
                  {{ content }}
                </v-card-text>
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn text color="purple" @click="terms = false"> Ok </v-btn>
                </v-card-actions>
              </v-card>
            </v-dialog>
            <v-dialog v-model="conditions" width="70%">
              <v-card>
                <v-card-title class="text-h6"> Conditions </v-card-title>
                <v-card-text v-for="n in 5" :key="n">
                  {{ content }}
                </v-card-text>
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn text color="purple" @click="conditions = false">
                    Ok
                  </v-btn>
                </v-card-actions>
              </v-card>
            </v-dialog>
          </v-card>

          <!-- <v-btn :disabled="!formIsValid" color="primary" @click="e1 = 2"> -->
          <v-btn color="primary" @click="e1 = 2"> Continue </v-btn>

          <v-btn @click="resetForm" text> Cancel </v-btn>
        </v-stepper-content>

        <v-stepper-content step="2">
          <v-row align="center" justify="center">
            <v-col cols="6" sm="6"
              ><v-card
                style="border-radius: 20px"
                elevation="4"
                width="360"
                height="auto"
              >
                <v-img
                  v-if="$vuetify.breakpoint.xs"
                  height="200px"
                  style="display: none"
                ></v-img>

                <v-img v-else :src="preview" height="200px"></v-img>

                <v-card-actions>
                  <v-list-item class="grow">
                    <v-list-item-avatar color="grey darken-3">
                      <v-img
                        :src="
                          form.userData.image
                            ? `http://127.0.0.1:8000/storage/${form.userData.image}`
                            : require('@/assets/img/unknown.jpeg')
                        "
                        class="elevation-6"
                        alt=""
                      ></v-img>
                    </v-list-item-avatar>

                    <v-list-item-content>
                      <v-list-item-title class="text-capitalize">{{
                        form.userData.name
                      }}</v-list-item-title>
                    </v-list-item-content>
                    <v-row align="center" justify="end">
                      <v-btn icon depressed color="red">
                        <v-icon>mdi-heart</v-icon>
                      </v-btn>
                      <span class="subheading mr-2">100</span>
                    </v-row>
                  </v-list-item>
                </v-card-actions>

                <v-card-title class="text-capitalize">{{
                  form.room_title
                }}</v-card-title>

                <v-card-actions>
                  <v-list-item class="grow">
                    <v-list-item-content>
                      <v-list-item-title>{{
                        form.experience
                      }}</v-list-item-title>
                    </v-list-item-content>

                    <div>
                      <span class="subheading mr-2">{{
                        getSpecialistName(form.specialist_id)
                      }}</span>
                    </div>
                  </v-list-item>
                </v-card-actions>
              </v-card></v-col
            >
            <v-col cols="6" sm="6">
              <v-text-field
                v-model="form.room_title"
                :rules="rules.room_title"
                color="blue darken-2"
                label="Set Your title"
                required
              ></v-text-field>
              <v-file-input
                v-model="form.room_image"
                label="Upload Image"
                accept="image/*"
                show-size
                @change="handleFileUpload"
              ></v-file-input>
            </v-col>
          </v-row>

          <v-btn @click="prev" text> Previous </v-btn>

          <v-btn color="primary" @click="submit()"> Join Now </v-btn>
        </v-stepper-content>
      </v-stepper-items>
    </v-stepper>
  </v-container>
</template>

<script>
import axios from 'axios'

export default {
  data() {
    const defaultForm = Object.freeze({
      userId: '',
      userData: { image: '', name: '' },
      experience: '',
      specialist_id: '',
      sip: '',
      room_title: '',
      room_image: '',
      terms: false,
    })

    return {
      e1: 1,
      form: { ...defaultForm },
      rules: {
        specialist_id: [(val) => !!val || 'Please select a Specialist'],
        experience: [
          (val) => (val || '').length > 0 || 'This field is required',
        ],
        room_title: [
          (val) => (val || '').length > 0 || 'This field is required',
        ],
        sip: [(val) => (val || '').length > 0 || 'This field is required'],
      },
      special: [],
      conditions: false,
      content:
        'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam. Sed nisi. Nulla quis sem at nibh elementum imperdiet. Duis sagittis ipsum. Praesent mauris. Fusce nec tellus sed augue semper porta. Mauris massa. Vestibulum lacinia arcu eget nulla. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Curabitur sodales ligula in libero. Sed dignissim lacinia nunc.',
      snackbar: false,
      terms: false,
      tampungfoto: '',
      defaultForm,
    }
  },

  computed: {
    formIsValid() {
      return (
        this.form.sip.length > 0 &&
        this.form.experience &&
        this.form.specialist_id &&
        this.form.terms
      )
    },
  },

  methods: {
    getSpecialistName(specialistId) {
      const selectedSpecialist = this.special.find(
        (specialist) => specialist.specialist_id === specialistId
      )
      return selectedSpecialist ? selectedSpecialist.name : 'Unknown Specialist'
    },

    async specialistGet() {
      try {
        const response = await axios.get(
          'http://localhost:8000/api/specialists'
        )
        console.log(response)
        this.special = response.data.specialists
      } catch (error) {
        console.error('Error fetching specialists:', error.response.data)
        this.$snackbar.error('Error fetching specialists')
      }
    },

    submit() {
      const formData = new FormData()
      formData.append('user_id', this.userId)
      formData.append('specialist_id', this.form.specialist_id)
      formData.append('experience', this.form.experience)
      formData.append('room_title', this.form.room_title)

      if (this.form.sip) {
        formData.append('SIP', this.form.sip)
      }

      if (this.form.room_image) {
        formData.append('room_image', this.form.room_image)
      }
      axios
        .post('http://localhost:8000/api/joindoctor', formData)
        .then((response) => {
          console.log(response.data)
          this.snackbar = true
          this.resetForm()
        })
        .catch((error) => {
          console.error('Error registering doctor:', error.response.data)
          this.$snackbar.error('Error registering doctor')
        })
    },

    getuser() {
      axios
        .get('http://127.0.0.1:8000/api/users/' + this.userId)
        .then((response) => {
          console.log(response)
          this.form.userData = response.data?.data
          console.log(this.form.userData)
        })
        .catch((error) => {
          console.error('Error fetching user data:', error.response.data)
        })
    },
    prev() {
      this.e1 = Math.max(this.e1 - 1, 1)
    },
    resetForm() {
      this.form = { ...this.defaultForm }
      this.$refs.form.reset()
    },
    handleFileUpload(event) {
      let files = event
      console.log(files)
      this.form.room_image = files
      let fotobaru = event.name
      this.preview = URL.createObjectURL(files)
      this.tampungfoto = fotobaru
    },
    // handleFileUploadSIP(event) {
    //   let files = event
    //   console.log(files)
    //   this.form.sip = files
    // },
  },

  mounted() {
    this.specialistGet()
    this.getuser()
  },
  created() {
    this.userId = this.$cookies.get('loginCookie').data?.data.id
  },
}
</script>
