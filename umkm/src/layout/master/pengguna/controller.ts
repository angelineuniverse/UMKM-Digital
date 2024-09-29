import client from "../../../service/service";

export function pengguna_index(params?: any) {
    return client.get('/user/userlist', {
        params: params
    })
}
export function pengguna_form() {
    return client.get('/user/form')
}
export function pengguna_form_edit(id: string | undefined) {
    return client.get('/user/edit/'+ id)
}
export function pengguna_store(data: any) {
    return client.post('/user', data)
}
export function pengguna_update(id: string | undefined,data: any) {
    return client.post('/user/update/'+id, data)
}
export function pengguna_destroy(id: number) {
    return client.delete('/user/'+id)
}