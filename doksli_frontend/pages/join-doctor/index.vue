<template>
  <v-container
    ><v-stepper v-model="e1">
      <v-stepper-header>
        <v-stepper-step :complete="e1 > 1" step="1">
          Identification
        </v-stepper-step>

        <v-divider></v-divider>

        <v-stepper-step :complete="e1 > 2" step="2">
          Certification
        </v-stepper-step>

        <v-divider></v-divider>

        <v-stepper-step step="3"> Costumizing Page </v-stepper-step>
      </v-stepper-header>

      <v-stepper-items>
        <v-stepper-content step="1">
          <v-card flat>
            <v-snackbar v-model="snackbar" absolute top right color="success">
              <span>Registration successful!</span>
              <v-icon dark> mdi-checkbox-marked-circle </v-icon>
            </v-snackbar>
            <v-form ref="form" @submit.prevent="submit">
              <v-container fluid>
                <v-row>
                  <v-col cols="12" sm="6">
                    <v-text-field
                      v-model="form.first"
                      :rules="rules.name"
                      color="purple darken-2"
                      label="First name"
                      required
                    ></v-text-field>
                  </v-col>
                  <v-col cols="12" sm="6">
                    <v-text-field
                      v-model="form.last"
                      :rules="rules.name"
                      color="blue darken-2"
                      label="Last name"
                      required
                    ></v-text-field>
                  </v-col>
                  <v-col cols="12">
                    <v-textarea v-model="form.bio" color="teal">
                      <template v-slot:label>
                        <div>Bio <small>(optional)</small></div>
                      </template>
                    </v-textarea>
                  </v-col>
                  <v-col cols="12" sm="6">
                    <v-select
                      v-model="form.Specialists"
                      :items="special"
                      :rules="rules.special"
                      color="pink"
                      label="Your Specialist"
                      required
                    ></v-select>
                  </v-col>
                  <v-col cols="12" sm="6">
                    <v-slider
                      v-model="form.age"
                      :rules="rules.age"
                      color="orange"
                      label="Age"
                      hint="Be honest"
                      min="1"
                      max="100"
                      thumb-label
                    ></v-slider>
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

          <v-btn :disabled="!formIsValid" color="primary" @click="e1 = 2">
            Continue
          </v-btn>

          <v-btn @click="resetForm" text> Cancel </v-btn>
        </v-stepper-content>

        <v-stepper-content step="2">
          <v-row align="center" justify="center">
            <v-col cols="12" sm="6">
              <!-- Input element for file upload -->
              <v-file-input
                v-model="form.image"
                label="Upload Image"
                accept="image/*"
                show-size
                @change="handleFileUpload"
              ></v-file-input>
            </v-col>
            <v-col cols="12" v-if="form.image">
              <!-- Display the selected image -->
              <v-img :src="form.image" height="150"></v-img>
            </v-col>
          </v-row>

          <v-btn @click="prev" text> Previous </v-btn>

          <v-btn color="primary" @click="e1 = 3"> Continue </v-btn>
        </v-stepper-content>

        <v-stepper-content step="3">
          <v-card class="mb-12" color="grey lighten-1" height="200px"></v-card>

          <v-btn @click="prev" text> Previous </v-btn>

          <v-btn color="primary" @click="goJoin()"> Join Now </v-btn>
        </v-stepper-content>
      </v-stepper-items>
    </v-stepper></v-container
  >
</template>

<script>
export default {
  data() {
    const defaultForm = Object.freeze({
      first: '',
      last: '',
      bio: '',
      Specialists: '',
      age: null,
      terms: false,
      image: null,
    })

    return {
      e1: 1,
      form: Object.assign({}, defaultForm),
      rules: {
        age: [(val) => val < 10 || `I don't believe you!`],
        special: [(val) => (val || '').length > 0 || 'This field is required'],
        name: [(val) => (val || '').length > 0 || 'This field is required'],
      },
      special: ['Orthopedi', 'Tes', 'Rabbit', 'Turtle', 'Snake'],
      conditions: false,
      content:
        'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam. Sed nisi. Nulla quis sem at nibh elementum imperdiet. Duis sagittis ipsum. Praesent mauris. Fusce nec tellus sed augue semper porta. Mauris massa. Vestibulum lacinia arcu eget nulla. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Curabitur sodales ligula in libero. Sed dignissim lacinia nunc.',
      snackbar: false,
      terms: false,
      defaultForm,
    }
  },

  computed: {
    formIsValid() {
      return (
        this.form.first &&
        this.form.last &&
        this.form.Specialists &&
        this.form.terms
      )
    },
  },

  methods: {
    prev() {
      this.e1 = Math.max(this.e1 - 1, 1)
    },
    resetForm() {
      this.form = Object.assign({}, this.defaultForm)
      this.$refs.form.reset()
    },
    submit() {
      this.snackbar = true
      this.resetForm()
    },
    handleFileUpload(files) {
      // Handle file upload and set the image field
      if (files.length > 0) {
        const reader = new FileReader()
        reader.onload = (e) => {
          this.form.image = e.target.result
        }
        reader.readAsDataURL(files[0])
      } else {
        this.form.image = null
      }
    },
  },
}
</script>