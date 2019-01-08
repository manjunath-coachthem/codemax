<template>
    <div>
        <div class="btn-wrapper">
            <router-link to="/model/new" class="btn btn-primary btn-sm">Create New Model</router-link>
        </div>
        <table class="table">
            <thead>
                <th>Model Name</th>
                <th>Manufacturer Name</th>
                <th width="20%">Upload Pics</th>
                <th width="15%">View / Sold</th>
                <th width="15%">Edit</th>
            </thead>
            <tbody>
                <template v-if="!models.length">
                    <tr>
                        <td colspan="4" class="text-center">No Model Available</td>
                    </tr>
                </template>
                <template v-else>
                    <tr v-for="model in models" :key="model.id">
                        <td>{{ model.name }}</td>
                        <td>{{ model.manufacturer }}</td>
                        <td width="15%">
                            <a href="#!" @click="openuploadpicsmodal(model.id)" data-toggle="modal" data-target="#pics-upload-modal">Upload Pics</a>
                        </td>
                        <td width="15%">
                            <a href="#!" @click="viewmodeldeatils(model.id)" data-toggle="modal" data-target="#myModal">View / Sold</a>
                        </td>
                        <td width="15%">
                            <router-link :to="`/model/${model.id}`">Edit</router-link>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>

        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header details-view-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel">Model Information</h4>
                    </div>
                    <div class="modal-body">
                        <table class="table table-striped">
                            <tbody>
                                <tr>
                                    <td><b>Model Name</b></td>
                                    <td>{{ popupDetails.name }}</td>
                                </tr>
                                <tr>
                                    <td><b>Manufacturer name</b></td>
                                    <td>{{ popupDetails.manufacturer_name }}</td>
                                </tr>
                                <tr>
                                    <td><b>Color</b></td>
                                    <td>{{ popupDetails.color }}</td>
                                </tr>
                                <tr>
                                    <td><b>Year</b></td>
                                    <td>{{ popupDetails.year }}</td>
                                </tr>
                                <tr>
                                    <td><b>Registration No</b></td>
                                    <td>{{ popupDetails.registration_no }}</td>
                                </tr>
                                <tr>
                                    <td><b>Note</b></td>
                                    <td>{{ popupDetails.note }}</td>
                                </tr>
                            </tbody>
                        </table>

                        <ul class="list-group image-preview" v-if="popupDetails.model_pics.length">
                            <li class="list-group-item">
                                <img v-for="model_pic in popupDetails.model_pics" :src="model_pic.pic_url">
                            </li>
                        </ul>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" id="close-popup-model">Close</button>
                        <button type="button" class="btn btn-primary" @click="modelSold(popupDetails.id)">Sold</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="pics-upload-modal" tabindex="-1" role="dialog" aria-labelledby="pics-upload-modal-label" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header details-view-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="pics-upload-modal-label">Upload Pics</h4>
                    </div>
                    <div class="modal-body">
                        <form id="form-pics-upload-modal" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="id" v-model="picsUploadData.id">
                            <div class="form-group row">
                                <label for="note" class="col-sm-3 form-control-label">Pics Upload</label>
                                <div class="col-sm-9">
                                    <label class="file">
                                        <input type="file" name="images[]" id="model-pics" multiple @change="modelPicsChanged">
                                        <span class="file-custom"></span>
                                    </label>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" id="close-upload-pic-model">Close</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        name: 'list',
        data(){
            return {
                popupDetails:{
                    id: 0,
                    name: '',
                    manufacturer_name: '',
                    color: '',
                    year: '',
                    registration_no: '',
                    model_pics:[]
                },
                picsUploadData:{
                    id: 0
                }
            }
        },
        methods: {
            viewmodeldeatils(id) {

                this.popupDetails = {
                    id: 0,
                    name: '',
                    manufacturer_name: '',
                    color: '',
                    year: '',
                    registration_no: '',
                    model_pics:[]
                }

                axios.get(`/api/model/${id}`, {
                    headers: {
                        "Authorization": `Bearer ${this.currentUser.token}`
                    }
                })
                .then((response) => {
                    this.popupDetails = response.data.model;
                });

            },
            modelSold(id) {
                axios.post('/api/model/remove', {id}, {
                    headers: {
                        "Authorization": `Bearer ${this.currentUser.token}`
                    }
                })
                .then((response) => {
                    if(response.data.success){
                        this.$store.dispatch('getModels');
                        alert('Marked as Sold !!');
                        $('#close-popup-model').trigger('click');
                    }
                });
            },
            openuploadpicsmodal(id) {
                this.picsUploadData.id = id;
            },
            modelPicsChanged(e) {

                var formData = new FormData($('#form-pics-upload-modal')[0]);

                axios.post('/api/uploadpics', formData, {
                    headers: {
                        "Authorization": `Bearer ${this.currentUser.token}`
                    }
                })
                .then((response) => {
                    if(response.data>0){
                        $('#close-upload-pic-model').trigger('click');
                        alert(response.data+' Pics uploaded successfully! Please click on view to preview them.');
                    }
                });

            }
        },
        computed: {
            currentUser(){
                return this.$store.state.currentUser;
            },
            models(){
                return this.$store.state.models;
            }
        },
        mounted(){
            this.$store.dispatch('getModels');
        }
    }
</script>

<style scoped>
.btn-wrapper{
    text-align: right;
    margin-bottom: 20px;
}

.modal-header.details-view-header{
    display: block !important;
}

.image-preview li img{
    width: 70px !important;
    height: auto !important;
    margin-left: 20px;
}
</style>