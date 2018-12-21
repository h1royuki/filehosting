<template>
    <div class = "video-player">
        <player :options="player"></player>
    </div>
</template>

<script>
    import Player from 'vue-dplayer'
    import 'vue-dplayer/dist/vue-dplayer.css'

    export default {

        data() {
            return {
                player: {
                    video: {
                        url: 'http://127.0.0.1:8082/download/' + this.id + '/stream'
                    }
                }
            }
        },

        components: {
            'player': Player
        },
        props : ['id']
    }
</script>
