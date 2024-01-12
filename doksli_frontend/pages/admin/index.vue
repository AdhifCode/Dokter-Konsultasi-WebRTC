<template>
  <v-container>
    <h1>Dashboard</h1>
    <v-row
      ><v-col cols="6"
        ><v-card rounded-lg>
          <v-container>
            <div class="d-flex">
              <v-card-title class="text-center">Activity</v-card-title>
              <v-card-actions class="ml-auto">
                <v-select
                  min-width="150px"
                  dense
                  outlined
                  v-model="selectedTimeRange"
                  :items="timeRanges"
                  @change="setTimeRange"
                  hide-details
                  :style="{
                    width: '120px',
                    'border-radius': '20px',
                  }"
                ></v-select>
              </v-card-actions>
            </div>

            <Chart :chartOptions="chartOptions" />
          </v-container>
        </v-card>
      </v-col>
      <v-col cols="6"
        ><v-card
          ><v-card-title>Top performers</v-card-title
          ><v-card-actions v-for="(user, index) in top3" :key="index">
            <v-list-item class="grow">
              <v-list-item-avatar color="grey darken-3" size="75">
                <v-img
                  class="elevation-6"
                  alt=""
                  :src="
                    user.user_image
                      ? `http://127.0.0.1:8000/storage/${user.user_image}`
                      : require('@/assets/img/unknown.jpeg')
                  "
                ></v-img>
              </v-list-item-avatar>

              <v-list-item-content>
                <v-list-item-title class="font-weight-bold text-capitalize">{{
                  user.name
                }}</v-list-item-title>
              </v-list-item-content>

              <span class="subheading">{{ user.email }}</span>
              <v-row align="center" justify="end">
                <span class="subheading mr-2">{{ user.total_favorites }}</span>
              </v-row>
            </v-list-item>
          </v-card-actions>
        </v-card></v-col
      >
    </v-row>
  </v-container>
</template>

<script>
import axios from 'axios'
import Chart from '@/components/Chart.vue'
import Highcharts from 'highcharts'

export default {
  components: {
    Chart,
  },
  layout: 'Admin',
  middleware: 'admin',
  data() {
    return {
      chartOptions: {
        chart: {
          height: 300,
          backgroundColor: 'transparent',
        },
        title: {
          text: '',
        },
        xAxis: {
          type: 'datetime',
          labels: {
            formatter: function () {
              return Highcharts.dateFormat('%Y', this.value)
            },
          },
          tickPositioner: function () {
            const data = this.chart.series[0].processedXData
            const ticks = []
            const dates = []

            data.forEach((timestamp) => {
              const date = new Date(timestamp)
              const year = date.getFullYear()
              const month = date.getMonth()
              const day = date.getDate()
              const dateString = `${year}-${month}-${day}`

              if (!dates.includes(dateString)) {
                dates.push(dateString)
                ticks.push(timestamp)
              }
            })

            return ticks
          },
        },
        yAxis: {
          title: {
            text: false,
          },
        },
        legend: {
          enabled: false,
        },
        credits: {
          enabled: false,
        },
        series: [
          {
            name: 'New User',
            data: [],
          },
        ],
      },
      showMenu: false,
      fav: true,
      menu: false,
      message: false,
      hints: true,
      top3: {},
      originalData: [],
      filteredData: [],
      selectedTimeRange: 'Month',
      timeRanges: ['Month', 'Year', 'All Time'],
    }
  },
  watch: {
    originalData: {
      handler() {
        this.$nextTick(() => {
          this.setTimeRange('Month')
        })
      },
      deep: true,
      immediate: true,
    },
  },
  methods: {
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
    getuser() {
      axios
        .get('http://127.0.0.1:8000/api/getuserchart')
        .then((response) => {
          const dataValues = response.data?.data

          const formattedData = dataValues.map(([dateString, value]) => {
            const date = new Date(eval(dateString))
            return [date.getTime(), value]
          })

          formattedData.sort((a, b) => a[0] - b[0])

          this.originalData = formattedData
          this.filteredData = formattedData

          this.chartOptions.series[0].data = this.filteredData
        })
        .catch((error) => {
          console.error('Error fetching user data:', error)
        })
    },
    setTimeRange(range) {
      const now = new Date()
      const currentYear = now.getFullYear()
      const currentMonth = now.getMonth()

      switch (range) {
        case 'Month':
          this.chartOptions.xAxis.labels.formatter = function () {
            const date = new Date(this.value)
            if (
              date.getFullYear() === currentYear &&
              date.getMonth() === currentMonth
            ) {
              return Highcharts.dateFormat('%e %b', this.value)
            } else {
              return ''
            }
          }
          this.filteredData = this.originalData.filter(([date, _]) => {
            const dataDate = new Date(date)
            return (
              dataDate.getFullYear() === currentYear &&
              dataDate.getMonth() === currentMonth
            )
          })
          break
        case 'Year':
          this.chartOptions.xAxis.labels.formatter = function () {
            const date = new Date(this.value)
            if (date.getFullYear() === currentYear) {
              return Highcharts.dateFormat('%b', this.value)
            } else {
              return ''
            }
          }
          const mergedData = []
          const dataByMonth = {}

          this.originalData.forEach(([date, value]) => {
            const dataDate = new Date(eval(date))
            const year = dataDate.getFullYear()
            const month = dataDate.getMonth()
            const key = `${year}-${month}`

            if (year === currentYear && dataByMonth[key]) {
              dataByMonth[key] += value
            } else if (year === currentYear) {
              dataByMonth[key] = value
            }
          })

          Object.entries(dataByMonth).forEach(([key, value]) => {
            const [year, month] = key.split('-')
            const date = Date.UTC(year, month, 1)
            mergedData.push([date, value])
          })

          this.filteredData = mergedData
          break
        case 'All Time':
          this.chartOptions.xAxis.labels.formatter = function () {
            return Highcharts.dateFormat('%Y', this.value)
          }
          const mergedDataAll = []
          const dataByYear = {}

          this.originalData.forEach(([date, value]) => {
            const dataDate = new Date(eval(date))
            const year = dataDate.getFullYear()

            if (dataByYear[year]) {
              dataByYear[year] += value
            } else {
              dataByYear[year] = value
            }
          })

          Object.entries(dataByYear).forEach(([year, value]) => {
            const date = Date.UTC(year, 0, 1)
            mergedDataAll.push([date, value])
          })

          this.filteredData = mergedDataAll
          break
      }

      this.chartOptions.series[0].data = this.filteredData
    },
    showAllTime() {
      this.chartOptions.xAxis.labels.formatter = function () {
        return Highcharts.dateFormat('%Y', this.value)
      }
      const mergedData = []
      const dataByYear = {}

      this.originalData.forEach(([date, value]) => {
        const dataDate = new Date(eval(date))
        const year = dataDate.getFullYear()

        if (dataByYear[year]) {
          dataByYear[year] += value
        } else {
          dataByYear[year] = value
        }
      })

      Object.entries(dataByYear).forEach(([year, value]) => {
        const date = Date.UTC(year, 0, 1)
        mergedData.push([date, value])
      })

      this.filteredData = mergedData
      this.chartOptions.series[0].data = this.filteredData
    },
    isaktif(pathh) {
      return this.$route.path === pathh
    },
    logout() {
      this.$store.dispatch('users/logout')
    },
  },
  created() {
    this.getuser()
  },
  mounted() {
    this.getPopularData()
  },
}
</script>

<style scoped>
</style>