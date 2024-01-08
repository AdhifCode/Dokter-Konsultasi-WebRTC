<template>
  <div class="consulting">
    <v-dialog v-model="dialogSetting" max-width="290">
      <v-card>
        <v-container>
          <VideoSetting
            :selectedAudio="selectedAudio"
            :selectedVideo="selectedVideo"
            :selectedSpeaker="selectedSpeaker"
            :audios="audios"
            :videos="videos"
            :speakers="speakers"
            @change="onChange"
          />
        </v-container>
      </v-card>
    </v-dialog>

    <p>Your Peer ID: {{ peerId }}</p>
    <div>
      <v-btn
        fab
        dark
        :ripple="false"
        color="#0C8CE9"
        @click="startVideo()"
        style="width: 56px; height: 56px"
      >
        <v-icon dark>mdi-video</v-icon>
      </v-btn>

      <v-btn
        fab
        dark
        :ripple="false"
        color="#31C9A2"
        @click="startVideo()"
        style="width: 56px; height: 56px"
      >
        <v-icon dark>mdi-microphone</v-icon>
      </v-btn>

      <v-btn color="primary" dark @click.stop="dialogSetting = true">
        <v-icon>mdi-dots-vertical</v-icon>
      </v-btn>
    </div>

    <div v-for="peer in peers" :key="peer.peerId">
      <video
        class="their-video"
        :id="peer.peerId"
        :peerId="peer.peerId"
        :srcObject.prop="peer.stream"
        width="740px"
        height="416px"
        color="grey lighten-1"
        autoplay
        playsinline
      ></video>
      <span>{{ peer.peerId }}</span>
    </div>
    <div>
      <video
        id="my-video"
        muted
        width="740px"
        height="416px"
        autoplay
        color="grey lighten-1"
        playsinline
      ></video>
    </div>
  </div>
</template>

<script>
import Peer from 'skyway-js'
import VideoSetting from '@/components/VideoSetting.vue'

export default {
  components: { VideoSetting },
  data() {
    return {
      localStream: null,
      peerId: '',
      inputId: '',
      dialogSetting: false,
      peer: null,
      roomId: 'punten',
      peers: [],
      selectedAudio: '',
      selectedVideo: '',
      selectedSpeaker: '',
      audios: [],
      videos: [],
      speakers: [],
    }
  },
  mounted() {
    this.peer = new Peer({
      key: 'abe02cb1-4e86-49d6-87f0-d682a3fd6a5f',
      debug: 3,
    })

    this.peer.on('open', (peerId) => {
      this.peerId = peerId
      const roomId = this.$route.query.roomId
      if (roomId) {
        this.joinRoom()
      }
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

    const savedSettings = localStorage.getItem('meetingSettings')
    if (savedSettings) {
      const { selectedAudio, selectedVideo, selectedSpeaker } =
        JSON.parse(savedSettings)
      this.selectedAudio = selectedAudio
      this.selectedVideo = selectedVideo
      this.selectedSpeaker = selectedSpeaker
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
        const myVideoElement = document.getElementById('my-video')

        if (myVideoElement) {
          myVideoElement.srcObject = stream
          this.localStream = stream

          // Panggil metode untuk mengganti stream di room SkyWay
          if (this.room) {
            this.room.replaceStream(this.localStream)
          }
        } else {
          console.error("Element with id 'my-video' not found.")
        }
      } catch (err) {
        console.error(err)
      }
    },
    // toggleVideo() {
    //   this.videoButtonClicked = !this.videoButtonClicked
    //   if (this.videoButtonClicked) {
    //     this.startVideo()
    //   } else {
    //     this.stopCamera()
    //   }
    //   console.log(`Video state: ${this.videoButtonClicked ? 'ON' : 'OFF'}`)
    // },

    // toggleVoice() {
    //   this.voiceButtonClicked = !this.voiceButtonClicked
    //   if (this.voiceButtonClicked) {
    //     this.startVoice()
    //   } else {
    //     this.stopVoice()
    //   }
    //   console.log(`Voice state: ${this.voiceButtonClicked ? 'ON' : 'OFF'}`)
    // },

    startVoice() {
      navigator.mediaDevices
        .getUserMedia({ video: false, audio: true })
        .then((stream) => {
          this.localStream = stream
        })
        .then(this.joinRoom)
        .catch((err) => {
          alert(`Error: Your device cannot use this type of stream.`)
        })
    },

    startVideo() {
      navigator.mediaDevices
        .getUserMedia({ video: true, audio: true })
        .then((stream) => {
          this.localStream = stream
          const myVideoElement = document.getElementById('my-video')

          if (myVideoElement) {
            myVideoElement.srcObject = this.localStream
          } else {
            console.error("Element with id 'my-video' not found.")
          }

          if (this.room) {
            this.room.replaceStream(this.localStream)
          }

          this.joinRoom()
        })
        .catch((err) => {
          alert(`Error: Your device cannot use this type of stream.`)
        })
    },

    joinRoom() {
      const peer = this.peer
      if (this.room) {
        this.room.replaceStream(this.localStream)
        return
      }
      this.room = peer.joinRoom(this.roomId, {
        mode: 'mesh',
        stream: this.localStream,
      })

      this.room.once('open', () => {
        console.log('=== You joined ===\n')
      })
      this.room.on('peerJoin', (peerId) => {
        console.log(`=== ${peerId} joined ===\n`)
      })

      this.room.on('peerLeave', (peerId) => {
        this.peers = this.peers.filter((p) => p.peerId !== peerId)
      })

      this.room.on('stream', async (stream) => {
        this.peers = this.peers.filter((p) => p.peerId !== stream.peerId)
        this.peers.push({ peerId: stream.peerId, stream: stream })
      })
    },
  },
  destroyed() {
    if (this.room) this.room.close()
    if (this.peer) this.peer.destroy()
  },
}
</script>

<style>
#cameraView {
  transform: rotateY(180deg);
  -webkit-transform: rotateY(180deg);
  -moz-transform: rotateY(180deg);
}
#my-video {
  background-color: rgb(81, 81, 81);
  border-radius: 10px;
}
.their-video {
  background-color: rgb(81, 81, 81);
  border-radius: 10px;
}
</style>
