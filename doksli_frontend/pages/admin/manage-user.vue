<template>
  <v-container
    ><div>
      <v-data-table
        :headers="headers"
        :items="tableData"
        :page.sync="page"
        :items-per-page="itemsPerPage"
        hide-default-footer
        class="elevation-1"
        @page-count="pageCount = $event"
      ></v-data-table>
      <div class="text-center pt-2">
        <v-pagination v-model="page" :length="pageCount"></v-pagination>
      </div></div
  ></v-container>
</template>
  
  <script>
import axios from 'axios'

export default {
  layout: 'Admin',
  data() {
    return {
      page: 1,
      pageCount: 0,
      itemsPerPage: 10,
      headers: [
        {
          text: 'Id',
          align: 'start',
          value: 'id',
        },
        { text: 'Name', value: 'name' },
        { text: 'Email', value: 'email' },
        { text: 'Phone', value: 'phone' },
        { text: 'Role', value: 'role.nama' },
        { text: 'Join', value: 'created_at' },
        { text: 'Actions', value: 'actions', sortable: false },
      ],
      tableData: [],
    }
  },
  methods: {
    formatDate(dateString) {
      const options = { day: 'numeric', month: 'short', year: 'numeric' }
      return new Date(dateString).toLocaleDateString('en-US', options)
    },
    getTableData() {
      axios
        .get('http://127.0.0.1:8000/api/getusers')
        .then((response) => {
          this.tableData = response.data.data.map((item) => {
            return {
              ...item,
              created_at: item.created_at
                ? this.formatDate(item.created_at)
                : 'null',
              phone: item.phone || 'null',
              iron: item.iron || 'null',
            }
          })
        })
        .catch((error) => {
          console.error('Error fetching user data:', error)
        })
    },
  },
  created() {
    this.getTableData()
  },
}
</script>
  