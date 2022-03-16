import { defineStore } from 'pinia'
import api from '@/services/api'

export const useAccountStore =  defineStore('account', {
    state() {
        return {
            id: 0,
            username: '',
            fullname: '',
            email: '',
            phone: '',
            type: '',
            message: '',
            log: ''
        }
    },
    actions: {
        async update() {
            let req = await api.get('/me')
            if(req.exception) {
                this.log = req.message
                return false
            } 
            let {id, username, fullname, email, phone, type, message} = req
            this.id = id
            this.username = username
            this.fullname = fullname
            this.email = email
            this.phone = phone
            this.type = type
            this.message = message
            return true
        }
    },
    getters: {
    }
})