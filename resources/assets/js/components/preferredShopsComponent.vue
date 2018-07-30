<template>
    <div class="container-fluid">
        <template v-if="preferredShops.length>0">
            <div v-for="(shopss, index) in groupedShops" :key="index" class="col-md-12">
                <div class="row">
                    <shop-component v-for="shop in shopss" v-on:spliceShop="preferredShops.splice(preferredShops.indexOf(shop), 1)" :key="shop._id"
                        :isPref="!0" :shop="shop"></shop-component>
                </div>
            </div>
        </template>
        <div v-else class="row">

            <div class="col-md-10 offset-md-1">
                <div class="alert alert-info text-center" role="alert">
                    <h1>You don't have any preferred shop yet</h1>
                </div>
            </div>
        </div>

    </div>
</template>
<script>
    import shopItemComponent from './shopItemComponent.vue'
    /* global _ */
    export default {
        name: 'PreferredShops',
        components: {
            'shop-component': shopItemComponent
        },
        computed: {
            preferredShops() {
                return this.$store.getters.preferredShops
            },
            groupedShops() {
                return _.chunk(this.preferredShops, 4)
            }
        },
        created() {
            this.$store.dispatch('getPreferredShops')
        }
    }

</script>
