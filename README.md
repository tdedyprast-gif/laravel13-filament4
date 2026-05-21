<<<<<<< HEAD
<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

In addition, [Laracasts](https://laracasts.com) contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

You can also watch bite-sized lessons with real-world projects on [Laravel Learn](https://laravel.com/learn), where you will be guided through building a Laravel application from scratch while learning PHP fundamentals.

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot. Install [Laravel Boost](https://laravel.com/docs/ai) to supercharge your AI workflow:

```bash
composer require laravel/boost --dev

php artisan boost:install
```

Boost provides your agent 15+ tools and skills that help agents build Laravel applications while following best practices.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
=======
# Ubuntu Docker Server untuk Laravel, Filament, dan Redis

Blueprint ini disiapkan untuk server Ubuntu 26.04 LTS dengan spesifikasi rendah:

- CPU: Intel Celeron 1005M dual core
- RAM tersedia: sekitar 3.2 GiB
- Disk: 465 GiB HDD
- Network: Fast Ethernet 100 Mbps
- Target: Docker server ringan untuk aplikasi Laravel, Filament, queue worker, scheduler, Redis, dan opsional MariaDB.

Fokus konfigurasi:

- Hemat RAM dan CPU.
- Kompatibel dengan Laravel modern dan Filament.
- Redis siap untuk cache, queue, dan session.
- Struktur folder mudah dioperasikan untuk multi aplikasi.
- Logging dibatasi agar HDD tidak cepat penuh.
- Tidak memakai stack berat seperti Kubernetes.

## 1. Struktur Folder Server

Rekomendasi struktur di server:

```text
/srv/docker
├── apps
│   └── laravel-filament
│       ├── app
│       │   └── source-code-laravel
│       ├── docker-compose.yml
│       ├── .env
│       ├── docker
│       │   ├── nginx
│       │   │   └── default.conf
│       │   ├── php
│       │   │   ├── Dockerfile
│       │   │   ├── php.ini
│       │   │   ├── opcache.ini
│       │   │   └── www.conf
│       │   ├── redis
│       │   │   └── redis.conf
│       │   ├── mysql
│       │   │   └── my.cnf
│       │   └── supervisor
│       │       └── laravel-worker.conf
│       └── storage
│           ├── mysql
│           ├── redis
│           └── backups
└── ops
    ├── backup-laravel.sh
    ├── healthcheck.sh
    └── ufw-rules.sh
```

Template di repository ini sudah mengikuti struktur tersebut:

```text
templates/
├── docker-compose.yml
├── env.example
└── docker/
    ├── mysql/my.cnf
    ├── nginx/default.conf
    ├── php/Dockerfile
    ├── php/opcache.ini
    ├── php/php.ini
    ├── php/www.conf
    ├── redis/redis.conf
    └── supervisor/laravel-worker.conf
ops/
├── backup-laravel.sh
├── healthcheck.sh
└── ufw-rules.sh
```

## 2. Persiapan Host Ubuntu

Jalankan sebagai user dengan akses sudo.

```bash
sudo apt update
sudo apt install -y ca-certificates curl gnupg lsb-release ufw fail2ban htop iotop ncdu git unzip
```

Aktifkan SSH hardening dasar:

```bash
sudo cp /etc/ssh/sshd_config /etc/ssh/sshd_config.bak
sudo sed -i 's/^#*PasswordAuthentication.*/PasswordAuthentication no/' /etc/ssh/sshd_config
sudo sed -i 's/^#*PermitRootLogin.*/PermitRootLogin no/' /etc/ssh/sshd_config
sudo systemctl restart ssh
```

Pastikan SSH key sudah bisa login sebelum menutup sesi terminal lama.

## 3. Install Docker Engine dan Compose Plugin

```bash
sudo install -m 0755 -d /etc/apt/keyrings
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo tee /etc/apt/keyrings/docker.asc >/dev/null
sudo chmod a+r /etc/apt/keyrings/docker.asc

echo \
  "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.asc] https://download.docker.com/linux/ubuntu \
  $(. /etc/os-release && echo "$VERSION_CODENAME") stable" | \
  sudo tee /etc/apt/sources.list.d/docker.list >/dev/null

sudo apt update
sudo apt install -y docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin
sudo systemctl enable --now docker
sudo usermod -aG docker "$USER"
```

Logout lalu login ulang agar group `docker` aktif.

Verifikasi:

```bash
docker version
docker compose version
```

## 4. Tuning Host untuk Server Spek Rendah

Tambahkan sysctl:

```bash
sudo tee /etc/sysctl.d/99-docker-laravel.conf >/dev/null <<'EOF'
vm.swappiness=10
vm.vfs_cache_pressure=50
fs.inotify.max_user_watches=524288
net.core.somaxconn=1024
net.ipv4.tcp_fin_timeout=15
EOF

sudo sysctl --system
```

Batasi log Docker:

```bash
sudo tee /etc/docker/daemon.json >/dev/null <<'EOF'
{
  "log-driver": "json-file",
  "log-opts": {
    "max-size": "10m",
    "max-file": "3"
  }
}
EOF

sudo systemctl restart docker
```

Aktifkan firewall:

```bash
sudo bash /srv/docker/ops/ufw-rules.sh
```

Jika server memakai port publik lain, tambahkan aturan sesuai kebutuhan.

## 5. Deploy Aplikasi Laravel

Buat folder aplikasi:

```bash
sudo mkdir -p /srv/docker/apps/laravel-filament
sudo chown -R "$USER:$USER" /srv/docker
cd /srv/docker/apps/laravel-filament
```

Salin template:

```bash
cp -r /path/ke/blueprint/templates/* .
cp /path/ke/blueprint/templates/env.example .env
```

Taruh source code Laravel di folder `app`:

```bash
git clone git@github.com:org/repo-laravel.git app
```

Edit `.env` di level compose:

```bash
nano .env
```

Build dan jalankan:

```bash
docker compose up -d --build
docker compose exec app composer install --no-dev --optimize-autoloader
docker compose exec app cp .env.example .env
docker compose exec app php artisan key:generate
docker compose exec app php artisan storage:link
docker compose exec app php artisan migrate --force
docker compose exec app php artisan filament:optimize
docker compose exec app php artisan optimize
```

Untuk Laravel yang memakai Vite:

```bash
docker compose exec app npm ci
docker compose exec app npm run build
```

## 6. Port dan Service

Default template:

- HTTP aplikasi: `8080` di host.
- PHP-FPM: internal container saja.
- Redis: internal container saja.
- MariaDB: internal container saja.

Akses dari browser:

```text
http://IP-SERVER:8080
```

Untuk production dengan domain, gunakan reverse proxy di host atau ubah mapping port Nginx ke `80:80` dan pasang TLS via Caddy/Nginx Proxy Manager/Traefik.

## 7. Rekomendasi `.env` Laravel

Di dalam source Laravel `app/.env`, gunakan contoh:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=http://IP-SERVER:8080

LOG_CHANNEL=stack
LOG_LEVEL=warning

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=change_this_db_password

CACHE_STORE=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis

REDIS_CLIENT=phpredis
REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379
```

## 8. Operasional Harian

Cek status:

```bash
cd /srv/docker/apps/laravel-filament
docker compose ps
docker compose logs --tail=100 nginx
docker compose logs --tail=100 app
docker compose logs --tail=100 queue
```

Restart aplikasi:

```bash
docker compose restart app nginx queue scheduler
```

Deploy update:

```bash
cd /srv/docker/apps/laravel-filament/app
git pull
cd ..
docker compose exec app composer install --no-dev --optimize-autoloader
docker compose exec app npm ci
docker compose exec app npm run build
docker compose exec app php artisan migrate --force
docker compose exec app php artisan filament:optimize
docker compose exec app php artisan optimize
docker compose restart app queue scheduler nginx
```

Backup database:

```bash
bash /srv/docker/ops/backup-laravel.sh laravel-filament
```

Healthcheck:

```bash
bash /srv/docker/ops/healthcheck.sh laravel-filament
```

## 9. Cron Host yang Disarankan

Laravel scheduler sudah berjalan sebagai container `scheduler`. Untuk backup host, tambahkan:

```bash
crontab -e
```

Isi:

```cron
15 2 * * * /srv/docker/ops/backup-laravel.sh laravel-filament >> /srv/docker/apps/laravel-filament/storage/backups/backup.log 2>&1
*/10 * * * * /srv/docker/ops/healthcheck.sh laravel-filament >/dev/null 2>&1
```

## 10. Catatan Kapasitas

Dengan RAM 4 GB dan CPU dual core:

- Jalankan 1 sampai 3 aplikasi Laravel kecil-menengah.
- Gunakan Redis untuk cache, session, dan queue agar respons Filament stabil.
- Hindari menjalankan banyak worker queue paralel.
- Batasi PHP-FPM `pm.max_children` ke 6 sampai 8.
- Hindari build frontend besar langsung di server saat jam sibuk.
- Gunakan MariaDB internal hanya untuk beban ringan. Untuk aplikasi penting, pertimbangkan database terpisah.
- HDD laptop tidak ideal untuk beban tulis tinggi. Aktifkan backup dan pantau SMART disk.

## 11. Checklist Implementasi

- [ ] SSH key login aktif.
- [ ] Password SSH dan root login dimatikan.
- [ ] Docker Engine dan Compose plugin terpasang.
- [ ] Docker log rotation aktif.
- [ ] UFW aktif untuk SSH, HTTP, HTTPS, dan port aplikasi.
- [ ] Folder `/srv/docker` dibuat.
- [ ] Template compose disalin ke aplikasi.
- [ ] Laravel `.env` production sudah diset.
- [ ] `APP_KEY` sudah dibuat.
- [ ] Migration berjalan.
- [ ] Redis terhubung untuk cache/session/queue.
- [ ] Queue worker dan scheduler berjalan.
- [ ] Backup harian aktif.
- [ ] Healthcheck aktif.

>>>>>>> 7aa700cc2796a34ea0d0efd6811cd80486e5cb4e
