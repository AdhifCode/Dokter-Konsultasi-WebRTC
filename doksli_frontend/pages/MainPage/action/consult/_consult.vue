<template>
  <v-container>
    <v-btn icon @click="goBack" :ripple="false">
      <v-icon left>mdi-arrow-left</v-icon>
    </v-btn>
    <div class="d-flex">
      <v-avatar height="200px" width="200px">
        <img
          :src="
            doctorData.user_image
              ? `http://127.0.0.1:8000/storage/${doctorData.user_image}`
              : require('@/assets/img/unknown.jpeg')
          "
          alt="Doctor Avatar"
          style="object-fit: cover"
        />
      </v-avatar>
      <div>
        <div>Hello</div>
        <div>{{ doctorData.name }}</div>
        <div class="d-flex">
          <v-icon>mdi-briefcase</v-icon>
          {{ doctorData.experience }} Tahun
        </div>
      </div>
    </div>
    <div class="d-flex">
      <div>
        <v-btn
          v-if="!orderMade"
          class="text-capitalize"
          @click="dialogVisible = true"
        >
          Make a schedule
        </v-btn>
        <v-btn v-else class="text-capitalize" @click="redirectToOrderDetails">
          View Order Details
        </v-btn>
        <v-dialog max-width="600" v-model="dialogVisible">
          <v-card>
            <v-container>
              <v-row>
                <v-col>
                  <v-date-picker
                    v-model="scheduling.date"
                    :allowed-dates="allowedDates"
                    class="mt-4"
                    min="2024-01-03"
                    max="2024-02-03"
                  ></v-date-picker>
                </v-col>
                <v-col
                  ><div class="d-flex">
                    <div>
                      <v-dialog
                        ref="dialog"
                        v-model="dialogStart"
                        :return-value.sync="scheduling.time_start"
                        persistent
                        width="460"
                      >
                        <template v-slot:activator="{ on, attrs }">
                          <v-text-field
                            v-model="scheduling.time_start"
                            label="Picker in dialog"
                            prepend-icon="mdi-clock-time-four-outline"
                            readonly
                            dense
                            solo
                            outlined
                            v-bind="attrs"
                            v-on="on"
                          ></v-text-field>
                        </template>
                        <v-time-picker
                          v-if="dialogStart"
                          v-model="scheduling.time_start"
                          full-width
                          ampm-in-title
                          format="24hr"
                          landscape
                          :min="start"
                          :max="end"
                        >
                          <v-spacer></v-spacer>
                          <v-btn
                            text
                            color="primary"
                            @click="dialogStart = false"
                          >
                            Cancel
                          </v-btn>
                          <v-btn
                            text
                            color="primary"
                            @click="$refs.dialog.save(scheduling.time_start)"
                          >
                            OK
                          </v-btn>
                        </v-time-picker>
                      </v-dialog>
                    </div>
                    <div>
                      <v-text-field
                        v-model="scheduling.time_end"
                        label="End Time"
                        solo
                        dense
                        outlined
                        readonly
                      ></v-text-field>
                    </div>
                  </div>
                  <v-btn @click="order">Order Now</v-btn>
                </v-col>
              </v-row>
            </v-container>
          </v-card>
        </v-dialog>
      </div>
    </div>
  </v-container>
</template>

<script>
import axios from 'axios'

export default {
  data() {
    return {
      userId: '',
      doctorData: {},
      dialogVisible: false,
      dialogStart: false,
      scheduling: { date: '2024-01-03', time_start: null, time_end: null },
      start: '08:00',
      end: '16:00',
      newData: {},
      choose_time: false,
      orderMade: false,
      bookedTimeSlots: [],
    }
  },

  methods: {
    async order() {
      try {
        const orderData = {
          user_id: this.userId,
          doctor_id: this.doctorData.doctor_id,
          room_id: this.$route.params.consult,
          time_start: this.newData.start,
          time_end: this.newData.end,
        }
        const response = await this.$axios.post(
          'http://localhost:8000/api/ordercreate',
          orderData
        )
        this.orderMade = true
        this.dialogStart = false
        console.log('Order created successfully:', response.data)
      } catch (error) {
        if (error.response && error.response.status === 400) {
          this.$toast.error(
            'The selected time slot is already booked. Please choose another time.'
          )
        } else {
          this.$toast.error('Failed to create order. Please try again.')
        }
      }
    },
    redirectToOrderDetails() {
      this.$router.push(`/order-details/${orderId}`) //
    },
    async fetchBookedTimeSlots() {
      try {
        const roomId = this.$route.params.consult
        const response = await this.$axios.get(
          `http://localhost:8000/api/bookedroom/${roomId}`
        )
        this.bookedTimeSlots = response.data.booked_time_slots
      } catch (error) {
        console.error('Error fetching booked time slots:', error)
      }
    },

    allowedDates(date) {
      const formattedDate = this.formatDateAllow(date)
      const isDateAvailable = this.checkDateAvailability(formattedDate)
      return isDateAvailable
    },

    formatDateAllow(date) {
      const parsedDate = typeof date === 'string' ? new Date(date) : date

      const year = parsedDate.getFullYear()
      const month = String(parsedDate.getMonth() + 1).padStart(2, '0')
      const day = String(parsedDate.getDate()).padStart(2, '0')

      return `${year}-${month}-${day}`
    },

    checkDateAvailability(date) {
      const existingTimeSlot = this.bookedTimeSlots.find((slot) => {
        const startTime = new Date(slot.time_start)
        const endTime = new Date(slot.time_end)
        return startTime <= date && date <= endTime
      })
      return !existingTimeSlot
    },
    goBack() {
      this.$router.push('../home')
    },
    async fetchDoctorData() {
      try {
        const roomId = this.$route.params.consult
        const response = await this.$axios.get(
          `http://localhost:8000/api/getroombyid/${roomId}`
        )
        this.doctorData = response.data.room
      } catch (error) {
        console.error('Error fetching doctor data:', error)
      }
    },
    calculateEndTime() {
      if (this.scheduling.time_start && this.scheduling.date) {
        const startTime = new Date(
          `${this.scheduling.date}T${this.scheduling.time_start}:00`
        )

        const endTime = new Date(startTime.getTime() + 60 * 60 * 1000)

        this.scheduling.time_end = this.formatDateTime(endTime)

        this.newData = {
          start: this.scheduling.date + ' ' + this.scheduling.time_start,
          end: this.scheduling.date + ' ' + this.scheduling.time_end,
        }

        console.log('New Data:', this.newData)
      }
    },

    formatDateTime(date) {
      const hours = date.getHours().toString().padStart(2, '0')
      const minutes = date.getMinutes().toString().padStart(2, '0')
      return `${hours}:${minutes}`
    },
  },

  watch: {
    'scheduling.time_start': function (newTimeStart) {
      this.calculateEndTime()
    },
  },

  mounted() {
    this.fetchDoctorData()
  },
  created() {
    const usid = this.$cookies.get('loginCookie')
    this.userId = usid.data?.data.id
    this.fetchBookedTimeSlots()
  },
}
</script>
