<script setup>
import axios from 'axios';
import {ref, watch} from 'vue';
import CurrencyInput from './CurrencyInput';

const props = defineProps({
    sales: Array,
    products: Array
});

const quantity = ref(0);
const unitCost = ref(0);
const sellingPrice = ref(0);
const hasError = ref(false);
const salesRef = ref(props.sales);
const product = ref(1);

const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

watch([quantity, unitCost], async ([newQuantity, newUnitCost]) => {
    if(newQuantity === 0 || newUnitCost === 0) {
        sellingPrice.value = 0;
        return;
    }
    if (newQuantity && newUnitCost) {

        const data = JSON.stringify({
            'quantity': quantity.value,
            'unit_cost': unitCost.value,
        });

        try {
            const response = await axios.post('/sales/calculate-price', data, {
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json'
                },
            });
            sellingPrice.value = response.data.selling_price;
        } catch (error) {
            // console.log(error);
        }
    }
})

async function recordSale() {

    const data = JSON.stringify({
        'quantity': quantity.value,
        'unit_cost': unitCost.value,
        'selling_price': sellingPrice.value,
        'product_id' : product.value,
    });
    try {
        const response = await axios.post('/sales/record', data, {
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json'
            },
        });

        if (response.data.sale) {
            const sale = response.data.sale;
            salesRef.value.push(sale);
            hasError.value = false;
        }

    } catch (error) {
        // console.log(error)
        hasError.value = true;
    }
}

function toGBP(cents) {
    const formatter = new Intl.NumberFormat('en-GB', {
        style: 'currency',
        currency: 'GBP'
    });
    return formatter.format(cents / 100);
}

</script>

<template>
    <section>
        <div class="w-full w-100">
            <div class="flex flex-wrap -mx-3 mb-2">
                <div class="w-full md:w-1/5 px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="product">
                        Product
                    </label>
                    <select
                        v-model="product"
                        class="appearance-none block w-full text-gray-700 border border-gray-700 rounded py-3.5 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                        id="product">
                        <option v-for="product in products" :value="product.id">{{ product.name }}</option>
                    </select>
                </div>
                <div class="w-full md:w-1/5 px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="qty">
                        Quantity
                    </label>
                    <CurrencyInput
                        id="qty"
                        v-model="quantity"
                        :options="{ currency: 'GBP', currencyDisplay: 'hidden', precision: 0 }"
                    />
                </div>
                <div class="w-full md:w-1/5 px-4 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="uc">
                        Unit Cost
                    </label>
                    <CurrencyInput
                        id="uc"
                        v-model="unitCost"
                        :options="{ currency: 'GBP' }"
                    />
                </div>
                <div class="w-full md:w-1/5 px-4 mb-6 md:mb-0">
                    <span class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                        Selling Price
                    </span>
                    <div class="appearance-none block w-full text-gray-700 py-3 px-3 leading-tight">
                        £{{ sellingPrice }}
                    </div>
                </div>
                <div class="w-full md:w-1/5 px-4 mb-6 md:mb-0">
                    <button
                        class="mt-7 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                        @click="recordSale()"
                    >Record Sale
                    </button>

                </div>
            </div>

            <div v-show="hasError" class="mt-5 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                 role="alert">
                <strong class="font-bold">Error: </strong>
                <span class="block sm:inline">Couldn't store sales record. Please check your input values.</span>
            </div>

        </div>

        <div class="place-self-center flex flex-col gap-y-3 w-100 mt-10">

            <table class="table-fixed border-spacing-2 border-separate">
                <thead>
                <tr class="px-12 bg-blue-200">
                    <th class="text-left px-6 py-2">Product</th>
                    <th class="text-left px-6 py-2">Quantity</th>
                    <th class="text-left px-6 py-2">Unit Cost</th>
                    <th class="text-left px-6 py-2">Selling Price</th>
                    <th class="text-left px-6 py-2">Sold at</th>
                </tr>
                </thead>

                <tbody>
                <tr class="px-12"
                    v-for="(sale, index) in salesRef"
                    :class="index % 2 === 0 ? 'bg-gray-200' : 'bg-gray-100'"
                >
                    <td class="px-6 py-4" v-text="sale.product"></td>
                    <td class="px-6 py-4" v-text="sale.quantity"></td>
                    <td class="px-6 py-4" v-text="toGBP(sale.unit_cost)"></td>
                    <td class="px-6 py-4" v-text="toGBP(sale.selling_price)"></td>
                    <td class="px-6 py-4" v-text="$filters.formatDate(sale.created_at)"></td>
                </tr>
                </tbody>
            </table>

            <div v-show="salesRef.length === 0"  class="mt-5 bg-blue-100 border border-b border-blue-500 text-blue-700 px-4 py-3" role="alert">
                <strong class="font-bold">Info: </strong>
                <span class="block sm:inline">No records yet.</span>
            </div>
        </div>
    </section>
</template>

