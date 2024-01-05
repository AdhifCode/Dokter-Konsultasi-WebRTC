<template>
  <v-container>
    <v-card rounded-lg>
      <v-container
        ><v-card-actions>
          <v-btn @click="setTimeRange('1m')">Month</v-btn>
          <v-btn @click="setTimeRange('1y')">Year</v-btn>
          <v-btn @click="showAllTime()">All Time</v-btn>
        </v-card-actions>
        <Chart :chartOptions="chartOptions"
      /></v-container>
    </v-card>
    <v-card class="mt-10"></v-card>
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
  data() {
    return {
      chartOptions: {
        chart: {
          backgroundColor: 'transparent',
        },
        title: {
          text: 'New User Chart',
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
      user: {
        initials: 'JD',
        fullName: 'John Doe',
        email: 'john.doe@doe.com',
      },
      routersku: [
        {
          nama: 'Produk',
          rt: '/halaman-admin/management-produk',
          icon: 'mdi-store-plus',
        },
        {
          nama: 'User',
          rt: '/halaman-admin/management-user',
          icon: 'mdi-package-variant-closed-plus',
        },
        {
          nama: 'Log Aktivitas',
          rt: '/halaman-admin/management-logactivity',
          icon: 'mdi-store-check-outline',
        },
      ],
      originalData: [],
      filteredData: [],
    }
  },
  watch: {
    originalData: {
      handler() {
        this.$nextTick(() => {
          this.setTimeRange('1m')
        })
      },
      deep: true,
      immediate: true,
    },
  },
  methods: {
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
        case '1m':
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
        case '1y':
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
}
</script>