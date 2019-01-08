<template>
    <div class="model-view">
        <form @submit.prevent="edit">

            <input type="hidden" v-model="model.id">

            <div class="form-group row">
                <label for="name" class="col-sm-3 form-control-label">Model Name</label>
                <div class="col-sm-9">
                    <input id="name" type="text" class="form-control" v-model="model.name" placeholder="Name"/>
                </div>
            </div>

            <div class="form-group row">
                <label for="manufacturer" class="col-sm-3 form-control-label">Manufacturer</label>
                <div class="col-sm-9">
                    <select id="manufacturer" class="form-control" v-model="model.manufacturer">
                        <option v-for="manufacturer in manufacturers" :value="manufacturer.id">{{ manufacturer.name }}</option>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label for="color" class="col-sm-3 form-control-label">Color</label>
                <div class="col-sm-9">
                    <input id="color" type="text" class="form-control" v-model="model.color" placeholder=""/>
                </div>
            </div>

            <div class="form-group row">
                <label for="year" class="col-sm-3 form-control-label">Year</label>
                <div class="col-sm-9">
                    <input id="year" type="text" class="form-control" v-model="model.year" placeholder=""/>
                </div>
            </div>

            <div class="form-group row">
                <label for="registration_no" class="col-sm-3 form-control-label">Registration No</label>
                <div class="col-sm-9">
                    <input id="registration_no" type="text" class="form-control" v-model="model.registration_no" placeholder=""/>
                </div>
            </div>

            <div class="form-group row">
                <label for="note" class="col-sm-3 form-control-label">Note</label>
                <div class="col-sm-9">
                    <textarea id="note" class="form-control" v-model="model.note"></textarea>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-12">
                    <center>
                        <button type="submit" class="btn btn-primary">Save</button>
                        <router-link to="/model" class="btn btn-secondary">Back</router-link>
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

            axios.get(`/api/model/${this.$route.params.id}`, {
                headers: {
                    "Authorization": `Bearer ${this.currentUser.token}`
                }
            })
            .then((response) => {
                this.model = response.data.model;
            });

        },
        data(){
            return {
                model: {
                    id: 0,
                    name: '',
                    manufacturer: null,
                    color: '',
                    year: '',
                    registration_no: ''
                },
                errors: null
            }
        },
        computed: {
            currentUser(){
                return this.$store.state.currentUser;
            },
            manufacturers(){
                return this.$store.state.manufacturers;
            }
        },
        methods: {
            edit() {

                this.error = null;

                const constraints = this.getConstraints();
                const errors = validate(this.$data.model, constraints);
                if(errors){
                   this.errors = errors;
                   return;
                }

                axios.post('/api/model/edit', this.$data.model, {
                    headers: {
                        "Authorization": `Bearer ${this.currentUser.token}`
                    }
                })
                .then((response) => {
                    this.$router.push('/model');
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
                    },
                    manufacturer: {
                        presence: true
                    },
                    color: {
                        presence: true
                    },
                    year: {
                        presence: true,
                        numericality: true
                    }
                }

            }
        },
        mounted(){
            this.$store.dispatch('getManufacturers');
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
