import client from "../../../service/service";

export function menu_index() {
    return client.get("/menu");
}

export function menu_form() {
    return client.get("/menu:form");
}