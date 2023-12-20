<template>
  <section class="container">
    <div>
      <video id="their-video" width="200" autoplay playsinline></video>
      <video
        id="my-video"
        muted="true"
        width="200"
        autoplay
        playsinline
      ></video>

      <div class="main">
        <h2>Doksli</h2>
        Audio :
        <select v-model="selectedAudio" @change="onChange">
          <option disabled value="">Please select one</option>
          <option
            v-for="(audio, key, index) in audios"
            v-bind:key="index"
            :value="audio.value"
          >
            {{ audio.text }}
          </option>
        </select>

        Video :
        <select v-model="selectedVideo" @change="onChange">
          <option disabled value="">Please select one</option>
          <option
            v-for="(video, key, index) in videos"
            v-bind:key="index"
            :value="video.value"
          >
            {{ video.text }}
          </option>
        </select>

        Speaker :
        <select v-model="selectedSpeaker" @change="onChange">
          <option disabled value="">Please select one</option>
          <option
            v-for="(speaker, key, index) in speakers"
            v-bind:key="index"
            :value="speaker.value"
          >
            {{ speaker.text }}
          </option>
        </select>

        <div>
          <p>
            Your id: <span id="my-id">{{ peerId }}</span>
          </p>
          <p>Tes</p>
          <input v-model="calltoid" placeholder="call id" />
          <button @click="makeCall" class="button--green">Call</button>
        </div>
      </div>
    </div>
  </section>
</template>
  
<script>
import Peer from 'skyway-js'

export default {
  data() {
    return {
      APIKey: 'abe02cb1-4e86-49d6-87f0-d682a3fd6a5f',
      selectedAudio: '',
      selectedVideo: '',
      selectedSpeaker: '',
      audios: [],
      videos: [],
      speakers: [],
      localStream: null,
      peerId: '',
      calltoid: '',
    }
  },

  methods: {
    onChange: function () {
      if (
        this.selectedAudio != '' &&
        this.selectedVideo != '' &&
        this.selectedSpeaker != ''
      ) {
        this.connectLocalCamera()
      }
    },

    connectLocalCamera: async function () {
      const constraints = {
        audio: this.selectedAudio
          ? { deviceId: { exact: this.selectedAudio } }
          : false,
        video: this.selectedVideo
          ? { deviceId: { exact: this.selectedVideo } }
          : false,
        speaker: this.selectedSpeaker
          ? { deviceId: { exact: this.selectedSpeaker } }
          : false,
      }

      const stream = await navigator.mediaDevices.getUserMedia(constraints)
      document.getElementById('my-video').srcObject = stream
      this.localStream = stream
    },

    makeCall: function () {
      console.log('makeCall')
      const call = this.peer.call(this.calltoid, this.localStream)
      this.connect(call)
    },

    connect: function (call) {
      call.on('stream', (stream) => {
        const el = document.getElementById('their-video')
        el.srcObject = stream
        el.play()
      })
    },
  },

  mounted: function () {
    this.peer = new Peer({
      key: this.APIKey,
      debug: 3,
    })

    this.peer.on('open', () => {
      this.peerId = this.peer.id
    })

    navigator.mediaDevices.enumerateDevices().then((deviceInfos) => {
      for (let i = 0; i !== deviceInfos.length; ++i) {
        const deviceInfo = deviceInfos[i]
        if (deviceInfo.kind === 'audioinput') {
          this.audios.push({
            text: deviceInfo.label || `Microphone ${this.audios.length + 1}`,
            value: deviceInfo.deviceId,
          })
          if (!this.selectedAudio) {
            this.selectedAudio = deviceInfo.deviceId
          }
        } else if (deviceInfo.kind === 'videoinput') {
          this.videos.push({
            text: deviceInfo.label || `Camera  ${this.videos.length + 1}`,
            value: deviceInfo.deviceId,
          })
          if (!this.selectedVideo) {
            this.selectedVideo = deviceInfo.deviceId
          }
        } else if (deviceInfo.kind === 'audiooutput') {
          this.speakers.push({
            text: deviceInfo.label || `Speaker ${this.speakers.length + 1}`,
            value: deviceInfo.deviceId,
          })
          if (!this.selectedSpeaker) {
            this.selectedSpeaker = deviceInfo.deviceId
          }
        }
      }

      // Automatically connect to the default devices
      this.connectLocalCamera()
    })
  },
}
</script>

