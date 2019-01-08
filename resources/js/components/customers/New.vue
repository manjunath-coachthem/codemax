<template>
    <div class="customer-new">
        <form @submit.prevent="add">

            <div class="form-group row">
                <label for="name" class="col-sm-2 form-control-label">Name</label>
                <div class="col-sm-10">
                    <input id="name" type="text" class="form-control" v-model="customer.name" placeholder="Customer Name"/>
                </div>
            </div>

            <div class="form-group row">
                <label for="email" class="col-sm-2 form-control-label">Email</label>
                <div class="col-sm-10">
                    <input id="email" type="text" class="form-control" v-model="customer.email" placeholder="Customer Email"/>
                </div>
            </div>

            <div class="form-group row">
                <label for="phone" class="col-sm-2 form-control-label">Phone</label>
                <div class="col-sm-10">
                    <input id="phone" type="text" class="form-control" v-model="customer.phone" placeholder="Customer Phone"/>
                </div>
            </div>

            <div class="form-group row">
                <label for="website" class="col-sm-2 form-control-label">Website</label>
                <div class="col-sm-10">
                    <input id="website" type="text" class="form-control" v-model="customer.website" placeholder="Customer Website"/>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-12">
                    <center>
                        <button type="submit" class="btn btn-primary">Create</button>
                        <router-link to="/customers" class="btn btn-secondary">Back</router-link>
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
        name: 'new',
        data(){
            return {
                customer: {
                    name: '',
                    email: '',
                    phone: '',
                    website: ''
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
            add() {

                this.error = null;

                const constraints = this.getConstraints();
                const errors = validate(this.$data.customer, constraints);
                if(errors){
                   this.errors = errors;
                   return;
                }

                axios.post('/api/customers/new', this.$data.customer, {
                    headers: {
                        "Authorization": `Bearer ${this.currentUser.token}`
                    }
                })
                .then((response) => {
                    this.$router.push('/customers');
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
                    email: {
                        presence: true,
                        email: true
                    },
                    phone: {
                        presence: true,
                        numericality: true,
                        length: {
                            minimum: 10,
                            message: 'must be at least 10 digits'
                        }
                    },
                    website: {
                        presence: true,
                        url: true
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
