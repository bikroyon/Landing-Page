<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="All Customers" />
        <div class="p-4 py-2">
            <!-- Header -->
            <div class="mb-6">
                <div>
                    <h1 class="mb-4 text-2xl font-bold">Customers</h1>
                </div>

                <div class="flex justify-between">
                    <form @submit.prevent="" class="flex gap-2">
                        <Input
                            v-model="filters.search"
                            @keyup.enter="applySearch"
                            type="text"
                            placeholder="Search by name, email or phone"
                            class="w-full rounded border p-2"
                        />
                        <Button type="submit"> <Icon icon="iconamoon:search-bold"/> </Button>
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

            <!-- Table -->
            <div class="overflow-x-auto rounded text-xs shadow">
                <table class="w-full table-auto text-left">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="p-2">#</th>
                            <th class="p-2">Image</th>
                            <th class="p-2">Name</th>
                            <th class="p-2">Email</th>
                            <th class="p-2">Phone</th>
                            <th class="p-2">Connect</th>
                            <th class="p-2">Address</th>
                            <th class="p-2">Status</th>
                            <th class="p-2">IsBanned</th>
                            <th class="p-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="customer in customers.data"
                            :key="customer.id"
                            class="border-t text-xs"
                        >
                            <td class="p-2">{{ customer.id }}</td>
                            <td class="p-2 font-semibold">
                                <img
                                    :src="customer.image"
                                    class="h-10 w-10 rounded object-cover"
                                    :alt="customer.name"
                                />
                            </td>
                            <td class="p-2 font-semibold">
                                {{ customer.name }}
                            </td>
                            <td class="p-2">{{ customer.email }}</td>
                            <td class="p-2">{{ customer.phone || '-' }}</td>
                            <td class="p-2">
                                <div class="flex flex-row gap-1">
                                    <!--Send Email-->
                                    <a
                                        v-if="customer.phone"
                                        :href="`https://wa.me/${customer.phone}`"
                                        target="_blank"
                                        class=""
                                    >
                                        <Button size="xs" class="h-7 p-1.5"
                                            ><Icon icon="ic:baseline-whatsapp"
                                        /></Button>
                                    </a>
                                    <!--Call on Phone-->
                                    <a
                                        v-if="customer.phone"
                                        :href="`tel:${customer.phone}`"
                                        class=""
                                    >
                                        <Button size="xs" class="h-7 p-1.5"
                                            ><Icon icon="ic:baseline-phone" />
                                        </Button>
                                    </a>
                                    <!--Send Email-->
                                    <a
                                        v-if="customer.email"
                                        :href="`mailto:${customer.email}`"
                                        class="text-red-600 underline"
                                    >
                                        <Button size="xs" class="h-7 p-1.5"
                                            ><Icon icon="ic:baseline-email" />
                                        </Button>
                                    </a>
                                </div>
                            </td>
                            <td class="p-2">{{ customer.address || '-' }}</td>
                            <td class="p-2">
                                <span
                                    :class="
                                        customer.is_banned
                                            ? 'rounded bg-red-200 px-2 py-1 text-xs text-red-800'
                                            : 'rounded bg-green-200 px-2 py-1 text-xs text-green-800'
                                    "
                                >
                                    {{
                                        customer.is_banned ? 'Banned' : 'Active'
                                    }}
                                </span>
                            </td>
                            <td class="p-2">
                                <ToggleSwitch
                                    :modelValue="customer.is_banned"
                                    @update:modelValue="
                                        (value) => toggleBan(customer, value)
                                    "
                                    :labal="false"
                                />
                            </td>
                            <td class="p-2">
                                <div class="flex gap-2">
                                    <Button
                                        @click="deleteCustomer(customer)"
                                        variant="destructive"
                                        size="xs"
                                    >
                                        <Icon icon="lucide:trash-2" />
                                    </Button>
                                    <Button
                                        size="xs"
                                        @click="deleteCustomer(customer)"
                                    >
                                        <Icon icon="lucide:edit-2" /> </Button
                                    ><Button
                                        size="xs"
                                        @click="deleteCustomer(customer)"
                                    >
                                        <Icon icon="lucide:eye" />
                                    </Button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6 flex justify-center">
                <nav class="flex gap-1">
                    <template v-for="(link, i) in customers.links" :key="i">
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

<script setup lang="ts">
import ToggleSwitch from '@/components/ToggleSwitch.vue';
import { Button } from '@/components/ui/button';
import Input from '@/components/ui/input/Input.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Icon } from '@iconify/vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

const page = usePage();
const customers = ref(page.props.customers);

const filters = ref({
    search: page.props.filters?.search || '',
});

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Customers', href: '/customers' },
];

// Apply search
function applySearch() {
    router.get('/customers', filters.value, {
        preserveState: true,
        replace: true,
    });
}

// Change page
function changePage(pageNumber: number) {
    router.get(
        '/customers',
        { ...filters.value, page: pageNumber },
        { preserveState: true, replace: true },
    );
}

// Toggle ban/unban
function toggleBan(customer: any) {
    router.post(
        `/customers/${customer.id}/toggle-ban`,
        {},
        {
            onSuccess: () => {
                customer.is_banned = !customer.is_banned;
            },
        },
    );
}

// Delete customer
function deleteCustomer(customer: any) {
    if (confirm(`Are you sure you want to delete ${customer.name}?`)) {
        router.delete(`/customers/${customer.id}`, {
            onSuccess: () => {
                // Remove deleted customer from table
                customers.value.data = customers.value.data.filter(
                    (c: any) => c.id !== customer.id,
                );
            },
        });
    }
}
</script>
