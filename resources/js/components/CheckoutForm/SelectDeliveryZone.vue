<template>
    <div class="mt-4">
        <h2 class="mb-2 text-xl font-bold">{{ title }}</h2>

        <div class="flex flex-wrap gap-3">
            <label
                v-for="zone in zones"
                :key="zone.id"
                class="cursor-pointer rounded-lg border px-4 py-2 transition-all duration-150"
                :class="{
                    'border-blue-600 bg-blue-600 text-white':
                        form.delivery_zone_id === zone.id,
                    'hover:border-blue-500': form.delivery_zone_id !== zone.id,
                }"
            >
                <input
                    type="radio"
                    :value="zone.id"
                    v-model="form.delivery_zone_id"
                    class="hidden"
                    @change="updateZone"
                />

                <div class="text-center">
                    <div class="font-semibold">{{ zone.name }}</div>
                    <div class="text-sm">
                        {{ Number(zone.delivery_fee).toFixed(2) }} ৳
                        <span
                            v-if="zone.free_delivery_min_order"
                            class="block text-xs text-green-200"
                        >
                            Free ≥
                            {{
                                Number(zone.free_delivery_min_order).toFixed(2)
                            }}
                            ৳
                        </span>
                    </div>
                </div>
            </label>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed, defineProps, watch } from 'vue';

const props = defineProps<{
    form: {
        delivery_zone_id: number | null;
        delivery_fee?: number;
        delivery_zone_name?: string;
    };
    title?: string;
    zones: Array<{
        id: number;
        name: string;
        delivery_fee: number | string;
        free_delivery_min_order?: number | string;
    }>;
    subtotal: number; // current subtotal to calculate free delivery
}>();

const zone = computed(() =>
    props.zones.find((z) => z.id === props.form.delivery_zone_id),
);

watch(
    [() => props.form.delivery_zone_id, () => props.subtotal],
    () => {
        if (!zone.value) {
            props.form.delivery_fee = 0;
            props.form.delivery_zone_name = '';
            return;
        }

        props.form.delivery_zone_name = zone.value.name;

        const fee = Number(zone.value.delivery_fee);
        const minOrder = zone.value.free_delivery_min_order
            ? Number(zone.value.free_delivery_min_order)
            : 0;

        props.form.delivery_fee =
            minOrder && props.subtotal >= minOrder ? 0 : fee;
    },
    { immediate: true },
);
</script>

<style scoped>
/* Optional: smooth highlight for selected zone */
label {
    min-width: 120px;
    text-align: center;
}
</style>
