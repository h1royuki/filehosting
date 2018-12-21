<template>
    <div class = "image-preview">
        <img class = "img-fluid" :src="preview">
    </div>
</template>

<script>
    export default {

        data() {
            return {
                preview: 'http://127.0.0.1:8082/preview/' + this.id
            }
        },

        props: ['id']
    }
</script>