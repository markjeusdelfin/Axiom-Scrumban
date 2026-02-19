<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <div class="mb-8">
            <h1 class="text-4xl font-black text-white mb-3 tracking-tight">Welcome Back</h1>
            <p class="text-gray-400 text-lg">Sign in to your account to get started</p>
        </div>

        <div v-if="status" class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/30 rounded-xl text-emerald-400 text-sm font-medium animate-pulse">
            ✓ {{ status }}
        </div>

        <form @submit.prevent="submit" class="space-y-6">
            <div class="space-y-2">
                <InputLabel for="email" value="Email Address" class="text-white/90 font-semibold text-sm" />

                <input
                    id="email"
                    type="email"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="you@example.com"
                    class="w-full px-4 py-3.5 bg-white/5 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/20 transition-all duration-200 backdrop-blur-sm hover:border-white/20"
                />

                <InputError class="mt-2 text-red-400 text-sm" :message="form.errors.email" />
            </div>

            <div class="space-y-2">
                <InputLabel for="password" value="Password" class="text-white/90 font-semibold text-sm" />

                <input
                    id="password"
                    type="password"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                    placeholder="••••••••"
                    class="w-full px-4 py-3.5 bg-white/5 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/20 transition-all duration-200 backdrop-blur-sm hover:border-white/20"
                />

                <InputError class="mt-2 text-red-400 text-sm" :message="form.errors.password" />
            </div>

            <div class="flex items-center justify-between pt-2">
                <label class="flex items-center space-x-3 cursor-pointer group">
                    <input
                        type="checkbox"
                        v-model="form.remember"
                        class="w-5 h-5 bg-white/5 border border-white/20 rounded-lg text-cyan-500 focus:ring-2 focus:ring-cyan-500/20 cursor-pointer group-hover:border-white/30 transition-all"
                    />
                    <span class="text-sm text-gray-400 group-hover:text-gray-300 transition-colors">Remember me</span>
                </label>

                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="text-sm text-cyan-400 hover:text-cyan-300 transition-colors font-semibold"
                >
                    Forgot password?
                </Link>
            </div>

            <button
                @click.prevent="submit"
                :disabled="form.processing"
                :class="{ 'opacity-60 cursor-not-allowed scale-98': form.processing }"
                class="w-full py-3.5 px-4 bg-gradient-to-r from-cyan-500 to-purple-600 text-white font-black rounded-xl hover:shadow-2xl hover:shadow-purple-600/40 transition-all duration-300 disabled:cursor-not-allowed mt-8 text-lg relative overflow-hidden group"
            >
                <span class="relative z-10 flex items-center justify-center">
                    {{ form.processing ? 'Signing in...' : 'Sign In' }}
                    <svg v-if="!form.processing" class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </span>
            </button>
        </form>

        <div class="mt-8 text-center border-t border-white/10 pt-6">
            <p class="text-gray-400 text-sm">
                Don't have an account?
                <Link
                    href="/register"
                    class="text-cyan-400 hover:text-cyan-300 font-bold transition-colors"
                >
                    Create one now
                </Link>
            </p>
        </div>
    </GuestLayout>
</template>
