<script setup>
import { ref } from 'vue';
import {useAuthStore} from '../../stores/auth.js'
const authStore = useAuthStore();
const form = ref({email:'',password:''})
</script>
<template>
  <div class="row mt-5">
    <div class="col-lg-4 offset-lg-4">
      <div class="card">
        <div class="card-header">
          Login
        </div>
        <div class="card-body">
          <form @submit.prevent="$event => authStore.login(form)">
            <div class="form-group">
              <label for="email">Email address</label>
              <input autofocus type="email" v-model="form.email" class="form-control" id="email" placeholder="Ingrese email"/>
              <!-- <div class="invalid-feedback" v-if="errors.email">
                {{ errors.email[0] }}
              </div> -->
            </div>
            <div class="form-group">
              <label for="password">Password</label>
              <input type="password" v-model="form.password" class="form-control" id="password" placeholder="********"/>
              <!-- <div class="invalid-feedback" v-if="errors.password">
                {{ errors.password[0] }}
              </div> -->
            </div>
            <div class="d-grid col-10 mx-auto">
              <button class="btn btn-primary mt-3">
                Login
              </button>
            </div>
            <router-link :to="{path:'register'}">Registrar</router-link>
          </form>
        </div>
      </div>
    </div>
    
  </div>
</template>