<template>
    <div id="app">
        <notifications animation-type="velocity" group="errors" ></notifications>
        <header class="header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-3 col-2">
                        <router-link to = "/">
                            <div class="logo">
                                <img class ="logo-image" :src="logo">
                                <img class ="logo-text" :src="logo_text">
                            </div>
                        </router-link>
                    </div>
                    <div class="col-lg-6 col-10">
                        <autocomplete
                                source="/api/search?query="
                                results-property="items"
                                results-display="filename"
                                input-class="search"
                                @selected="toFile">
                        </autocomplete>
                    </div>
                </div>
            </div>
        </header>
        <transition name="fade">
            <router-view></router-view>
        </transition>
    </div>
</template>

<script>

    import Autocomplete from 'vuejs-auto-complete'
    import logo from './assets/img/logo.svg';
    import logo_text from './assets/img/logo_text.svg';

    export default {
        data() {
            return {
                logo: logo,
                logo_text: logo_text
            }
        },
        components: {
            Autocomplete
        },
        methods: {
            toFile(file) {
                this.$router.push('/file/' + file.value);
            }
        }
    }
</script>
