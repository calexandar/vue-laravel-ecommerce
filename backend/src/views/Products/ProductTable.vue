<template>
    <div class="bg-white p-4 rounded-lg shadow animate-fade-in-down">
        <div class="flex justify-between border-b-2 pb-3">
            <div class="flex items-center">
                <span class="whitespace-nowrap mr-3">Per Page</span>
                <select 
                    class="appearance-none relative block w-24 px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                >
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <span class="ml-3">Found [total] products</span>
            </div>
            <div>
                <input 
                    class="appearance-none relative block w-48 px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                    placeholder="Type to Search products"
                    >   
            </div>
        </div>

        <table class="table-auto w-full">
            <thead>
                <tr>

                </tr>
            </thead>
            <tbody v-if="products.loading || !products.data.length">
                <tr>
                    <td colspan="6">
                        <Spinner v-if="products.loading"/>
                        <p class="text-center py-8 text-gray-700">
                            There are no products
                        </p>
                    </td>
                </tr>
            </tbody>
            <tbody v-else>
                <tr v-for="(product, index) of products.data">
                    <td class="border-b p-2">{{ product.id }}</td>
                    <td class="border-b p-2">
                        <img class="w-16 h-16 object-cover" :src="product.image_url" :alt="product.title">
                    </td>
                    <td class="border-b p-2 max-w-[200px] whitespace-nowrap overflow-hidden text-ellipsis">
                        {{ product.title }}
                    </td>
                    <td class="border-b p-2">
                        {{ product.price }}
                    </td>
                    <td class="border-b p-2">
                        {{ product.updated_at }}
                    </td>
                    <td class="border-b p-2">
                        <Menu as="div" class="relative inline-block text-left">
                            <div>
                                <MenuButton class="inline-flex items-center justify-center w-full justify-center rounded-full w-10 h-10 bg-black bg-opacity-0 text-sm font-medium text-white hover:bg-opacity-5 focus:bg-opacity-5 focus:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75">
                                    <EllipsisVerticalIcon class="h-5 w-5 text-indigo-500" aria-hidden="true"/>
                                </MenuButton>
                            </div>

                            <Transition
                                enter-active-class="transition duration-100 ease-out"
                                enter-from-class="transform scale-95 opacity-0"
                                enter-to-class="transform scale-100 opacity-100"
                                leave-active-class="transition duration-75 ease-in"
                                leave-from-class="transform scale-100 opacity-100"
                                leave-to-class="transform scale-95 opacity-0"
                            >
                                <MenuItems
                                    class="absolute z-10 right-0 mt-2 w-32 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                                >
                                    <div class="px-1 py-1">
                                        <MenuItem v-slot="{active}">
                                            <button
                                                :class="[
                                                    active ? 'bg-indigo-600 text-white' : 'text-gray-900',
                                                    'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                                                ]"
                                            >
                                                <PencilSquareIcon
                                                    :active="active"
                                                    class="mr-2 h-5 w-5 text-indigo-400"
                                                    aria-hidden="true"
                                                />
                                                Edit
                                            </button>
                                        </MenuItem>
                                        <MenuItem v-slot="{active}">
                                            <button
                                                :class="[
                                                    active ? 'bg-indigo-600 text-white' : 'text-gray-900',
                                                    'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                                                ]"
                                            >
                                                <TrashIcon
                                                    :active="active"
                                                    class="mr-2 h-5 w-5 text-indigo-400"
                                                    aria-hidden="true"
                                                />
                                                Delete
                                            </button>
                                        </MenuItem>
                                    </div>
                                </MenuItems>
                            </Transition>
                        </Menu>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue';
import store from '../../store';
import Spinner from '../../components/core/Spinner.vue';
import { Menu, MenuButton, MenuItems, MenuItem } from '@headlessui/vue';
import { EllipsisVerticalIcon, PencilSquareIcon, TrashIcon } from '@heroicons/vue/24/outline';


const products = computed(()=> store.state.products);

onMounted(()=>{
    getProducts();
})

function getProducts(url=null) {
    store.dispatch("getProducts", {
        url,
    });
}
</script>

<style scoped>

</style>