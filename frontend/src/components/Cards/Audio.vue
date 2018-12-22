<template>
    <div class = "file-preview">
        <player :music="player"></player>
    </div>
</template>

<script>
    import player from 'vue-aplayer'

    export default {

        data() {
            return {
                player: {
                    src: process.env.VUE_API_URI + 'download/' + this.id + '/stream',
                    title: this.info.title,
                    artist: this.info.artist
                }
            }
        },

        components: {
            'player': player
        },
        props : ['id', 'info']
    }
</script>
