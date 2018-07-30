<template>
    <div class="container-fluid">
        <div v-for="(shopss, index) in groupedShops" :key="index" class="col-md-12">
            <div class="row">
                <shop-component v-for="shop in shopss" v-on:spliceShop="nearbyShops.splice(nearbyShops.indexOf(shop), 1)" :key="shop._id"
                    :isPref="!1" :shop="shop"></shop-component>
            </div>
        </div>
    </div>
</template>
<script>
    import shopItemComponent from './shopItemComponent.vue'
    /* global _ */
    export default {
        name: 'NearbyShops',
        components: {
            'shop-component': shopItemComponent
        },
        computed: {
            nearbyShops() {
                return this.$store.getters.nearbyShops
            },
            groupedShops() {
                return _.chunk(this.nearbyShops, 4)
            }
        },
        methods: {
            getPosition(options) {
                return new Promise((resolve, reject) => {
                    navigator.geolocation.getCurrentPosition(resolve, reject, options)
                })
            },
            watchPosition(toast) {
                var id
                const options = {
                    enableHighAccuracy: false,
                    timeout: 500,
                    maximumAge: 0
                }
                this.$snotify.async('Okey allow me to have your position', ':)', () => new Promise((resolve, reject) => {
                    id = navigator.geolocation.watchPosition(
                        position => {
                            navigator.geolocation.clearWatch(id)
                            setTimeout(() => resolve({
                                title: 'Success!',
                                body: 'thank your for your trust enjoy :)!',
                                config: {
                                    timeout: 3000,
                                    showProgressBar: true,
                                    closeOnClick: false
                                }
                            }), 5000);
                            this.$snotify.remove(toast.id)
                            this.$store.dispatch('getNearbyShops', position.coords)
                        },
                        () => {
                            setTimeout(() => reject({
                                title: 'Error!',
                                body: 'still blocked I can\'t get your location, try removing site from the block list :P',
                                config: {
                                    timeout: 4000,
                                    showProgressBar: true,
                                    closeOnClick: false
                                }
                            }), 5000);
                        }, options)
                }))
            },
            initNearbyShops() {
                if ('geolocation' in navigator) {
                    this.getPosition().then(position => {
                        this.$store.dispatch('getNearbyShops', position.coords)
                    }).catch(err => {
                        if (err.PERMISSION_DENIED) {
                            this.$snotify.confirm('Please share your location to get the nearest shops',
                                'Share location', {
                                    timeout: 0,
                                    showProgressBar: false,
                                    closeOnClick: false,
                                    buttons: [{
                                            text: 'Ok',
                                            action: toast => this.watchPosition(toast.id),
                                            bold: false
                                        },
                                        {
                                            text: 'Close',
                                            action: toast => {
                                                this.$store.dispatch('getNearbyShops')
                                                this.$snotify.remove(toast.id)
                                            },
                                            bold: true
                                        }
                                    ]
                                })
                        }
                    })
                } else {
                    this.$store.dispatch('getNearbyShops')
                }
            }
        },
        created() {
            this.$store.commit('updateLoader', true)
            this.initNearbyShops()
        }
    }

</script>
