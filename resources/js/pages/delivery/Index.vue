<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    zones: Object,
    filters: Object,
});

const breadcrumbs = [
    { title: 'Delivery Zones', href: '/delivery-zones' },
];  

const search = ref(props.filters.search || '');

// Build plain URLs manually
const baseUrl = '/delivery-zones';

watch(search, (value) => {
    router.get(
        baseUrl,
        { search: value },
        { preserveState: true, replace: true },
    );
});

const toggleStatus = (zoneId) => {
    router.post(
        `${baseUrl}/${zoneId}/toggle-status`,
        {},
        { preserveScroll: true },
    );
};

const deleteZone = (zoneId) => {
    if (confirm('Are you sure you want to delete this delivery zone?')) {
        router.delete(`${baseUrl}/${zoneId}`, { preserveScroll: true });
    }
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Delivery Zones" />

        <div class="space-y-6 p-6">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold">Delivery Zones</h1>
                <Link
                    href="/delivery-zones/create"
                    class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700"
                >
                    + Add New
                </Link>
            </div>

            <!-- 🔍 Search -->
            <input
                v-model="search"
                type="text"
                placeholder="Search by name, region or area..."
                class="w-full rounded border px-3 py-2 md:w-1/3"
            />

            <!-- 📋 Table -->
            <div class="overflow-x-auto rounded-lg bg-white shadow">
                <table class="min-w-full text-left text-sm">
                    <thead class="border-b bg-gray-100 text-gray-700">
                        <tr>
                            <th class="p-3">Name</th>
                            <th class="p-3">Region</th>
                            <th class="p-3">Area</th>
                            <th class="p-3 text-right">Delivery Fee</th>
                            <th class="p-3 text-right">Free Min Order</th>
                            <th class="p-3 text-center">Status</th>
                            <th class="p-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="zone in props.zones.data"
                            :key="zone.id"
                            class="border-b hover:bg-gray-50"
                        >
                            <td class="p-3">{{ zone.name }}</td>
                            <td class="p-3">{{ zone.region || '-' }}</td>
                            <td class="p-3">{{ zone.area || '-' }}</td>
                            <td class="p-3 text-right">
                                {{ Number(zone.delivery_fee ?? 0).toFixed(2) }}
                            </td>
                            <td class="p-3 text-right">
                                {{ zone.free_delivery_min_order ?? '-' }}
                            </td>
                            <td class="p-3 text-center">
                                <button
                                    @click="toggleStatus(zone.id)"
                                    class="rounded px-2 py-1 text-xs font-medium"
                                    :class="
                                        zone.status
                                            ? 'bg-green-100 text-green-700 hover:bg-green-200'
                                            : 'bg-red-100 text-red-700 hover:bg-red-200'
                                    "
                                >
                                    {{ zone.status ? 'Active' : 'Inactive' }}
                                </button>
                            </td>
                            <td class="space-x-3 p-3 text-right">
                                <Link
                                    :href="`/delivery-zones/${zone.id}/edit`"
                                    class="text-blue-600 hover:underline"
                                >
                                    Edit
                                </Link>
                                <button
                                    @click="deleteZone(zone.id)"
                                    class="text-red-600 hover:underline"
                                >
                                    Delete
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- 📄 Pagination -->
            <div class="mt-4 flex flex-wrap justify-end gap-2">
                <template v-for="link in props.zones.links" :key="link.label">
                    <Link
                        v-if="link.url"
                        :href="link.url"
                        v-html="link.label"
                        class="rounded border px-3 py-1"
                        :class="{
                            'bg-blue-600 text-white': link.active,
                            'text-gray-700 hover:bg-gray-100': !link.active,
                        }"
                    />
                    <span
                        v-else
                        v-html="link.label"
                        class="rounded border px-3 py-1 text-gray-400"
                    ></span>
                </template>
            </div>
        </div>
    </AppLayout>
</template>
