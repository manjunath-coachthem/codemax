<template>
    <div class="navbar navbar-expand-md navbar-light navbar-laravel">
        <div class="container">
            <router-link class="navbar-brand" to="/">CodeMax</router-link>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto">
                    <template v-if="!currentUser">
                        <li>
                            <router-link to="/login" class="nav-link">Login</router-link>
                        </li>
                        <li>
                            <router-link to="/register" class="nav-link">Register</router-link>
                        </li>
                    </template>
                    <template v-else>
                        <li>
                            <router-link to="/manufacturer" class="nav-link">Manufacturer</router-link>
                        </li>
                        <li>
                            <router-link to="/model" class="nav-link">Model</router-link>
                        </li>
                        <li>
                            <router-link to="/inventory" class="nav-link">Inventory</router-link>
                        </li>
                        <!--<li>
                            <router-link to="/customers" class="nav-link">Customers</router-link>
                        </li>-->
                        <li class="nav-item dropdown">

                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ currentUser.name }}<span class="caret"></span>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a href="#!" @click.prevent="logout" class="dropdown-item">Logout</a>
                            </div>

                        </li>
                    </template>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'app-header',
        methods: {
            logout(){
                this.$store.commit('logout');
                this.$router.push('/login');
            }
        },
        computed: {
            currentUser(){
                return this.$store.state.currentUser;
            }
        },
        mounted(){
            //alert(JSON.stringify(this.$store.state.currentUser));
        }
    }
</script>

<style scoped>
    a.router-link-active{
        color: orange !important;
    }
</style>
