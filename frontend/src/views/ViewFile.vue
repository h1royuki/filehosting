<template xmlns="http://www.w3.org/1999/html">
    <section class="file-content">
        <div class="container-fluid">
            <div v-if="response" class="row justify-content-md-center ">
                <div class="col-lg-6 preview">
                    <div v-if = "response.type == types.audio">
                        <audio-card v-bind:id="response.id" v-bind:info="response.info"></audio-card>
                    </div>
                    <div v-if = "response.type == types.video">
                        <video-card v-bind:id="response.id"></video-card>
                    </div>
                    <div v-if = "response.type == types.image">
                        <image-card v-bind:id="response.id"></image-card>
                    </div>
                    <div v-if = "response.type == types.archive">
                        <archive-card v-bind:info="response.info"></archive-card>
                    </div>
                    <div class="row justify-content-md-center file-card">
                        <div class="col-lg-8">
                            <p class ="file-name">{{ response.filename }}</p>
                            <p class="file-date">Uploaded {{ response.date_upload | moment("D/M/Y") }}</p>
                                <div class ="file-info">Size: {{ response.size | bitsConvert }}</div>
                                <div class ="file-info">MD5: {{ response.hash }}</div>
                            <div v-if = "response.type == types.audio">
                                <audio-info v-bind:info="response.info"></audio-info>
                            </div>
                            <div v-if = "response.type == types.video">
                                <video-info v-bind:info="response.info"></video-info>
                            </div>
                            <div v-if = "response.type == types.image">
                                <image-info v-bind:info="response.info"></image-info>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <button class="btn btn-primary button download-button" @click="downloadFile">
                                Download
                            </button>
                            <p class ="file-download">Dwnld {{ response.downloads }} times </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="row file-card">
                <comments v-bind:file_id="file_id"></comments>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
    import FileTypes from '../FileTypes'

    import VideoCard from '../components/Cards/Video'
    import AudioCard from '../components/Cards/Audio'
    import ImageCard from '../components/Cards/Image'
    import ArchiveCard from '../components/Cards/Archive'

    import VideoInfo from '../components/FileInfo/Video'
    import AudioInfo from '../components/FileInfo/Audio'
    import ImageInfo from '../components/FileInfo/Image'

    import Comments from '../components/Comments'

    export default {

        components: {
            'video-card' : VideoCard,
            'audio-card' : AudioCard,
            'image-card' : ImageCard,
            'archive-card' : ArchiveCard,

            'video-info' : VideoInfo,
            'audio-info' : AudioInfo,
            'image-info' : ImageInfo,

            'comments' : Comments
        },

        data() {
            return {
                file_id: this.$route.params.id,
                response: null,
                download_url: process.env.VUE_API_URI + 'download/' + this.$route.params.id,
                types: FileTypes
            }
        },

        created() {
            this.getFile();
        },
        watch: {
            '$route'() {
                this.getFile();
            }
        },
        methods: {

            getFile() {
                this.$http
                    .get('/file/' + this.file_id)
                    .then(response => (this.response = response.data));
            },
            downloadFile() {
                window.location.href = this.download_url;
            }
        }
    }
</script>
