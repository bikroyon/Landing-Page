<script setup lang="ts">
import BarChart from '@/components/Dashboard/BarChart.vue';
import LineChart from '@/components/Dashboard/LineChart.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Icon } from '@iconify/vue';
import { Head } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];
const totalSale = 543;
const totalRevenue = 85000;
const completedOrders = 497;
const fakeRate = 3.2;
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <!-- Total Sale -->
                <div
                    class="flex items-center justify-between rounded-2xl border border-gray-100 bg-white p-5 shadow-md"
                >
                    <div>
                        <p class="text-sm font-medium text-gray-500">
                            Total Sale
                        </p>
                        <h2 class="mt-1 text-3xl font-bold text-green-600">
                            {{ totalSale }}
                        </h2>
                    </div>

                    <Icon
                        icon="solar:cart-large-2-bold"
                        class="text-4xl text-green-500"
                    />
                </div>

                <!-- Total Revenue -->
                <div
                    class="flex items-center justify-between rounded-2xl border border-gray-100 bg-white p-5 shadow-md"
                >
                    <div>
                        <p class="text-sm font-medium text-gray-500">
                            Total Revenue
                        </p>
                        <h2 class="mt-1 text-3xl font-bold text-blue-600">
                            ৳ {{ totalRevenue }}
                        </h2>
                    </div>

                    <Icon
                        icon="solar:wallet-bold"
                        class="text-4xl text-blue-500"
                    />
                </div>

                <!-- Completed Orders -->
                <div
                    class="flex items-center justify-between rounded-2xl border border-gray-100 bg-white p-5 shadow-md"
                >
                    <div>
                        <p class="text-sm font-medium text-gray-500">
                            Completed Orders
                        </p>
                        <h2 class="mt-1 text-3xl font-bold text-indigo-600">
                            {{ completedOrders }}
                        </h2>
                    </div>

                    <Icon
                        icon="solar:check-circle-bold"
                        class="text-4xl text-indigo-500"
                    />
                </div>

                <!-- Fake Order Rate -->
                <div
                    class="flex items-center justify-between rounded-2xl border border-gray-100 bg-white p-5 shadow-md"
                >
                    <div>
                        <p class="text-sm font-medium text-gray-500">
                            Fake Order Rate
                        </p>
                        <h2 class="mt-1 text-3xl font-bold text-red-500">
                            {{ fakeRate }}%
                        </h2>
                    </div>

                    <Icon
                        icon="solar:danger-circle-bold"
                        class="text-4xl text-red-500"
                    />
                </div>
            </div>
             <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <!-- Total Sale -->
                <div
                    class="flex items-center justify-between rounded-2xl border border-gray-100 bg-white p-5 shadow-md"
                >
                    <div>
                        <p class="text-sm font-medium text-gray-500">
                            Total Customers
                        </p>
                        <h2 class="mt-1 text-3xl font-bold text-green-600">
                            {{ totalSale }}
                        </h2>
                    </div>

                    <Icon
                        icon="solar:cart-large-2-bold"
                        class="text-4xl text-green-500"
                    />
                </div>

                <!-- Total Revenue -->
                <div
                    class="flex items-center justify-between rounded-2xl border border-gray-100 bg-white p-5 shadow-md"
                >
                    <div>
                        <p class="text-sm font-medium text-gray-500">
                            Total Reviews
                        </p>
                        <h2 class="mt-1 text-3xl font-bold text-blue-600">
                            ৳ {{ totalRevenue }}
                        </h2>
                    </div>

                    <Icon
                        icon="solar:wallet-bold"
                        class="text-4xl text-blue-500"
                    />
                </div>

                <!-- Completed Orders -->
                <div
                    class="flex items-center justify-between rounded-2xl border border-gray-100 bg-white p-5 shadow-md"
                >
                    <div>
                        <p class="text-sm font-medium text-gray-500">
                            Completed Orders
                        </p>
                        <h2 class="mt-1 text-3xl font-bold text-indigo-600">
                            {{ completedOrders }}
                        </h2>
                    </div>

                    <Icon
                        icon="solar:check-circle-bold"
                        class="text-4xl text-indigo-500"
                    />
                </div>

                <!-- Fake Order Rate -->
                <div
                    class="flex items-center justify-between rounded-2xl border border-gray-100 bg-white p-5 shadow-md"
                >
                    <div>
                        <p class="text-sm font-medium text-gray-500">
                            Fake Order Rate
                        </p>
                        <h2 class="mt-1 text-3xl font-bold text-red-500">
                            {{ fakeRate }}%
                        </h2>
                    </div>

                    <Icon
                        icon="solar:danger-circle-bold"
                        class="text-4xl text-red-500"
                    />
                </div>
            </div>
            <div class="flex flex-row gap-4">
                <!-- Left: Line Chart -->
                <div
                    class="w-[65%] rounded-2xl border border-gray-200 bg-white p-4 shadow-sm"
                >
                    <h3 class="mb-3 text-lg font-semibold text-gray-700">
                        Weekly Sales (Last 7 Days)
                    </h3>

                    <LineChart :isWeekly="true" :weeklyData="[25, 30, 45, 50, 70, 60, 40]" />
                </div>

                <!-- Right: Most Ordering Time & Places -->
                <div class="w-[35%] space-y-4">
                    <!-- Most Ordering Time -->
                    <div
                        class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm"
                    >
                        <h3 class="mb-3 text-lg font-semibold text-gray-700">
                            Most Ordering Time
                        </h3>

                        <BarChart
                            :times="[
                                '8 AM - 11 AM',
                                '12 PM - 3 PM',
                                '4 PM - 6 PM',
                                '7 PM - 10 PM',
                            ]"
                            :values="[38, 31, 31, 22]"
                            label="Orders (%)"
                        />
                    </div>

                    <!-- Most Ordered Places -->
                    <div
                        class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm"
                    >
                        <h3 class="mb-3 text-lg font-semibold text-gray-700">
                            Most Ordered Locations
                        </h3>

                        <ul class="space-y-2 text-sm">
                            <li class="flex justify-between">
                                <span class="text-gray-600">Dhaka</span>
                                <span class="font-semibold text-green-600"
                                    >62%</span
                                >
                            </li>
                            <li class="flex justify-between">
                                <span class="text-gray-600">Chattogram</span>
                                <span class="font-semibold text-green-600"
                                    >20%</span
                                >
                            </li>
                            <li class="flex justify-between">
                                <span class="text-gray-600">Gazipur</span>
                                <span class="font-semibold text-green-600"
                                    >11%</span
                                >
                            </li>
        
                        </ul>
                    </div>
                </div>
            </div>
            <!-- New Row -->
            <div class="flex flex-row gap-4">
                <!-- Left: Last Orders Table -->
                <div
                    class="w-[40%] rounded-2xl border border-gray-200 bg-white p-4 shadow-sm "
                >
                    <h3 class="mb-3 text-lg font-semibold text-gray-700">
                        Last Orders
                    </h3>

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-gray-600">
                            <thead
                                class="bg-gray-50 text-xs text-gray-500 uppercase"
                            >
                                <tr>
                                    <th class="px-3 py-2">Order ID</th>
                                    <th class="px-3 py-2">Customer</th>
                                    <th class="px-3 py-2">Amount</th>
                                    <th class="px-3 py-2">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-3 py-2">#1021</td>
                                    <td class="px-3 py-2">Rahim</td>
                                    <td class="px-3 py-2">৳ 1500</td>
                                    <td
                                        class="px-3 py-2 font-semibold text-green-600"
                                    >
                                        Completed
                                    </td>
            
                                </tr>
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-3 py-2">#1022</td>
                                    <td class="px-3 py-2">Karim</td>
                                    <td class="px-3 py-2">৳ 2000</td>
                                    <td
                                        class="px-3 py-2 font-semibold text-yellow-500"
                                    >
                                        Pending
                                    </td>

                                </tr>
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-3 py-2">#1023</td>
                                    <td class="px-3 py-2">Salma</td>
                                    <td class="px-3 py-2">৳ 1200</td>
                                    <td
                                        class="px-3 py-2 font-semibold text-red-500"
                                    >
                                        Cancelled
                                    </td>

                                </tr>
                                <!-- Add more orders dynamically -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Right: Weekly Sales Line Chart -->
                <div
                    class="w-[60%] rounded-2xl border border-gray-200 bg-white p-4 shadow-sm"
                >
                    <h3 class="mb-3 text-lg font-semibold text-gray-700">
                        Year Sales (Last 12 Months)
                    </h3>

                    <LineChart :yearlyData="[20, 35, 40, 55, 70, 90, 120, 110, 95, 80, 60, 40]" />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
