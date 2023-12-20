<template>
  <div>
    <v-dialog v-model="dialogsearch" max-width="600px">
      <v-card class="pa-3 rounded-lg">
        <v-text-field
          class="nyari rounded-lg"
          label="Search for doctor"
          single-line
          hide-details
          outlined
          append-icon="mdi-magnify"
          placehoder="cari"
          v-model="searchKeyword"
          @enter="search"
        ></v-text-field>
      </v-card>
    </v-dialog>
    <v-dialog v-model="dialogNotes" max-width="600px"
      ><v-card style="border-radius: 20px" elevation="0"
        ><v-container
          ><div>{{ notes.doctor }}</div>
          <div>{{ notes.specialist }}</div>
          <v-textarea
            solo-inverted
            disabled
            auto-grow
            copyable
            copy-icon="mdi-close-circle"
            v-model="notes.note"
            label="Doctor is not giving a note"
          ></v-textarea></v-container></v-card
    ></v-dialog>
    <v-container>
      <div v-if="$route.path.includes('/home')">
        <div flat class="rounded-pill" color="#d9d9d9"></div>
        <v-row class="p-0">
          <v-col
            cols="12"
            :class="[
              '   mt-4   col-md-4  col-sm-6 col-xs-6 d-flex justify-center  ',
              $vuetify.breakpoint.lgAndUp ? 'col-lg-3' : '',
            ]"
            v-for="(cardItem, index) in filteredItems"
            :key="index"
            ><v-card
              style="border-radius: 20px"
              elevation="4"
              width="360"
              height="auto"
              @click="toconsule(cardItem)"
            >
              <v-img
                v-if="$vuetify.breakpoint.xs"
                height="200px"
                style="display: none"
              ></v-img>
              <v-img
                v-else
                :src="require(`@/assets/img/${cardItem.image}`)"
                height="200px"
              ></v-img>
              <v-card-actions>
                <v-list-item class="grow">
                  <v-list-item-avatar color="grey darken-3">
                    <v-img
                      :src="require(`@/assets/img/${cardItem.image}`)"
                      class="elevation-6"
                      alt=""
                    ></v-img>
                  </v-list-item-avatar>

                  <v-list-item-content>
                    <v-list-item-title class="text-capitalize">{{
                      cardItem.name
                    }}</v-list-item-title>
                  </v-list-item-content>

                  <v-btn icon depressed @click="addFavorite()" class="pa-1">
                    <v-icon>mdi-heart</v-icon>
                  </v-btn>
                </v-list-item>
              </v-card-actions>

              <v-card-title>{{ cardItem.title }}</v-card-title>

              <v-card-actions>
                <v-list-item class="grow">
                  <v-list-item-content>
                    <v-list-item-title>{{ cardItem.name }}</v-list-item-title>
                  </v-list-item-content>

                  <div>
                    <span class="subheading mr-2">{{
                      cardItem.specialist
                    }}</span>
                  </div>
                </v-list-item>
              </v-card-actions>
            </v-card></v-col
          ></v-row
        >
      </div>
      <div v-if="$route.path.includes('/schedule')"><DoctorSchedule /></div>
      <div v-if="$route.path.includes('/profile')">
        <v-row>
          <v-col cols="6"
            ><v-card style="border-radius: 20px"
              ><v-img
                height="250"
                :src="require(`@/assets/img/profile.jpg`)"
              ></v-img>
              <div>
                <v-row>
                  <v-col><v-card-title>My Profile</v-card-title></v-col>
                  <v-col
                    ><v-card-subtitle>{{
                      profile.address
                    }}</v-card-subtitle></v-col
                  >
                </v-row>
                <div class="ma-4">
                  <v-row
                    ><v-col
                      ><v-text-field
                        v-model="profile.name"
                      ></v-text-field></v-col
                    ><v-col>
                      <v-text-field
                        v-model="profile.phone"
                      ></v-text-field></v-col
                  ></v-row>
                  <v-text-field v-model="profile.email"></v-text-field>
                  <v-btn class="text-capitalize" rounded>Edit</v-btn>
                </div>
              </div>
            </v-card></v-col
          >
          <v-col cols="6">
            <div>
              <v-card style="border-radius: 20px" height="300px">
                <div class="d-flex">
                  <v-card-title>My Favorite</v-card-title>
                  <v-text-field
                    outlined
                    label="Prepend"
                    prepend-icon="mdi-map-marker"
                  >
                  </v-text-field>
                </div>
                <v-container
                  ><v-divider class="mb-2"></v-divider>
                  <div class="scrollable-container">
                    <div
                      v-for="(cardItem, index) in items"
                      :key="index"
                      class="d-flex justify-space-between align-center ma-2"
                    >
                      <div>
                        <div class="black--text">{{ cardItem.specialist }}</div>
                        <div>By Dr.{{ cardItem.name }}</div>
                      </div>
                      <v-btn
                        :color="greengrey"
                        class="justify-end text-capitalize"
                        elevation="0"
                        rounded
                        @click="toconsule(cardItem)"
                        >Visit</v-btn
                      >
                    </div>
                  </div>
                </v-container>
              </v-card>
            </div>
            <div>
              <v-card style="border-radius: 20px" class="mt-2" height="275px">
                <v-card-title>Recent Order</v-card-title>
                <v-container>
                  <div class="scrollable-container">
                    <div
                      v-for="(cardItem, index) in items"
                      :key="index"
                      class="d-flex justify-space-between align-center ma-2"
                    >
                      <div>
                        <div class="text-bold">{{ cardItem.specialist }}</div>
                        <div>By Dr.{{ cardItem.name }}</div>
                      </div>
                      <v-btn
                        :color="greengrey"
                        class="justify-end text-capitalize"
                        elevation="0"
                        rounded
                        @click="showNotes(cardItem)"
                        >View Notes</v-btn
                      >
                    </div>
                  </div>
                </v-container>
              </v-card>
            </div>
          </v-col>
        </v-row>
      </div>
      <div v-if="$route.path.includes('/meet')">
        <v-card
          v-for="(cardItem, index) in items"
          :key="index"
          class="d-flex mt-2"
          elevation="0"
          @click="room(cardItem.consule_id)"
        >
          <v-img></v-img>
          <div>{{ cardItem.title }}</div>
          12.00
        </v-card>
      </div>
      <!-- <div v-else>Kosong kang</div> -->
      <!-- <div class="text-center">
        <v-pagination v-model="page" :length="4" circle></v-pagination>
      </div> -->
    </v-container>
    <div class="bottom-navigation-container" style="height: 75px">
      <v-bottom-navigation
        elevation="6"
        fixed
        style="border-radius: 25px; margin-left: 38%; margin-bottom: 15px"
        max-width="400px"
        v-model="value"
        :input-value="ButtonActive"
      >
        <v-btn
          class="d-flex"
          :ripple="false"
          plain
          :color="color"
          dark
          @click="dialogsearch = true"
          :small="$vuetify.breakpoint.smAndDown ? true : false"
          ><v-icon :small="$vuetify.breakpoint.smAndDown ? true : false"
            >mdi-magnify</v-icon
          ></v-btn
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
    </div>
  </div>
</template>

<script>
import DoctorSchedule from '~/components/DoctorSchedule.vue'

export default {
  components: { DoctorSchedule },
  middleware: ['authenticated'],
  // layout: 'BottomNavigation',
  data() {
    return {
      searchKeyword: '',
      value: 2,
      ButtonActive: true,
      notes: { note: '', doctor: '', specialist: '' },
      navigationItems: [
        { icon: 'mdi-calendar-month', slug: '/schedule' },
        { icon: 'mdi-home-variant', slug: '/home' },
        { icon: 'mdi-video', slug: '/meet' },
        { icon: 'profile.jpg', slug: '/profile' },
      ],
      page: 1,
      dialogsearch: false,
      dialogNotes: false,
      cari: '',
      items: [
        {
          consule_id: '1',
          image: 'Landing_Orthopedi.jpg',
          name: 'Abdul',
          title: 'Membantu untuk masalah kecantikan',
          specialist: 'Dermatologist',
          notes: 'Sebaiknya hindari makanan2 yang berminyak',
        },
        {
          consule_id: '2',
          image: 'Landing_Orthopedi.jpg',
          name: 'Bella',
          title: 'Membantu untuk masalah mata',
          specialist: 'Ophthalmologist',
          notes:
            'Pastikan menggunakan kacamata hitam saat berada di luar ruangan',
        },
        {
          consule_id: '3',
          image: 'Landing_Orthopedi.jpg',
          name: 'Charlie',
          title: 'Membantu untuk masalah jantung',
          specialist: 'Cardiologist',
          notes:
            'Jaga asupan makanan rendah garam dan hindari makanan berlemak',
        },
        {
          consule_id: '4',
          image: 'Landing_Orthopedi.jpg',
          name: 'David',
          title: 'Membantu untuk masalah anak-anak',
          specialist: 'Pediatrician',
          notes: 'Pastikan anak-anak mendapatkan vaksinasi sesuai jadwal',
        },
        {
          consule_id: '5',
          image: 'Landing_Orthopedi.jpg',
          name: 'Eva',
          title: 'Membantu untuk masalah pencernaan',
          specialist: 'Gastroenterologist',
          notes: 'Perbanyak konsumsi serat untuk menjaga kesehatan pencernaan',
        },
        {
          consule_id: '6',
          image: 'Landing_Orthopedi.jpg',
          name: 'Frank',
          title: 'Membantu untuk masalah endokrin',
          specialist: 'Endocrinologist',
          notes: 'Penting untuk menjaga kadar gula darah tetap stabil',
        },
        {
          consule_id: '7',
          image: 'Landing_Orthopedi.jpg',
          name: 'Grace',
          title: 'Membantu untuk masalah kesehatan mental',
          specialist: 'Psychiatrist',
          notes: 'Berbicara terbuka dapat membantu mengatasi masalah mental',
        },
        {
          consule_id: '8',
          image: 'Landing_Orthopedi.jpg',
          name: 'Harry',
          title: 'Membantu untuk masalah urologi',
          specialist: 'Urologist',
          notes: 'Minum banyak air untuk menjaga kesehatan saluran kemih',
        },
        {
          consule_id: '9',
          image: 'Landing_Orthopedi.jpg',
          name: 'Ivy',
          title: 'Membantu untuk masalah kehamilan',
          specialist: 'Obstetrician',
          notes: 'Ikuti prenatal care secara teratur selama kehamilan',
        },
        {
          consule_id: '10',
          image: 'Landing_Orthopedi.jpg',
          name: 'Jack',
          title: 'Membantu untuk masalah onkologi',
          specialist: 'Oncologist',
          notes:
            'Penting untuk mendeteksi kanker sejak dini melalui pemeriksaan rutin',
        },
        {
          consule_id: '11',
          image: 'Landing_Orthopedi.jpg',
          name: 'Kelly',
          title: 'Membantu untuk masalah ginjal',
          specialist: 'Nephrologist',
          notes: 'Kurangi konsumsi garam untuk menjaga kesehatan ginjal',
        },
        {
          consule_id: '12',
          image: 'Landing_Orthopedi.jpg',
          name: 'Liam',
          title: 'Membantu untuk masalah alergi',
          specialist: 'Allergist',
          notes:
            'Identifikasi alergen dan hindari kontak dengan alergen tersebut',
        },
        {
          consule_id: '13',
          image: 'Landing_Orthopedi.jpg',
          name: 'Mia',
          title: 'Membantu untuk masalah rematologi',
          specialist: 'Rheumatologist',
          notes: 'Senam ringan dapat membantu meredakan nyeri pada sendi',
        },
        {
          consule_id: '14',
          image: 'Landing_Orthopedi.jpg',
          name: 'Nathan',
          title: 'Membantu untuk masalah paru-paru',
          specialist: 'Pulmonologist',
          notes: 'Hindari paparan asap rokok dan lingkungan berpolusi',
        },
        {
          consule_id: '15',
          image: 'Landing_Orthopedi.jpg',
          name: 'Olivia',
          title: 'Membantu untuk masalah hematologi',
          specialist: 'Hematologist',
          notes: 'Konsumsi makanan yang kaya zat besi untuk mencegah anemia',
        },
        {
          consule_id: '16',
          image: 'Landing_Orthopedi.jpg',
          name: 'Peter',
          title: 'Membantu untuk masalah ginekologi',
          specialist: 'Gynecologist',
          notes:
            'Lakukan pemeriksaan Pap smear secara teratur untuk deteksi dini kanker serviks',
        },
        {
          consule_id: '17',
          image: 'Landing_Orthopedi.jpg',
          name: 'Quinn',
          title: 'Membantu untuk masalah THT',
          specialist: 'ENT Specialist',
          notes: 'Hindari penggunaan cotton bud untuk membersihkan telinga',
        },
        {
          consule_id: '18',
          image: 'Landing_Orthopedi.jpg',
          name: 'Ryan',
          title: 'Membantu untuk masalah penyakit menular',
          specialist: 'Infectious Disease Specialist',
          notes: 'Ikuti protokol kebersihan untuk mencegah penularan penyakit',
        },
        {
          consule_id: '19',
          image: 'Landing_Orthopedi.jpg',
          name: 'Sara',
          title: 'Membantu untuk masalah fisioterapi',
          specialist: 'Physiotherapist',
          notes: 'Lakukan latihan fisioterapi sesuai petunjuk profesional',
        },
        {
          consule_id: '20',
          image: 'Landing_Orthopedi.jpg',
          name: 'Tom',
          title: 'Membantu untuk masalah diet',
          specialist: 'Dietician',
          notes: 'Konsultasikan pola makan sehat sesuai kebutuhan tubuh',
        },
      ],
      profile: {
        name: 'Nadhifal',
        phone: '+62 896 3683 5581',
        email: 'nadhifalazharuddiya@gmail.com',
        address: 'Bumi Mutiara BLOK JF 10/16, Gunung Putri, Bogor',
      },
    }
  },
  methods: {
    showNotes(cardItem) {
      console.log(cardItem.notes)
      this.dialogNotes = true
      this.notes.note = cardItem.notes
      this.notes.doctor = cardItem.name
      this.notes.specialist = cardItem.specialist
    },
    toconsule(cardItem) {
      console.log(cardItem.consule_id)
      this.$router.push(`../action/consule/${cardItem.consule_id}`)
    },
    pushing(data) {
      this.$router.push('/MainPage/action' + data)
    },
    room(id) {
      console.log(cardItem.consule_id)
      this.$store.commit('meet/setInputId', cardItem.consule_id)
      this.$router.push('/MainPage/action/room')
    },
    search() {
      if (this.searchKeyword.trim() !== '') {
        this.$router.push('/MainPage/action/home')
      }
    },
  },
  computed: {
    filteredItems() {
      return this.items.filter(
        (item) =>
          item.name.toLowerCase().includes(this.searchKeyword.toLowerCase()) ||
          item.title.toLowerCase().includes(this.searchKeyword.toLowerCase()) ||
          item.specialist
            .toLowerCase()
            .includes(this.searchKeyword.toLowerCase())
      )
    },

    color() {
      switch (this.value) {
        case 0:
          return '#31C9A2'
        case 1:
          return '#FF6B82'
        case 2:
          return '#9675F7'
        case 3:
          return '#0C8CE9'
      }
    },
  },
}
</script>

<style scoped>
.scrollable-container {
  max-height: 175px;
  overflow-y: auto;
}

.scrollable-container::-webkit-scrollbar {
  width: 8px;
}

.scrollable-container::-webkit-scrollbar-thumb {
  background-color: #9675f7;
  border-radius: 20px;
}

.scrollable-container::-webkit-scrollbar-track {
  background-color: transparent;
}
</style>