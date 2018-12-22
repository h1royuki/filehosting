<template>
    <div class = "image-preview">
        <img class = "img-fluid" :src="preview">
    </div>
</template>

<script>
    export default {

        data() {
            return {
                preview: process.env.VUE_API_URI + 'preview/' + this.id
            }
        },

        props: ['id']
    }
</script>