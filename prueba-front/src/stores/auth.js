import {defineStore} from 'pinia';
import { show_alert } from '../functions';

export const useAuthStore = defineStore('auth',{
    state: () => ({
        authUser:null,
        authToken:null,
        authRol:null
    }),
    getters: {
        user:(state) => state.authUser,
        token:(state) => state.authToken,
        rol:(state) => state.authRol
    },
    actions:{
        async getToken(){
            await axios.post(import.meta.env.VITE_API_ENDPOINT+'refresh').then(
                (res) => {
                    this.authToken = res.data.token;
                    this.authUser = res.data.user;
                    this.authRol = res.data.rol;
                    this.router.go();
                }
                
            )
        },
        async login(form){
            await axios.post(import.meta.env.VITE_API_ENDPOINT+'login',form).then(
                (res) => {
                    this.authToken = res.data.token;
                    this.authUser = res.data.user;
                    this.authRol = res.data.rol;
                    this.router.push('/');
                }
            ).catch(
                (errors) => {
                    let desc = '';
                    let er = Object.values(errors.response.data);
                    er.map(
                        (e) => {desc = desc + ' '+ e}
                    )
                    show_alert(desc,'error','');
                }
            )
        },
        async register(form){
            await axios.post(import.meta.env.VITE_API_ENDPOINT+'register',form).then(
                (res) => {
                    show_alert(res.data.message,'success','');
                    setTimeout(
                        () => this.router.push('/login',2000)
                    )
                    this.router.push('/users');
                }
            ).catch(
                (errors) => {
                    let desc = '';
                    let er = Object.values(errors.response.data.errors);
                    er.map(
                        (e) => {desc = desc + ' '+ e}
                    )
                    show_alert(desc,'error','');
                }
            )
        },
        async logout(){
            axios.defaults.headers.common['Authorization'] = `Bearer ${this.authToken}`;
            await axios.post(import.meta.env.VITE_API_ENDPOINT+'logout');
            this.authToken = null;
            this.authUser = null;
            this.authRol = null;
            this.router.push('/login')
        }
    },
    persist:true
});
