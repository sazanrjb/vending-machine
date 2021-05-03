import httpClient from '../utils/HttpClient';
import * as API from './index';

export function getProducts() {
    return httpClient.get(API.PRODUCTS);
}

export function getPurchases() {
    return httpClient.get(API.PURCHASES);
}

export function purchaseProduct(payload = {}) {
    return httpClient.post(API.PURCHASE_PRODUCT, payload);
}

export function refundProduct(payload = {}) {
    return httpClient.post(API.REFUND_PRODUCT, payload);
}
