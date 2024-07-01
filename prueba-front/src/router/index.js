import {createRouter,createWebHistory} from "vue-router"
import Home from '../views/Home.vue'
import {useAuthStore} from '../stores/auth'

const role = (to, from, next) => {
    const auth = useAuthStore()
    if (auth.rol == 'admin') {
        return next();
    } else {
        return next("/login");
    }
};

const router =  createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        {
            path: "/",
            name: "Home",
            component: () => Home
        },
        {
            path: "/login",
            name: "Login",
            component: () => import( "../views/Auth/Login.vue")
        },
        {
            path: "/register",
            name: "Register",
            component: () => import( "../views/Auth/Register.vue")
        },
        {
            path: "/users",
            name: "Users",
            beforeEnter: role,
            component: () => import( "../views/users/Index.vue")
        },
        {
            path: "/create",
            name: "Create",
            component: () => import( "../views/users/Create.vue")
        },
        {
            path: "/edit/:id",
            name: "Edit",
            component: () => import( "../views/users/Edit.vue")
        }
    ]
})

router.beforeEach( async (to) =>{
    const publicPage = ['/login','/register','/']
    const authRequired = !publicPage.includes(to.path)
    const auth = useAuthStore()
    if(authRequired && !auth.user){
        auth.returnUrl = to.fullPath
        return '/login'
    }
})

export default router;