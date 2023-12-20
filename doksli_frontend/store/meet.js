export const state = () => ({
  inputId: null,
})

export const mutations = {
  setInputId(state, id) {
    state.inputId = id
  },
}

export const getters = {
  getInputId: (state) => state.inputId,
}
