# Migration Guide: theshivara.com (New Hostinger)

## Server Details
- **Domain**: theshivara.com
- **User**: u646482356
- **Path**: /home/u646482356/public_html/
- **PHP**: 8.2+
- **Database**: MySQL (create via Hostinger panel)

---

## Step 1: Clone Repository on New Server

SSH into the new server or use Hostinger Terminal:

```bash
cd /home/u646482356/public_html
git clone -b premium-theme https://github.com/sahilsindhaniamits/Shivara.git .
```

If public_html is not empty, delete default files first:
```bash
cd /home/u646482356/public_html
rm -rf *
git clone -b premium-theme https://github.com/sahilsindhaniamits/Shivara.git .
```

---

## Step 2: Install Dependencies

```bash
composer install --no-dev --optimize-autoloader
```

---

## Step 3: Configure Environment

```bash
cp .env.example .env
php artisan key:generate
```

Then edit `.env` with your actual values:
```
DB_DATABASE=u646482356_shivara
DB_USERNAME=u646482356_shivara
DB_PASSWORD=YOUR_ACTUAL_PASSWORD

RAZORPAY_KEY_ID=rzp_live_YOUR_KEY
RAZORPAY_KEY_SECRET=YOUR_SECRET
```

---

## Step 4: Storage Link

```bash
php artisan storage:link
```

On Hostinger shared hosting, if symlink doesn't work:
```bash
ln -s ../storage/app/public public/storage
```

---

## Step 5: Run Migrations

```bash
php artisan migrate --force
```

---

## Step 6: Set Permissions

```bash
chmod -R 775 storage bootstrap/cache
chown -R u646482356:u646482356 storage bootstrap/cache
```

---

## Step 7: Optimize for Production

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## Step 8: Configure Hostinger

1. **PHP Version**: Set to 8.2 in Hostinger panel
2. **Document Root**: Should point to `/public_html` (default)
3. **SSL**: Enable SSL for theshivara.com in Hostinger panel

---

## Step 9: Razorpay Magic Checkout Configuration

In Razorpay Dashboard > Magic Checkout > Coupon Settings:

| Setting | Value |
|---------|-------|
| URL for get promotions | `https://theshivara.com/api/promotions` |
| URL for apply promotions | `https://theshivara.com/api/promotions/apply` |
| Auto fetch coupon | Enabled |

In Razorpay Dashboard > Webhooks:
| Setting | Value |
|---------|-------|
| Webhook URL | `https://theshivara.com/api/razorpay/webhook` |
| Events | payment.captured, payment.failed, order.paid |

In Razorpay Dashboard > Magic Checkout > Checkout Setup:
| Setting | Value |
|---------|-------|
| Abandoned webhook URL | `https://theshivara.com/api/razorpay/webhook` |

---

## Step 10: DNS Configuration

Point theshivara.com to Hostinger nameservers:
- ns1.dns-parking.com
- ns2.dns-parking.com

Or use Hostinger's provided nameservers from your hosting panel.

---

## Step 11: Transfer Data from Old Server

If you need to transfer products/orders from the old server:

```bash
# On OLD server: export database
mysqldump -u old_user -p old_database > backup.sql

# Transfer to new server, then import:
mysql -u u646482356_shivara -p u646482356_shivara < backup.sql
```

Also transfer uploaded images:
```bash
# Copy storage/app/public/ folder from old to new
```

---

## Step 12: Post-Migration Verification

```bash
php artisan migrate --force
php artisan route:clear
php artisan config:clear
php artisan view:clear
php artisan cache:clear
```

Test:
- [ ] Homepage loads
- [ ] Products display with images
- [ ] Add to cart works
- [ ] Checkout with Razorpay works
- [ ] Admin panel accessible
- [ ] SSL working (https://)

---

## Future Updates (Deploy Commands)

```bash
cd /home/u646482356/public_html
git fetch origin premium-theme
git reset --hard origin/premium-theme
php artisan migrate --force
php artisan route:clear
php artisan view:clear
php artisan cache:clear
```

---

## Important Notes

- Images use `/public/storage/` prefix (Hostinger shared hosting path)
- The `public/` folder IS the document root on Hostinger
- All static assets (logo, certification images) are in `/public/` directly
- Admin panel: https://theshivara.com/admin
- First admin user needs `is_admin = 1` in users table
