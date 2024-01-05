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
            v-for="(cardItem, index) in filteredRooms"
            :key="index"
            ><v-card
              style="border-radius: 20px"
              elevation="4"
              width="360"
              height="auto"
              @click="toconsult(cardItem)"
            >
              <v-img
                v-if="$vuetify.breakpoint.xs"
                height="200px"
                style="display: none"
              ></v-img>
              <v-img
                :src="`http://127.0.0.1:8000/storage/${cardItem.room_image}`"
                height="200px"
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
                    <v-btn
                      icon
                      depressed
                      @click.stop="addFavorite(cardItem)"
                      :color="cardItem.isFavorite ? 'red' : ''"
                    >
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
      <div v-if="$route.path.includes('/schedule')">
        <v-row>
          <v-col cols="12"><DoctorSchedule /></v-col>
          <!-- <v-col cols="8">
            <v-card
              v-for="(cardItem, index) in items"
              :key="index"
              class="d-flex mt-2"
              elevation="0"
              @click="room(cardItem.room_id)"
            >
              <v-img></v-img>
              <div>{{ cardItem.title }}</div>
              12.00
            </v-card>
          </v-col> -->
        </v-row>
      </div>
      <div v-if="$route.path.includes('/profile')">
        <v-row>
          <v-col cols="6"
            ><v-card style="border-radius: 20px"
              ><v-img
                :src="preview"
                v-if="preview"
                height="250px"
                class="position-relative"
                @mouseover="showIcon = true"
                @mouseleave="showIcon = false"
              >
                <v-btn
                  v-if="editMode && showIcon"
                  @click="handleImageChange()"
                  :ripple="false"
                  depressed
                  x-large
                  icon
                  class="icon-overlay"
                  :class="{ 'elevation-12': showIcon }"
                  ><v-icon v-show="showIcon" color="white">
                    mdi-image-edit-outline
                  </v-icon>
                  <input
                    ref="imageInput"
                    type="file"
                    style="display: none"
                    accept="image/*"
                    v-on:change="onFileInputChange"
                /></v-btn>
                ></v-img
              >
              <v-img
                v-else
                :src="
                  profile.image
                    ? `http://127.0.0.1:8000/storage/${profile.image}`
                    : require('@/assets/img/unknown.jpeg')
                "
                height="250px"
                class="position-relative"
                @mouseover="showIcon = true"
                @mouseleave="showIcon = false"
              >
                <v-btn
                  v-if="editMode && showIcon"
                  @click="handleImageChange()"
                  :ripple="false"
                  depressed
                  x-large
                  icon
                  class="icon-overlay"
                  :class="{ 'elevation-12': showIcon }"
                  ><v-icon v-show="showIcon" color="white">
                    mdi-image-edit-outline
                  </v-icon>
                  <input
                    ref="imageInput"
                    type="file"
                    style="display: none"
                    accept="image/*"
                    v-on:change="onFileInputChange"
                /></v-btn>
              </v-img>
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
                        :readonly="!editMode"
                        v-model="profile.name"
                      ></v-text-field></v-col
                    ><v-col>
                      <vue-tel-input-vuetify
                        block
                        class="rounded-lg"
                        :readonly="!editMode"
                        v-model="profile.phone"
                        solo
                        :maxLen="15"
                        defaultCountry="ID"
                        :onlyCountries="['ID', 'MY', 'SG']"
                      ></vue-tel-input-vuetify> </v-col
                  ></v-row>
                  <v-text-field
                    :readonly="!editMode"
                    v-model="profile.email"
                    type="email"
                  ></v-text-field>
                  <div v-if="!editMode" class="ma-4">
                    <v-btn
                      class="text-capitalize"
                      rounded
                      @click="editMode = true"
                      >Edit</v-btn
                    >
                  </div>

                  <div v-if="editMode" class="ma-4">
                    <v-btn class="text-capitalize" rounded @click="cancelEdit"
                      >Cancel</v-btn
                    >
                    <v-btn class="text-capitalize" rounded @click="saveChanges"
                      >Save</v-btn
                    >
                  </div>
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
                      v-for="(favorite, index) in favoriteRooms"
                      :key="index"
                      class="d-flex justify-space-between align-center ma-2"
                    >
                      <div>
                        <div class="font-weight-black">
                          {{ favorite.specialist }}
                        </div>
                        <div class="font-italic">By Dr.{{ favorite.name }}</div>
                      </div>
                      <v-btn
                        class="justify-end text-capitalize"
                        elevation="0"
                        rounded
                        @click="toconsult(favorite)"
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
                      v-for="(cardItem, index) in recentOrders"
                      :key="index"
                      class="d-flex justify-space-between align-center ma-2"
                    >
                      <div>
                        <div class="text-bold">
                          {{ cardItem.room.doctor.specialist }}
                        </div>
                        <div>By Dr.{{ cardItem.room.doctor.user.name }}</div>
                      </div>
                      <v-btn
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
          v-for="(orderItem, index) in upcomingOrders"
          :key="index"
          class="d-flex mt-2"
          outlined
          elevation="0"
          rounded-lg
          @click="room(orderItem.room.room_id)"
        >
          <v-container>
            <div class="d-flex">
              <v-avatar>
                <v-img
                  :src="
                    orderItem.room.doctor.user.image
                      ? `http://127.0.0.1:8000/storage/${orderItem.room.doctor.user.image}`
                      : require('@/assets/img/unknown.jpeg')
                  "
                  class="elevation-6"
                  alt=""
                ></v-img
              ></v-avatar>
              <div class="ml-2">
                <div class="font-weight-bold">
                  {{ orderItem.room.room_title }}
                </div>
                <div class="font-italic">
                  Dr. {{ orderItem.room.doctor.user.name }}
                </div>
              </div>
              <div>
                {{ $moment(orderItem.time_start).fromNow() }}
              </div>
            </div>
          </v-container>
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
          <v-icon v-if="item.icon">
            {{ item.icon }}
          </v-icon>
          <div v-else>
            <v-avatar width="32px" height="32px">
              <img
                :src="
                  profile.image
                    ? `http://127.0.0.1:8000/storage/${profile.image}`
                    : require('@/assets/img/unknown.jpeg')
                "
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
import axios from 'axios'

export default {
  components: { DoctorSchedule },
  middleware: ['authenticated'],
  data() {
    return {
      userid: '',
      upcomingOrders: [],
      recentOrders: [],
      showIcon: false,
      searchKeyword: '',
      editMode: false,
      value: 2,
      ButtonActive: true,
      notes: { note: '', doctor: '', specialist: '' },
      navigationItems: [
        { icon: 'mdi-calendar-month', slug: '/schedule' },
        { icon: 'mdi-home-variant', slug: '/home' },
        { icon: 'mdi-video', slug: '/meet' },
        { icon: '', slug: '/profile' },
      ],
      page: 1,
      dialogsearch: false,
      dialogNotes: false,
      cari: '',
      rooms: [],
      favoriteRooms: [],
      profile: [],
      tampungfoto: '',
      preview: null,
    }
  },
  methods: {
    cancelEdit() {
      this.editMode = false
      this.getUserData()
      this.preview = null
    },
    handleImageChange() {
      this.$refs.imageInput.click()
    },

    onFileInputChange(event) {
      let files = event.target.files[0]
      this.datafoto = files
      let fotobaru = event.name
      console.log(files)
      this.preview = URL.createObjectURL(files)
      this.tampungfoto = fotobaru
    },

    saveChanges() {
      const formData = new FormData()
      formData.append('name', this.profile.name)
      formData.append('email', this.profile.email)
      formData.append('phone', this.profile.phone)

      if (this.datafoto) {
        formData.append('image', this.datafoto)
      }

      axios
        .post(`http://localhost:8000/api/updateuser/${this.userid}`, formData)
        .then((response) => {
          const data = response.data

          if (data.success) {
            this.profile = data.data
            console.log('Profile updated successfully')
            this.editMode = false
          } else {
            console.error('Failed to update profile:', data.message)
          }
        })
        .catch((error) => {
          console.error('Error updating profile:', error)
        })
    },

    getUserData() {
      axios
        .get(`http://localhost:8000/api/users/${this.userid}`)
        .then((response) => {
          console.log('API Response:', response)
          this.profile = response.data?.data
        })
        .catch((error) => {
          console.error('Error fetching user data:', error)
        })
    },

    getOrders() {
      axios
        .get(`http://localhost:8000/api/orders/${this.userid}`)
        .then((response) => {
          const orders = response.data.user_orders

          const currentTime = this.$moment()
          this.recentOrders = orders.filter((order) => {
            const orderTime = this.$moment(order.time_start)
            return currentTime.diff(orderTime, 'seconds') >= 1
          })

          this.upcomingOrders = orders.filter((order) => {
            const orderTime = this.$moment(order.time_start)
            return currentTime.diff(orderTime, 'seconds') < 1
          })
        })
        .catch((error) => {
          console.error('Error fetching orders:', error)
        })
    },

    getFavoriteRooms() {
      axios
        .get(`http://localhost:8000/api/userfavorite?user_id=${this.userid}`)
        .then((response) => {
          this.favoriteRooms = response.data.favorites
        })
        .catch((error) => {
          console.error('Error fetching favorite rooms:', error)
        })
    },

    getRooms() {
      axios
        .get('http://localhost:8000/api/getrooms')
        .then((response) => {
          this.rooms = response.data.rooms

          this.rooms.forEach((cardItem) => {
            this.checkFavoriteStatus(cardItem)
          })
        })
        .catch((error) => {
          console.error('Error fetching rooms:', error)
        })
    },

    addFavorite(cardItem) {
      axios
        .post(`http://localhost:8000/api/favorite/${cardItem.room_id}`, {
          user_id: this.userid,
        })
        .then((response) => {
          const message = response.data.message
          const isFavorite = response.data.is_favorite
          console.log(message)

          this.$set(cardItem, 'isFavorite', isFavorite)
          this.$forceUpdate()
        })
        .catch((error) => {
          console.error('Error adding favorite:', error)
        })
    },

    checkFavoriteStatus(cardItem) {
      axios
        .get(`http://localhost:8000/api/favorite/${cardItem.room_id}/status`, {
          params: { user_id: this.userid },
        })
        .then((response) => {
          const isFavorite = response.data.is_favorite

          this.$set(cardItem, 'isFavorite', isFavorite)
        })
        .catch((error) => {
          console.error('Error checking favorite status:', error)
        })
    },
    showNotes(cardItem) {
      console.log(cardItem.notes)
      this.dialogNotes = true
      this.notes.note = cardItem.notes
      this.notes.doctor = cardItem.name
      this.notes.specialist = cardItem.specialist
    },
    toconsult(favorite) {
      console.log(favorite.room_id)
      this.$router.push(`../action/consult/${favorite.room_id}`)
    },
    pushing(data) {
      this.$router.push('/MainPage/action' + data)
    },
    room(id) {
      const orderItem = this.rooms.find((item) => item.room_id === id)
      if (orderItem) {
        console.log('Idnya', orderItem.room_id)
        this.$store.commit('meet/setInputId', orderItem.room_id)
        this.$router.push('/MainPage/action/room')
      } else {
        console.error('Card item not found for id:', id)
      }
    },

    search() {
      if (this.searchKeyword.trim() !== '') {
        this.$router.push('/MainPage/action/home')
      }
    },
  },
  computed: {
    filteredRooms() {
      return this.rooms.filter(
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
  mounted() {
    this.getRooms()
    this.getOrders()

    this.rooms.forEach((cardItem) => {
      this.checkFavoriteStatus(cardItem)
    })
    this.getFavoriteRooms()
    this.getUserData()
  },

  created() {
    const usid = this.$cookies.get('loginCookie')
    if (usid) {
      this.userid = usid.data?.data.id
      console.log(usid)
    } else {
      this.userid = null
    }
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

.icon-overlay {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  font-size: 24px; /* Sesuaikan ukuran ikon sesuai kebutuhan */
  color: white; /* Sesuaikan warna ikon sesuai kebutuhan */
}
</style>