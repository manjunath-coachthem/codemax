<template>
    <div>
        <table class="table">
            <thead>
                <th>Sr. No</th>
                <th>Inventory Name</th>
                <th>Manufacturer Name</th>
                <th>Count</th>
            </thead>
            <tbody>
                <template v-if="!inventories.length">
                    <tr>
                        <td colspan="4" class="text-center">No Inventories</td>
                    </tr>
                </template>
                <template v-else>
                    <tr v-for="(inventory, index) in inventories" :key="inventory.id">
                        <td>{{ index + 1 }}</td>
                        <td>{{ inventory.name }}</td>
                        <td>{{ inventory.manufacturer }}</td>
                        <td>{{ inventory.count }}</td>
                    </tr>
                </template>
            </tbody>
        </table>

    </div>
</template>

<script>
    export default {
        name: 'list',
        data(){
            return {
                srno:1
            }
        },
        methods: {
            //
        },
        computed: {
            currentUser(){
                return this.$store.state.currentUser;
            },
            inventories(){
                return this.$store.state.inventories;
            }
        },
        mounted(){
            this.$store.dispatch('getInventories');
        }
    }
</script>

<style scoped>
.btn-wrapper{
    text-align: right;
    margin-bottom: 20px;
}
</style>