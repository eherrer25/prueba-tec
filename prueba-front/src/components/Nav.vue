<script setup>
import {useAuthStore} from '../stores/auth.js'
const authStore = useAuthStore();
</script>

<template>  
    <nav class="navbar navbar-expand-lg navbar-light bg-light p-3">
        <RouterLink to="/" class="navbar-brand">Prueba</RouterLink>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto" v-if="!authStore.user">
                <li class="nav-item">
                   <RouterLink to="/login" class="nav-link">Login</RouterLink>
                </li>
                <li class="nav-item">
                   <RouterLink to="/register" class="nav-link">Registrar</RouterLink>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto" v-if="authStore.user && authStore.rol == 'admin'">
                <li class="nav-item">
                   <RouterLink to="/users" class="nav-link">Usuarios</RouterLink>
                </li>
            </ul>
            <ul class="navbar-nav mr-auto mb-2 mb-lg-0" v-if="authStore.user">
                <li class="nav-item">
                   <button @click="(authStore.logout)" class="btn btn-primary">Salir</button>
                </li>
            </ul>
        </div>
    </nav>
</template>