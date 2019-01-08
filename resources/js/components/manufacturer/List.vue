<template>
    <div>
        <div class="btn-wrapper">
            <router-link to="/manufacturer/new" class="btn btn-primary btn-sm">Create New Manufacturer</router-link>
        </div>
        <table class="table">
            <thead>
                <th>Name</th>
                <th width="15%">View / Edit</th>
            </thead>
            <tbody>
                <template v-if="!manufacturers.length">
                    <tr>
                        <td colspan="2" class="text-center">No Manufacturer Available</td>
                    </tr>
                </template>
                <template v-else>
                    <tr v-for="manufacturer in manufacturers" :key="manufacturer.id">
                        <td>{{ manufacturer.name }}</td>
                        <td width="15%">
                            <router-link :to="`/manufacturer/${manufacturer.id}`">View / Edit</router-link>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        name: 'list',
        computed: {
            manufacturers(){
                return this.$store.state.manufacturers;
            }
        },
        mounted(){
            this.$store.dispatch('getManufacturers');
        }
    }
</script>

<style scoped>
.btn-wrapper{
    text-align: right;
    margin-bottom: 20px;
}
</style>