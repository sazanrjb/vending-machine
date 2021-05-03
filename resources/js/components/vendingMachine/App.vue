<template>
    <div>
        <a-page-header
            style="border: 1px solid rgb(235, 237, 240)"
            title="VENDING MACHINE"
        />
        <div class="margin-top-10 padding-x-15">
            <a-row class="flex flex-align-center flex-space-between">
                <a-col>
                    <div><strong>Coins in Machine:</strong> {{ amount_in_machine }}</div>
                </a-col>
                <a-col>
                    <div><strong>Remaining Coins:</strong> {{ amount_limit - amount_in_machine }}</div>
                </a-col>
            </a-row>
            <a-row class="margin-top-10">
                <a-col>
                    <div>
                        Insert Coin Amount
                        <div><a-input-number v-model="amount" style="width: 200px"/></div>
                    </div>
                </a-col>
            </a-row>
            <br>
            <a-list :grid="{ gutter: 16, column: 3 }" :data-source="products">
                <a-list-item slot="renderItem" slot-scope="item, index">
                    <a-card :title="item.name">
                        <div>
                            <a-row>
                                <a-col>
                                    <div class="flex flex-space-between flex-align-center">
                                        <a-space><a-icon type="money-collect"/>Price</a-space>
                                        <div>Rs {{ item.price }}</div>
                                    </div>
                                </a-col>
                            </a-row>
                            <a-divider/>
                            <a-row>
                                <a-col>
                                    <div class="flex flex-space-between flex-align-center">
                                        <a-space><a-icon type="appstore"/>Stock</a-space>
                                        <div>{{ item.total }}</div>
                                    </div>
                                </a-col>
                            </a-row>
                        </div>
                        <a-button class="margin-top-10 width-100p" type="default"
                                  @click="purchaseProduct(item.id)">Buy
                        </a-button>
                    </a-card>
                </a-list-item>
            </a-list>
            <a-list bordered :data-source="purchases" class="margin-bottom-15">
                <a-list-item slot="renderItem" slot-scope="item, index"
                             class="flex flex-space-between flex-align-center width-100p">
                    <div>
                        You purchased {{ item.product.name }} for Rs. {{ item.price }} {{item.purchased_at.formatted }}.
                        <div><strong>Amount Paid:</strong> {{ item.amount_paid }}</div>
                        <div><strong>Amount Returned:</strong> {{ item.amount_returned }}</div>
                    </div>

                    <a-button type="danger" @click="refundProduct(item.id)">Refund</a-button>
                </a-list-item>
                <div slot="header">
                    <strong>Purchases</strong>
                </div>
            </a-list>
        </div>
    </div>
</template>

<script>
import {getProducts, getPurchases, purchaseProduct, refundProduct} from '../../api/calls';

export default {
    name: 'VendingMachineApp',
    data() {
        return {
            products: [],
            purchases: [],
            amount: null,
            amount_in_machine: 0,
            amount_limit: 0,
        }
    },
    created() {
        Promise.all([
            this.fetchProducts(),
            this.fetchPurchases(),
        ])
    },
    methods: {
        fetchProducts() {
            getProducts().then(({data}) => {
                this.products = data.data;
                this.amount_in_machine = data.amount;
                this.amount_limit = data.amount_limit;
            })
                .catch(({response}) => {
                    this.$notification.open({
                        message: response.data.message
                    });
                })
        },
        fetchPurchases() {
            getPurchases().then(({data}) => {
                this.purchases = data.data;
            })
                .catch(({response}) => {
                    this.$notification.open({
                        message: response.data.message
                    });
                })
        },
        purchaseProduct(productId) {
            if (!this.amount) {
                return false;
            }

            const payload = {
                product_id: productId,
                amount: this.amount
            };
            purchaseProduct(payload)
                .then(({data}) => {
                    this.$notification.open({
                        message: data.message,
                    });

                    this.fetchProducts();
                    this.fetchPurchases()
                })
                .catch(({response}) => {
                    this.$notification.open({
                        message: response.data.message
                    });
                });
        },
        refundProduct(purchaseId) {
            const payload = {
                purchase_id: purchaseId,
            };
            refundProduct(payload)
                .then(({data}) => {
                    this.$notification.open({
                        message: data.message,
                    });
                    this.fetchProducts();
                    this.fetchPurchases()
                })
                .catch(({response}) => {
                    this.$notification.open({
                        message: response.data.message
                    });
                });
        },
    },
}
</script>

<style scoped>
.anticon {
    font-size: 24px;
}

.flex {
    display: flex;
}

.flex-space-between {
    justify-content: space-between;
}

.flex-align-center {
    align-items: center
}

.margin-top-10 {
    margin-top: 10px;
}

.width-100p {
    width: 100%;
}

.margin-bottom-15 {
    margin-bottom: 15px;
}

.padding-x-15 {
    padding: 0 15px;
}
</style>
