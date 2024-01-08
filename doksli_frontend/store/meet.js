export const state = () => ({
  peer: null,
  room: null,
})

export const mutations = {
  setPeer(state, peer) {
    state.peer = peer
  },
  setRoom(state, room) {
    state.room = room
  },
}
