<template>
    <div v-if="info" class="archive-preview">
        <table class="table table-sm">
            <thead>
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Size</th>
                <th scope="col">Type</th>
            </tr>
            </thead>
            <tbody v-if="info.directories" v-for="dir in info.directories">
            <tr>
                <td>{{ dir }}</td>
                <td></td>
                <td>Directory</td>
            </tr>
            </tbody>
            <tbody v-if="info.files" v-for="(size, name) in info.files">
            <tr>
                <td>{{ name }}</td>
                <td>{{ size |  bitsConvert }}</td>
                <td>File</td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {

        data() {
            return {
            }
        },

        props: ['info']
    }
</script>