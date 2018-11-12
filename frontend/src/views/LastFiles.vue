<template xmlns="http://www.w3.org/1999/html">
    <section class="content last-files-container">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 text-center" v-for="item in response">
                    <router-link :to ="`/file/${item.id}`">
                        <div class = "file-card-small">
                            <div v-if = "item.type == 0" class = "file other-icon small"> <file-icon class = "icon" /></div>
                            <div v-if = "item.type == 1" class = "file audio-icon small"><audio-icon class = "icon" /></div>
                            <div v-if = "item.type == 2" class = "file video-icon small"><video-icon class = "icon" /></div>
                            <div v-if = "item.type == 3" class = "file image-icon small"><image-icon class = "icon" /></div>

                            <div class = "file-info-small">
                                <p class ="file-name-small">{{ item.filename }}</p>
                                <p class="file-size">{{ item.size | bitsConvert }}</p>
                                <p class="file-date">{{ item.date_upload | moment("D/M/Y") }} </p>
                            </div>
                        </div>
                    </router-link>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
    import FileIcon from "vue-material-design-icons/File.vue";
    import AudioIcon from "vue-material-design-icons/FileMusic.vue";
    import VideoIcon from "vue-material-design-icons/FileVideo.vue";
    import ImageIcon from "vue-material-design-icons/FileImage.vue";

    export default {
        data() {
            return {
                response: null
            }
        },

        components: {
            "file-icon": FileIcon,
            "audio-icon": AudioIcon,
            "video-icon": VideoIcon,
            "image-icon": ImageIcon
        },
        created() {
            this.$http
                .get('/last')
                .then(response => (this.response = response.data));
        }
    }


</script>
