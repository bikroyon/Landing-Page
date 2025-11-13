<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Other Pages" />

        <div class="p-4 py-2">
            <!-- Header -->
            <div class="mb-6">
                <div>
                    <h1 class="mb-4 text-2xl font-bold">Other Pages</h1>
                </div>

                <div class="flex justify-between">
                    <form @submit.prevent="" class="flex gap-2">
                        <Input
                            v-model="search"
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
                                Add New Page
                                <Icon icon="iconoir:multiple-pages-plus" />
                            </Button>
                        </Link>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto rounded-lg shadow">
                <table class="min-w-full text-left">
                    <thead class="border-b bg-gray-200">
                        <tr class="">
                            <th class="p-2">#</th>
                            <th class="p-2">Title</th>
                            <th class="p-2">Slug</th>
                            <th class="p-2">Last Update</th>
                            <th class="p-2">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr v-for="p in pages" :key="p.id" class="border-b">
                            <td class="p-2">{{ p.id }}</td>
                            <td class="p-2">{{ p.title }}</td>
                            <td class="p-2">{{ p.slug }}</td>
                            <td class="p-2">
                                {{ new Date(p.updated_at).toLocaleString() }}
                            </td>
                            <td class="p-2">
                                <div class="flex gap-2">
                                    <Link
                                        :href="`/pages/${p.id}/edit`"
                                        class="text-blue-600 underline"
                                    >
                                        <Button variant="default" size="xs"
                                            ><icon icon="lucide:edit-2"
                                        /></Button>
                                    </Link>
                                    <Button variant="destructive" size="xs"
                                        ><icon icon="lucide:trash-2"
                                    /></Button>
                                    <Button variant="default" size="xs"
                                        ><icon icon="lucide:eye"
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

<script setup>
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import AppLayout from '@/layouts/AppLayout.vue';
import { Icon } from '@iconify/vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    pages: Array,
});
const breadcrumbs = [{ title: 'Other Pages', href: '/pages' }];
</script>
