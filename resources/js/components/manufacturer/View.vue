<template>
    <div class="manufacturer-view">
        <form @submit.prevent="edit">

            <input type="hidden" v-model="manufacturer.id">

            <div class="form-group row">
                <label for="name" class="col-sm-2 form-control-label">Name</label>
                <div class="col-sm-10">
                    <input id="name" type="text" class="form-control" v-model="manufacturer.name" placeholder="Manufacturer Name"/>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-12">
                    <center>
                        <button type="submit" class="btn btn-primary">Save</button>
                        <router-link to="/manufacturer" class="btn btn-secondary">Back</router-link>
                    </center>
                </div>
            </div>

            <div class="form-group row" v-if="errors">
                <div class="col-sm-12 errors">
                    <ul>
                        <li v-for="(fieldsError, fieldName) in errors" :key="fieldName">
                            <strong >{{ fieldName }}</strong> {{ fieldsError.join('\n') }}
                        </li>
                    </ul>
                </div>
            </div>

        </form>
    </div>
</template>

<script>
    import validate from 'validate.js';

    export default {
        name: 'edit',
        created(){

            axios.get(`/api/manufacturer/${this.$route.params.id}`, {
                headers: {
                    "Authorization": `Bearer ${this.currentUser.token}`
                }
            })
            .then((response) => {
                this.manufacturer = response.data.manufacturer;
            });

        },
        data(){
            return {
                manufacturer: {
                    id: 0,
                    name: ''
                },
                errors: null
            }
        },
        computed: {
            currentUser(){
                return this.$store.state.currentUser;
            }
        },
        methods: {
            edit() {

                this.error = null;

                const constraints = this.getConstraints();
                const errors = validate(this.$data.manufacturer, constraints);
                if(errors){
                   this.errors = errors;
                   return;
                }

                axios.post('/api/manufacturer/edit', this.$data.manufacturer, {
                    headers: {
                        "Authorization": `Bearer ${this.currentUser.token}`
                    }
                })
                .then((response) => {
                    this.$router.push('/manufacturer');
                });

            },
            getConstraints() {

                return {
                    name: {
                        presence: true,
                        length: {
                            minimum: 3,
                            message: 'must be at least 3 characters long'
                        }
                    }
                }

            }
        }
    }
</script>

<style scoped>
    .errors{
        background: lightcoral;
        border-radius: 5px;
        padding: 21px 0 2px 0;
    }
</style>
