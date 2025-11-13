<template>
  <div class="mt-4">
      <h2
      class="mb-2 text-2xl font-extrabold text-gray-700 flex items-center gap-2"
    >
      <span class="h-6 w-1 rounded-full bg-green-500"></span>
      {{ title }}
    </h2>

    <!-- Payment options -->
    <div class="flex flex-wrap gap-3">
      <label
        v-for="method in activeMethods"
        :key="method.id"
        class="cursor-pointer rounded-lg border px-4 py-2 transition-all duration-150"
        :class="{
          'border-green-600 bg-green-600 text-white': form.payment_method_id === method.id,
          'hover:border-green-500': form.payment_method_id !== method.id,
        }"
      >
        <input
          type="radio"
          :value="method.id"
          v-model="form.payment_method_id"
          class="hidden"
        />
        <div class="flex flex-col items-center">
          <div class="text-center font-semibold">{{ method.name }}</div>
        </div>
      </label>
    </div>

    <!-- Non-COD Methods -->
    <div
      v-if="selectedMethod && selectedMethod.code !== 'COD'"
      class="mt-4 rounded-lg border bg-gray-50 p-4"
    >
      <!-- QR Payment Instructions -->
      <div
        class="mb-4 flex flex-col items-start justify-start gap-6 md:flex-row"
      >
        <!-- QR Image -->
        <div class="flex flex-col items-center">
          <img
            :src="selectedMethod.qr_code"
            alt="QR Code"
            class="mb-2 h-34 w-34 rounded-lg object-cover"
          />
          <p class="text-center text-[0.6rem] text-gray-700">
            {{ selectedMethod.name }} অ্যাপ ব্যবহার করে নিচের QR কোডটি স্ক্যান করুন<br />
            অথবা পাঠান এই নম্বরে:
            <b>{{ selectedMethod.account_number }}</b>
          </p>
        </div>

        <!-- Step-by-step Instructions -->
        <div class="flex-1">
          <h3 class="text-base font-semibold text-gray-600">
            পেমেন্ট করার ধাপসমূহ:
          </h3>
          <ul
            class="list-inside list-decimal text-[0.7rem] leading-relaxed text-gray-700"
          >
            <li>{{ selectedMethod.name }} অ্যাপে লগইন করুন।</li>
            <li>“Send Money” বা “পেমেন্ট” অপশনটি নির্বাচন করুন।</li>
            <li>
              QR কোড স্ক্যান করুন অথবা নিচের নম্বরটি লিখুন:
              <b>{{ selectedMethod.account_number }}</b>
            </li>
            <li>
              প্রয়োজনীয় পরিমাণ টাকা লিখে “Next” বা “Send” বাটনে ক্লিক করুন।
            </li>
            <li>
              পেমেন্ট সম্পন্ন হলে ট্রান্স্যাকশন আইডি (Txn ID) কপি করে নিচে দিন।
            </li>
          </ul>
        </div>
      </div>

      <!-- Transaction Inputs -->
      <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2">
        <!-- Transaction ID -->
        <div>
          <label class="mb-1 block text-sm font-semibold text-gray-700">
            ট্রান্স্যাকশন আইডি (Transaction ID)
          </label>
          <input
            type="text"
            v-model="form.transaction_id"
            placeholder="যেমনঃ TXN123456789"
            class="w-full rounded-lg border border-gray-300 bg-white p-2 text-sm text-gray-800 placeholder-gray-400 transition outline-none focus:border-green-600 focus:ring-1 focus:ring-green-600"
          />
        </div>

        <!-- Mobile Number -->
        <div>
          <label class="mb-1 block text-sm font-semibold text-gray-700">
            আপনার মোবাইল নম্বর
          </label>
          <input
            type="text"
            v-model="form.transaction_mobile_number"
            placeholder="যে নাম্বার থেকে পেমেন্ট করেছেন"
            class="w-full rounded-lg border border-gray-300 bg-white p-2 text-sm text-gray-800 placeholder-gray-400 transition outline-none focus:border-green-600 focus:ring-1 focus:ring-green-600"
          />
        </div>
      </div>

      <!-- ❌ Error Message -->
      <div
        v-if="showError"
        class="mt-3 rounded-md bg-red-100 px-3 py-2 text-sm text-red-700 border border-red-300"
      >
        ⚠️ দয়া করে ট্রান্স্যাকশন আইডি এবং মোবাইল নম্বর লিখুন।
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed, defineProps, watch, ref } from "vue";

const props = defineProps<{
  title?: string;
  form: {
    payment_method_id: number | null;
    transaction_id?: string;
    transaction_mobile_number?: string;
  };
  paymentMethods: Array<{
    id: number;
    name: string;
    code: string;
    provider?: string;
    qr_code?: string;
    account_number?: string;
    status: boolean | number;
  }>;
}>();

// ✅ Active methods only
const activeMethods = computed(() =>
  props.paymentMethods.filter((m) => m.status === true || m.status === 1)
);

// ✅ Currently selected method
const selectedMethod = computed(() =>
  props.paymentMethods.find((m) => m.id === props.form.payment_method_id)
);

// ✅ Error message control
const showError = computed(() => {
  if (!selectedMethod.value || selectedMethod.value.code === "COD") return false;
  return (
    !props.form.transaction_id?.trim() ||
    !props.form.transaction_mobile_number?.trim()
  );
});

// ✅ Auto-select COD by default
watch(
  () => props.paymentMethods,
  (methods) => {
    if (!props.form.payment_method_id) {
      const cod = methods.find((m) => m.code === "COD");
      if (cod) props.form.payment_method_id = cod.id;
    }
  },
  { immediate: true }
);
</script>
