<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    tickets: Object,
});
</script>

<template>
    <AppLayout>
        <div class="p-6">
            <h1 class="mb-4 text-xl font-bold">All Support Tickets</h1>

            <table class="w-full border">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="p-2">User</th>
                        <th class="p-2">Order</th>
                        <th class="p-2">Subject</th>
                        <th class="p-2">Status</th>
                        <th class="p-2">Action</th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="t in tickets.data" :key="t.id">
                        <td class="p-2">{{ t.user.name }}</td>
                        <td class="p-2">#{{ t.order_id }}</td>
                        <td class="p-2">{{ t.subject }}</td>
                        <td class="p-2">{{ t.status }}</td>
                        <td class="p-2">
                            <Link
                                :href="`/supports/${t.id}`"
                                class="text-blue-600 underline"
                                >Manage</Link
                            >
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AppLayout>
</template>
