<script setup>
import { ref } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import Button from '@/components/ui/button/Button.vue'
import Icon from '@/components/Icon.vue'
import Heading from '@/components/Heading.vue'
import ToggleSwitch from '@/components/ToggleSwitch.vue'


const props = defineProps({
  role: Object,
  menus: Array,
  actions: Array,
  accessMap: Object
})

const permissions = ref({})
const breadcrumbs = [
  { title: 'Dashboard', href: '/' },
  { title: 'Roles', href: '/roles' },
  { title: `${props.role.name}`, href: `/roles/${props.role.id}` },
]
// Initialize permissions from accessMap
for (const [menuId, actionIds] of Object.entries(props.accessMap)) {
  permissions.value[menuId] = actionIds
}

const togglePermission = (menuId, actionId) => {
  const current = permissions.value[menuId] || []
  if (current.includes(actionId)) {
    permissions.value[menuId] = current.filter(id => id !== actionId)
  } else {
    permissions.value[menuId] = [...current, actionId]
  }
}

const isChecked = (menuId, actionId) => {
  return permissions.value[menuId]?.includes(actionId)
}

const submit = () => {
  router.post(route('roles.access.update', props.role.id), {
    permissions: permissions.value,
  }, { preserveScroll: true })
}

</script>

<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-4 space-y-4">
      <div class="flex items-center justify-between mb-4">
        <Heading :title="`Permissions For → ${role.name}`" />

        <Link href="/roles">
        <Button>
          <Icon name="circleArrowLeft" />
          <span>Back</span>
        </Button>
        </Link>
      </div>
      <table class="w-full table-auto border">
        <thead class="bg-gray-200">
          <tr>
            <th class="border border-gray-200 px-3 py-2">Menu</th>
            <th v-for="action in actions" :key="action.id" class="border border-gray-200 px-3 py-2 text-center">
              {{ action.name }}
            </th>
          </tr>
        </thead>
        <tbody>
          <template v-for="menu in menus" :key="menu.id">
            <tr class="border">
              <td class="border font-semibold px-3 py-2">{{ menu.name }}</td>
              <td v-for="action in actions" :key="action.id" class="text-center border">
                <div class="flex items-center justify-center">
                  <ToggleSwitch :model-value="isChecked(menu.id, action.id)"
                    @update:modelValue="togglePermission(menu.id, action.id)" active-label="" inactive-label="" />
                </div>
              </td>
            </tr>

            <tr v-for="child in menu.children" :key="child.id">
              <td class="pl-6 border px-3 py-2">↳ {{ child.name }}</td>
              <td v-for="action in actions" :key="action.id" class="text-center border">
                <div class="flex justify-center items-center">
                  <ToggleSwitch :model-value="isChecked(child.id, action.id)"
                    @update:modelValue="togglePermission(child.id, action.id)" active-label="" inactive-label="" />
                </div>
              </td>

            </tr>
          </template>
        </tbody>
      </table>

      <Button class="mt-4" @click="submit">Save Access</Button>
    </div>
  </AppLayout>
</template>
