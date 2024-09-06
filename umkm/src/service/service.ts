import axios from 'axios';
import { getCookie } from 'typescript-cookie';
import { notification } from '../component/notification/notificationService';
const client = axios.create({
    baseURL: 'http://127.0.0.1:8000/api/v1',
    headers: {
        Authorization: `Bearer ${getCookie('LOG')}`
    }
});

client.interceptors.request.use((config) => {
    const token = getCookie('LOG');
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
});

client.interceptors.response.use(
    async function (response) {
        const res = await response.data;
        if (res?.notif) {
            notification.show({
                key: 'notif',
                position: 'top-right',
                theme: res?.notif?.theme,
                title: res?.notif?.title,
                body: res?.notif?.body,
                duration: 5000
            })
        }
        return res;
    },
    function (err) {
        if (err.response.status === 400) {
            notification.show({
                key: 'notif',
                position: 'top-right',
                theme: 'error',
                title: 'Validation Error',
                body: err.response?.data?.message,
                duration: 5000
            })
        }
        Promise.reject(new Error(err));
    }
);

export default client;