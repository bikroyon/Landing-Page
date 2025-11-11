<script setup>
import ToggleSwitch from '@/components/ToggleSwitch.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import AppLayout from '@/layouts/AppLayout.vue';
import { Icon } from '@iconify/vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    zones: Object,
    filters: Object,
});

const breadcrumbs = [{ title: 'Delivery Zones', href: '/delivery-zones' }];

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
            <!-- Header -->
            <div class="mb-6">
                <div>
                    <h1 class="mb-4 text-2xl font-bold">Delivery Zones</h1>
                </div>

                <div class="flex justify-between">
                    <form @submit.prevent="" class="flex gap-2">
                        <Input
                            v-model="search"
                            type="text"
                            placeholder="Search by name, email or phone"
                            class="w-full rounded border p-2"
                        />
                        <Button type="submit"> Search </Button>
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

            <!-- 📋 Table -->
            <div class="overflow-x-auto rounded-lg shadow">
                <table class="min-w-full text-left text-sm">
                    <thead class="border-b bg-gray-200">
                        <tr>
                            <th class="p-3">#</th>
                            <th class="p-3">Name</th>
                            <th class="p-3">Region</th>
                            <th class="p-3">Area</th>
                            <th class="p-3">Delivery Fee</th>
                            <th class="p-3">Free Min Order</th>
                            <th class="p-3">Status</th>
                            <th class="p-3 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="zone in props.zones.data"
                            :key="zone.id"
                            class="border-b"
                        >
                            <td class="p-3">{{ zone.id }}</td>
                            <td class="p-3">{{ zone.name }}</td>
                            <td class="p-3">{{ zone.region || '-' }}</td>
                            <td class="p-3">{{ zone.area || '-' }}</td>
                            <td class="p-3">
                                {{ Number(zone.delivery_fee ?? 0).toFixed(2) }}
                            </td>
                            <td class="p-3">
                                {{ zone.free_delivery_min_order ?? '-' }}
                            </td>
                            <td class="p-3">
                                <ToggleSwitch
                                    :modelValue="zone.status"
                                    @update:modelValue="
                                        () => toggleStatus(zone.id)
                                    "
                                    :labal="false"
                                />
                            </td>
                            <td class="space-x-3 p-3 text-center">
                                <Link
                                    :href="`/delivery-zones/${zone.id}/edit`"
                                    class="text-blue-600 hover:underline"
                                >
                                    <Button size="xs">
                                        <Icon icon="lucide:edit-2" />
                                    </Button>
                                </Link>
                                <Link @click="deleteZone(zone.id)">
                                    <Button size="xs" variant="destructive">
                                        <Icon icon="lucide:trash-2" />
                                    </Button>
                                </Link>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6 flex justify-center">
                <nav class="flex gap-1">
                    <template v-for="(link, i) in zones.links" :key="i">
                        <Button
                            v-if="link.url"
                            @click="goTo(link.url)"
                            v-html="link.label"
                            :class="[
                                'rounded border px-3 py-1',
                                link.active
                                    ? 'text-white' // Active page
                                    : 'bg-white text-gray-700 hover:bg-gray-100',
                            ]"
                        ></Button>

                        <!-- No URL = disabled (Previous/Next) -->
                        <span
                            v-else
                            v-html="link.label"
                            class="px-3 py-1 text-gray-400"
                        ></span>
                    </template>
                </nav>
            </div>
        </div>
    </AppLayout>
</template>
