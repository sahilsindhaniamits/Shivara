# Razorpay Magic Checkout - CDN Bypass Configuration Guide

## The Problem

Hostinger CDN's "Checking your browser" challenge intercepts ALL incoming requests
and serves a JavaScript-based browser verification page. This blocks server-to-server
calls from Razorpay to our API endpoints (`/api/shipping-info`, `/api/promotions`).

When Razorpay can't reach these endpoints, Magic Checkout falls back to Standard
Checkout (no OTP, no saved addresses, no COD option in popup).

## Solution Overview (3 Layers)

### Layer 1: Alternative URL Path (RECOMMENDED - Immediate Fix)

We've created duplicate endpoints under `/razorpay-hooks/*` that bypass the typical
API path that CDN challenges:

- **Shipping Info**: `https://theshivara.com/razorpay-hooks/shipping-info`
- **Promotions**: `https://theshivara.com/razorpay-hooks/promotions`
- **Apply Promotion**: `https://theshivara.com/razorpay-hooks/promotions/apply`

**Action Required**: Update the URLs in Razorpay Dashboard:
1. Go to https://dashboard.razorpay.com
2. Navigate to **Settings** > **Magic Checkout** (or Checkout Settings)
3. Update **Shipping Info URL** to: `https://theshivara.com/razorpay-hooks/shipping-info`
4. Update **Promotions URL** to: `https://theshivara.com/razorpay-hooks/promotions`
5. Save changes

### Layer 2: Hostinger CDN Settings (MOST RELIABLE - Do This Too)

Disable CDN challenge specifically for API paths in Hostinger hPanel:

1. Log into Hostinger hPanel
2. Go to **Websites** > **theshivara.com** > **Dashboard**
3. Navigate to **Performance** > **CDN** > **Manage**
4. Go to the **Security** tab
5. **Option A** (Best): Change security level from "I'm Under Attack" to "Medium" or "Low"
6. **Option B** (If Option A isn't available): Look for "Page Rules" or "Traffic Blocking"
   and add an exception for paths starting with `/api/` and `/razorpay-hooks/`

If using Cloudflare directly (not Hostinger CDN):
1. Go to Cloudflare Dashboard > your domain
2. Navigate to **Security** > **WAF** > **Custom Rules**
3. Create a rule:
   - Name: "Allow Razorpay API Access"
   - Expression: `(http.request.uri.path contains "/api/") or (http.request.uri.path contains "/razorpay-hooks/")`
   - Action: **Skip** (skip all remaining rules, or skip Browser Integrity Check)

### Layer 3: Response Headers (.htaccess + Middleware)

Already configured in the code:
- `.htaccess` sends `CDN-Cache-Control: no-store` for `/api/*` and `/razorpay-hooks/*`
- `BypassCdnChallenge` middleware adds these headers programmatically
- This tells the CDN to never cache these responses

## Testing Magic Checkout

After deploying and updating the Razorpay Dashboard URLs:

1. Add a product to cart
2. Click "Pay Now" in the side cart
3. You should see the Magic Checkout flow:
   - Phone number entry
   - OTP verification
   - Address selection/entry
   - Delivery options
   - Payment method selection (UPI, Card, COD)
4. If you only see the standard payment popup (enter card details directly), the
   shipping-info endpoint is still being blocked.

## Quick Diagnostic

Test if the endpoint is reachable from outside:
```bash
curl -X POST https://theshivara.com/razorpay-hooks/shipping-info \
  -H "Content-Type: application/json" \
  -d '{"addresses":[{"zipcode":"110001","state":"Delhi","city":"New Delhi","country":"IN"}]}'
```

Expected response:
```json
{"addresses":[{"zipcode":"110001","state":"Delhi","city":"New Delhi","country":"IN","serviceable":true,"cod":true,"cod_fee":0,"shipping_fee":0}]}
```

If you get an HTML page with "Checking your browser..." — the CDN is still blocking.
In that case, the Hostinger CDN security level MUST be lowered (Layer 2 above).

## Nuclear Option (If Nothing Else Works)

Disable Hostinger CDN entirely:
1. hPanel > Performance > CDN > **Disable**
2. This removes ALL CDN benefits but guarantees Razorpay can reach your endpoints
3. Your site will still work fine, just without CDN edge caching

## Files Modified

- `public/.htaccess` - Added CDN bypass headers for `/api/*` and `/razorpay-hooks/*`
- `bootstrap/app.php` - CSRF exceptions + CDN bypass middleware for API routes
- `app/Http/Middleware/BypassCdnChallenge.php` - New middleware for API response headers
- `routes/web.php` - Added `/razorpay-hooks/*` alternative endpoints
- `routes/api.php` - Existing `/api/shipping-info` and `/api/promotions` (unchanged)
- `resources/views/partials/side-cart.blade.php` - Magic Checkout options updated
- `app/Http/Controllers/Storefront/CheckoutController.php` - Enhanced for Magic Checkout
