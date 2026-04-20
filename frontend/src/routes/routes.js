import { createRouter, createWebHistory } from 'vue-router';
import landingPage from '../views/landingPage.vue';
import login from '../views/auth/login.vue';
import register from '../views/auth/register.vue';
import general from '../views/general.vue';
import Verification from '@/views/auth/verification.vue';
import PanelAministrador from '@/components/PanelAministrador.vue';
import SellProduct from '@/views/products/SellProduct.vue';
import MyProducts from '@/views/products/MyProducts.vue';
import Explorar from '@/views/Explorar.vue';
import legal from '@/views/footer/legal.vue';
import Favorites from '@/views/products/Favorites.vue';
import Cart from '@/views/user/Cart.vue';
import Perfil from '@/views/perfil/Perfil.vue';

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: '/',
            name: 'landing',
            component: landingPage
        },
        {
            path: '/legal',
            name: 'legal',
            component: legal
        },
        {
            path: '/login',
            name: 'login',
            component: login
        },
        {
            path: '/register',
            name: 'register',
            component: register
        },
        {
            path: '/verificar-email',
            name: 'verificar',
            component: Verification
        },
        {
            path: '/general',
            name: 'general',
            component: general
        },
        {
            path: '/vender',
            name: 'vender',
            component: SellProduct
        },
        {
            path: '/mis-productos',
            name: 'mis-productos',
            component: MyProducts
        },
        {
            path: '/explorar',
            name: 'Explorar',
            component: Explorar
        },
        {
            path: '/producto/:id',
            name: 'ProductoDetalle',
            component: () => import('../views/products/Details.vue')
        },
        {
            path: '/favoritos',
            name: 'Favoritos',
            component: Favorites
        },
        {
            path: '/carrito',
            name: 'Carrito',
            component: Cart
        },
        {
            path: '/perfil',
            name: 'Perfil',
            component: Perfil
        },
        {
            path: '/admin',
            name: 'Admin',
            component: PanelAministrador,
            meta: { 
                requiresAdmin: true 
            }
        }
    ]
});

// ==========================================
// SEGURIDAD DE LAS RUTAS
// ==========================================
router.beforeEach((to, from, next) => {
    const userData = localStorage.getItem("usuario");
    let user = null;

    if (userData) {
        try {
            user = JSON.parse(userData);
        } catch (e) {
            user = null;
        }
    }

    if (user && user.role === 'admin') {
        const permitidasParaAdmin = ['Admin', 'login', 'register', 'general', 'landing', 'perfil'];
        
        if (!permitidasParaAdmin.includes(to.name)) {
            return next({ name: 'Admin' });
        }
    }

    if (to.meta.requiresAdmin) {
        if (user && user.role === 'admin') {
            next();
        } else if (user) {
            next({ name: 'general' });
        } else {
            next({ name: 'login' });
        }
    } else {
        next();
    }
});

export default router;