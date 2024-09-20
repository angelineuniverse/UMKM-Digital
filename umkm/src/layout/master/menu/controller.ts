import client from "../../../service/service";

export function menu_index() {
    return client.get("/menu/1");
}