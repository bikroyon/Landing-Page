<script setup>
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import AppLayout from '@/layouts/AppLayout.vue';
import { Icon } from '@iconify/vue';
import { Head } from '@inertiajs/vue3';
import { ProgressIndicator, ProgressRoot } from 'reka-ui';
import { computed, ref, watch } from 'vue';

// State
const phone = ref('');
const result = ref(null);
const error = ref(null);
const loading = ref(false);
const progressValue = ref(0);

// Animated counters
const animatedTotal = ref(0);
const animatedDelivered = ref(0);
const animatedReturned = ref(0);
const animatedSuccess = ref(0);
const defaultCouriers = {
    redx: { total: 0, successful: 0, returned: 0 },
    pathao: { total: 0, successful: 0, returned: 0 },
    carrybee: { total: 0, successful: 0, returned: 0 },
    perceldex: { total: 0, successful: 0, returned: 0 },
    'delivery tiger': { total: 0, successful: 0, returned: 0 },
    steadfast: { total: 0, successful: 0, returned: 0 },
    paperfly: { total: 0, successful: 0, returned: 0 },
    bikecurier: { total: 0, successful: 0, returned: 0 },
};

function lerpColor(a, b, amount) {
    const ah = parseInt(a.replace(/#/g, ''), 16),
        ar = ah >> 16,
        ag = (ah >> 8) & 0xff,
        ab = ah & 0xff;

    const bh = parseInt(b.replace(/#/g, ''), 16),
        br = bh >> 16,
        bg = (bh >> 8) & 0xff,
        bb = bh & 0xff;

    const rr = ar + amount * (br - ar);
    const rg = ag + amount * (bg - ag);
    const rb = ab + amount * (bb - ab);

    return (
        '#' +
        (
            (1 << 24) +
            (Math.round(rr) << 16) +
            (Math.round(rg) << 8) +
            Math.round(rb)
        )
            .toString(16)
            .slice(1)
    );
}

const progressColor = computed(() => {
    const v = animatedSuccess.value;

    // Color stops
    const rose = '#e11d48';
    const sky = '#0284c7';
    const emerald = '#059669';

    if (v <= 50) {
        // 0% → 50% = rose → sky
        const t = v / 50;
        return lerpColor(rose, sky, t);
    } else if (v <= 100) {
        // 50% → 100% = sky → emerald
        const t = (v - 50) / 50;
        return lerpColor(sky, emerald, t);
    }

    return emerald;
});

// Computed data
const summary = computed(() => result.value?.overall || {});
const couriers = ref(JSON.parse(JSON.stringify(defaultCouriers)));
const successPercent = computed(() => summary.value.success_ratio || 0);

const breadcrumbs = [{ title: 'Fraud Checker', href: '/fraud-checker' }];

// --- Utility: Count Animation Function ---
function animateCount(targetRef, toValue, duration = 2000, onUpdate) {
    const start = targetRef.value || 0;
    const startTime = performance.now();

    const frame = (now) => {
        const progress = Math.min((now - startTime) / duration, 1);
        const eased = 1 - Math.pow(1 - progress, 3); // easeOutCubic
        const val = Math.round(start + (toValue - start) * eased);

        if (onUpdate) onUpdate(val);
        else targetRef.value = val;

        if (progress < 1) requestAnimationFrame(frame);
    };

    requestAnimationFrame(frame);
}

// Watch success ratio for progress
watch(successPercent, (newVal) => {
    progressValue.value = newVal;
});

// Animate counts whenever result updates
watch(result, (newVal) => {
    if (newVal && newVal.overall) {
        animateCount(animatedTotal, newVal.overall.total);
        animateCount(animatedDelivered, newVal.overall.delivered);
        animateCount(animatedReturned, newVal.overall.returned);
        animateCount(animatedSuccess, newVal.overall.success_ratio);
    }

    // Animate courier values individually
    if (newVal?.couriers) {
        Object.keys(defaultCouriers).forEach((key) => {
            const newData = newVal.couriers[key] || {
                total: 0,
                successful: 0,
                returned: 0,
            };
            const current = couriers.value[key];

            animateCount(
                { value: current.total },
                newData.total,
                1500,
                (val) => (couriers.value[key].total = val),
            );
            animateCount(
                { value: current.successful },
                newData.successful,
                1500,
                (val) => (couriers.value[key].successful = val),
            );
            animateCount(
                { value: current.returned },
                newData.returned,
                1500,
                (val) => (couriers.value[key].returned = val),
            );
        });
    }
});

// Methods
const search = async () => {
    error.value = null;
    result.value = null;
    loading.value = true;

    try {
        // If you want to use real API later, uncomment below:

        const response = await fetch('/fraud-checker/search', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN':
                    document.querySelector('meta[name="csrf-token"]')
                        ?.content || '',
                Accept: 'application/json',
            },
            body: JSON.stringify({ phone: phone.value }),
        });
        const data = await response.json();
        if (!response.ok)
            throw new Error(data.error || 'Something went wrong!');
        result.value = data;
    } catch (err) {
        error.value = 'Network error! ' + err.message;
    }

    loading.value = false;
};
const successStatus = computed(() => {
    const v = animatedSuccess.value;

    if (v < 50) {
        return {
            title: 'অনিরাপদ',
            description: 'সতর্ক থাকুন, ডেলিভারি সফল হওয়ার সম্ভাবনা কম।',
        };
    } else if (v >= 50 && v < 80) {
        return {
            title: 'মাঝারি',
            description: 'কিছুটা বিশ্বাসযোগ্য, কিছু সতর্কতা প্রয়োজন।',
        };
    } else {
        return {
            title: 'বিশ্বাসযোগ্য',
            description: 'এটি আপনার জন্য নিরাপদ ডেলিভারি হতে পারে।',
        };
    }
});
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head>
            <meta name="csrf-token" content="{{ csrf_token() }}" />
        </Head>

        <div class="p-4 py-2">
            <!-- Header -->
            <div
                class="mb-6 flex items-center justify-between rounded-3xl border border-gray-200 p-6 shadow"
            >
                <div class="text-left">
                    <h1 class="text-2xl font-bold">Fraud Checker</h1>
                    <p>
                        এখানে মোবাইল
                        <b class="text-rose-600">(017123456789)</b> নাম্বার দিয়ে
                        চেক করুন।
                    </p>
                </div>
                <!-- Input -->
                <div class="flex gap-2">
                    <Input
                        v-model="phone"
                        type="text"
                        placeholder="01XXXXXXXXX"
                        class="h-10 px-3 text-3xl"
                    />
                    <Button size="lg" @click="search" :disabled="loading">
                        {{ loading ? 'অপেক্ষা করুন...' : 'চেক করুন' }}
                    </Button>
                </div>
            </div>

            <!-- Error -->
            <div v-if="error" class="mb-4 rounded bg-red-100 p-3 text-red-700">
                {{ error }}
            </div>

            <!-- Summary Cards -->
            <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-4">
                <!-- মোট অর্ডার -->
                <div
                    class="flex items-center gap-3 rounded-3xl p-4 text-left shadow-lg"
                >
                    <div class="rounded-full bg-slate-600 p-2 text-white">
                        <Icon icon="mdi:cart" class="h-8 w-8" />
                    </div>
                    <div>
                        <p class="text-xs">মোট অর্ডার</p>
                        <div class="text-5xl font-bold text-slate-600">
                            {{ animatedTotal }}
                        </div>
                        <p class="text-xs">সকল অর্ডার বিশ্লেষণ</p>
                    </div>
                </div>

                <!-- মোট ডেলিভারি -->
                <div
                    class="flex items-center gap-3 rounded-3xl p-4 text-left shadow-lg"
                >
                    <div class="rounded-full bg-emerald-600 p-2 text-white">
                        <Icon icon="icon-park-solid:success" class="h-8 w-8" />
                    </div>
                    <div>
                        <p class="text-xs">মোট ডেলিভারি</p>
                        <div class="text-5xl font-bold text-emerald-600">
                            {{ animatedDelivered }}
                        </div>
                        <p class="text-xs">সফল হয়েছে</p>
                    </div>
                </div>

                <!-- মোট বাতিল -->
                <div
                    class="flex items-center gap-3 rounded-3xl p-4 text-left shadow-lg"
                >
                    <div class="rounded-full bg-rose-600 p-2 text-white">
                        <Icon
                            icon="material-symbols:cancel-rounded"
                            class="h-8 w-8"
                        />
                    </div>
                    <div>
                        <p class="text-xs">মোট বাতিল</p>
                        <div class="text-5xl font-bold text-rose-600">
                            {{ animatedReturned }}
                        </div>
                        <p class="text-xs">রিটার্ন করেছে</p>
                    </div>
                </div>

                <!-- সফল হওয়ার সম্ভাবনা -->
                <div
                    class="flex items-center gap-3 rounded-3xl p-4 text-left shadow-lg"
                >
                    <div class="rounded-full bg-violet-600 p-2 text-white">
                        <Icon icon="lets-icons:server-fill" class="h-8 w-8" />
                    </div>
                    <div>
                        <p class="text-xs">সফল হওয়ার সম্ভাবনা</p>
                        <div class="text-2xl font-bold text-violet-600">
                            {{ animatedSuccess }}%
                        </div>
                        <ProgressRoot
                            v-model="progressValue"
                            class="relative mx-auto h-3 w-full overflow-hidden rounded-full border border-gray-300 bg-white"
                        >
                            <ProgressIndicator
                                class="block h-full rounded-full bg-violet-600 transition-transform duration-[660ms]"
                                :style="`transform: translateX(-${100 - progressValue}%)`"
                            />
                        </ProgressRoot>
                    </div>
                </div>
            </div>

            <div class="flex h-fit flex-row items-stretch gap-6">
                <!-- Courier Table -->
                <div
                    class="flex w-full flex-1 flex-col overflow-x-auto rounded-3xl border border-gray-200 shadow"
                >
                    <table class="w-full flex-1 border-collapse border">
                        <thead class="bg-gray-200">
                            <tr>
                                <th class="border p-2">কুরিয়ার</th>
                                <th class="border p-2">মোট</th>
                                <th class="border p-2">ডেলিভারি</th>
                                <th class="border p-2">রিটার্ন</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="(c, key) in couriers"
                                :key="key"
                                class="transition hover:bg-gray-50"
                            >
                                <td
                                    class="border p-2 pl-4 text-left capitalize"
                                >
                                    {{ key }}
                                </td>
                                <td
                                    class="border p-2 text-center font-medium text-slate-600"
                                >
                                    {{ c.total }}
                                </td>
                                <td
                                    class="border p-2 text-center font-semibold text-green-600"
                                >
                                    {{ c.successful }}
                                </td>
                                <td
                                    class="border p-2 text-center font-semibold text-red-600"
                                >
                                    {{ c.returned }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Donut Chart -->
                <div
                    class="flex w-full flex-1 flex-col justify-center rounded-3xl border border-gray-200 p-6 shadow"
                >
                    <div class="flex justify-center">
                        <svg
                            width="200"
                            height="200"
                            viewBox="0 0 42 42"
                            class="donut"
                        >
                            <circle
                                r="15.9155"
                                cx="21"
                                cy="21"
                                fill="transparent"
                                stroke="#eee"
                                stroke-width="6"
                            ></circle>
                            <circle
                                r="15.9155"
                                cx="21"
                                cy="21"
                                fill="transparent"
                                :stroke="progressColor"
                                stroke-width="6"
                                :stroke-dasharray="`${animatedSuccess} ${100 - animatedSuccess}`"
                                stroke-linecap="round"
                                transform="rotate(-90 21 21)"
                                class="transition-all duration-700 ease-in-out"
                            ></circle>
                            <text
                                x="50%"
                                y="50%"
                                text-anchor="middle"
                                dy="0.3em"
                                class="fill-gray-700 text-lg font-bold transition-colors duration-700 ease-in-out"
                                :style="{ fill: progressColor }"
                            >
                                {{ animatedSuccess }}%
                            </text>
                        </svg>
                    </div>
                    <div class="flex flex-col items-center justify-center">
                        <div
                            class="flex flex-col items-center justify-center text-center"
                        >
                            <b>{{ successStatus.title }}</b>
                            <p>{{ successStatus.description }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
.donut text {
    font-size: 6px;
    fill: #333;
}
</style>
