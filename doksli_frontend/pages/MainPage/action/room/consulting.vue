<template>
  <div :class="{ page: consulting, 'consulting-page': !consulting }">
    <v-alert
      v-if="alertData.show"
      :type="alertData.type"
      dismissible
      dense
      @input="resetAlert"
    >
      {{ alertData.message }}
    </v-alert>
    <v-navigation-drawer v-model="drawer" absolute temporary>
      <v-list-item>
        <v-list-item-avatar>
          <v-img src="https://randomuser.me/api/portraits/men/78.jpg"></v-img>
        </v-list-item-avatar>

        <v-list-item-content>
          <v-list-item-title>John Leider</v-list-item-title>
        </v-list-item-content>
      </v-list-item>

      <v-divider></v-divider>

      <v-list dense>
        <v-list-item v-for="item in items" :key="item.title" link>
          <v-list-item-icon>
            <v-icon>{{ item.icon }}</v-icon>
          </v-list-item-icon>

          <v-list-item-content>
            <v-list-item-title>{{ item.title }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>
    <v-container>
      <div v-if="!consulting" class="preparing mt-16">
        <v-row class="pt-4"
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
              <div
                class="mt-4"
                style="position: absolute; left: 33%; bottom: 33%"
              >
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
            <VideoPrepare
              :selectedAudio="selectedAudio"
              :selectedVideo="selectedVideo"
              :selectedSpeaker="selectedSpeaker"
              :audios="audios"
              :videos="videos"
              :speakers="speakers"
              @change="onChange"
            />
          </v-col>
          <v-col
            cols="4"
            align="center"
            class="d-flex align-center justify-center"
          >
            <div>
              <p>
                Your id: <span id="my-id">{{ peerId }}</span>
              </p>
              <v-btn
                color="#0C8CE9"
                rounded
                dark
                class="text-capitalize"
                @click="startMeeting()"
                >Join Now</v-btn
              >
            </div></v-col
          >
        </v-row>
      </div>
      <div v-else class="consulting">
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

        <!-- <p>Your Peer ID: {{ peerId }}</p>
      <p>Your Username: {{ userData.name }}</p> -->

        <div v-for="peer in peers" :key="peer.peerId">
          <video
            class="their-video"
            :id="peer.peerId"
            :peerId="peer.peerId"
            :srcObject.prop="peer.stream"
            color="grey lighten-1"
            autoplay
            playsinline
          ></video>
          <!-- <span>{{ peer.stream.peerId }}</span> -->
        </div>

        <div class="consulting">
          <video
            id="my-video"
            muted
            width="185px"
            height="104px"
            autoplay
            color="grey lighten-1"
            playsinline
          ></video>
        </div>
        <v-footer color="transparent" fixed padless>
          <v-container
            ><v-row justify="center" align="center">
              <v-col>
                <v-btn
                  fab
                  small
                  outlined
                  dark
                  @click.stop="dialogSetting = true"
                >
                  <v-icon dark>mdi-dots-vertical</v-icon>
                </v-btn>
              </v-col>

              <v-col>
                <v-row justify="center" align="center">
                  <v-btn
                    fab
                    class="mx-2"
                    dark
                    small
                    :ripple="false"
                    color="#0C8CE9"
                    @click="toggleVideo()"
                    :class="{ 'button-clicked': videoButtonClicked }"
                  >
                    <v-icon dark>
                      {{ videoButtonClicked ? 'mdi-video' : 'mdi-video-off' }}
                    </v-icon>
                  </v-btn>

                  <v-btn
                    fab
                    dark
                    small
                    class="mx-2"
                    :ripple="false"
                    color="#31C9A2"
                    @click="toggleVoice()"
                    :class="{ 'button-clicked': voiceButtonClicked }"
                  >
                    <v-icon dark>
                      {{
                        voiceButtonClicked
                          ? 'mdi-microphone'
                          : 'mdi-microphone-off'
                      }}
                    </v-icon>
                  </v-btn>

                  <v-btn
                    class="mx-2"
                    fab
                    color="#FF6B82"
                    dark
                    small
                    @click="disconnect()"
                  >
                    <v-icon dark>mdi-phone-off</v-icon>
                  </v-btn>
                </v-row>
              </v-col>

              <v-col class="text-right">
                <v-btn
                  fab
                  color="#9675F7"
                  small
                  dark
                  @click.stop="drawer = !drawer"
                >
                  <v-icon dark>mdi-message-text</v-icon>
                </v-btn>
              </v-col>
            </v-row></v-container
          >
        </v-footer>
      </div></v-container
    >
  </div>
</template>

<script>
import Peer from 'skyway-js'
import axios from 'axios'
import VideoSetting from '@/components/VideoSetting.vue'
import VideoPrepare from '@/components/VideoPrepare.vue'

export default {
  components: { VideoSetting, VideoPrepare },
  middleware: 'consulting',
  data() {
    return {
      userId: '',
      userData: '',
      localStream: null,
      peerId: '',
      inputId: '',
      dialogSetting: false,
      peer: null,
      roomId: '',
      consulting: false,
      peers: [],
      videoButtonClicked: true,
      voiceButtonClicked: true,
      selectedAudio: '',
      selectedVideo: '',
      selectedSpeaker: '',
      audios: [],
      videos: [],
      speakers: [],
      alertData: {
        show: false,
        type: 'info',
        message: '',
      },
      drawer: null,
    }
  },
  mounted() {
    this.peer = new Peer({
      key: 'abe02cb1-4e86-49d6-87f0-d682a3fd6a5f',
      debug: 3,
    })

    this.peer.on('open', (peerId) => {
      this.peerId = peerId
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

    this.getuser()
  },
  created() {
    this.userId = this.$cookies.get('loginCookie').data?.data.id
    this.roomId = this.$cookies.get('MeetingPermission')
  },
  methods: {
    cek() {
      console.log(this.peers)
      console.log(this.localStream)
    },
    getuser() {
      axios
        .get('http://localhost:8000/api/users/' + this.userId)
        .then((response) => {
          console.log(response)
          this.us = response.data?.data
          console.log(this.userData)
        })
        .catch((error) => {
          console.error('Error fetching user data:', error.response.data)
        })
    },

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
          const isAlreadyPlaying = myVideoElement.srcObject
          myVideoElement.srcObject = stream
          this.localStream = stream

          if (this.room && isAlreadyPlaying) {
            this.room.replaceStream(this.localStream)
          }
        } else {
          console.error("Element with id 'my-video' not found.")
        }
      } catch (err) {
        console.error(err)
      }
    },

    toggleVideo() {
      this.videoButtonClicked = !this.videoButtonClicked

      if (this.videoButtonClicked) {
        this.startVideo()
      } else {
        this.stopVideo()
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

    stopVideo() {
      if (this.localStream) {
        const tracks = this.localStream.getVideoTracks()
        tracks.forEach((track) => track.stop())
        this.localStream = null
      }
    },

    stopVoice() {
      if (this.localStream) {
        const tracks = this.localStream.getAudioTracks()
        tracks.forEach((track) => track.stop())
        this.localStream = null
      }
    },

    startVoice() {
      navigator.mediaDevices
        .getUserMedia({ video: false, audio: true })
        .then((stream) => {
          this.localStream = stream
        })
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
        })
        .catch((err) => {
          alert(`Error: Your device cannot use this type of stream.`)
        })
    },

    startMeeting() {
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
          this.consulting = true

          if (this.videoButtonClicked) {
            this.startVideo()
          } else {
            this.stopVideo()
          }
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
        this.showAlert('info', `Peer ${peerId} joined.`)
      })

      this.room.on('peerLeave', (peerId) => {
        this.peers = this.peers.filter((p) => p.peerId !== peerId)
        this.showAlert('error', `Peer ${peerId} disconnected.`)
      })

      this.room.on('stream', async (stream, peerId) => {
        // Get user data for the peer from the API
        axios
          .get('http://127.0.0.1:8000/api/users/' + this.userId)
          .then((response) => {
            const peerUserData = response.data?.data

            // Include user data in the stream object
            const peerStream = {
              peerId: peerId,
              stream: stream,
              userData: peerUserData,
            }

            // Update or add the peer to the peers array
            const existingPeerIndex = this.peers.findIndex(
              (p) => p.peerId === peerId
            )
            if (existingPeerIndex !== -1) {
              this.$set(this.peers, existingPeerIndex, peerStream)
            } else {
              this.peers.push(peerStream)
            }
          })
          .catch((error) => {
            console.error(
              'Error fetching user data for peer:',
              error.response.data
            )
          })
      })
    },
    disconnect() {
      if (this.room) {
        this.room.close()
        this.room = null
        this.showAlert('error', 'You have disconnected from the room.')
      }

      if (this.localStream) {
        const tracks = this.localStream.getTracks()
        tracks.forEach((track) => track.stop())
        this.localStream = null
      }

      if (this.peer) {
        this.peer.destroy()
        this.peer = null
      }

      this.consulting = false

      this.peers = []
    },
    showAlert(type, message) {
      this.alertData.show = true
      this.alertData.type = type
      this.alertData.message = message

      setTimeout(() => {
        this.resetAlert()
      }, 5000)
    },

    resetAlert() {
      this.alertData.show = false
      this.alertData.type = 'info'
      this.alertData.message = ''
    },
  },

  destroyed() {
    if (this.room) this.room.close()
    if (this.peer) this.peer.destroy()
    this.disconnect()
  },
}
</script>

<style scoped>
#cameraView {
  transform: rotateY(180deg);
  -webkit-transform: rotateY(180deg);
  -moz-transform: rotateY(180deg);
}

#my-video {
  background-color: rgb(81, 81, 81);
  border-radius: 10px;
}

.page {
  background-color: #1f1f1f;
  height: 100%;
}

.consulting #my-video {
  background-color: rgb(81, 81, 81);
  border-radius: 10px;
  position: absolute;
  box-shadow: 0 0 2px 0.1px #ffffffb7;
  top: 0;
  right: 0;
  margin: 20px 50px 0 0; /* Atur margin sesuai kebutuhan */
}
.their-video {
  background-color: rgb(81, 81, 81);
  border-radius: 10px;
  width: 100%;
  height: 89.6vh;
  /* padding-bottom: 56.5%; */
}
</style>
