<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const passwordStrength = ref(0);

const getPasswordStrength = (password) => {
    let strength = 0;
    if (password.length >= 8) strength++; 
    if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++; 
    if (password.match(/[0-9]/)) strength++;
    if (password.match(/[^a-zA-Z0-9]/)) strength++; 
    return strength;
};

const passwordStrengthColor = computed(() => {
    const strength = getPasswordStrength(form.password);
    if (strength === 0) return 'bg-gray-400';
    if (strength === 1) return 'bg-red-500';
    if (strength === 2) return 'bg-yellow-500';
    if (strength === 3) return 'bg-cyan-500';
    return 'bg-emerald-500';
});

const passwordStrengthText = computed(() => {
    const strength = getPasswordStrength(form.password);
    if (strength === 0) return '';
    if (strength === 1) return 'Weak';
    if (strength === 2) return 'Fair';
    if (strength === 3) return 'Good';
    return 'Strong';
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <div class="mb-8">
            <h1 class="text-4xl font-black text-white mb-3 tracking-tight">Create Account</h1>
            <p class="text-gray-400 text-lg">Join thousands using Axiom for project management</p>
        </div>

        <form @submit.prevent="submit" class="space-y-6">
            <div class="space-y-2">
                <InputLabel for="name" value="Full Name" class="text-white/90 font-semibold text-sm" />

                <input
                    id="name"
                    type="text"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                    placeholder="John Doe"
                    class="w-full px-4 py-3.5 bg-white/5 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/20 transition-all duration-200 backdrop-blur-sm hover:border-white/20"
                />

                <InputError class="mt-2 text-red-400 text-sm" :message="form.errors.name" />
            </div>

            <div class="space-y-2">
                <InputLabel for="email" value="Email Address" class="text-white/90 font-semibold text-sm" />

                <input
                    id="email"
                    type="email"
                    v-model="form.email"
                    required
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
                    autocomplete="new-password"
                    placeholder="••••••••"
                    @input="passwordStrength = getPasswordStrength(form.password)"
                    class="w-full px-4 py-3.5 bg-white/5 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/20 transition-all duration-200 backdrop-blur-sm hover:border-white/20"
                />

                <!-- Password Strength Indicator -->
                <div v-if="form.password" class="mt-3 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-400">Strength</span>
                        <span class="text-xs font-semibold" :class="passwordStrengthColor === 'bg-emerald-500' ? 'text-emerald-400' : 'text-' + passwordStrengthColor.split('-')[1] + '-400'">
                            {{ passwordStrengthText }}
                        </span>
                    </div>
                    <div class="w-full h-2 bg-white/10 rounded-full overflow-hidden">
                        <div :class="`h-full ${passwordStrengthColor} transition-all duration-300`" :style="{ width: (passwordStrength / 4) * 100 + '%' }"></div>
                    </div>
                </div>

                <InputError class="mt-2 text-red-400 text-sm" :message="form.errors.password" />
            </div>

            <div class="space-y-2">
                <InputLabel for="password_confirmation" value="Confirm Password" class="text-white/90 font-semibold text-sm" />

                <input
                    id="password_confirmation"
                    type="password"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                    placeholder="••••••••"
                    class="w-full px-4 py-3.5 bg-white/5 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/20 transition-all duration-200 backdrop-blur-sm hover:border-white/20"
                />

                <InputError class="mt-2 text-red-400 text-sm" :message="form.errors.password_confirmation" />
            </div>

            <button
                @click.prevent="submit"
                :disabled="form.processing"
                :class="{ 'opacity-60 cursor-not-allowed scale-98': form.processing }"
                class="w-full py-3.5 px-4 bg-gradient-to-r from-cyan-500 to-purple-600 text-white font-black rounded-xl hover:shadow-2xl hover:shadow-purple-600/40 transition-all duration-300 disabled:cursor-not-allowed mt-8 text-lg relative overflow-hidden group"
            >
                <span class="relative z-10 flex items-center justify-center">
                    {{ form.processing ? 'Creating account...' : 'Create Account' }}
                    <svg v-if="!form.processing" class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </span>
            </button>
        </form>

        <div class="mt-8 text-center border-t border-white/10 pt-6">
            <p class="text-gray-400 text-sm">
                Already have an account?
                <Link
                    href="/login"
                    class="text-cyan-400 hover:text-cyan-300 font-bold transition-colors"
                >
                    Sign in here
                </Link>
            </p>
        </div>
    </GuestLayout>
</template>
