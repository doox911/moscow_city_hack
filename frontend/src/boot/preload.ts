import ApiRequest from "../api/ApiRequest";
import AuthService from "../services/auth.service";

export default async ({ app, router, store }: any) => {
    ApiRequest.beforeRequest = (request) => {
        request.config.headers = {
            'content-type': 'application/json',
            'Authorization': `Bearer ${ AuthService.getToken() }`,
        };
    };
    await AuthService.init();
}