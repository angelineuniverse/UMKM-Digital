export function mappingForm(form: any) {
    const output = new FormData();
    form.forEach((item: any) => {
        if (item[item.key] != null) {
            switch (item.type) {
                case "text":
                case "number":
                case "select":
                case "password":
                    output.append(item.key, item[item.key]);
                    break;
                default:
                    break;
            }
        }
    })
    return output;
}