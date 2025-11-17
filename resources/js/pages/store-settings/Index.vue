<template>
    <AppLayout>
        <div class="p-4 py-2">
            <!-- Header -->
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-2xl font-bold">Store Settings</h1>
                <Button type="button" @click="submit" class="">
                    Save Settings <Icon icon="ic:round-save" />
                </Button>
            </div>
            <!-- ✅ RekaUI Tabs -->
            <TabsRoot
                v-model="activeTab"
                default-value="Basic Info"
                class="w-full"
            >
                <TabsList
                    class="relative mb-4 flex gap-0.5 justify-self-start overflow-x-auto"
                >
                    <TabsTrigger
                        v-for="tab in tabs"
                        :key="tab"
                        :value="tab"
                        class="relative rounded-sm px-4 py-2 text-sm whitespace-nowrap text-gray-700 select-none after:absolute after:bottom-0 after:left-0 after:h-[3px] after:w-full after:scale-x-0 after:rounded-full after:bg-gray-900 after:transition-all hover:bg-gray-100 hover:text-gray-900 data-[state=active]:bg-gray-100 data-[state=active]:font-semibold data-[state=active]:text-gray-900 data-[state=active]:after:scale-x-100"
                    >
                        {{ tab }}
                    </TabsTrigger>
                </TabsList>

                <!-- ✅ Basic Info -->
                <TabsContent value="Basic Info">
                    <div class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-2">
                        <label for="store_name">Store Name</label>
                        <Input id="store_name" v-model="form.store_name" />

                        <label for="store_tagline">Tagline</label>
                        <Input
                            id="store_tagline"
                            v-model="form.store_tagline"
                        />

                        <label for="store_logo">Logo URL</label>
                        <ImageUploader v-model="form.store_logo" class="w-60" />
                        <label for="store_favicon">Favicon URL</label>
                        <ImageUploader
                            v-model="form.store_favicon"
                            class="w-60"
                        />
                    </div>
                    <!-- ✅ Meta / SEO -->
                    <h2 class="mt-8 text-lg font-semibold text-gray-800">
                        Meta & SEO
                    </h2>
                    <div class="mt-4">
                        <label for="meta_title">Meta Title</label>
                        <Input id="meta_title" v-model="form.meta_title" />

                        <label for="meta_description" class="mt-4"
                            >Meta Description</label
                        >
                        <Textarea
                            id="meta_description"
                            v-model="form.meta_description"
                        />
                    </div>
                </TabsContent>

                <!-- ✅ Contact Info + Social Links -->
                <TabsContent value="Contact Info">
                    <div class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-2">
                        <label for="email">Email</label>
                        <Input id="email" v-model="form.email" />

                        <label for="phone">Phone</label>
                        <Input id="phone" v-model="form.phone" />

                        <label for="address">Address</label>
                        <Input id="address" v-model="form.address" />

                        <label for="city">City</label>
                        <Input id="city" v-model="form.city" />

                        <label for="country">Country</label>
                        <Input id="country" v-model="form.country" />

                        <label for="postal_code">Postal Code</label>
                        <Input id="postal_code" v-model="form.postal_code" />
                    </div>

                    <!-- ✅ Social Links -->
                    <h2 class="mt-8 text-lg font-semibold text-gray-800">
                        Social Links
                    </h2>
                    <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2">
                        <label for="facebook_url">Facebook URL</label>
                        <Input id="facebook_url" v-model="form.facebook_url" />

                        <label for="instagram_url">Instagram URL</label>
                        <Input
                            id="instagram_url"
                            v-model="form.instagram_url"
                        />
                        <label for="tiktok_url">Tiktok URL</label>
                        <Input id="tiktok_url" v-model="form.tiktok_url" />
                        <label for="twitter_url">Twitter URL</label>
                        <Input id="twitter_url" v-model="form.twitter_url" />

                        <label for="youtube_url">YouTube URL</label>
                        <Input id="youtube_url" v-model="form.youtube_url" />
                    </div>
                </TabsContent>

                <!-- ✅ Business Settings + Meta/SEO -->
                <TabsContent value="Business Settings">
                    <div class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-2">
                        <label for="currency">Currency</label>
                        <Input id="currency" v-model="form.currency" />

                        <label for="currency_symbol">Currency Symbol</label>
                        <Input
                            id="currency_symbol"
                            v-model="form.currency_symbol"
                        />

                        <div class="mt-2 flex items-center space-x-2">
                            <ToggleSwitch
                                :labal="false"
                                v-model="form.maintenance_mode"
                            />
                            <label for="maintenance_mode"
                                >Enable Maintenance Mode</label
                            >
                        </div>
                    </div>

                    <!-- ✅ Payment Configs -->
                    <h2 class="mt-8 text-lg font-semibold text-gray-800">
                        Payment Options
                    </h2>
                    <div class="mt-4 grid grid-cols-1 gap-3 md:grid-cols-2">
                        <!-- COD -->
                        <div>
                            <label class="flex items-center space-x-2">
                                <ToggleSwitch
                                    :label="false"
                                    v-model="form.enable_cod"
                                />
                                <span>Cash on Delivery</span>
                            </label>
                            <div v-if="form.enable_cod">
                                <label for="extra_cod"
                                    >Extra Charge For COD <b>(%)</b></label
                                >
                                <Input type="text" v-model="form.extra_charge_cod_percentage" />
                            </div>
                        </div>

                        <!-- Bkash -->
                        <div>
                            <label class="flex items-center space-x-2">
                                <ToggleSwitch
                                    :label="false"
                                    v-model="form.enable_bkash"
                                />
                                <span>Bkash</span>
                            </label>

                            <div
                                v-if="form.enable_bkash"
                                class="mt-2 space-y-2"
                            >
                                <small class="text-gray-500"
                                    >Ensure Bkash is properly configured in
                                    Payment Methods.</small
                                >
                                <label>Bkash Account Number</label>
                                <Input v-model="form.bkash_account_number" />

                                <label>Bkash QR Code</label>
                                <ImageUploader
                                    v-model="form.bkash_qr_code"
                                    class="w-40"
                                />
                            </div>
                        </div>

                        <!-- Nagad -->
                        <div>
                            <label class="flex items-center space-x-2">
                                <ToggleSwitch
                                    :label="false"
                                    v-model="form.enable_nagad"
                                />
                                <span>Nagad</span>
                            </label>

                            <div
                                v-if="form.enable_nagad"
                                class="mt-2 space-y-2"
                            >
                                <small class="text-gray-500"
                                    >Ensure Nagad is properly configured in
                                    Payment Methods.</small
                                >
                                <label>Nagad Account Number</label>
                                <Input v-model="form.nagad_account_number" />

                                <label>Nagad QR Code</label>
                                <ImageUploader
                                    v-model="form.nagad_qr_code"
                                    class="w-40"
                                />
                            </div>
                        </div>

                        <!-- Rocket -->
                        <div>
                            <label class="flex items-center space-x-2">
                                <ToggleSwitch
                                    :label="false"
                                    v-model="form.enable_rocket"
                                />
                                <span>Rocket</span>
                            </label>

                            <div
                                v-if="form.enable_rocket"
                                class="mt-2 space-y-2"
                            >
                                <small class="text-gray-500"
                                    >Ensure Rocket is properly configured in
                                    Payment Methods.</small
                                >
                                <label>Rocket Account Number</label>
                                <Input v-model="form.rocket_account_number" />

                                <label>Rocket QR Code</label>
                                <ImageUploader
                                    v-model="form.rocket_qr_code"
                                    class="w-40"
                                />
                            </div>
                        </div>
                    </div>
                </TabsContent>

                <!-- ✅ Section Titles -->
                <TabsContent value="Section Titles">
                    <div class="flex flex-col gap-4">
                        <div>
                            <h2 class="font-semibold text-gray-700">
                                Section Titles
                            </h2>
                            <div class="flex flex-row gap-4">
                                <div class="w-full">
                                    <label for="products_title"
                                        >Products Title</label
                                    >
                                    <Input
                                        id="products_title"
                                        v-model="form.products_title"
                                    />
                                </div>
                                <div class="w-full">
                                    <label for="customer_info_title"
                                        >Customer Info Title</label
                                    >
                                    <Input
                                        id="customer_info_title"
                                        v-model="form.customer_info_title"
                                    />
                                </div>
                            </div>
                            <div class="flex flex-row gap-4">
                                <div class="w-full">
                                    <label for="delivery_zone_title"
                                        >Delivery Zone Title</label
                                    >
                                    <Input
                                        id="delivery_zone_title"
                                        v-model="form.delivery_zone_title"
                                    />
                                </div>
                                <div class="w-full">
                                    <label for="additional_note_title"
                                        >Additional Note Title</label
                                    >
                                    <Input
                                        id="additional_note_title"
                                        v-model="form.additional_note_title"
                                    />
                                </div>
                            </div>
                            <div class="flex flex-row gap-4">
                                <div class="w-full">
                                    <label for="order_summary_title"
                                        >Order Summary Title</label
                                    >
                                    <Input
                                        id="order_summary_title"
                                        v-model="form.order_summary_title"
                                    />
                                </div>
                                <div class="w-full">
                                    <label for="payment_title"
                                        >Payment Title</label
                                    >
                                    <Input
                                        id="payment_title"
                                        v-model="form.payment_title"
                                    />
                                </div>
                            </div>
                        </div>
                        <div>
                            <h2 class="font-semibold text-gray-700">
                                Active Fields
                            </h2>
                            <div class="flex flex-row gap-4">
                                <div>
                                    <h3>Name</h3>
                                    <ToggleSwitch
                                        :label="false"
                                        v-model="form.customer_info_name_status"
                                    />
                                </div>
                                <div>
                                    <h3>Mobile Number</h3>
                                    <ToggleSwitch
                                        :label="false"
                                        v-model="
                                            form.customer_info_phone_status
                                        "
                                    />
                                </div>
                                <div>
                                    <h3>E-mail</h3>
                                    <ToggleSwitch
                                        :label="false"
                                        v-model="
                                            form.customer_info_email_status
                                        "
                                    />
                                </div>
                                <div>
                                    <h3>Address</h3>
                                    <ToggleSwitch
                                        :label="false"
                                        v-model="
                                            form.customer_info_address_status
                                        "
                                    />
                                </div>
                                <div>
                                    <h3>Additional Note</h3>
                                    <ToggleSwitch
                                        :label="false"
                                        v-model="form.additional_note_status"
                                    />
                                </div>
                            </div>
                        </div>
                        <div>
                            <div>
                                <label
                                    for="customer_info_label"
                                    class="font-semibold text-gray-700"
                                    >Customer Info Label</label
                                >
                                <ToggleSwitch
                                    :label="false"
                                    v-model="form.customer_info_label"
                                />
                            </div>
                            <div class="flex flex-row gap-4">
                                <div class="w-full">
                                    <label
                                        for="customer_info_name"
                                        v-if="form.customer_info_label"
                                        >Customer Name Label</label
                                    >
                                    <Input
                                        v-if="form.customer_info_label"
                                        id="customer_info_name_label"
                                        v-model="form.customer_info_name_label"
                                    />
                                </div>
                                <div class="w-full">
                                    <label
                                        v-if="form.customer_info_label"
                                        for="customer_info_phone"
                                        >Customer Phone Label</label
                                    >
                                    <Input
                                        v-if="form.customer_info_label"
                                        id="customer_info_phone"
                                        v-model="form.customer_info_phone_label"
                                    />
                                </div>
                            </div>
                            <div class="flex flex-row gap-4">
                                <div class="w-full">
                                    <label
                                        v-if="form.customer_info_label"
                                        for="customer_info_email_label"
                                        >Customer Email Label</label
                                    >
                                    <Input
                                        v-if="form.customer_info_label"
                                        id="customer_info_email_label"
                                        v-model="form.customer_info_email_label"
                                    />
                                </div>
                                <div class="w-full">
                                    <label
                                        v-if="form.customer_info_label"
                                        for="customer_info_address_label"
                                        >Customer Address Label</label
                                    >
                                    <Input
                                        v-if="form.customer_info_label"
                                        id="customer_info_address_label"
                                        v-model="
                                            form.customer_info_address_label
                                        "
                                    />
                                </div>
                            </div>
                        </div>

                        <div>
                            <label
                                for="submit_button"
                                class="font-semibold text-gray-700"
                                >Submit Button Text</label
                            >
                            <Input
                                id="submit_button"
                                v-model="form.submit_button"
                            />
                        </div>
                    </div>
                </TabsContent>

                <!--Email & SMS-->
                <TabsContent value="Email & SMS">
                    <div class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-2">
                        <!-- SMTP Email Settings -->
                        <h2
                            class="col-span-2 text-lg font-semibold text-gray-800"
                        >
                            SMTP Email Settings
                        </h2>

                        <label for="mail_driver">Mail Driver</label>
                        <Input
                            id="mail_driver"
                            v-model="form.mail_driver"
                            placeholder="smtp"
                        />

                        <label for="mail_host">SMTP Host</label>
                        <Input
                            id="mail_host"
                            v-model="form.mail_host"
                            placeholder="smtp.gmail.com"
                        />

                        <label for="mail_port">SMTP Port</label>
                        <Input
                            id="mail_port"
                            v-model="form.mail_port"
                            type="number"
                            placeholder="587"
                        />

                        <label for="mail_username">SMTP Username</label>
                        <Input
                            id="mail_username"
                            v-model="form.mail_username"
                        />

                        <label for="mail_password">SMTP Password</label>
                        <Input
                            id="mail_password"
                            v-model="form.mail_password"
                            type="password"
                        />

                        <label for="mail_encryption">Encryption</label>
                        <Input
                            id="mail_encryption"
                            v-model="form.mail_encryption"
                            placeholder="tls / ssl"
                        />

                        <label for="mail_from_address">From Email</label>
                        <Input
                            id="mail_from_address"
                            v-model="form.mail_from_address"
                        />

                        <label for="mail_from_name">From Name</label>
                        <Input
                            id="mail_from_name"
                            v-model="form.mail_from_name"
                        />

                        <!-- SMS Settings -->
                        <h2
                            class="col-span-2 mt-6 text-lg font-semibold text-gray-800"
                        >
                            SMS Gateway Settings
                        </h2>

                        <label for="sms_api_url">SMS API URL</label>
                        <Input
                            id="sms_api_url"
                            v-model="form.sms_api_url"
                            placeholder="https://api.smsprovider.com/send"
                        />

                        <label for="sms_api_key">API Key</label>
                        <Input id="sms_api_key" v-model="form.sms_api_key" />

                        <label for="sms_sender_id">Sender ID</label>
                        <Input
                            id="sms_sender_id"
                            v-model="form.sms_sender_id"
                            placeholder="Bikroyon"
                        />
                    </div>
                </TabsContent>

                <!--Tackings-->
                <TabsContent value="Trackings">
                    <label>Facebook Pixel ID</label>
                    <Input v-model="form.fb_pixel_id" />
                    <label>Facebook Pixel Token</label>
                    <Input v-model="form.fb_pixel_access_token" />

                    <label>Fraud Spy API KEY</label>
                    <Input v-model="form.fraudchecker_api_key" />
                </TabsContent>
            </TabsRoot>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import ImageUploader from '@/components/ImageUploader.vue';
import ToggleSwitch from '@/components/ToggleSwitch.vue';
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';
import { useToast } from '@/composables/useToast';
import AppLayout from '@/layouts/AppLayout.vue';
import { Icon } from '@iconify/vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { TabsContent, TabsList, TabsRoot, TabsTrigger } from 'reka-ui';
import { ref, watch } from 'vue';
const { showToast } = useToast();
// ✅ Tabs list
const tabs = [
    'Basic Info',
    'Contact Info',
    'Business Settings',
    'Section Titles',
    'Email & SMS',
    'Trackings',
];

// ✅ Load last active tab from localStorage or fallback
const activeTab = ref(localStorage.getItem('active_tab') || 'Basic Info');

// ✅ Save tab on change
watch(activeTab, (val) => {
    localStorage.setItem('active_tab', val);
});
// ✅ Form setup
const page = usePage();
const setting = page.props.setting || {};

// Payment Methods defaults
const form = useForm({
    store_name: setting.store_name || '',
    store_tagline: setting.store_tagline || '',
    store_logo: setting.store_logo || '',
    store_favicon: setting.store_favicon || '',
    email: setting.email || '',
    phone: setting.phone || '',
    address: setting.address || '',
    city: setting.city || '',
    country: setting.country || '',
    postal_code: setting.postal_code || '',
    currency: setting.currency || 'BDT',
    currency_symbol: setting.currency_symbol || '৳',
    maintenance_mode: setting.maintenance_mode ? 1 : 0,
    facebook_url: setting.facebook_url || '',
    tiktok_url: setting.tiktok_url || '',
    instagram_url: setting.instagram_url || '',
    twitter_url: setting.twitter_url || '',
    youtube_url: setting.youtube_url || '',

    // ✅ Payments
    enable_cod: setting.cod_method?.status ? 1 : 0,
    extra_charge_cod_percentage: setting.cod_method?.extra_charge_cod_percentage,

    enable_bkash: setting.bkash_method?.status ? 1 : 0,
    bkash_account_number: setting.bkash_method?.account_number || '',
    bkash_qr_code: setting.bkash_method?.qr_code || '',

    enable_nagad: setting.nagad_method?.status ? 1 : 0,
    nagad_account_number: setting.nagad_method?.account_number || '',
    nagad_qr_code: setting.nagad_method?.qr_code || '',

    enable_rocket: setting.rocket_method?.status ? 1 : 0,
    rocket_account_number: setting.rocket_method?.account_number || '',
    rocket_qr_code: setting.rocket_method?.qr_code || '',

    meta_title: setting.meta_title || '',
    meta_description: setting.meta_description || '',
    products_title: setting.products_title || 'Products',
    customer_info_title: setting.customer_info_title || 'Customer Information',
    customer_info_label: setting.customer_info_label? 1 : 0,
    customer_info_name_label: setting.customer_info_name_label || 'Enter Name',
customer_info_name_status: setting.customer_info_name_status ? 1 : 0,
    customer_info_phone_label:
        setting.customer_info_phone_label || 'Enter Phone',
    customer_info_phone_status: setting.customer_info_phone_status ? 1 : 0,
    customer_info_email_label:
        setting.customer_info_email_label || 'Enter Email',
    customer_info_email_status: setting.customer_info_email_status ? 1 : 0,
    customer_info_address_label:
        setting.customer_info_address_label || 'Enter Address',
    customer_info_address_status: setting.customer_info_address_status ? 1 : 0,
    delivery_zone_title: setting.delivery_zone_title || 'Delivery Zone',
    additional_note_title: setting.additional_note_title || 'Additional Notes',
    additional_note_status: setting.additional_note_status ? 1 : 0,
    order_summary_title: setting.order_summary_title || 'Order Summary',
    payment_title: setting.payment_title || 'Payment Method',
    submit_button: setting.submit_button || 'Place Order',

    //Tracking
    fb_pixel_id: setting.fb_pixel_id || '',
    fb_pixel_access_token: setting.fb_pixel_access_token || '',
    fraudchecker_api_key: setting.fraudchecker_api_key || '',

    //email & sms
    mail_driver: setting.mail_driver || 'smtp',
    mail_host: setting.mail_host || '',
    mail_port: setting.mail_port || 587,
    mail_username: setting.mail_username || '',
    mail_password: setting.mail_password || '',
    mail_encryption: setting.mail_encryption || 'tls',
    mail_from_address: setting.mail_from_address || '',
    mail_from_name: setting.mail_from_name || '',
    sms_api_url: setting.sms_api_url || '',
    sms_api_key: setting.sms_api_key || '',
    sms_sender_id: setting.sms_sender_id || '',
});

function submit() {
    form.post('/store-settings/update', {
        onSuccess: () => {
            showToast('ধন্যবাদ!', 'সেটিংস সফলভাবে আপডেট হয়েছে।', 'success');
        },
        onError: () => {
            showToast(
                'দুঃক্ষিত!',
                'কোথাও কিছু ভুল হয়েছে, দয়া করে চেক করুন।',
                'error',
            );
        },
    });
    console.log(form)
}
</script>
