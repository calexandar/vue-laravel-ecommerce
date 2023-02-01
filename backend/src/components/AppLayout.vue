<template>
    <div v-if="currentUser.id" class="min-h-full bg-gray-200 flex">
        <Sidebar />
        <div class="flex-1">
            <Navbar/>
            <main class="p-6">
                <router-view></router-view>
            </main>
        </div>

    </div>
</template>

<script setup>
import {ref, computed, onMounted, onUnmounted} from 'vue'
import Sidebar from "../components/Sidebar.vue";
import Navbar from "../components/Navbar.vue";
import store from "../store";


const currentUser = computed(()=> store.state.user.data);

onMounted(()=>{
    store.dispatch('getCurrentUser')
})

</script>

<style  scoped>

</style>