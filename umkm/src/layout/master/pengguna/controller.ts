import client from "../../../service/service";

export function pengguna_index(params?: any) {
    return client.get('/user/userlist', {
        params: params
    })
}

export function pengguna_form() {
    return client.get('/user/form')
}