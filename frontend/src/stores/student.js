import { defineStore } from 'pinia'
import api from '@/services/api'

export const useStudentStore =  defineStore('student', {
    state() {
        return {
            list: [],
            log: ''
        }
    },
    actions: {
        async get() {
            let req = await api.get('/students')
            if(req.exception) {
                this.log = req.message
                return false
            }
            this.list = req.data
            return true
        },
    },
    getters: {
    }
})