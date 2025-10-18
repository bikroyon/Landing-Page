<script setup lang="ts">
import { computed } from 'vue';
import { useForm, Head } from '@inertiajs/vue3';
import AuthBase from '@/layouts/AuthLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { login } from '@/routes';

const props = defineProps<{
  prefill?: {
    name?: string;
    email?: string;
    phone?: string;
    address?: string;
  };
}>();

// Determine if we have prefill (existing user)
const hasPrefill = computed(() => !!(props.prefill?.name && (props.prefill.email || props.prefill.phone)));

// Use Inertia useForm for reactive data + POST
const form = useForm({
  name: props.prefill?.name || '',
  email: props.prefill?.email || '',
  phone: props.prefill?.phone || '',
  address: props.prefill?.address || '',
  password: '',
  password_confirmation: ''
});
</script>

<template>
<AuthBase title="Create an account" description="Enter your details below to create your account">
  <Head title="Register" />

  <form @submit.prevent="form.post('/register')" class="flex flex-col gap-6">
    <div class="grid gap-6">
      <!-- Only show these fields if no prefill -->
      <template v-if="!hasPrefill">
        <div class="grid gap-2">
          <Label for="name">Name</Label>
          <Input id="name" type="text" v-model="form.name" placeholder="Full name"/>
          <InputError :message="form.errors.name" />
        </div>

        <div class="grid gap-2">
          <Label for="email">Email</Label>
          <Input id="email" type="email" v-model="form.email" placeholder="email@example.com"/>
          <InputError :message="form.errors.email" />
        </div>

        <div class="grid gap-2">
          <Label for="phone">Phone</Label>
          <Input id="phone" type="text" v-model="form.phone" placeholder="017xxxxxxxx"/>
          <InputError :message="form.errors.phone" />
        </div>

        <div class="grid gap-2">
          <Label for="address">Address</Label>
          <Input id="address" type="text" v-model="form.address" placeholder="Address"/>
          <InputError :message="form.errors.address" />
        </div>
      </template>

      <!-- Hidden fields for prefill -->
      <template v-else>
        <input type="hidden" name="name" :value="form.name"/>
        <input type="hidden" name="email" :value="form.email"/>
        <input type="hidden" name="phone" :value="form.phone"/>
        <input type="hidden" name="address" :value="form.address"/>
      </template>

      <!-- Always show password fields -->
      <div class="grid gap-2">
        <Label for="password">Password</Label>
        <Input id="password" type="password" v-model="form.password" placeholder="Password"/>
        <InputError :message="form.errors.password" />
      </div>

      <div class="grid gap-2">
        <Label for="password_confirmation">Confirm Password</Label>
        <Input id="password_confirmation" type="password" v-model="form.password_confirmation" placeholder="Confirm Password"/>
        <InputError :message="form.errors.password_confirmation" />
      </div>

      <Button type="submit" class="mt-2 w-full" :disabled="form.processing">Create account</Button>
    </div>

    <div class="text-center text-sm text-muted-foreground">
      Already have an account? <TextLink :href="login()">Log in</TextLink>
    </div>
  </form>
</AuthBase>
</template>
