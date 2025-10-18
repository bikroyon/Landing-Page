<?php

namespace App\Services;

use App\Models\Offer;
use App\Models\Order;
use App\Models\Product;
use App\Models\DeliveryZone;

class OfferService
{
    /**
     * Get applicable offer for order
     *
     * @param array $orderData
     *   - 'items' => [ ['product_id'=>1,'quantity'=>2], ... ]
     *   - 'delivery_zone_id' => int|null
     *   - 'coupon_code' => string|null
     */
    public function getApplicableOffers(array $orderData)
    {
        $subtotal = 0;
        $products = Product::whereIn('id', array_column($orderData['items'], 'product_id'))->get();

        foreach ($orderData['items'] as $item) {
            $product = $products->firstWhere('id', $item['product_id']);
            if ($product) {
                $subtotal += $product->price * $item['quantity'];
            }
        }

        $appliedOffers = [];

        // Coupon offer
        if (!empty($orderData['coupon_code'])) {
            $coupon = Offer::where('offer_type', 'coupon')
                ->where('code', $orderData['coupon_code'])
                ->first();
            if ($coupon && $coupon->isActive()) {
                $appliedOffers[] = $coupon;
            }
        }

        // Delivery zone offer
        if (!empty($orderData['delivery_zone_id'])) {
            $deliveryOffer = Offer::where('offer_type', 'delivery')
                ->where('delivery_zone_id', $orderData['delivery_zone_id'])
                ->first();
            if ($deliveryOffer && $deliveryOffer->isActive()) {
                $appliedOffers[] = $deliveryOffer;
            }
        }

        // Product-specific offers
        $productIds = array_column($orderData['items'], 'product_id');
        $productOffers = Offer::where('offer_type', 'product')
            ->whereIn('product_id', $productIds)
            ->get();

        foreach ($productOffers as $po) {
            if ($po->isActive()) $appliedOffers[] = $po;
        }

        // Cart-wide offers
        $cartOffers = Offer::where('offer_type', 'cart')
            ->get()
            ->filter(fn($offer) => $offer->isActive() && $subtotal >= $offer->min_order_amount);

        foreach ($cartOffers as $co) $appliedOffers[] = $co;

        return $appliedOffers;
    }

    /**
     * Calculate total discount for the order
     */
    public function calculateTotalDiscount(array $orderData): float
    {
        $subtotal = 0;
        $products = Product::whereIn('id', array_column($orderData['items'], 'product_id'))->get();

        foreach ($orderData['items'] as $item) {
            $product = $products->firstWhere('id', $item['product_id']);
            if ($product) $subtotal += $product->price * $item['quantity'];
        }

        $offers = $this->getApplicableOffers($orderData);
        $totalDiscount = 0;

        foreach ($offers as $offer) {
            if ($offer->offer_type === 'product') {
                // product-level discount
                foreach ($orderData['items'] as $item) {
                    if ($item['product_id'] == $offer->product_id) {
                        $productPrice = $products->firstWhere('id', $item['product_id'])->price;
                        $totalDiscount += $offer->calculateDiscount($productPrice * $item['quantity']);
                    }
                }
            } else {
                // cart/coupon/delivery discount applies on subtotal
                $totalDiscount += $offer->calculateDiscount($subtotal);
            }
        }

        return min($subtotal, $totalDiscount);
    }
}
