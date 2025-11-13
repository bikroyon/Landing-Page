<script setup>
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import AppLayout from '@/layouts/AppLayout.vue';
import { Icon } from '@iconify/vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    tickets: Object,
    filters: Object,
});

const filters = ref({
    search: props.filters?.search || '',
});

const breadcrumbs = [{ title: 'All Support Tickets', href: '/supports' }];

const searchTickets = () => {
    router.get('/admin/supports', {
        search: filters.value.search,
    });
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="All Support Tickets" />
        <div class="p-4 py-2">
            <!-- Header -->
            <div class="mb-6">
                <div>
                    <h1 class="mb-4 text-2xl font-bold">All Support Tickets</h1>
                </div>

                <div class="flex justify-between">
                    <form @submit.prevent="searchTickets" class="flex gap-2">
                        <Input
                            v-model="filters.search"
                            @keyup.enter="searchTickets"
                            type="text"
                            placeholder="Search by name, email or phone"
                            class="w-full rounded border p-2"
                        />
                        <Button type="submit">
                            <Icon icon="iconamoon:search-bold" />
                        </Button>
                    </form>

                    <div class="flex gap-2">
                        <Button
                            >Export
                            <Icon icon="lucide:printer" />
                        </Button>
                        <Link href="/">
                            <Button>
                                Make an Order
                                <Icon icon="lucide:plus" />
                            </Button>
                        </Link>
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto rounded-lg shadow">
                <table class="min-w-full text-left text-sm">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="p-2">ID</th>
                            <th class="p-2">User</th>
                            <th class="p-2">Mobile</th>
                            <th class="p-2">Order</th>
                            <th class="p-2">Subject</th>
                            <th class="p-2">Status</th>
                            <th class="p-2">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr v-for="t in tickets.data" :key="t.id">
                            <td>
                                {{ t.id }}
                            </td>
                            <td class="p-2">
                                <div class="flex items-center gap-3">
                                    <!-- User Image -->
                                    <img
                                        :src="
                                            t.user.image
                                                ? `/storage/${t.user.image}`
                                                : '/User.png'
                                        "
                                        alt="User"
                                        class="h-10 w-10 rounded-lg object-cover"
                                    />

                                    <div>
                                        <!-- User Name -->
                                        <div class="font-semibold">
                                            {{ t.user.name }}
                                        </div>
                                        <!-- User Email -->
                                        <div class="text-sm text-gray-500">
                                            {{ t.user.email }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="p-2">
                                {{ t.user.phone }}
                                <div class="flex flex-row gap-1">
                                    <!--Send Email-->
                                    <a
                                        v-if="t.user.phone"
                                        :href="`https://wa.me/${t.user.phone}`"
                                        target="_blank"
                                        class=""
                                    >
                                        <Button size="xs" class="h-7 p-1.5"
                                            ><Icon icon="ic:baseline-whatsapp"
                                        /></Button>
                                    </a>
                                    <!--Call on Phone-->
                                    <a
                                        v-if="t.user.phone"
                                        :href="`tel:${t.user.phone}`"
                                        class=""
                                    >
                                        <Button size="xs" class="h-7 p-1.5"
                                            ><Icon icon="ic:baseline-phone" />
                                        </Button>
                                    </a>
                                    <!--Send Email-->
                                    <a
                                        v-if="t.user.email"
                                        :href="`mailto:${t.user.email}`"
                                        class="text-red-600 underline"
                                    >
                                        <Button size="xs" class="h-7 p-1.5"
                                            ><Icon icon="ic:baseline-email" />
                                        </Button>
                                    </a>
                                </div>
                            </td>
                            <td class="p-2">#{{ t.order.order_number }}</td>
                            <td class="p-2">{{ t.subject }}</td>
                            <td class="p-2">{{ t.status }}</td>
                            <td class="p-2">
                                <div class="flex gap-2">
                                    <Link :href="`/supports/${t.id}`" class="">
                                        <Button size="xs"
                                            ><Icon icon="oui:app-management"
                                        /></Button>
                                    </Link>
                                    <Button variant="destructive" size="xs"
                                        ><Icon icon="iconamoon:trash-bold"
                                    /></Button>
                                    <Button size="xs"
                                        ><Icon icon="iconamoon:eye"
                                    /></Button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
