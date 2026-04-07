import { createRouter, createWebHistory } from 'vue-router';
import landingPage from '../views/landingPage.vue';
import login from '../views/auth/login.vue';
import register from '../views/auth/register.vue';
import general from '../views/general.vue';
import Verification from '@/views/auth/verification.vue';

import SellProduct from '@/views/products/SellProduct.vue';
import MyProducts from '@/views/products/MyProducts.vue';

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: '/',
            name: 'landing',
            component: landingPage
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
        }
    ]
})

export default router;