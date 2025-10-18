<script setup lang="ts">
import {
  SidebarGroup,
  SidebarGroupLabel,
  SidebarMenu,
  SidebarMenuButton,
  SidebarMenuItem
} from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';

const page = usePage();
const chillActive = ref<NavItem | null>(null);

// Props
const { items } = defineProps<{ items: NavItem[] }>()

// Flatten to find the current active item, including deep routes
onMounted(() => {
  const flat = items.flatMap(i => [i, ...(i.children || [])]);
  chillActive.value = flat.find(i =>
    page.url === i.href || page.url.startsWith(i.href + '/')
  ) || null;
});

// Track open dropdowns
const openIndexes = ref<number[]>([]);

function toggle(index: number) {
  if (openIndexes.value.includes(index)) {
    openIndexes.value = openIndexes.value.filter(i => i !== index);
  } else {
    openIndexes.value = [index]; // Only allow one open at a time
  }
}

function isOpen(index: number) {
  return openIndexes.value.includes(index);
}

// Open dropdown if it contains the current active child
function shouldShowChildren(item: NavItem, index: number) {
  return isOpen(index) || chillActive.value?.parent_id === item.id;
}
</script>

<template>
  <SidebarGroup class="px-2 py-0">
    <SidebarMenu>
      <SidebarMenuItem v-for="(item, index) in items" :key="item.id">
        <SidebarMenuButton
          as-child
          :is-active="item.href === page.url || chillActive?.parent_id === item.id"
          :tooltip="item.title"
        >
          <button
            v-if="item.children && item.children.length > 0"
            @click.prevent="toggle(index)"
            class="flex items-center w-full gap-2"
          >
            <Icon :icon="item.icon" class="text-lg text-gray-600" />
            <span>{{ item.name }}</span>
            <Icon
              :class="shouldShowChildren(item, index) ? 'rotate-180' : ''"
              icon="iconamoon:arrow-down-2-bold"
              class="ml-auto transition-transform duration-300"
            />
          </button>

          <Link
            v-else
            :href="item.href"
            class="flex items-center gap-2 w-full"
          >
            <Icon :icon="item.icon" class="text-lg text-gray-600" />
            <span>{{ item.name }}</span>
          </Link>
        </SidebarMenuButton>

        <!-- Child Dropdown -->
        <SidebarMenu
          v-if="item.children && item.children.length > 0"
          :class="shouldShowChildren(item, index)
            ? 'max-h-fit opacity-100'
            : 'max-h-0 opacity-0'"
          class=" overflow-hidden transition-all duration-300 ease-in-out"
        >
          <SidebarMenuItem
            v-for="child in item.children"
            :key="child.id"
          >
            <SidebarMenuButton
              as-child
              :class="chillActive?.parent_id === item.id && page.url.startsWith(child.href)
                ? 'border-l-4 border-gray-200 text-gray-700'
                : ''"
              :tooltip="child.title"
            >
              <Link
                :href="child.href"
                class="flex items-center gap-2 w-full"
                @click.prevent="toggle(index)"
              >
                <Icon
                  :icon="child.icon"
                  :class="chillActive?.parent_id === item.id && page.url.startsWith(child.href)
                    ? 'text-gray-700'
                    : 'text-gray-600'"
                  class="text-lg"
                />
                <span>{{ child.name }}</span>
              </Link>
            </SidebarMenuButton>
          </SidebarMenuItem>
        </SidebarMenu>
      </SidebarMenuItem>
    </SidebarMenu>
  </SidebarGroup>
</template>
