
<template>
  <div>
    <v-app-bar app
      ><p>Your Peer ID: {{ peerId }}</p>
    </v-app-bar>
    <div class="video-container">
      <div class="screenShare" v-if="displayButtonClicked && localStream">
        <video
          :id="'screen-share-' + peerId"
          :srcObject.prop="localStream"
          autoplay
          playsinline
        ></video>
      </div>
      <div class="otherScreen" v-for="peer in peers" :key="peer.peerId">
        <!-- {{ console.log('peer.stream:', peer.stream) }} -->
        <div v-if="!peer.stream">User {{ peer.peerId }} - No Stream</div>
        <video
          v-else
          :id="peer.peerId"
          :srcObject.prop="peer.stream"
          autoplay
          playsinline
        ></video>
      </div>
      <div class="myScreen">
        <div class="idleScreen" v-if="!localStream">{{ peerId }}</div>
        <video
          v-else
          id="my-video"
          :srcObject.prop="localStream"
          autoplay
          muted
          playsinline
        ></video>
      </div>
    </div>
    <div class="btn-action">
      <v-btn
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
            :fill="videoButtonClicked ? 'black' : 'white'"
            d="M21.526 8.149C21.231 7.966 20.862 7.951 20.553 8.105L18 9.382V7C18 5.897 17.103 5 16 5H4C2.897 5 2 5.897 2 7V17C2 18.104 2.897 19 4 19H16C17.103 19 18 18.104 18 17V14.618L20.553 15.894C20.694 15.965 20.847 16 21 16C21.183 16 21.365 15.949 21.526 15.851C21.82 15.668 22 15.347 22 15V9C22 8.653 21.82 8.332 21.526 8.149Z"
          ></path>
        </svg>
      </v-btn>
      <v-btn
        tonal
        :ripple="false"
        rounded
        icon
        @click="displayButtonClicked ? stopDisplay() : startDisplay()"
        style="width: 56px; height: 56px"
        :class="{ 'button-clicked': displayButtonClicked }"
      >
        <svg
          class="controlIcon-10O-4h centerIcon-JYpTUi"
          width="24"
          height="24"
          viewBox="0 0 24 24"
          xmlns="http://www.w3.org/2000/svg"
        >
          <path
            :fill="displayButtonClicked ? 'black' : 'white'"
            fill-rule="evenodd"
            clip-rule="evenodd"
            d="M2 4.5C2 3.397 2.897 2.5 4 2.5H20C21.103 2.5 22 3.397 22 4.5V15.5C22 16.604 21.103 17.5 20 17.5H13V19.5H17V21.5H7V19.5H11V17.5H4C2.897 17.5 2 16.604 2 15.5V4.5ZM13.2 14.3375V11.6C9.864 11.6 7.668 12.6625 6 15C6.672 11.6625 8.532 8.3375 13.2 7.6625V5L18 9.6625L13.2 14.3375Z"
          ></path>
        </svg>
      </v-btn>
      <v-btn
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
            :fill="voiceButtonClicked ? 'black' : 'white'"
          ></path>
          <path
            d="M9.01 11.085C9.015 11.1125 9.02 11.14 9.02 11.17L15 5.18V5C15 3.34 13.66 2 12 2C10.34 2 9 3.34 9 5V11C9 11.03 9.005 11.0575 9.01 11.085Z"
            :fill="voiceButtonClicked ? 'black' : 'white'"
          ></path>
          <path
            d="M11.7237 16.0927L10.9632 16.8531L10.2533 17.5688C10.4978 17.633 10.747 17.6839 11 17.72V22H13V17.72C16.28 17.23 19 14.41 19 11H17.3C17.3 14 14.76 16.1 12 16.1C11.9076 16.1 11.8155 16.0975 11.7237 16.0927Z"
            :fill="voiceButtonClicked ? 'black' : 'white'"
          ></path>
          <path
            d="M21 4.27L19.73 3L3 19.73L4.27 21L8.46 16.82L9.69 15.58L11.35 13.92L14.99 10.28L21 4.27Z"
            class="slash-2yrR11"
            :fill="voiceButtonClicked ? 'red' : 'white'"
          ></path></svg></v-btn
      ><v-btn
        tonal
        :ripple="false"
        icon
        @click="disconnect"
        style="width: 56px; height: 56px"
        class="discon"
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
            fill="currentColor"
            fill-rule="evenodd"
            clip-rule="evenodd"
            d="M21.1169 1.11603L22.8839 2.88403L19.7679 6.00003L22.8839 9.11603L21.1169 10.884L17.9999 7.76803L14.8839 10.884L13.1169 9.11603L16.2329 6.00003L13.1169 2.88403L14.8839 1.11603L17.9999 4.23203L21.1169 1.11603ZM18 22H13C6.925 22 2 17.075 2 11V6C2 5.447 2.448 5 3 5H7C7.553 5 8 5.447 8 6V10C8 10.553 7.553 11 7 11H6C6.063 14.938 9 18 13 18V17C13 16.447 13.447 16 14 16H18C18.553 16 19 16.447 19 17V21C19 21.553 18.553 22 18 22Z"
          ></path>
        </svg>
      </v-btn>
    </div>
    <v-alert v-if="userJoinedMessage" type="success">
      {{ userJoinedMessage }}
    </v-alert>
  </div>
</template>
  <script>
import Peer from 'skyway-js'

export default {
  data() {
    return {
      userJoinedMessage: '',
      displayButtonClicked: false,
      videoButtonClicked: false,
      voiceButtonClicked: false,
      localStream: null,
      peerId: '',
      inputId: '',
      peer: null,
      room: false,
      peers: [],
      displayPeerId: '',
      displayPeer: null,
      displayRoom: null,
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
  },
  methods: {
    async startDisplay() {
      if (!navigator.mediaDevices.getDisplayMedia) {
        alert(`Error: Your device cannot use this type of stream.`)
        return
      }

      // Hentikan stream lama jika ada
      if (!this.peer || !this.peer.open) {
        // Jika belum terhubung, mungkin perlu menunggu atau memberikan pesan kepada pengguna
        console.error('Error: Peer is not connected to SkyWay server.')
        return
      }

      // Hentikan stream lama jika ada
      if (this.localStream) {
        await this.localStream.getTracks().forEach((track) => track.stop())
        this.localStream = null
      }

      // Dapatkan stream baru dari tampilan
      const displayStream = await navigator.mediaDevices.getDisplayMedia()
      this.localStream = displayStream
      this.displayButtonClicked = true

      // Hentikan stream lama jika ada (jika ingin membuat peer baru setiap kali)
      if (this.displayPeer) {
        this.displayPeer.destroy()
        this.displayPeer = null
      }

      // Membuat peer baru untuk berbagi tampilan
      const displayPeer = new Peer({
        key: 'abe02cb1-4e86-49d6-87f0-d682a3fd6a5f',
        debug: 3,
      })

      displayPeer.on('open', (displayPeerId) => {
        this.displayPeerId = displayPeerId
      })

      this.displayPeer = displayPeer

      // Bergabung ke dalam room dengan stream baru
      this.joinDisplayRoom()
      // const displayStream = await navigator.mediaDevices.getDisplayMedia()
      // this.localStream = displayStream
      // this.displayButtonClicked = true

      // if (this.videoButtonClicked) {
      //   this.addScreenShareView()
      // }
      // const screenShareVideo = document.getElementById(
      //   'screen-share-' + this.peerId
      // )
      // screenShareVideo.srcObject = displayStream

      // this.joinRoom()
    },

    // async stopDisplay() {
    //   if (this.localStream) {
    //     this.localStream.getTracks().forEach((track) => track.stop())
    //     this.localStream = null
    //   }
    //   this.displayButtonClicked = false
    // },
    async stopDisplay() {
      if (this.localStream) {
        this.localStream.getTracks().forEach((track) => track.stop())
        this.localStream = null
      }

      if (this.displayPeer && this.displayPeer.open) {
        this.displayPeer.destroy()
        this.displayPeer = null
      }

      this.displayButtonClicked = false
    },

    joinDisplayRoom() {
      if (this.displayPeer) {
        this.displayRoom = this.displayPeer.joinRoom('displayRoom', {
          mode: 'mesh',
          stream: this.localStream,
        })

        this.displayRoom.on('peerLeave', (peerId) => {
          // Handle event ketika peer meninggalkan room display
          console.log(`Peer ${peerId} meninggalkan room display`)
        })

        this.displayRoom.on('stream', async (stream) => {
          // Handle event ketika ada stream baru pada room display
          console.log(
            `Stream baru dari peer ${stream.peerId} pada room display`
          )

          // Misalnya, Anda dapat menambahkan stream tersebut ke daftar peers
          // Jangan lupa untuk menyertakan peerId untuk stream di daftar peers
          this.peers = this.peers.filter((p) => p.peerId !== stream.peerId)
          this.peers.push({ peerId: stream.peerId, stream: stream })
        })
      }
    },

    async startVoice() {
      if (this.localStream) {
        await this.localStream.getAudioTracks().forEach((track) => track.stop())
        this.localStream = null
      }
      try {
        const stream = await navigator.mediaDevices.getUserMedia({
          audio: true,
        })
        this.localStream = stream
        this.voiceButtonClicked = true
      } catch (err) {
        console.error(err)
      }
    },
    async stopVoice() {
      if (this.localStream) {
        this.localStream.getTracks().forEach((track) => track.stop())
        this.localStream = null
      }
      this.voiceButtonClicked = false
    },
    async startVideo() {
      if (this.localStream) {
        this.localStream.getVideoTracks().forEach((track) => track.stop())
        this.localStream = null
      }
      try {
        const stream = await navigator.mediaDevices.getUserMedia({
          video: true,
          audio: true,
        })
        this.localStream = stream
        this.videoButtonClicked = true
        this.joinRoom()
      } catch (err) {
        console.error(err)
      }
    },
    async stopVideo() {
      if (this.localStream) {
        await this.localStream.getTracks().forEach((track) => track.stop())
        this.localStream = null
        this.videoButtonClicked = false
      }
    },
    joinRoom() {
      const peer = this.peer
      if (this.room) {
        this.room.replaceStream(this.localStream)
        return
      }
      this.room = peer.joinRoom(this.$route.query.roomId, {
        mode: 'mesh',
        stream: this.localStream,
      })

      this.room.on('peerLeave', (peerId) => {
        this.peers = this.peers.filter((p) => p.peerId !== peerId)
      })

      this.room.on('peerJoin', (peerId) => {
        this.userJoinedMessage = `User with Peer ID ${peerId} has joined the room!`
        setTimeout(() => {
          this.userJoinedMessage = ''
        }, 5000)
      })

      this.room.on('stream', async (stream) => {
        this.peers = this.peers.filter((p) => p.peerId !== stream.peerId)
        this.peers.push({ peerId: stream.peerId, stream: stream })
      })
    },
    disconnect() {
      if (this.room) {
        this.room.close()
        this.room = null
      }

      if (this.peer) {
        this.peer.destroy()
        this.peer = null
      }

      if (this.localStream) {
        this.localStream.getTracks().forEach((track) => track.stop())
        this.localStream = null
      }

      this.peers = []
      this.displayButtonClicked = false
      this.voiceButtonClicked = false
      this.videoButtonClicked = false

      this.$router.push('/')
    },
  },
  destroyed() {
    if (this.room) this.room.close()
    if (this.peer) this.peer.destroy()
  },
}
</script>
  
    <style>
.video-container {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(900px, 1fr));
  gap: 10px;
  justify-content: center;
  justify-items: center;
  align-items: center;
  height: 50vh;
}

.screenShare,
.otherScreen,
.myScreen,
#my-video {
  width: 100%;
  height: 100%;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  background-color: gray;
  border-radius: 10px;
}

.screenShare video,
.myScreen,
#my-video {
  width: 50%;
  height: 50%;
  max-width: 50%;
  max-height: 50vh;
}

/* .video-container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 50vh;
  }
  #cameraView {
    transform: rotateY(180deg);
    -webkit-transform: rotateY(180deg);
  }
  #my-video {
    max-width: 50vw;
    max-height: 30vh;
    right: 10px;
    bottom: 10px;
  } */

.discon {
  background-color: red;
}

.btn-action {
  position: fixed;
  display: flex;
  justify-content: space-between;
  width: 15%;
  right: 800px;
  bottom: 50px;
}

.button-clicked {
  background-color: white;
}

body::-webkit-scrollbar {
  width: 0em;
}
</style>