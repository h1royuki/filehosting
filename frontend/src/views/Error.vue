<template xmlns="http://www.w3.org/1999/html">
    <section class="content">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 m-auto text-center" >
                    <img :src="img" class="error">
                    <h2 v-if="error">{{ error.error }}</h2>
                    <h2 v-else>Page not found</h2>
                    <router-link to="/"><h4>Return to home</h4></router-link>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
    import img from './assets/img/error.svg';

    export default {

        data() {
            return {
                img: img,
            }
        },
        props : ['error']
    }
</script>