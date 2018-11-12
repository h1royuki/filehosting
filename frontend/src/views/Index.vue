<template>

    <div class="index">
        <section id="content" class="content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 m-auto">
                        <div v-if="percents" class="progress">
                            <div class="progress-text">
                                {{ percents + '%' }}
                            </div>
                            <div class="progress-bar" role="progressbar"
                                 v-bind:style="{width: + percents  + '%'}" aria-valuemin="0" aria-valuemax="100">
                            </div>
                        </div>
                        <div v-else class ="file-input">
                            <button class="choose-file">
                                <div  v-if="file">{{ file.name }}</div>
                                <upload-icon v-else class = "upload-icon" />
                            </button>
                            <input class ="chooser" @change="fileChange" type="file" name="upload"/>
                            <input type ="submit" @click="uploadFile" value="Upload file" class="button upload-button">
                            <p class="text_ib">Maximum file size is 10 MB</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <div class="container">
            <div class="row justify-content-md-center">
                <div class="col-sm-auto">
                    <router-link to="/last">
                        <button type="button" class="btn btn-dark button-lf align-middle">Last files</button>
                    </router-link>
                </div>
            </div>
        </div>
        </section>
    </div>

</template>

<script>

    import CloudUploadIcon from "vue-material-design-icons/CloudUpload.vue";

    export default {
        data() {
            return {
                maxsize: 10485760,
                file: null,
                response: null,
                percents: null,
            }
        },

        components: {
            "upload-icon": CloudUploadIcon,
        },

        methods: {

            validateFile() {

                if (!this.file) {
                    this.sendError('File not attached');
                    return true;
                }

                if (this.file.name.length <= 1) {
                    this.sendError('Unknown file name');
                    return true;
                }

                if (this.file.size > this.maxsize) {
                    this.sendError('File too big');
                    return true;
                }

                return false;


            },
            fileChange(e) {
                let files = e.target.files;
                if (!files.length)
                    return;
                this.file = files[0];

            },

            percentsUpdate(percent) {
                this.percents = percent;
            },

            redirectToFile(response) {
                let link = '/file/' + response.id;
                this.$router.push(link);
            },

            uploadFile() {
                if (this.validateFile()) {
                    return;
                }
                this.sendFile(this.percentsUpdate, this.redirectToFile);
            },

            sendFile(percentsUpdate, redirectToFile) {

                let formData = new FormData();

                formData.append('upload', this.file);

                let config = {
                    onUploadProgress: function (progressEvent) {
                        let percent = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                        percentsUpdate(percent);
                    },
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }

                };

                this.$http.post('/upload', formData, config)
                    .then((response) => {
                        redirectToFile(response.data);
                    });
            }
        }
    }


</script>
