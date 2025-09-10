# trellonativemini
# Trello Native PHP

**Stack**: PHP Native + MySQL + Bootstrap 5 + jQuery + SweetAlert2 + SortableJS

## Fitur
- Login (seed: admin@example.com / admin123)
- Board (project) dengan sub board default: **todo, progres, review, done**
- Tambah card, edit, delete
- Drag & drop antar sub board
- Saat pindah ke **review** akan diminta hasil review (approved/revisi)
- **Ke Done harus Approved** (validasi server-side)
- Deadline + notifikasi SweetAlert saat lewat deadline, dengan opsi menambah waktu
- AJAX API (PHP PDO) + penyimpanan MySQL

## Instalasi
1. Copy folder ke webroot, misal `C:\xampp\htdocs\trello_native_php`.
2. Duplicate `.env.sample` jadi `.env`, sesuaikan koneksi DB.
3. Buat database `trello_native` (atau sesuai `.env`), lalu import `database/schema.sql`.
4. Akses `http://localhost/trello_native_php/` lalu login: **admin@example.com / admin123**.
5. Buat board dari Home, otomatis terbentuk 4 sub board.
6. Tambahkan card pada masing-masing kolom, drag & drop untuk memindahkan.

## Catatan
- Validasi *Done* dilakukan di server (API `list_update.php`) agar aman walau client dimodifikasi.
- Script polling overdue berjalan setiap 15 detik.

## Update - additional features
- Role support: `admin` and `user`. Admin can create boards. Seeded users:
  - admin@example.com / admin123 (admin)
  - user@example.com / user123 (user)
- Multi-labels for cards (Red, Green, Blue, Yellow, Purple)
- Priority badges with distinct colors
- Overdue beep sound using WebAudio when deadline passes
- DB changes: `users.role` and `lists.labels` (JSON string)

If you have an existing DB from previous version, run `database/schema_update.sql` or apply the ALTER statements manually.
