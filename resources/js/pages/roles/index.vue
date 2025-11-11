<script setup>
import { ref } from 'vue'
import { useForm, router, Link } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import Button from '@/components/ui/button/Button.vue'
import Icon from '@/components/Icon.vue'
import Heading from '@/components/Heading.vue'
import ToggleSwitch from '@/components/ToggleSwitch.vue'
import Input from '@/components/ui/input/Input.vue'

const props = defineProps({
  roles: Object,
  filters: Object,
})

const breadcrumbs = [
  { title: 'Dashboard', href: '/' },
  { title: 'Roles', href: '/roles' },
]

const form = useForm({
  id: null,
  name: '',
  status: 1,
})

const editing = ref(false)

function editRole(role) {
  editing.value = true
  form.id = role.id
  form.name = role.name
  form.status = role.status
}

function resetForm() {
  form.reset()
  editing.value = false
}

function submit() {
  if (editing.value) {
    form.put(`/roles/${form.id}`, {
      onSuccess: resetForm,
    })
  } else {
    form.post('/roles', {
      onSuccess: resetForm,
    })
  }
}

function destroy(id) {
  if (confirm('Are you sure?')) {
    router.delete(`/roles/${id}`)
  }
}
const search = ref(props.filters.search || '')

function doSearch() {
  router.get('/roles', { search: search.value }, { preserveState: true, replace: true })
}

</script>

<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-4">
      <div class="flex items-center justify-between mb-4">
        <Heading title="All Roles & Permissions" />
        <Link href="/customers">
        <Button>
          <Icon name="ContactRound" />
          <span>Apply Role to User</span>
        </Button>
        </Link>
      </div>

      <!-- Create/Edit Role Form -->
      <form @submit.prevent="submit" class="mb-4 p-2 border rounded flex items-center justify-between gap-2">
        <div class=" w-[55%]">
          <Input v-model="form.name" class="w-full border rounded px-2 py-1" label="Role Name" :isRequired="true"
            placeholder="Role Name.." />
        </div>
        <div class=" w-[35%]">
          <Input v-model="form.description" class="w-full border rounded px-2 py-1" label="Role Description"
            :isRequired="false" placeholder="Description.." />
        </div>
        <div class=" w-[15%]">
          <label for="">Status</label>
          <ToggleSwitch class="w-fit" v-model="form.status" active-label="Active" inactive-label="Inactive" />
        </div>
        <div class=" w-fit h-full flex flex-row gap-2">
          <Button class="h-14" type="submit">
            {{ editing ? 'Update' : 'Create' }}
          </Button>
          <Button variant="destructive" type="button" @click="resetForm" v-if="editing" class=" h-14">Cancel</BUtton>
        </div>
      </form>
      <!-- Search -->
      <div class="mb-4 flex items-center justify-between gap-2">
        <Input v-model="search" placeholder="Search roles..." class="w-64" @keyup.enter="doSearch" />
        <Button> <Icon name="printer"/> Print</Button>
        <Button> <Icon name="sheet"/> Export</Button>
      </div>

      <!-- Role Table -->
      <table class="w-full border">
        <thead>
          <tr class="bg-gray-100 flex flex-row justify-between items-center px-2">
            <th class="text-left p-2">#</th>
            <th class="text-left p-2">Name</th>
            <th class="text-left p-2">Status</th>
            <th class="text-left p-2">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="role in props.roles.data" :key="role.id"
            class="border-t flex flex-row justify-between items-center">
            <td class="p-2">{{ role.id }}</td>
            <td class="p-2">{{ role.name }}</td>
            <td class="p-2">
              <span :class="role.status ? 'text-green-600' : 'text-red-600'">
                {{ role.status ? 'Active' : 'Inactive' }}
              </span>
            </td>
            <td class="p-2 space-x-2">
              <Button @click="editRole(role)" size="sm" class="">
                <Icon name="edit" />
              </Button>
              <Link :href="`/roles/${role.id}/permissions`">
              <Button variant="" size="sm" class="">
                <Icon name="eye" />
              </Button>
              </Link>
              <Button variant="destructive" size="sm" @click="destroy(role.id)" class="">
                <Icon name="trash" />
              </Button>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Pagination -->
      <div v-if="roles?.links?.length" class="p-4 flex justify-center">
        <Component :is="roles.links.length ? 'nav' : 'div'" class="flex gap-1">
          <template v-for="(link, key) in roles.links" :key="key">
            <Button v-if="link.url" @click="router.visit(link.url)" v-html="link.label" :class="[
              'px-3 py-1 border rounded',
              link.active ? 'bg-gray-950 text-white' : 'bg-white text-gray-700 hover:bg-gray-100'
            ]" />
            <span v-else v-html="link.label" class="px-3 py-1 text-gray-400" />
          </template>
        </Component>
      </div>


    </div>
  </AppLayout>
</template>
