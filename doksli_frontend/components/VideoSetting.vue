<template>
  <div>
    <div>
      <span>Audio : </span>
      <v-select
        solo
        :prepend-icon="audioIcon"
        v-model="selectedAudio"
        @change="onChange"
        :items="audios"
      ></v-select>
      <span>Camera : </span>
      <v-select
        solo
        :prepend-icon="videoIcon"
        v-model="selectedVideo"
        @change="onChange"
        :items="videos"
      ></v-select>
      <span>Speaker : </span>
      <v-select
        :prepend-icon="speakerIcon"
        color="blue"
        solo
        depressed
        v-model="selectedSpeaker"
        @change="onChange"
        :items="speakers"
      ></v-select>
    </div>
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
  