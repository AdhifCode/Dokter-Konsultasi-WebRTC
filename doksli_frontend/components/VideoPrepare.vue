<template>
  <div class="d-flex">
    <v-select
      style="width: 300px"
      solo
      rounded
      outlined
      prepend-inner-icon="mdi-microphone"
      v-model="selectedAudio"
      @change="onChange"
      :items="audios"
    ></v-select>
    <v-select
      solo
      style="width: 300px"
      rounded
      outlined
      prepend-inner-icon="mdi-video"
      v-model="selectedVideo"
      @change="onChange"
      :items="videos"
    ></v-select>
    <v-select
      prepend-inner-icon="mdi-volume-high"
      color="blue"
      solo
      rounded
      style="width: 300px"
      outlined
      depressed
      v-model="selectedSpeaker"
      @change="onChange"
      :items="speakers"
    ></v-select>
  </div>
</template>

<script>
export default {
  props: {
    selectedAudio: String,
    selectedVideo: String,
    selectedSpeaker: String,
    audios: Array,
    videos: Array,
    speakers: Array,
  },
  data() {
    return {
      audioIcon: 'mdi-microphone',
      videoIcon: 'mdi-video',
      speakerIcon: 'mdi-volume-high',
    }
  },
  methods: {
    onChange() {
      this.$emit('change', {
        selectedAudio: this.selectedAudio,
        selectedVideo: this.selectedVideo,
        selectedSpeaker: this.selectedSpeaker,
      })
    },
  },
}
</script>