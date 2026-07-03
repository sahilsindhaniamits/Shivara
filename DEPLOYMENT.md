# Shivara Laravel - Hostinger Deployment Guide

## Prerequisites
- Hostinger Business or Premium Web Hosting plan
- Domain pointing to Hostinger nameservers
- SSH access enabled (optional but recommended)

## Step-by-Step Deployment

### 1. Create MySQL Database
1. Login to Hostinger hPanel → **Databases** → **MySQL Databases**
2. Create a new database (e.g., `u123456789_shivara`)
3. Note the database name, username, and password

### 2. Upload Files
**Option A: Via File Manager**
1. Zip the entire project folder
2. Upload via hPanel → **File Manager** → `public_html`
3. Extract the zip file

**Option B: Via Git (Recommended)**
1. Enable SSH access in hPanel
2. SSH into your server: `ssh u123456789@your-server.hostinger.com`
3. Navigate to `public_html`:
```bash
cd public_html
git clone https://github.com/sahilsindhaniamits/Shivara.git .
```

### 3. Configure Document Root
Since Laravel's entry point is in the `/public` folder:

**Option A: .htaccess method (Recommended for shared hosting)**
Create `.htaccess` in root (`public_html/`):
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

**Option B: Move public files**
1. Move everything from `public/` to `public_html/`
2. Edit `public_html/index.php`:
   - Change `__DIR__.'/../vendor/autoload.php'` to `__DIR__.'/../vendor/autoload.php'`
   - Change `__DIR__.'/../bootstrap/app.php'` paths accordingly

### 4. Install Dependencies
```bash
cd public_html
composer install --optimize-autoloader --no-dev
```

### 5. Configure Environment
```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` with your database credentials:
```
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=u123456789_shivara
DB_USERNAME=u123456789_shivara
DB_PASSWORD=your_password_here

RAZORPAY_KEY_ID=rzp_live_xxxx
RAZORPAY_KEY_SECRET=xxxx
```

### 6. Run Migrations & Seed
```bash
php artisan migrate --force
php artisan db:seed --force
```

### 7. Set Permissions
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### 8. Optimize for Production
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link
```

### 9. Setup Cron Job (Optional)
In hPanel → **Cron Jobs**, add:
```
* * * * * cd /home/u123456789/public_html && php artisan schedule:run >> /dev/null 2>&1
```

## Admin Access
- URL: `https://theshivara.com/admin`
- Email: `admin@theshivara.com`
- Password: `admin123` (change immediately after first login!)

## Performance Tips for Hostinger
1. **Enable OPcache**: Already enabled on Hostinger by default
2. **Use File Cache**: Already configured (no Redis needed)
3. **Image Optimization**: Upload optimized images via admin panel
4. **CDN**: Consider Cloudflare free plan for static assets
5. **Database Indexing**: Migrations already include proper indexes

## Why This is Faster Than Next.js on Hostinger
- **Server-Side Rendering**: All pages are rendered on the server with Blade templates
- **No JavaScript Runtime**: No Node.js overhead; PHP is native to Hostinger
- **Optimized Queries**: Eloquent ORM with eager loading
- **File Caching**: Route, config, and view caching eliminate repeated parsing
- **No Cold Starts**: Unlike serverless/SSR Next.js, traditional PHP is always warm
- **CDN-Friendly**: Static HTML responses are easily cached

## Troubleshooting
- **500 Error**: Check `storage/logs/laravel.log` and fix permissions
- **Images not showing**: Run `php artisan storage:link`
- **Routes not working**: Ensure `.htaccess` mod_rewrite is enabled
- **Slow first load**: Run the optimization commands in step 8
