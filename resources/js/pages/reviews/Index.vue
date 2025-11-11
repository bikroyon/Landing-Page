<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <!-- Header -->
            <div class="mb-6">
                <div>
                    <h1 class="mb-4 text-2xl font-bold">Reviews</h1>
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

            <!-- Reviews Table -->
            <div class="overflow-x-auto rounded shadow">
                <table class="w-full table-auto text-left text-xs">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="p-2">#</th>
                            <th class="p-2">Product</th>
                            <th class="p-2">User</th>
                            <th class="p-2">Rating</th>
                            <th class="p-2">Comment</th>
                            <th class="p-2">Published</th>
                            <th class="p-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="review in reviews.data"
                            :key="review.id"
                            class="border-t"
                        >
                            <td class="p-2">{{ review.id }}</td>
                            <td class="p-2">
                                <div class="flex items-center gap-2">
                                    <!-- Product Image -->
                                    <img
                                        :src="
                                            review.product.image ??
                                            'https://placehold.co/600x400'
                                        "
                                        alt="Product"
                                        class="h-10 w-10 flex-shrink-0 rounded object-cover"
                                    />

                                    <!-- Name + Description -->
                                    <div class="flex flex-col truncate">
                                        <span
                                            class="truncate text-sm font-medium"
                                        >
                                            {{ review.product.name }}
                                        </span>
                                        <span
                                            class="truncate text-xs text-gray-500"
                                        >
                                            {{ review.product.description }}
                                        </span>
                                    </div>
                                </div>
                            </td>

                            <td class="p-2">{{ review.user.name }}</td>
                            <td class="p-2">{{ review.rating }}</td>
                            <td class="p-2">{{ review.comment || '-' }}</td>
                            <td class="p-2">
                                <ToggleSwitch
                                    :modelValue="review.is_published"
                                    @update:modelValue="
                                        (value) => togglePublish(review, value)
                                    "
                                    :labal="false"
                                />
                            </td>

                            <td class="flex gap-2 p-2">
                                <Button @click="deleteReview(review)" size="xs">
                                    <Icon icon="lucide:edit-2" />
                                </Button>
                                <Button @click="deleteReview(review)" size="xs">
                                    <Icon icon="lucide:eye" />
                                </Button>
                                <Button
                                    @click="deleteReview(review)"
                                    size="xs"
                                    variant="destructive"
                                >
                                    <Icon icon="lucide:trash-2" />
                                </Button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6 flex justify-center">
                <nav class="flex gap-1">
                    <template v-for="(link, i) in reviews.links" :key="i">
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
import { Input } from '@/components/ui/input';
import AppLayout from '@/Layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Icon } from '@iconify/vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

const page = usePage();
const reviews = ref(page.props.reviews);

const filters = ref({
    search: page.props.filters?.search || '',
});

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Reviews', href: '/admin/reviews' },
];

// Create a reusable form for all actions
const form = useForm({ search: filters.value.search });

// Search
function applySearch() {
    form.get('/admin/reviews', {
        preserveState: true,
        onSuccess: (pageData) => {
            reviews.value = pageData.props.reviews;
        },
    });
}

// Pagination
function changePage(pageNumber: number) {
    form.get(
        '/admin/reviews',
        { page: pageNumber },
        {
            preserveState: true,
            onSuccess: (pageData) => {
                reviews.value = pageData.props.reviews;
            },
        },
    );
}

// Toggle publish/unpublish
function togglePublish(review: any) {
    router.post(
        `/reviews/${review.id}/toggle-publish`,
        {},
        {
            onSuccess: () => {
                // Flip the local boolean immediately
                review.is_published = !review.is_published;
            },
        },
    );
}

// Delete review
function deleteReview(review) {
    if (!confirm('Are you sure you want to delete this review?')) return;

    form.delete(`/reviews/${review.id}`, {
        onSuccess: () => {
            reviews.value.data = reviews.value.data.filter(
                (r) => r.id !== review.id,
            );
        },
    });
}
</script>
