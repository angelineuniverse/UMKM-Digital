import client from "../../service/service";

export function menu(){
    return client.get('/menu');
}