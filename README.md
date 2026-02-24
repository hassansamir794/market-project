# Market

A Laravel + Blade + Tailwind web app for a local market with:
- Public product browsing
- Category filtering and search
- Admin product/category management
- Mobile-friendly layout and navigation

## Requirements

- PHP 8.1+
- Composer
- MySQL
- Node.js 18+ and npm

## Setup

1. Install dependencies:
```bash
composer install
npm install
```

2. Copy env and generate key:
```bash
cp .env.example .env
php artisan key:generate
```

3. Configure DB in `.env`, then run:
```bash
php artisan migrate
```

4. Link storage for product images:
```bash
php artisan storage:link
```

## Run (Local)

Terminal 1:
```bash
php artisan serve --host=0.0.0.0 --port=8000
```

Terminal 2 (dev assets):
```bash
npm run dev
```

Then open:
- Laptop: `http://127.0.0.1:8000`
- Phone on same Wi-Fi: `http://<your-lan-ip>:8000`

## LAN / Phone Notes

If mobile shows unstyled HTML:
- Ensure `public/hot` points to your LAN IP (not `localhost` or `[::1]`), or remove `public/hot` to use built assets.
- Set these values in `.env`:
```env
APP_URL=http://<your-lan-ip>
VITE_PORT=5173
VITE_HMR_HOST=<your-lan-ip>
VITE_HMR_PROTOCOL=ws
```
- Clear config:
```bash
php artisan config:clear
```

## Build (Production Assets)

```bash
npm run build
```

## Production Security Checklist

Set these in `.env` before go-live:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
FORCE_HTTPS=true
TRUSTED_PROXIES=*
SESSION_DRIVER=database
SESSION_SECURE_COOKIE=true
```

Then run:
```bash
php artisan session:table
php artisan migrate
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Also verify:
- SSL certificate is active and HTTP redirects to HTTPS.
- `public/hot` is not present on production server.
- Only `public/` is web-accessible.
- Regular database and `storage/app/public` backups are configured.

## Main Routes

- `/` Home
- `/products` Product list
- `/products/{product}` Product details
- `/about` About + location
- `/admin/products` Admin products (auth + admin)
- `/admin/categories` Admin categories (auth + admin)

## Admin Access

Admin area requires:
- Logged-in user
- `is_admin = 1` on user record

## Tests

```bash
php artisan test
```
