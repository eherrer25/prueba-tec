<script setup>
import { ref,onMounted } from 'vue';
import {confirmation, sendRequest} from '../../functions'
import {useAuthStore} from '../../stores/auth'
const authStore = useAuthStore();
axios.defaults.headers.common['Authorization'] = `Bearer ${authStore.token}`;

onMounted( () => {getUsers()});
const users = ref([]);
const load = ref(false);
const getUsers = async () =>{
    await axios.get(import.meta.env.VITE_API_ENDPOINT+'users').then(
    
        response => (users.value = response.data.data)
    ).catch((errors) => {
            authStore.$reset()
        });
    load.value = true
}
const deleteUser = (id,name) => {
    confirmation(name,(import.meta.env.VITE_API_ENDPOINT+'users/delete/'+id),'users')
}
</script>
<template>
    <div class="row">
        <div class="col-md-4 offset-md-4">
            <div class="d-grid col-10 mx-auto">
                <router-link :to="{path:'create'}" class="btn btn-success">
                    <i class="fa-solid fa-circle-plus"> Add</i>
                </router-link>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-6 offset-md-3">
            <div class="card border border-white text-center" v-if="!load">
                <div class="card-body">
                    <i class="fa fa-spin fa-spinner"></i>
                </div>
            </div>
            <div class="table-responsive" v-else>
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
                        <tr v-for="user,i in users" :key="user.id">
                            <td>{{ (i+1) }}</td>
                            <td>{{ user.name }}</td>
                            <td>{{ user.email }}</td>
                            <td>{{ user.roles[0].name }}</td>
                            <td>
                                <router-link :to="{path:'edit/'+user.id}" class="btn btn-warning">
                                    <i class="fa-solid fa-edit"></i>
                                </router-link>
                                <button class="btn btn-danger"
                                @click="$event => deleteUser(user.id,user.name)"> <i class="fa-solid fa-trash"></i> </button>
                            </td>
                        </tr>
                    </tbody>
                </table>

            </div>

        </div>
    </div>
</template>