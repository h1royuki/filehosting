<template>
    <div class = "image-preview">
        <img class = "img-fluid" :src="preview">
    </div>
</template>

<script>
    export default {

        data() {
            return {
                preview: '/api/preview/' + this.id
            }
        },

        props: ['id']
    }
</script>