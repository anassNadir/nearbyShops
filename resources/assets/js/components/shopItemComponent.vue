<template>
    <transition name="shop" mode="in-out">
        <div class="shop-item col-md-3">
            <h4 class="itemName">{{ shop.name }}</h4>
            <img :src="shop.picture" class="itemPicture" alt="">
            <h5>{{ shop.email }}</h5>
            <h5>{{ shop.city }}</h5>
            <div class="btn-group-center">
                <div class="btn-group">
                    <button v-if="!isPref" :disabled="loading" type="button" class="btn btn-default btn-danger" @click="dislikeShop(shop)">Dislike</button>
                    <button v-if="!isPref" :disabled="loading" type="button" class="btn btn-default btn-success" @click="likeShop(shop)">Like</button>
                    <button v-if="isPref" :disabled="loading" type="button" class="btn btn-default btn-danger" @click="removeShop(shop)">Remove</button>
                </div>
            </div>
        </div>
    </transition>
</template>
<script>
    export default {
        name: 'ShopItem',
        props: {
            shop: Object,
            isPref: Boolean
        },
        data() {
            return {
                loading: false
            }
        },
        methods: {
            likeShop(shop) {
                this.loading = true
                this.$store.dispatch('likeShop', shop).then(() => {
                    this.$emit('spliceShop')
                }).catch(err => console.log(err))
            },
            dislikeShop(shop) {
                this.loading = true
                this.$store.dispatch('dislikeShop', shop).then(() => {
                    this.$emit('spliceShop')
                }).catch(err => console.log(err))
            },
            removeShop(shop) {
                this.loading = true
                this.$store.dispatch('removeShop', shop).then(() => {
                    this.$emit('spliceShop')
                }).catch(err => console.log(err))
            }
        }
    }

</script>

<style>
    .itemPicture {
        height: 150px;
        width: 150px;
        margin-bottom: 20px;
        border: 2px solid black;
    }

    .shop-item {
        border: 2px solid black;
        padding: 10px;
        text-align: center;
        margin: 5px 0;
    }

    .btn-group-center {
        text-align: center;
    }

    .btn-group>.btn:first-child:not(:last-child) {
        margin-right: 10px
    }

    .shop-enter,
    .slide-leave-to {
        opacity: 0;
    }

    .shop-enter-active {
        animation: shop-in 1s ease-out;
        transition: all 1s ease-out;
    }

    @keyframes shop-in {
        from {
            transform: translateX(30px);
        }
        to {
            transform: translateX(0);
        }
    }

</style>
