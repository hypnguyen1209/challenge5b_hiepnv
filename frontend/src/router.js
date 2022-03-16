import {createRouter, createWebHashHistory } from 'vue-router'
//import Login from '@/pages/Login.vue'
import Challenge from '@/pages/Challenge.vue'
import Student from '@/pages/Student.vue'
import Exercise from '@/pages/Exercise.vue'
import Home from '@/pages/Home.vue'
import Manager from '@/pages/admin/Manager.vue'
import Profile from '@/pages/Profile.vue'
import NotFound from '@/pages/NotFound.vue'
const routes = [
    {
        path: '/',
        component: Home
    },
    {
        path: '/challenges',
        component: Challenge
    },
    {
        path: '/exercises',
        component: Exercise
    },
    {
        path: '/students',
        component: Student
    },
    {
        path: '/manager',
        component: Manager
    },
    {
        path: '/me',
        component: Profile
    },
    {
        path: '/:pathMatch(.*)*', 
        name: 'NotFound', 
        component: NotFound
    }
]

export default createRouter({
    history: createWebHashHistory(),
    routes
})