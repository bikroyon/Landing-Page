<script setup>
import Icon from '@/components/Icon.vue';
import ToggleSwitch from '@/components/ToggleSwitch.vue';
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    roles: Object,
    filters: Object,
});

const breadcrumbs = [
    { title: 'Dashboard', href: '/' },
    { title: 'Roles', href: '/roles' },
];

const form = useForm({
    id: null,
    name: '',
    status: 1,
});

const editing = ref(false);

function editRole(role) {
    editing.value = true;
    form.id = role.id;
    form.name = role.name;
    form.status = role.status;
}

function resetForm() {
    form.reset();
    editing.value = false;
}

function submit() {
    if (editing.value) {
        form.put(`/roles/${form.id}`, {
            onSuccess: resetForm,
        });
    } else {
        form.post('/roles', {
            onSuccess: resetForm,
        });
    }
}

function destroy(id) {
    if (confirm('Are you sure?')) {
        router.delete(`/roles/${id}`);
    }
}
const search = ref(props.filters.search || '');

function doSearch() {
    router.get(
        '/roles',
        { search: search.value },
        { preserveState: true, replace: true },
    );
}
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4 py-2">
            <div class="mb-4 flex items-center justify-between">
                <h1 class="mb-4 text-2xl font-bold">Manage Roles</h1>
                <Link href="/customers">
                    <Button>
                        <Icon name="ContactRound" />
                        <span>Apply Role to User</span>
                    </Button>
                </Link>
            </div>

            <!-- Create/Edit Role Form -->
            <form
                @submit.prevent="submit"
                class="mb-4 flex items-center justify-between gap-2 rounded border p-2"
            >
                <div class="w-[55%]">
                    <Input
                        v-model="form.name"
                        class="w-full rounded border px-2 py-1"
                        label="Role Name"
                        :isRequired="true"
                        placeholder="Role Name.."
                    />
                </div>
                <div class="w-[35%]">
                    <Input
                        v-model="form.description"
                        class="w-full rounded border px-2 py-1"
                        label="Role Description"
                        :isRequired="false"
                        placeholder="Description.."
                    />
                </div>
                <div class="w-[15%]">
                    <label for="">Status</label>
                    <ToggleSwitch
                        class="w-fit"
                        v-model="form.status"
                        active-label="Active"
                        inactive-label="Inactive"
                    />
                </div>
                <div class="flex h-full w-fit flex-row gap-2">
                    <Button class="h-14" type="submit">
                        {{ editing ? 'Update' : 'Create' }}
                    </Button>
                    <Button
                        variant="destructive"
                        type="button"
                        @click="resetForm"
                        v-if="editing"
                        class="h-14"
                        >Cancel</Button
                    >
                </div>
            </form>
            <!-- Search -->
            <div class="mb-4 flex items-center justify-between gap-2">
                <div class="flex gap-2">
                    <Input
                        v-model="search"
                        placeholder="Search roles..."
                        class="w-64"
                        @keyup.enter="doSearch"
                    />
                    <Button><Icon name="search" /></Button>
                </div>

                <Button> <Icon name="sheet" /> Export</Button>
            </div>

            <!-- Role Table -->
            <div class="overflow-x-auto rounded-lg shadow">
                <table class="min-w-full text-left">
                    <thead class="border-b bg-gray-200">
                        <tr
                            class="flex flex-row items-center justify-between px-2"
                        >
                            <th class="p-2 text-left">#</th>
                            <th class="p-2 text-left">Name</th>
                            <th class="p-2 text-left">Status</th>
                            <th class="p-2 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="role in props.roles.data"
                            :key="role.id"
                            class="flex flex-row items-center justify-between border-t"
                        >
                            <td class="p-2">{{ role.id }}</td>
                            <td class="p-2">{{ role.name }}</td>
                            <td class="p-2">
                                <span
                                    :class="
                                        role.status
                                            ? 'text-green-600'
                                            : 'text-red-600'
                                    "
                                >
                                    {{ role.status ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="space-x-2 p-2">
                                <Button
                                    @click="editRole(role)"
                                    size="sm"
                                    class=""
                                >
                                    <Icon name="edit" />
                                </Button>
                                <Link :href="`/roles/${role.id}/permissions`">
                                    <Button variant="" size="sm" class="">
                                        <Icon name="eye" />
                                    </Button>
                                </Link>
                                <Button
                                    variant="destructive"
                                    size="sm"
                                    @click="destroy(role.id)"
                                    class=""
                                >
                                    <Icon name="trash" />
                                </Button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            <div v-if="roles?.links?.length" class="flex justify-center p-4">
                <Component
                    :is="roles.links.length ? 'nav' : 'div'"
                    class="flex gap-1"
                >
                    <template v-for="(link, key) in roles.links" :key="key">
                        <Button
                            v-if="link.url"
                            @click="router.visit(link.url)"
                            v-html="link.label"
                            :class="[
                                'rounded border px-3 py-1',
                                link.active
                                    ? 'bg-gray-950 text-white'
                                    : 'bg-white text-gray-700 hover:bg-gray-100',
                            ]"
                        />
                        <span
                            v-else
                            v-html="link.label"
                            class="px-3 py-1 text-gray-400"
                        />
                    </template>
                </Component>
            </div>
        </div>
    </AppLayout>
</template>
