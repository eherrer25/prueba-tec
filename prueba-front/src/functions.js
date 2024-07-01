import Swal from "sweetalert2";
import { nextTick } from "vue";
import {useAuthStore} from "./stores/auth";

export function show_alert(msg,icon,focus)
{
    if(focus !== ''){
        nextTick(()=> focus.value.focus());
    }
    Swal.fire({
        title:msg,icon:icon,buttonsStyling:true
    });
}

export function confirmation(name,url,redirect)
{
    const alert = Swal.mixin({buttonsStyling:true});
    alert.fire({
        title: 'Estas seguro de eliminar: '+name+'?',
        icon:'question',showCancelButton:true,
        confirmButtonText:'<i class="fa fa-solid fa-check"> Eliminar</i>',
        cancelButtonText:'<i class="fa fa-solid fa-ban"> Cancelar</i>',
    }).then((result) => {
        if(result.isConfirmed){
            sendRequest('DELETE',{},url,redirect)
        }
    });
}

export async function sendRequest(method,params,url,redirect='')
{
    const authStore = useAuthStore();
    axios.defaults.headers.common['Authorization'] = `Bearer ${authStore.token}`;
    let res;
    await axios({ method:method, url:url,data:params}).then(
        response => {
            res = response.data.status
            show_alert(response.data.message,'success',''),
            setTimeout(
                ()=> (redirect !== '') ? window.location.href = redirect: ''
                ,2000)
        }).catch((errors) => {
            let desc = '';
            let er = Object.values(errors.response.data);
            er.map((e)=>{
                desc = desc+' '+e
            })
            show_alert(desc,'error','')
        })

        return res;
}