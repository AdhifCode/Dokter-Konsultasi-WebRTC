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
            <div>
              <v-btn
                fab
                dark
                :ripple="false"
                color="#0C8CE9"
                @click="toggleVideo()"
                :class="{ 'button-clicked': videoButtonClicked }"
                style="width: 56px; height: 56px"
              >
                <v-icon dark>{{
                  videoButtonClicked ? 'mdi-video' : 'mdi-video-off'
                }}</v-icon>
              </v-btn>

              <v-btn
                fab
                dark
                :ripple="false"
                color="#31C9A2"
                @click="toggleVoice()"
                :class="{ 'button-clicked': voiceButtonClicked }"
                style="width: 56px; height: 56px"
              >
                <v-icon dark>{{
                  voiceButtonClicked ? 'mdi-microphone' : 'mdi-microphone-off'
                }}</v-icon>
              </v-btn>
            </div>
          </div>
          <VideoSetting
            :selectedAudio="selectedAudio"
            :selectedVideo="selectedVideo"
            :selectedSpeaker="selectedSpeaker"
            :audios="audios"
            :videos="videos"
            :speakers="speakers"
            @change="onChange"
          />
        </v-col>
        <v-col cols="4">
          <div>
            <p>
              Your id: <span id="my-id">{{ peerId }}</span>
            </p>
            <v-btn depressed @click="joinRoom" class="button--green"
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
import VideoSetting from '@/components/VideoSetting.vue'

export default {
  components: { VideoSetting },
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
    onChange({ selectedAudio, selectedVideo, selectedSpeaker }) {
      this.selectedAudio = selectedAudio
      this.selectedVideo = selectedVideo
      this.selectedSpeaker = selectedSpeaker

      if (
        this.selectedAudio !== '' &&
        this.selectedVideo !== '' &&
        this.selectedSpeaker !== ''
      ) {
        this.connectLocalCamera()
      }

      const settings = {
        selectedAudio: this.selectedAudio,
        selectedVideo: this.selectedVideo,
        selectedSpeaker: this.selectedSpeaker,
      }
      localStorage.setItem('meetingSettings', JSON.stringify(settings))
    },

    async connectLocalCamera() {
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

      try {
        const stream = await navigator.mediaDevices.getUserMedia(constraints)
        document.getElementById('my-video').srcObject = stream
        this.localStream = stream
      } catch (err) {
        console.error(err)
      }
    },

    toggleVideo() {
      this.videoButtonClicked = !this.videoButtonClicked
      if (this.videoButtonClicked) {
        this.startVideo()
      } else {
        this.stopCamera()
      }
      console.log(`Video state: ${this.videoButtonClicked ? 'ON' : 'OFF'}`)
    },

    toggleVoice() {
      this.voiceButtonClicked = !this.voiceButtonClicked
      if (this.voiceButtonClicked) {
        this.startVoice()
      } else {
        this.stopVoice()
      }
      console.log(`Voice state: ${this.voiceButtonClicked ? 'ON' : 'OFF'}`)
    },

    async startVideo() {
      if (!this.localStream) {
        try {
          const stream = await navigator.mediaDevices.getUserMedia({
            video: true,
            audio: true,
          })
          this.localStream = new MediaStream()
          stream
            .getVideoTracks()
            .forEach((track) => this.localStream.addTrack(track))
          this.connectLocalCamera()
          this.videoButtonClicked = true
          console.log('Video started')
        } catch (err) {
          console.error(err)
        }
      }
      this.saveSettingsToLocal()
    },

    stopCamera() {
      if (this.localStream) {
        this.localStream.getVideoTracks().forEach((track) => track.stop())
        this.localStream = null
        this.videoButtonClicked = false
        console.log('Camera stopped')
      }
    },

    async startVoice() {
      if (!this.localStream) {
        this.localStream = new MediaStream()
      }

      try {
        const stream = await navigator.mediaDevices.getUserMedia({
          audio: true,
        })
        this.localStream.addTrack(stream.getAudioTracks()[0])
        console.log('Voice started')
      } catch (err) {
        console.error(err)
      }
      this.saveSettingsToLocal()
    },

    stopVoice() {
      if (this.localStream) {
        this.localStream.getAudioTracks().forEach((track) => track.stop())
        console.log('Voice stopped')
      }
    },

    saveSettingsToLocal() {
      const settings = {
        selectedAudio: this.selectedAudio,
        selectedVideo: this.selectedVideo,
        selectedSpeaker: this.selectedSpeaker,
      }

      localStorage.setItem('meetingSettings', JSON.stringify(settings))
    },

    joinRoom() {
      this.$router.push(`/MainPage/action/room/consulting`)
      const peer = this.$store.state.peer
      const room = this.$store.state.room
    },
  },

  mounted() {
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
  