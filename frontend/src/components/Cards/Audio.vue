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
                    src: '/api/download/' + this.id + '/stream',
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
