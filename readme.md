# Starter Laravel Template

Starter Laravel yang siap pakai untuk aplikasi admin dengan autentikasi berbasis tabel `pengguna`, menu dinamis dari database, kontrol akses per-route, dan pengelolaan App Settings. Cocok untuk dipakai di shared hosting seperti Hostinger.

## Fitur Utama
- Autentikasi memakai model `App\Models\Pengguna` dengan kolom `username/password/level/status`
- Menu dinamis (`menu_items`) + override akses via `menu_permissions`
- App Settings tersimpan di DB (`settings`) termasuk upload background login ke `public/login`
- Endpoint aman untuk clear cache/config via token
- Struktur deployment yang ramah shared hosting (`public/index.php` + root `index.php` forward)
- Siap dipakai di PHP 8.1 (Laravel 10)

## Kebutuhan Sistem
- PHP `>= 8.1`
- Composer
- Node.js (opsional, jika ingin build frontend/Vite)

## Instalasi (Lokal Windows)
```powershell
# Clone & masuk ke folder
git clone <repo-url> c:\xampp81\htdocs\template\template_laravel
cd c:\xampp81\htdocs\template\template_laravel

# Salin .env dan isi koneksi database
copy .env.example .env

# Install dependencies
composer install

# Migrasi DB
php artisan migrate

# Seed data contoh
php artisan db:seed --class=Database\Seeders\SampleProjectSeeder

# (Opsional) Build aset jika memakai Vite
npm ci
npm run build

# Jalankan
php artisan serve
```

## Konfigurasi Utama
- `.env`:
  - `APP_URL` diisi domain tanpa `/public`, contoh: `APP_URL=https://yourdomain.com`
  - Jangan commit `.env`
- `config/auth.php`:
  - Guard `web` memakai provider `pengguna`
  - Default `passwords` disetel ke `pengguna`
- `config/filesystems.php`:
  - Disk `public_root` menunjuk ke `public_path()` untuk menyimpan file ke `public/login`

## Tabel & Model
- `pengguna` → `App\Models\Pengguna` (primary key `id_pengguna`)
- `menu_items` → `App\Models\MenuItem`
- `menu_permissions` → `App\Models\MenuPermission` (array `allowed_levels`)
- `settings` → `App\Models\Setting` (key-value)
- `audit_logs`, `login_logs`, `menu_logs`
- (Opsional) `sessions` jika `SESSION_DRIVER=database`
- `password_resets` untuk reset password

## Routes Penting
- `/` → redirect ke `/login`
- `/starter` (dashboard) [auth]
- `/pengguna` [auth, level 1]
- `/audit-logs`, `/login-logs`, `/menu-items`, `/menu-permissions`, `/app-settings`
- `/maintenance/cache-clear?token=...` → clear cache/config/route/view (butuh token)

## Deployment (Hostinger/Shared Hosting)
- Document root:
  - Ideal: arahkan domain ke subfolder `public/`
  - Alternatif: taruh `index.php` di root yang `require __DIR__.'/public/index.php'`
- `.htaccess` di root (contoh):

- Clear cache di server:
```bash
# SSH, sesuaikan path PHP
cd ~/public_html
/usr/bin/php8.1 artisan config:clear
/usr/bin/php8.1 artisan cache:clear
/usr/bin/php8.1 artisan route:clear
/usr/bin/php8.1 artisan view:clear
```
- Tanpa SSH: set `CACHE_CLEAR_TOKEN` di `.env`, lalu akses:
  - `https://yourdomain.com/maintenance/cache-clear?token=<token>`

## Upload Background Login
- Di App Settings, unggah file → disimpan ke `public/login/<file>.jpg`
- URL publik: `https://yourdomain.com/login/<file>.jpg`
- Pastikan izin:
  - Folder `public/login` → 755
  - File gambar → 644

## Seeders (Data Contoh)
- `SampleProjectSeeder` memanggil:
  - `PenggunaSeeder` (admin/user/viewer)
  - `MenuSeeder` (impor dari `config/menus.php`)
  - `MenuPermissionsSeeder` (aturan akses standar)
  - `SettingsSeeder` (brand/title/caption/copyright)
  - `DemoLogsSeeder` (contoh log)

## SFTP Auto Upload (Opsional)
- Buat `.vscode/sftp.json` di lokal (jangan commit), contoh:
```json
{
  "name": "Production",
  "protocol": "ftp",
  "host": "ftp.yourdomain.com",
  "port": 21,
  "username": "USER",
  "password": "PASS",
  "remotePath": "/home/USER/domains/yourdomain.com/public_html",
  "context": "c:/path/to/project",
  "uploadOnSave": true,
  "ignore": ["**/.git/**","**/node_modules/**","**/vendor/**","**/.env","**/.env.*","**/storage/**","**/*.zip","**/*.sql","**/*.sql.gz"]
}
```

## Troubleshooting
- 403 di `/public/login`:
  - Perbaiki `.htaccess` root (gunakan rule di atas) dan pastikan `APP_URL` tanpa `/public`
- Aset 404 (`/assets`, `/build`):
  - Upload folder `public/assets` dan hasil `npm run build` ke `public/build`
  - Rule `.htaccess` harus memetakan `assets/` dan `build/` ke `public/...`
- PHP CLI mismatch:
  - Gunakan binary sesuai versi (mis. `/usr/bin/php8.1`)

## Lisensi
MIT
