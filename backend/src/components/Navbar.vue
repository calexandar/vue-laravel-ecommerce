<template>
    <Disclosure as="nav" class="bg-gray-100" v-slot="{ open }">
      <div class="mx-auto max-w-7xl px-2 sm:px-6 lg:px-8">
        <div class="relative flex h-16 items-center justify-between">
          <div class="flex flex-1 items-center justify-center sm:items-stretch sm:justify-start">
            <div class="flex flex-shrink-0 items-center">
              <button 
                  class="flex items-center justify-center rounded transition-colors w-8 h-8 text-gray-700 hover:bg-black/10">
                  <Bars3Icon class="w-6"/>
              </button>
            </div>

          </div>
          <div class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0">
            <!-- Profile dropdown -->
            <Menu as="div" class="relative ml-3">
              <div>
                <MenuButton class="flex rounded-full bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800">
                  <span class="sr-only">Open user menu</span>
                  <img class="h-8 w-8 rounded-full" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="" />
                </MenuButton>
              </div>
              <transition enter-active-class="transition ease-out duration-100" enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100" leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
                <MenuItems class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                  <MenuItem v-slot="{ active }">
                    <a href="#" :class="[active ? 'bg-gray-100' : '', 'block px-4 py-2 text-sm text-gray-700']">Your Profile</a>
                  </MenuItem>

                  <MenuItem v-slot="{ active }">
                    <button 
                      @click="logout"
                      :class="[
                        active ? 'bg-gray-100' : '', 'block px-4 py-2 text-sm text-gray-700'
                      ]"
                    >Sign out
                    </button>
                  </MenuItem>
                </MenuItems>
              </transition>
            </Menu>
          </div>
        </div>
      </div>

    </Disclosure>
  </template>
  
  <script setup>
  import { Disclosure, DisclosureButton, DisclosurePanel, Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
  import { Bars3Icon,Bars4Icon, BellIcon, XMarkIcon } from '@heroicons/vue/24/outline'
  import router from '../router';
  import store from '../store';
  


  function logout() {
    store.dispatch('logout')
      .then(()=> {
        router.push({name:'login'})
      })
  }
  </script>