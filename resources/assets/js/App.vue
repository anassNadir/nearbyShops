<template>
    <div id="app">
        <header-component></header-component>
        <div v-if="isLoading" class="app-loader">
            <div class="loader"></div>
        </div>

        <div v-cloak v-show="!isLoading" class="content">
            <transition name="route" mode="out-in">
                <router-view />
            </transition>
        </div>
        <vue-snotify />
    </div>
</template>

<script>
    import Header from './components/headerComponent.vue'
    export default {
        name: 'App',
        computed: {
            isLoading() {
                return this.$store.state.isLoading
            }
        },
        components: {
            'header-component': Header
        }
    }

</script>
<style>
    [v-cloak] {
        display: none;
    }

    .app-loader {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .app-loader .loader {
        border: 16px solid #f3f3f3;
        border-top: 16px solid #3498db;
        border-radius: 50%;
        width: 120px;
        height: 120px;
        animation: spin .7s linear infinite;
    }


    @keyframes spin {
        from {
            transform: scale(1) rotate(0deg);
        }
        to {
            transform: scale(1) rotate(360deg);
        }
    }

    .route-enter-active,
    .route-leave-active {
        transition: opacity 1s, transform 1s;
    }

    .route-enter,
    .route-leave-to {
        opacity: 0;
        transform: translateX(-30%);
    }

    body {
        background: #9CECFB;
        background: -webkit-linear-gradient(to right, #0052D4, #65C7F7, #9CECFB);
        background: linear-gradient(to right, #0052D4, #65C7F7, #9CECFB);
    }

    .content {
        position: relative;
        overflow: hidden;
        padding-top: 6rem;
        padding-bottom: 6rem;
    }

    @media (max-width: 767px) {
        .content {
            padding-top: 4.5rem;
            padding-bottom: 4.5rem;
        }
    }

</style>
