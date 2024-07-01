<script setup>

import { reactive, computed } from 'vue';
import {useAuthStore} from '../../stores/auth.js'
import { useVuelidate } from '@vuelidate/core'
import { minLength, required, email, sameAs,helpers } from '@vuelidate/validators'

const authStore = useAuthStore();
// const form = ref({name:'',email:'',password:''})

const form = reactive({
  name: '',
  email: '',
  password: '',
  confirmPassword: ''
})

const rules = computed(() => {
  return {
    name: { required: helpers.withMessage('Este campo es requerido', required) },
    email: {
      required: helpers.withMessage('Este campo es requerido', required),
      email: helpers.withMessage('Debe ser de tipo email', email) 
    },
    password: {
      required: helpers.withMessage('Este campo es requerido', required), 
      minLength: helpers.withMessage('Debe contener minimo 8 caracteres', minLength(8))
    },
    confirmPassword: {
      required: helpers.withMessage('Este campo es requerido', required),
      sameAs: helpers.withMessage('Contraseña no coincide', sameAs(form.password)),
    }
  }
})

const v$ = useVuelidate(rules, form)

async function handleSubmit() {
  // Validate the form fields
  const result = await v$.value.$validate()
  if (!result) {
    // If there are errors in the form, show an alert indicating that the form is invalid
    alert('El formulario tiene errores')
    return
  }
  authStore.register(form)
}

</script>
<template>
  <div class="row mt-5">
    <div class="col-lg-4 offset-lg-4">
      <div class="card">
        <div class="card-header">
          Registrar
        </div>
        <div class="card-body">
          <form @submit.prevent="handleSubmit">
            <div class="form-group">
              <label for="email">Nombre</label>
              <input type="text" v-model="form.name" class="form-control" id="name" placeholder="Ingrese nombre"/>
                <div class="invalid-feedback" :class="{ 'd-block': v$.name.$errors.length }">
                    <div class="input-errors" v-for="error of v$.name.$errors" :key="error.$uid">
                        <div class="error-msg">{{ error.$message }}</div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" v-model="form.email" class="form-control" id="email" placeholder="Ingrese email"/>
                <div class="invalid-feedback" :class="{ 'd-block': v$.email.$errors.length }">
                    <div class="input-errors" v-for="error of v$.email.$errors" :key="error.$uid">
                        <div class="error-msg">{{ error.$message }}</div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" v-model="form.password" class="form-control" id="password" placeholder="Ingrese contraseña"/>
               <div class="invalid-feedback" :class="{ 'd-block': v$.password.$errors.length }">
                    <div class="input-errors" v-for="error of v$.password.$errors" :key="error.$uid">
                        <div class="error-msg">{{ error.$message }}</div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="password-confirm">Confirmar Contraseña</label>
                <input v-model="form.confirmPassword" type="password" placeholder="Confirm password" id="password-confirm" class="form-control">
                <div class="invalid-feedback" :class="{ 'd-block': v$.confirmPassword.$errors.length }">
                    <div class="input-errors" v-for="error of v$.confirmPassword.$errors" :key="error.$uid">
                        <div class="error-msg">{{ error.$message }}</div>
                    </div>
                </div>
            </div>
            <div class="d-grid col-10 mx-auto">
              <button class="btn btn-primary mt-3">
                Enviar
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
    
  </div>
</template>