<template>
    <div class="info">
        <p class = 'file-info'>Resolution: {{ info.width }} x {{ info.height }}</p>
        <p class = 'file-info'>Format: {{ info.format }}</p>
    </div>
</template>

<script>
    export default {
        props: ['info']
    }
</script>