class API {
    constructor() {
        this.prefixAPI = '/api'
    }
    async catchException(err) {
        sweetAlert('Oops...', '' + err, 'error')
    }
    authHeader() {
        try {
            let auth = window.localStorage.getItem('auth')
            let contentType = {
                'Content-Type': 'application/json'
            }
            let acceptType = {
                'Accept': 'application/json'
            }
            return auth ? {
                Authorization: 'Bearer ' + auth,
                ...contentType,
                ...acceptType
            } : {
                ...contentType,
                ...acceptType
            }
        } catch (error) {
            console.log(error)
            return {
                ...contentType,
                ...acceptType
            }
        }
    }
    /**
     * 
     * @async
	 * @param {string} username 
	 * @param {string} password 
	 * @returns {Promise<void>}
	 * @throws {Error}
     */
    async login(username, password) {
        try {
            let req = await this.post('/login', {
                username, password
            })
            return req
        } catch (err) {
            this.catchException(err)
        }
    }

    async logout() {
        return await this.get('/logout', {
            token: window.localStorage.getItem('auth')
        })
    }
    async get(url, params = undefined) {
        try {
            let qs = new URLSearchParams(params).toString()
            let req = await fetch(`${this.prefixAPI}${url}${qs ? '?' + qs : ''}`, {
                method: 'GET',
                headers: this.authHeader()
            })
            let result = await req.json()
            return result
        } catch (err) {
            this.catchException(err)
        }
    }
    async post(url, body = {}) {
        try {
            let req = await fetch(`${this.prefixAPI}${url}`, {
                method: 'POST',
                body: JSON.stringify(body),
                headers: this.authHeader()
            })
            let result = await req.json()
            return result
        } catch (err) {
            this.catchException(err)
        }
    }
    async put(url, body = {}) {
        try {
            let req = await fetch(`${this.prefixAPI}${url}`, {
                method: 'PUT',
                body: JSON.stringify(body),
                headers: this.authHeader()
            })
            let result = await req.json()
            return result
        } catch (err) {
            this.catchException(err)
        }
    }
    async delete(url) {
        try {
            let req = await fetch(`${this.prefixAPI}${url}`, {
                method: 'DELETE',
                headers: this.authHeader()
            })
            let result = await req.json()
            return result
        } catch (err) {
            this.catchException(err)
        }
    }
    async upload(url, file, body = {}) {
        try {
            let formData = new FormData()
            formData.append('file', file)
            Object.keys(body).forEach(item => {
                formData.append(item, body[item])
            })
            let req = await fetch(`${this.prefixAPI}${url}`, {
                method: 'POST',
                body: formData,
                headers: {
                    Authorization: this.authHeader().Authorization,
                    'Accept': 'application/json'
                }
            })
            let result = await req.json()
            return result
        } catch (err) {
            this.catchException(err)
        }
    }
}

export default new API()