<template>
    <div style="padding: 24px; margin-top:10px; width: 60%; margin: auto;">
        <a-layout theme="light">
            <vending-machine-app/>
        </a-layout>
    </div>
</template>

<script>
import Vue from 'vue'
import Antd from 'ant-design-vue'
import VendingMachineApp from './vendingMachine/App.vue';
import 'ant-design-vue/dist/antd.css';

Vue.use(Antd);

export default {
    components: {
        VendingMachineApp,
    },
}
</script>
