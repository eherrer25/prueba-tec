
<script setup>
import {useAuthStore} from '../stores/auth'
const authStore = useAuthStore();
</script>

<template>
  <div class="home mt-5">
    <!-- <div class="alert alert-success" role="alert" v-if="success">
      {{ success }}
    </div>
    <div class="alert alert-danger" role="alert" v-if="error">
      {{ error }}
    </div> -->
    <h2 v-if="authStore.user">Hola, {{ authStore.user.name }}! Haz ingresado como {{ authStore.rol == 'admin' ? 'Administrador': 'Invitado'}}.</h2>
    <h2 v-else>Bienvenido, por favor ingresa o registrate</h2>
    
  </div>
</template>