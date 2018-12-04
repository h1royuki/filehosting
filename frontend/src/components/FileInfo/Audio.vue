<template>
    <div class="info">
        <p v-if="info.title" class = 'file-info'>Title: {{ info.title }}</p>
        <p v-if="info.artist" class = 'file-info'>Artist: {{ info.artist }}</p>
        <p v-if="info.album" class = 'file-info'>Album: {{ info.album }}</p>
        <p v-if="info.bitrate" class = 'file-info'>Bitrate: {{ info.bitrate }}</p>
        <p v-if="info.dataformat" class = 'file-info'>Format: {{ info.dataformat }}</p>
    </div>
</template>

<script>
    export default {
        props: ['info']
    }
</script>