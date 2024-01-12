export const state = () => ({
  cookieData: null,
})

export const mutations = {
  setCookieData(state, cookieData) {
    state.cookieData = cookieData
  },
}

export const actions = {
  makePermission({ commit, app }, orderItem) {
    const currentTime = new Date()
    const endTime = new Date(orderItem.time_end + 'Z')

    const timeDifference = endTime.getTime() - currentTime.getTime()

    const secretKey = orderItem.room
    this.$cookies.set('MeetingPermission', secretKey, {
      maxAge: Math.floor(timeDifference / 1000),
    })

    commit('setCookieData', secretKey)
    window.location.replace('/MainPage/action/room/consulting')
  },
  checkPermission({ commit }) {
    console.log('Checking')
    const MeetingPermission = this.$cookies.get('MeetingPermission')
    console.log('cookie value:', MeetingPermission)
    commit('setCookieData', MeetingPermission)
  },
}
