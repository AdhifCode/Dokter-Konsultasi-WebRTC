<template>
  <v-container>
    <div>
      <v-row
        ><v-col cols="8">
          <div class="video-container" style="background-color: grays">
            <video
              id="my-video"
              muted="true"
              width="740px"
              height="416px"
              autoplay
              color="grey lighten-1"
              playsinline
            ></video>
            <div class="btn-action">
              <v-btn
                outlined
                tonal
                :ripple="false"
                rounded
                icon
                @click="videoButtonClicked ? stopVideo() : startVideo()"
                :class="{ 'button-clicked': videoButtonClicked }"
                style="width: 56px; height: 56px"
              >
                <svg
                  class="controlIcon-10O-4h centerIcon-JYpTUi"
                  aria-hidden="true"
                  role="img"
                  width="24"
                  height="24"
                  viewBox="0 0 24 24"
                >
                  <path
                    :fill="videoButtonClicked ? 'white' : 'black'"
                    d="M21.526 8.149C21.231 7.966 20.862 7.951 20.553 8.105L18 9.382V7C18 5.897 17.103 5 16 5H4C2.897 5 2 5.897 2 7V17C2 18.104 2.897 19 4 19H16C17.103 19 18 18.104 18 17V14.618L20.553 15.894C20.694 15.965 20.847 16 21 16C21.183 16 21.365 15.949 21.526 15.851C21.82 15.668 22 15.347 22 15V9C22 8.653 21.82 8.332 21.526 8.149Z"
                  ></path>
                </svg>
              </v-btn>
              <v-btn
                outlined
                tonal
                :ripple="false"
                rounded
                icon
                @click="voiceButtonClicked ? stopVoice() : startVoice()"
                :class="{ 'button-clicked': voiceButtonClicked }"
                style="width: 56px; height: 56px"
              >
                <svg
                  class="controlIcon-10O-4h centerIcon-JYpTUi"
                  aria-hidden="true"
                  role="img"
                  width="24"
                  height="24"
                  viewBox="0 0 24 24"
                >
                  <path
                    d="M6.7 11H5C5 12.19 5.34 13.3 5.9 14.28L7.13 13.05C6.86 12.43 6.7 11.74 6.7 11Z"
                    :fill="voiceButtonClicked ? 'white' : 'black'"
                  ></path>
                  <path
                    d="M9.01 11.085C9.015 11.1125 9.02 11.14 9.02 11.17L15 5.18V5C15 3.34 13.66 2 12 2C10.34 2 9 3.34 9 5V11C9 11.03 9.005 11.0575 9.01 11.085Z"
                    :fill="voiceButtonClicked ? 'white' : 'black'"
                  ></path>
                  <path
                    d="M11.7237 16.0927L10.9632 16.8531L10.2533 17.5688C10.4978 17.633 10.747 17.6839 11 17.72V22H13V17.72C16.28 17.23 19 14.41 19 11H17.3C17.3 14 14.76 16.1 12 16.1C11.9076 16.1 11.8155 16.0975 11.7237 16.0927Z"
                    :fill="voiceButtonClicked ? 'white' : 'black'"
                  ></path>
                  <path
                    d="M21 4.27L19.73 3L3 19.73L4.27 21L8.46 16.82L9.69 15.58L11.35 13.92L14.99 10.28L21 4.27Z"
                    class="slash-2yrR11"
                    :fill="voiceButtonClicked ? 'red' : 'black'"
                  ></path></svg
              ></v-btn>
            </div>
          </div>

          <div class="d-flex main" style="max-width: 170px">
            <!-- <div > -->
            <v-select
              solo
              prepend-icon="mdi-microphone"
              v-model="selectedAudio"
              @change="onChange"
              :items="audios"
            >
            </v-select>
            <v-select
              solo
              prepend-icon="mdi-video"
              v-model="selectedVideo"
              @change="onChange"
              :items="videos"
            ></v-select>

            <v-select
              prepend-icon="mdi-volume-high"
              color="blue"
              solo
              depressed
              v-model="selectedSpeaker"
              @change="onChange"
              :items="speakers"
            >
            </v-select></div
        ></v-col>
        <v-col cols="4">
          <div>
            <p>
              Your id: <span id="my-id">{{ peerId }}</span>
            </p>
            <!-- <v-btn @click="makeCall" class="button--green">Join Now</v-btn> -->
            <v-btn depressed @click="join" class="button--green"
              >Join Now</v-btn
            >
          </div></v-col
        >
      </v-row>
    </div>
  </v-container>
</template>
    
<script>
import Peer from 'skyway-js'

export default {
  data() {
    return {
      APIKey: 'abe02cb1-4e86-49d6-87f0-d682a3fd6a5f',
      videoButtonClicked: false,
      voiceButtonClicked: false,
      selectedAudio: '',
      selectedVideo: '',
      selectedSpeaker: '',
      audios: [],
      videos: [],
      speakers: [],
      localStream: null,
      peerId: '',
      roomId: '',
      inputId: '20',
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

    join() {
      this.$router.push(`../${this.inputId}`)
    },

    startVideo() {
      this.videoButtonClicked = true
      this.localStream.getVideoTracks().forEach((track) => {
        track.enabled = true
      })
    },

    stopVideo() {
      this.videoButtonClicked = false
      this.localStream.getVideoTracks().forEach((track) => {
        track.enabled = false
      })
    },

    startVoice() {
      this.voiceButtonClicked = true
      this.localStream.getAudioTracks().forEach((track) => {
        track.enabled = true
      })
    },

    stopVoice() {
      this.voiceButtonClicked = false
      this.localStream.getAudioTracks().forEach((track) => {
        track.enabled = false
      })
    },

    // makeCall: function () {
    //   console.log('makeCall')
    //   const call = this.peer.call(this.calltoid, this.localStream)
    //   this.connect(call)
    // },

    // connect: function (call) {
    //   call.on('stream', (stream) => {
    //     const el = document.getElementById('their-video')
    //     el.srcObject = stream
    //     el.play()
    //   })
    // },
  },

  mounted: function () {
    this$store
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
      this.connectLocalCamera()
    })
  },
}
</script>

<style>
#my-video {
  background-color: rgb(81, 81, 81);
  border-radius: 10px;
}
</style>
  