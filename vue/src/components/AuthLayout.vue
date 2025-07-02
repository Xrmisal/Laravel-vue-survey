<script>
</script>

<template>
    <div class="min-h-screen flex-none">
        <!-- Top strip / logo -->
        <header class="p-4 bg-gray-50 text-center justify-center items-center">
            <img class="mx-auto h-10 w-auto" src="https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=indigo&shade=600" alt="Your Company" />
            <h2 class="mt-10 text-center text-2xl/9 font-bold tracking-tight text-gray-900"><Router-Link v-if="$route.name==='Login'" :to="{ name: 'Register' }">Sign in to your account</Router-Link><Router-Link v-else :to="{ name: 'Login' }">Register for free</Router-Link></h2>
        </header>
        
        <!-- slot for login/register forms -->
        <main class="flex-1 flex justify-center px-2-full max-w-s">
            <router-view />
        </main>
        
        <!-- Bottom nav -->
        <footer class="p-4 text-center text-sm text-gray-600">
            <RouterLink v-if="$route.name==='Login'" :to="{ name: 'Register' }">
                Not a member? Register
            </RouterLink>
            <RouterLink v-else :to="{ name: 'Login' }">
                Have an account? Login
            </RouterLink>
        </footer>
    </div>
</template>



