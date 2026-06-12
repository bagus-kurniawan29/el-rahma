WEBSITE KOPERASI SYARIAH EL RAHMA LOMBOK RINJANI
================================================

Teknologi:
- PHP Native
- Tailwind CSS melalui CDN
- JavaScript sederhana untuk menu mobile
- MySQL (`db_el_rahma`) untuk akun, sesi, cabang, dan pengajuan dana

Cara menjalankan di XAMPP:
1. Tempatkan folder el-rahma di dalam htdocs.
2. Jalankan Apache di XAMPP.
3. Buka http://localhost/el-rahma/

Data utama berada di data/data.php.
Logo dan foto kantor berada di assets/img/.

Database MySQL:
1. Jalankan Apache dan MySQL pada XAMPP.
2. Aplikasi otomatis membuat database `db_el_rahma` saat pertama dibuka.
3. Alternatif manual: impor `database/db_el_rahma.sql` melalui phpMyAdmin.
4. Jika akun MySQL berbeda, edit `config/database.php`.

Deployment Vercel:
- MySQL pada komputer lokal tidak dapat diakses langsung oleh Vercel.
- Gunakan MySQL hosting/cloud dan isi `DATABASE_URL`, atau variabel
  `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, dan `DB_PASS` di Vercel.

Halaman:
- index.php       : Beranda
- profil.php      : Profil koperasi
- layanan.php     : Layanan anggota
- portofolio.php  : Program koperasi
- kontak.php      : Kontak dan tautan WhatsApp
- login.php       : Login admin dan member
- register.php    : Pendaftaran member
- dashboard.php   : Dashboard sesuai role
- export.php      : Ekspor data pengajuan untuk Excel

Akun demo:
- Member: member@elrahma.id / Member123!
- Admin Rempung: admin.rempung@elrahma.id / Admin123!
- Admin Pringgabaya: admin.pringgabaya@elrahma.id / Admin123!
- Admin Mataram: admin.mataram@elrahma.id / Admin123!
- Admin Mamben: admin.mamben@elrahma.id / Admin123!

Catatan:
Tampilan menggunakan utility class Tailwind langsung pada markup PHP. Tidak ada lagi stylesheet CSS custom.
