<script>
import { useCurrencyInput } from 'vue-currency-input'

export default {
    name: 'CurrencyInput',
    props: {
        modelValue: Number,
        options: Object
    },
    setup(props) {
        const { inputRef } = useCurrencyInput(props.options)

        return { inputRef }
    }
}
</script>

<template>
    <input
        ref="inputRef"
        type="text"
        class="appearance-none block w-full text-gray-700 border border-gray-700 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
    />
</template>
