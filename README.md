# 🎭 Sinema Tegal (Laka-Laka)
> **Platform Streaming Film Lokal, Dokumenter Budaya, & Nada Khas Tegalan Berbasis Laravel**

Sinema Tegal adalah platform streaming hibrida film bioskop rakyat (sistem tiket Pay-Per-View) dan musik daerah Pantura (ala Spotify / YouTube Music) dengan tema khas budaya, tradisi, dan sejarah Kota & Kabupaten Tegal, Jawa Tengah.

---

## 🌟 Fitur Utama

- **🎬 Sinema & Film Tegalan**:
  - *Sintren: Tembang Asmara Muarareja* (Drama Horor Mistik)
  - *Ki Enthus Susmono: Dalang Gila Pesisir* (Dokumenter Biografi)
  - *Jodoh Segelas Teh Poci Wasgitel* (Komedi Romantis Ngapak)
  - *Babad Ki Gede Sebayu: Titah Air Kali Gung* (Sejarah Kolosal)
  - *Misteri Pabrik Gula Jatibarang 1891* (Horor Sejarah)
  - *Warteg Empire: Invasi Rasa Pantura* (Dokumenter Sosial)
- **🎵 Nada Tegalan (Player Musik Persisten ala Spotify)**:
  - Pemutar audio docked di bawah layar yang tetap aktif saat berpindah halaman.
  - Koleksi tembang daerah: *Tegal Laka-Laka*, *Man Sakwi*, *Turun Sintren*, *Teh Poci Wasgitel Blues*, dll.
  - Drawer **Lirik Ngapak & Terjemahan Bahasa Indonesia**.
- **🎟️ Bioskop Rakyat (Sistem Tiket Pay-Per-View)**:
  - Pembelian tiket premiere satuan (aktif 7 hari) dengan kode tiket unik.
- **👑 Langganan VIP Sebayu**:
  - Pilihan paket (Paket Cilik, Paket Wasgitel, Paket Laka-Laka) untuk akses arsip eksklusif & audio Hi-Fi.
- **💰 Dompet Bahari**:
  - Saldo digital terintegrasi untuk pembelian tiket dan VIP 1-klik, serta simulasi top-up.
- **⚙️ Kurator & Admin Hub**:
  - Dashboard statistik pendapatan, penjualan tiket, serta form rilis film dan tembang baru.
- **👤 1-Click Demo Persona Switcher**:
  - Tombol cepat di navbar untuk berganti profil (Admin, VIP Kang Slamet, Reguler Yu Minah, Kreator).

---

## 🚀 Panduan Menjalankan Secara Lokal

```bash
# 1. Clone repository
git clone https://github.com/hardadinuraziz/sinemategal.git
cd sinemategal

# 2. Install dependensi Composer
composer install

# 3. Salin environment dan generate key
cp .env.example .env
php artisan key:generate

# 4. Migrasi & Seeder Database
touch database/database.sqlite
php artisan migrate:fresh --seed

# 5. Jalankan server lokal
php artisan serve --port=8000
```
Buka browser: **http://127.0.0.1:8000**

---

## 🚂 Panduan Deploy ke Railway

Proyek ini telah dilengkapi dengan `Procfile` dan `nixpacks.toml` yang sudah dioptimasi untuk Railway.

### Langkah-langkah di Dashboard Railway:
1. Masuk ke [Railway.app](https://railway.app).
2. Klik **New Project** → **Deploy from GitHub repo**.
3. Pilih repository **`hardadinuraziz/sinemategal`**.
4. Tambahkan **Variables** (Environment Variables) di tab Variables Railway:
   - `APP_NAME` : `Sinema Tegal`
   - `APP_ENV` : `production`
   - `APP_DEBUG` : `false`
   - `APP_KEY` : *(Generate via `php artisan key:generate --show` atau salin dari `.env`)*
   - `APP_URL` : `https://${{RAILWAY_PUBLIC_DOMAIN}}`
   - `DB_CONNECTION` : `sqlite`
   - `DB_DATABASE` : `/app/database/database.sqlite`
   - `SESSION_DRIVER` : `database`
   - `CACHE_STORE` : `database`
5. Railway akan mendeteksi `nixpacks.toml` atau `Procfile` secara otomatis, mengompilasi dependensi PHP, membuat database SQLite, menjalankan migrasi & seeder budaya, dan menjalankan aplikasi.
6. Klik **Generate Domain** di tab **Settings** → **Networking** untuk mendapatkan tautan publik website.

---

## 👥 Akun Demo Bawaan

| Nama | Email | Peran | Status Akses |
| :--- | :--- | :--- | :--- |
| **Ki Patih Budaya** | `admin@sinemategal.id` | Admin | Akses Kurator Hub + Semua Film/Musik |
| **Kang Slamet Slawi** | `vip@sinemategal.id` | User | Anggota VIP Wasgitel Aktif |
| **Yu Minah Pesisir** | `user@sinemategal.id` | User | Reguler (Memiliki 1 Tiket Film Sintren Aktif) |
| **Mas Bowo Sineas** | `creator@sinemategal.id` | Creator | Sineas & Musisi Lokal Tegal |

*Password default semua akun:* `password`
*(Atau gunakan tombol 1-klik di bar navigasi atas website).*

---

## 📄 Lisensi & Budaya
Dikembangkan untuk melestarikan dan mempromosikan seni, tradisi, dan perfilman daerah khas Tegal, Jawa Tengah.
Motto: **"Tegal Laka-Laka - Wasgitel (Wangi, Sepet, Legi, tur Kentel)"**
