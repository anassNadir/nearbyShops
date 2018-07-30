<template>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
        <a class="navbar-brand" href="#">Nearby Shops</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon" />
        </button>
        <div id="navbarNav" class="collapse navbar-collapse">
            <ul class="navbar-nav ml-auto">
                <li v-if="!isLoggedIn" class="nav-item">
                    <router-link :to="{ name: 'SignIn' }" class="nav-link">Sign In</router-link>
                </li>
                <li v-if="!isLoggedIn" class="nav-item">
                    <router-link :to="{ name: 'SignUp' }" class="nav-link">Sign Up</router-link>
                </li>
                <li v-if="isLoggedIn" class="nav-item">
                    <router-link :to="{ name: 'Nearby' }" class="nav-link">Nearby Shops</router-link>
                </li>
                <li v-if="isLoggedIn" class="nav-item">
                    <router-link :to="{ name: 'Preferred' }" class="nav-link">My preferred Shops</router-link>
                </li>
                <li v-if="isLoggedIn" class="nav-item">
                    <a class="nav-link" style="cursor:pointer" @click.prevent="logout">Logout</a>
                </li>
            </ul>
        </div>
    </nav>
</template>

<script>
    export default {
        name: 'Header',
        computed: {
            isLoggedIn() {
                return this.$store.getters.isLoggedIn
            }
        },
        methods: {
            logout() {
                this.$store.dispatch('logout')
                    .then(() => {
                        this.$router.push('/sign-in')
                    })
            }
        }
    }

</script>
