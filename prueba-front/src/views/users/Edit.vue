<script setup>
import { reactive, computed,onMounted,ref } from 'vue';
import { useRoute } from 'vue-router';
import { sendRequest} from '../../functions'
import {useAuthStore} from '../../stores/auth'
import { useVuelidate } from '@vuelidate/core'
import { required,minLength,helpers } from '@vuelidate/validators'

const authStore = useAuthStore();
axios.defaults.headers.common['Authorization'] = `Bearer ${authStore.token}`;

const route = useRoute();
const form = reactive({
    name: '',
    password: ''
})
const id = ref(route.params.id);

onMounted( () => { getUser() });

const getUser = () => {
    axios.get(import.meta.env.VITE_API_ENDPOINT+'users/'+id.value).then(
        res => {
            form.name = res.data.data.name
        }
    )
}

const rules = computed(() => {
  return {
    name: { required: helpers.withMessage('Este campo es requerido', required) },
    password: {
      minLength: helpers.withMessage('Debe contener minimo 8 caracteres', minLength(8))
    },
  }
})

const v$ = useVuelidate(rules, form)

async function handleSubmit() {
  const result = await v$.value.$validate()
  if (!result) {
    alert('El formulario tiene errores')
    return
  }
  sendRequest('PUT',form,import.meta.env.VITE_API_ENDPOINT+'users/'+id.value,'/users');
}
</script>
<template>
    <div class="row mt-5">
        <div class="col-lg-4 offset-lg-4">
        <div class="card">
            <div class="card-header">
            Editar
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
                    <label for="password">Contraseña</label>
                    <input type="password" v-model="form.password" class="form-control" id="password" placeholder="Ingrese contraseña"/>
                    <div class="invalid-feedback" :class="{ 'd-block': v$.password.$errors.length }">
                        <div class="input-errors" v-for="error of v$.password.$errors" :key="error.$uid">
                            <div class="error-msg">{{ error.$message }}</div>
                        </div>
                    </div>
                </div>
                <div class="d-grid col-10 mx-auto">
                <button class="btn btn-primary mt-3">
                    Editar
                </button>
                </div>
            </form>
            </div>
        </div>
        </div>
    </div>
</template>