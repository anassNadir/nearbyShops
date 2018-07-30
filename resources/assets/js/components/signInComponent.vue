<template>
    <div class="container">
        <div class="row">
            <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
                <div class="card card-signin my-5">
                    <div class="card-body">
                        <h5 class="card-title text-center">Sign In</h5>
                        <form novalidate="1" @submit.prevent="login" class="form-signin">
                            <div class="form-label-group">
                                <input type="email" name="email" id="inputSignEmail" autocomplete="off" class="form-control" v-model.trim="formData.email.value"
                                    placeholder="Email address" required autofocus>
                                <label for="inputSignEmail">Email address</label>
                                <transition name="slide-fade">
                                    <p class="error-msg" v-show="!formData.email.valid"> {{formData.email.msg}} </p>
                                </transition>
                            </div>
                            <div class="form-label-group">
                                <input type="password" name="password" id="inputSignPassword" autocomplete="off" v-model="formData.password.value" class="form-control"
                                    placeholder="Choose Password" required>
                                <label for="inputSignPassword">Choose Password</label>
                                <transition name="slide-fade">
                                    <p class="error-msg" v-show="!formData.password.valid"> {{formData.password.msg}} </p>
                                </transition>
                            </div>
                            <button class="btn btn-lg btn-primary btn-block text-uppercase" :disabled="isLoading" type="submit">Sign in</button>
                            <hr class="my-4">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'SignIn',
        data() {
            return {
                isLoading: false,
                formData: {
                    email: {
                        value: '',
                        required: 1,
                        msgs: {
                            required: 'email field is required',
                            valid: 'please enter a valid email address'
                        },
                        msg: 'email field is required',
                        valid: 1
                    },
                    password: {
                        value: '',
                        required: 1,
                        msg: 'password field is required',
                        valid: 1
                    }
                }
            }
        },
        methods: {
            validateForm() {
                const emailInControl = document.querySelector('.form-label-group input[name="email"]')
                const passInControl = document.querySelector('.form-label-group input[name="password"]')

                const validatePass = () => {
                    if (!this.formData.password.value) {
                        this.formData.password.valid = !1
                    } else {
                        this.formData.password.valid = 1
                    }
                }
                const validateEmail = () => {
                    if (!this.formData.email.value) {
                        this.formData.email.valid = !1
                        this.formData.email.msg = this.formData.email.msgs.required
                    } else if (!validEmail(this.formData.email.value)) {
                        this.formData.email.valid = !1
                        this.formData.email.msg = this.formData.email.msgs.valid
                    } else {
                        this.formData.email.valid = 1
                    }
                }
                const validEmail = email => {
                    const re =
                        /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
                    return re.test(email)
                }
                passInControl.addEventListener('blur', validatePass)
                passInControl.addEventListener('keyup', validatePass)
                emailInControl.addEventListener('blur', validateEmail)
                emailInControl.addEventListener('keyup', validateEmail)
            },
            login() {
                this.isLoading = true
                let errors = 0
                for (const [key, content] of Object.entries(this.formData)) {
                    if (content.required && !content.value) {
                        console.log(content.value)
                        content.valid = !1
                        errors++
                    } else if (!content.valid) {
                        errors++
                    }
                }
                if (errors > 0) {
                    this.isLoading = false;
                    return !1
                }

                const formData = {
                    email: this.formData.email.value,
                    password: this.formData.password.value
                }

                this.$store.dispatch('authenticate', formData).then(() => {
                    this.$router.push('/nearby-shops')
                }).catch(res => {
                    this.isLoading = false
                    this.$snotify.warning(res.error, {
                        showProgressBar: true,
                        closeOnClick: true,
                        timeout: 3000
                    })
                })
            }
        },
        mounted() {
            setTimeout(() => {
                this.validateForm()
            }, 1200)
        }
    }

</script>
<style>
    .slide-fade-enter-active {
        transition: all .3s ease;
    }

    .slide-fade-leave-active {
        transition: all .8s cubic-bezier(1.0, 0.5, 0.8, 1.0);
    }

    .slide-fade-enter,
    .slide-fade-leave-to {
        transform: translateX(10px);
        opacity: 0;
    }

    .card-signin {
        border: 0;
        border-radius: 1rem;
        box-shadow: 0 0.5rem 1rem 0 rgba(0, 0, 0, 0.1);
    }

    .card-signin .card-title {
        margin-bottom: 2rem;
        font-weight: 300;
        font-size: 1.5rem;
    }

    .card-signin .card-body {
        padding: 2rem;
    }

    .form-signin {
        width: 100%;
    }

    .form-signin .btn {
        font-size: 80%;
        border-radius: 5rem;
        letter-spacing: .1rem;
        font-weight: bold;
        padding: 1rem;
        transition: all 0.2s;
    }

    .form-label-group {
        position: relative;
        margin-bottom: 1rem;
    }

    .form-label-group input {
        border-radius: 2rem;
    }

    .form-label-group>input,
    .form-label-group>label {
        padding: .75rem 1.5rem;
    }

    .form-label-group>label {
        position: absolute;
        top: 0;
        left: 0;
        display: block;
        width: 100%;
        margin-bottom: 0;
        line-height: 1.5;
        color: #495057;
        border: 1px solid transparent;
        border-radius: .25rem;
        transition: all .1s ease-in-out;
    }

    .form-label-group .error-msg {
        font-size: 15px;
        font-weight: 600;
        color: red;
        padding: 0 10px;
    }

    .form-label-group input::-webkit-input-placeholder {
        color: transparent;
    }

    .form-label-group input:-ms-input-placeholder {
        color: transparent;
    }

    .form-label-group input::-ms-input-placeholder {
        color: transparent;
    }

    .form-label-group input::-moz-placeholder {
        color: transparent;
    }

    .form-label-group input::placeholder {
        color: transparent;
    }

    .form-label-group input:not(:placeholder-shown) {
        padding-top: calc(.75rem + .75rem * (2 / 3));
        padding-bottom: calc(.75rem / 3);
    }

    .form-label-group input:not(:placeholder-shown)~label {
        padding-top: calc(.75rem / 3);
        padding-bottom: calc(.75rem / 3);
        font-size: 12px;
        color: #777;
    }

</style>
