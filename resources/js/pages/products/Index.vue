<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';
import { useAccess } from '@/composables/useAccess';
import { useToast } from '@/composables/useToast';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Icon } from '@iconify/vue';
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3';
import {
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogOverlay,
    DialogPortal,
    DialogRoot,
    DialogTitle,
} from 'reka-ui';
import { ref } from 'vue';
const { can } = useAccess('products');
const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Products', href: '/products' },
];

const page = usePage();
const filters = page.props.filters;
const props = defineProps({
    products: Object,
    filters: Object,
});

const search = ref(filters.search || '');

// Delete form
const form = useForm({});

// Search
// ✅ Search
function searchProducts() {
    router.get('/products', { search: search.value }, { preserveState: true });
}

// ✅ Pagination
function goTo(url) {
    if (url) router.get(url, {}, { preserveState: true });
}

const showDeleteDialog = ref(false);
const deleteId = ref(null);
const { showToast } = useToast();

function openDeleteDialog(id) {
    deleteId.value = id;
    showDeleteDialog.value = true;
}

function confirmDelete() {
    if (!deleteId.value) return;

    form.delete(`/products/${deleteId.value}`, {
        preserveScroll: true,
        preserveState: false,
        onSuccess: () => {
            showToast('Deleted', 'Product deleted successfully!', 'success');
            showDeleteDialog.value = false;
        },

        onError: () => {
            showToast('Error', 'Failed to delete Product.', 'error');
        },
    });
}
function printTable() {
    const printContents = document.getElementById('print-table')!.innerHTML;
    const originalContents = document.body.innerHTML;

    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
    window.location.reload(); // Reload SPA to restore Inertia state
}

function exportTableToCSV(filename = 'products.csv') {
    const table = document.getElementById('print-table') as HTMLTableElement;
    if (!table) return;

    let csv: string[] = [];
    const rows = table.querySelectorAll('tr');

    rows.forEach((row) => {
        const cols = row.querySelectorAll('th, td');
        let rowData: string[] = [];

        cols.forEach((col, index) => {
            // Skip last column (Action)
            if (index === cols.length - 1) return;

            // Flatten variants column
            let cellText = '';
            if (col.querySelectorAll) {
                // Join inner texts of child divs, separated by " | "
                const childDivs = col.querySelectorAll('div');
                if (childDivs.length) {
                    cellText = Array.from(childDivs)
                        .map((div) => div.innerText.replace(/\n/g, ' '))
                        .join(' | ');
                } else {
                    cellText = col.innerText.trim();
                }
            } else {
                cellText = col.innerText.trim();
            }

            // Escape quotes
            cellText = cellText.replace(/"/g, '""');
            rowData.push(`"${cellText}"`);
        });

        csv.push(rowData.join(','));
    });

    // Download CSV
    const csvString = csv.join('\n');
    const blob = new Blob([csvString], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = filename;
    link.click();
    URL.revokeObjectURL(link.href);
}
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="All Products" />
        <div class="p-4 py-2">
            <div>
                <h1 class="mb-4 text-2xl font-bold">All Products</h1>
            </div>

            <!-- Search & Actions -->
            <div
                class="mb-4 flex flex-col gap-2 sm:flex-row sm:justify-between"
            >
                <!-- Search Form -->
                <form
                    @submit.prevent="searchProducts"
                    class="flex flex-1 gap-2"
                >
                    <Input
                        type="text"
                        v-model="search"
                        @keyup.enter="searchProducts"
                        placeholder="Search products..."
                        class="min-w-0 flex-1 rounded border p-2"
                    />
                    <Button type="submit" class="flex-none">
                        <Icon icon="lucide:search" />
                    </Button>
                </form>

                <!-- Action Buttons -->
                <div
                    class="mt-2 flex flex-wrap justify-center gap-2 sm:mt-0 sm:justify-end"
                >
                    <Button
                        v-if="can('export')"
                        @click="() => exportTableToCSV()"
                    >
                        Export All
                        <Icon icon="f7:doc-chart" />
                    </Button>

                    <Button v-if="can('print')" @click="printTable">
                        Print All
                        <Icon icon="lucide:printer" />
                    </Button>

                    <Link v-if="can('create')" href="/products/create">
                        <Button>
                            Add New Product
                            <Icon icon="lucide:plus" />
                        </Button>
                    </Link>
                </div>
            </div>

            <!-- Products Table -->
            <div class="overflow-x-auto rounded shadow">
                <!-- Desktop / Tablet Table -->
                <table
                    class="hidden min-w-full rounded-lg border sm:table"
                    id="print-table"
                >
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-2 text-center">ID</th>
                            <th class="p-2 text-left">Product</th>
                            <th class="p-2 text-left">Price</th>
                            <th class="p-2 text-left">Variants</th>
                            <th class="p-2 text-left">Status</th>
                            <th class="p-2 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="product in products.data"
                            :key="product.id"
                            class="border-t transition hover:bg-gray-50"
                        >
                            <td class="p-2 text-center text-sm">
                                {{ product.id }}
                            </td>
                            <td class="p-2 align-middle">
                                <div class="flex items-center gap-2">
                                    <img
                                        :src="
                                            product.image ??
                                            'https://placehold.co/600x400'
                                        "
                                        class="h-10 w-10 rounded object-cover"
                                    />
                                    <div class="flex flex-col">
                                        <span
                                            class="w-40 truncate font-medium sm:w-60"
                                        >
                                            {{ product.name }}
                                        </span>
                                        <span
                                            class="w-40 truncate text-xs text-gray-500 sm:w-60"
                                        >
                                            {{ product.description }}
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="p-2 text-sm">
                                {{ Number(product.price).toFixed(2) }}৳
                            </td>
                            <td class="w-60 p-2 text-xs">
                                <div v-if="product.variations?.length">
                                    <div
                                        v-for="(v, i) in product.variations"
                                        :key="i"
                                        class="flex justify-between border-b py-1 last:border-b-0"
                                    >
                                        <span>{{ v.name }}</span>
                                        <span class="text-xs text-gray-600"
                                            >SKU: {{ v.sku }}</span
                                        >
                                        <span
                                            :class="
                                                v.stock > 0
                                                    ? 'text-xs text-green-600'
                                                    : 'text-xs text-red-600'
                                            "
                                        >
                                            {{ v.stock }}
                                        </span>
                                        <span> {{ v.extra_price }} ৳ </span>
                                    </div>
                                </div>
                                <div v-else class="text-xs text-gray-400">
                                    No Variants
                                </div>
                            </td>
                            <td class="p-2 text-sm">
                                {{ product.status ? 'Active' : 'Inactive' }}
                            </td>
                            <td class="p-2 text-center">
                                <div
                                    class="flex flex-wrap justify-center gap-2"
                                >
                                    <Link
                                        v-if="can('edit')"
                                        :href="`/products/${product.id}/edit`"
                                    >
                                        <Button size="sm"
                                            ><Icon icon="lucide:edit"
                                        /></Button>
                                    </Link>
                                    <Button
                                        size="sm"
                                        variant="destructive"
                                        v-if="can('delete')"
                                        @click="openDeleteDialog(product.id)"
                                    >
                                        <Icon icon="lucide:trash-2" />
                                    </Button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!-- Mobile Cards -->
                <div class="flex flex-col gap-4 sm:hidden" id="print-table">
                    <div
                        v-for="product in products.data"
                        :key="product.id"
                        class="rounded-lg border p-4 shadow-sm"
                    >
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <img
                                    :src="
                                        product.image ??
                                        'https://placehold.co/600x400'
                                    "
                                    class="h-12 w-12 rounded object-cover"
                                />
                                <div class="flex flex-col">
                                    <span class="text-sm font-medium">{{
                                        product.name
                                    }}</span>
                                    <span
                                        class="max-w-[200px] truncate text-xs text-gray-500"
                                        >{{ product.description }}</span
                                    >
                                </div>
                            </div>
                            <div class="flex h-8 items-start">
                                <span class="text-xs font-bold"
                                    >{{
                                        Number(product.price).toFixed(2)
                                    }}৳</span
                                >
                            </div>
                        </div>

                        <div class="mt-2 grid gap-1 text-xs">
                            <div v-if="product.variations?.length">
                                <div
                                    v-for="(v, i) in product.variations"
                                    :key="i"
                                    class="grid grid-cols-4 items-center gap-2 border-b py-1 last:border-b-0"
                                >
                                    <span class="truncate">{{ v.name }}</span>
                                    <span class="truncate"
                                        >SKU: {{ v.sku }}</span
                                    >
                                    <span
                                        :class="
                                            v.stock > 0
                                                ? 'text-green-600'
                                                : 'text-red-600'
                                        "
                                    >
                                        {{ v.stock }}
                                    </span>
                                    <span
                                        :class="
                                            v.stock > 0
                                                ? 'text-green-600'
                                                : 'text-red-600'
                                        "
                                    >
                                        {{ v.extra_price }} ৳
                                    </span>
                                </div>
                            </div>
                            <div v-else class="text-gray-400">No Variants</div>
                        </div>

                        <div class="mt-2 flex items-center justify-between">
                            <span class="text-sm">{{
                                product.status ? 'Active' : 'Inactive'
                            }}</span>
                            <div class="flex gap-2">
                                <Link
                                    v-if="can('edit')"
                                    :href="`/products/${product.id}/edit`"
                                >
                                    <Button size="sm"
                                        ><Icon icon="lucide:edit"
                                    /></Button>
                                </Link>
                                <Button
                                    size="sm"
                                    variant="destructive"
                                    v-if="can('delete')"
                                    @click="openDeleteDialog(product.id)"
                                >
                                    <Icon icon="lucide:trash-2" />
                                </Button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="flex items-center justify-center p-4 text-xs">
            <Component
                :is="products.links.length ? 'nav' : 'div'"
                class="flex gap-1"
            >
                <template v-for="(link, key) in products.links" :key="key">
                    <Button
                        v-if="link.url"
                        @click="router.visit(link.url)"
                        v-html="link.label"
                        :class="[
                            'rounded px-1.5 py-0 text-xs sm:px-3 sm:py-1',
                            link.active
                                ? 'text-white'
                                : 'bg-white text-gray-700 hover:bg-gray-100',
                        ]"
                    />
                    <span
                        v-else
                        v-html="link.label"
                        class="flex items-center justify-center px-1 py-0 text-gray-400 sm:px-3 sm:py-1"
                    />
                </template>
            </Component>
        </div>
        <!-- ✅ DELETE CONFIRMATION MODAL -->
        <DialogRoot
            :open="showDeleteDialog"
            @update:open="showDeleteDialog = $event"
        >
            <DialogPortal>
                <DialogOverlay class="fixed inset-0 z-30 bg-black/50" />

                <DialogContent
                    class="fixed top-1/2 left-1/2 z-[100] w-[90vw] max-w-[400px] -translate-x-1/2 -translate-y-1/2 rounded-xl bg-white p-6 shadow-xl"
                >
                    <DialogTitle class="text-lg font-bold text-red-600">
                        Confirm Delete
                    </DialogTitle>

                    <DialogDescription class="mb-4 text-gray-600">
                        Are you sure you want to delete this order? This action
                        cannot be undone.
                    </DialogDescription>

                    <div class="mt-6 flex justify-end gap-3">
                        <DialogClose>
                            <Button class="rounded bg-gray-200 px-4 py-2">
                                Cancel
                            </Button>
                        </DialogClose>

                        <Button
                            @click="confirmDelete"
                            class="rounded bg-red-600 px-4 py-2 text-white"
                        >
                            Delete
                        </Button>
                    </div>

                    <DialogClose
                        class="absolute top-3 right-3 text-gray-600 hover:text-black"
                    >
                        <Icon icon="lucide:x" class="h-4 w-4" />
                    </DialogClose>
                </DialogContent>
            </DialogPortal>
        </DialogRoot>
    </AppLayout>
</template>
<style scoped>
@media print {
    /* Hide the last column (Action) */
    table th:last-child,
    table td:last-child {
        display: none;
    }

    body {
        margin: 0;
        font-size: 12px;
    }

    table {
        border-collapse: collapse;
        width: 100%;
    }

    th,
    td {
        border: 1px solid #000;
        padding: 4px;
    }
}
</style>
