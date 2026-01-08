# Pertemuan 16 / UAS
Halo semuanya, sebelumnya nama saya Ahmad Rizky Pramudia Pratama dengan NIM 312410272 Kelas TI.24.A4, disini saya
akan menjelaskan tugas UAS kali ini tentang Membuat Aplikasi Sederhana dengan beberapa ketentuan:

• Berdasarkan project praktikum OOP dan Modular menggunakan Routing App (gunakan .htaccess)
• Sempurnakan Desain tampilan responsive (mobile first) ➔ silakan gunakan Framework CSS (Twitter Bootstrap atau yg lain)
• Lengkapi dengan sistem login dengan role admin dan user
• Semua fungsi berjalan: CRUD, Filter Pencaraian, dan Pagination

Sebelum memulai langkah-langkah kedepannya, pastikan XAMPP nya sudah dibuka dan bagian Apache dan MySQL sudah aktif

Pertama-tama buat databasenya terlebih dahulu yak, disini saya namakan databasenya ```db_uas_proweb```
untuk commandnya simpel aja seperti ini

``` CREATE DATABASE db_uas_proweb ```

<img src="LabUAS/Langkah 1.png" alt="Tutorial" width="400">

Setelah itu, kita bikin tabel baru di database yang baru saja dibuat, saya akan buat 2 tabel
Yaitu tabel user dan tabel produk, untuk commandnya seperti berikut

```
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user'
);

CREATE TABLE produk (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama_produk VARCHAR(100) NOT NULL,
    harga INT NOT NULL,
    stok INT NOT NULL
);
```

Kemudian masing-masing tabel bisa diisi databasenya
Disini tabel pertama yang namanya produk, karena produk saya tentang alat rumah tangga
Saya isi di SQL nya seperti ini

```
-- Memasukkan 10 data alat rumah tangga
INSERT INTO produk (nama_produk, harga, stok) VALUES 
('Blender Philips HR2115', 650000, 15),
('Rice Cooker Miyako MCM-508', 275000, 20),
('Setrika Uap Panasonic', 350000, 12),
('Air Fryer Mito AF1', 850000, 8),
('Vacuum Cleaner Sharp', 1200000, 5),
('Mixer Turbo EHM9000', 450000, 10),
('Dispenser Polytron Hydra', 1800000, 4),
('Oven Listrik Kirin KBO-190', 550000, 7),
('Kipas Angin Cosmos Wadesta', 380000, 25),
('Teko Listrik Oxone', 225000, 30);
```

Maka isi databasenya akan seperti berikut

<img src="LabUAS/Langkah 2.png" alt="Tutorial" width="400">

Selanjutnya disini saya akan isi tabel usernya
akan ada 3 akses, 2 user dan 1 admin, usernya punya nama Naila dan Akira
Untuk cara isinya ke SQL dan isi berikut

```
INSERT INTO users (username, password, role) VALUES 
('Naila', 'naila123', 'user'),
('Akira', 'akira123', 'user'),
('Admin', 'admin123', 'admin');
```

Maka isi databasenya akan seperti berikut

<img src="LabUAS/Langkah 3.png" alt="Tutorial" width="400">

Selanjutnya kita akan buat php nya di file xampp
Pertama buat file di directory xampp/htdocs, disini nama file saya UAS-app
Lalu untuk struktur directory di dalam file UAS-app nya seperti ini 

<img src="LabUAS/Langkah 4.png" alt="Tutorial" width="400">

Masing-masing php saya isi kode berikut

• config.php
```
<?php
class Config {
    private $host = "localhost";
    private $user = "root";
    private $pass = "";
    private $db   = "db_uas_proweb";
    protected $conn;

    public function __construct() {
        try {
            $this->conn = new PDO("mysql:host=$this->host;dbname=$this->db", $this->user, $this->pass);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Koneksi gagal: " . $e->getMessage();
        }
    }
}
```

• controller.php
```
<?php
require_once 'Config.php';

class Controller extends Config {
    // LOGIN (Teks Biasa)
    public function auth($username, $password) {
        $sql = "SELECT * FROM users WHERE username = :user";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['user' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && $password == $user['password']) {
            $_SESSION['user_id']  = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role']     = $user['role'];
            return true;
        }
        return false;
    }

    // TAMPIL DATA + SEARCH + PAGINATION
    public function getProduk($keyword = '', $limit = 5, $offset = 0) {
        $sql = "SELECT * FROM produk WHERE nama_produk LIKE :key ORDER BY id DESC LIMIT $limit OFFSET $offset";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['key' => "%$keyword%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countProduk($keyword = '') {
        $sql = "SELECT COUNT(*) as total FROM produk WHERE nama_produk LIKE :key";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['key' => "%$keyword%"]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    // CRUD: TAMBAH
    public function tambahProduk($nama, $harga, $stok) {
        $sql = "INSERT INTO produk (nama_produk, harga, stok) VALUES (:nama, :harga, :stok)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['nama' => $nama, 'harga' => $harga, 'stok' => $stok]);
    }

    // CRUD: UPDATE
    public function updateProduk($id, $nama, $harga, $stok) {
        $sql = "UPDATE produk SET nama_produk = :nama, harga = :harga, stok = :stok WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['id' => $id, 'nama' => $nama, 'harga' => $harga, 'stok' => $stok]);
    }

    // CRUD: HAPUS
    public function hapusProduk($id) {
        $sql = "DELETE FROM produk WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}
```

• footer.php
```
</div> <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
```

• header.php
```
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi UAS - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .card-login { margin-top: 100px; border-radius: 15px; }
    </style>
</head>
<body>
<div class="container">
```

• dashboard.php
```
<?php include 'layout/header.php'; ?>

<div class="d-flex justify-content-between mt-4">
    <h4>Halo, <?= $_SESSION['username']; ?> (<?= $_SESSION['role']; ?>)</h4>
    <a href="index.php?url=logout" class="btn btn-danger">Logout</a>
</div>

<div class="card mt-3 shadow-sm">
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-6">
                <form action="index.php" method="GET" class="d-flex">
                    <input type="hidden" name="url" value="dashboard">
                    <input type="text" name="search" class="form-control me-2" placeholder="Cari..." value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>">
                    <button class="btn btn-primary">Cari</button>
                </form>
            </div>
            <div class="col-md-6 text-end">
                <?php if($_SESSION['role'] == 'admin') : ?>
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalTambah">+ Tambah</button>
                <?php endif; ?>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>No</th><th>Nama Produk</th><th>Harga</th><th>Stok</th>
                        <?php if($_SESSION['role'] == 'admin') : ?> <th>Aksi</th> <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php $no=$offset+1; foreach($produk as $p) : ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $p['nama_produk']; ?></td>
                        <td><?= $p['harga']; ?></td>
                        <td><?= $p['stok']; ?></td>
                        <?php if($_SESSION['role'] == 'admin') : ?>
                        <td>
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEdit" 
                                    data-id="<?= $p['id']; ?>" data-nama="<?= $p['nama_produk']; ?>" 
                                    data-harga="<?= $p['harga']; ?>" data-stok="<?= $p['stok']; ?>">Edit</button>
                            <a href="index.php?url=hapus&id=<?= $p['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus?')">Hapus</a>
                        </td>
                        <?php endif; ?>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <nav><ul class="pagination justify-content-center">
            <?php for($i=1; $i<=$total_halaman; $i++) : ?>
                <li class="page-item <?= ($page==$i)?'active':'' ?>"><a class="page-link" href="index.php?url=dashboard&page=<?= $i ?>"><?= $i ?></a></li>
            <?php endfor; ?>
        </ul></nav>
    </div>
</div>

<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog"><form action="index.php?url=tambah_aksi" method="POST" class="modal-content">
        <div class="modal-header"><h5>Tambah Produk</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
            <input type="text" name="nama" class="form-control mb-2" placeholder="Nama" required>
            <input type="number" name="harga" class="form-control mb-2" placeholder="Harga" required>
            <input type="number" name="stok" class="form-control mb-2" placeholder="Stok" required>
        </div>
        <div class="modal-footer"><button class="btn btn-primary">Simpan</button></div>
    </form></div>
</div>

<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog"><form action="index.php?url=edit_aksi" method="POST" class="modal-content">
        <div class="modal-header"><h5>Edit Produk</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
            <input type="hidden" name="id" id="e_id">
            <input type="text" name="nama" id="e_nama" class="form-control mb-2" required>
            <input type="number" name="harga" id="e_harga" class="form-control mb-2" required>
            <input type="number" name="stok" id="e_stok" class="form-control mb-2" required>
        </div>
        <div class="modal-footer"><button class="btn btn-primary">Update</button></div>
    </form></div>
</div>

<script>
    var mEdit = document.getElementById('modalEdit')
    mEdit.addEventListener('show.bs.modal', function (event) {
        var b = event.relatedTarget
        document.getElementById('e_id').value = b.getAttribute('data-id')
        document.getElementById('e_nama').value = b.getAttribute('data-nama')
        document.getElementById('e_harga').value = b.getAttribute('data-harga')
        document.getElementById('e_stok').value = b.getAttribute('data-stok')
    })
</script>

<?php include 'layout/footer.php'; ?>
```

• login.php
```
<?php include 'layout/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-12 col-md-5 col-lg-4">
        <div class="card card-login shadow border-0">
            <div class="card-body p-4">
                <h3 class="text-center fw-bold mb-4">Login UAS</h3>
                
                <form action="index.php?url=proses_login" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" placeholder="Masukkan username" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-2">Masuk</button>
                </form>
                
                <div class="mt-3 text-center text-muted" style="font-size: 0.8rem;">
                    Gunakan <b>admin</b> / <b>admin123</b>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'layout/footer.php'; ?>
```

• .htaccess
```
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php?url=$1 [L,QSA]
```

• index.php
```
<?php
session_start();
require_once 'app/Controller.php';
$app = new Controller();
$url = isset($_GET['url']) ? $_GET['url'] : 'login';

switch ($url) {
    case 'login':
        include 'views/login.php';
        break;

    case 'proses_login':
        if ($app->auth($_POST['username'], $_POST['password'])) {
            header('Location: dashboard');
        } else {
            echo "<script>alert('Gagal! Cek User/Pass'); window.location='login';</script>";
        }
        break;

    case 'dashboard':
        if (!isset($_SESSION['user_id'])) { header('Location: login'); exit; }
        $keyword = isset($_GET['search']) ? $_GET['search'] : '';
        $page    = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit   = 5;
        $offset  = ($page > 1) ? ($page * $limit) - $limit : 0;
        $produk  = $app->getProduk($keyword, $limit, $offset);
        $total_halaman = ceil($app->countProduk($keyword) / $limit);
        include 'views/dashboard.php';
        break;

    case 'tambah_aksi':
        $app->tambahProduk($_POST['nama'], $_POST['harga'], $_POST['stok']);
        header('Location: dashboard');
        break;

    case 'edit_aksi':
        $app->updateProduk($_POST['id'], $_POST['nama'], $_POST['harga'], $_POST['stok']);
        header('Location: dashboard');
        break;

    case 'hapus':
        $app->hapusProduk($_GET['id']);
        header('Location: dashboard');
        break;

    case 'logout':
        session_destroy();
        header('Location: login');
        break;
}
```

Jika sudah selesai mengisi kode dan menyesuaikan directory diatas, maka bisa langsung ditest lewat web browser
``` http://localhost:8080/uas-app ```

Maka akan masuk ke tampilan awal/login disini

<img src="LabUAS/Langkah 5.png" alt="Tutorial" width="400">

Dari sini, kita bisa pakai 3 akun yang terdaftar tadi
2 akun role user dengan nama Naila dan Akira
1 akun role admin dengan nama Admin

untuk passwordnya adalah
Naila - naila123
Akira - akira123
Admin - admin123

Jika login ke akun Naila/Akira, maka tampilannya hanya sebagai user saja
Seperti gambar dibawah

<img src="LabUAS/Langkah 6.png" alt="Tutorial" width="400">

Tetapi jika login ke akun Admin, maka tampilannya sebagai admin
Seperti gambar dibawah

<img src="LabUAS/Langkah 7.png" alt="Tutorial" width="400">

Perbedaannya, Naila dan Akira sebagai user hanya bisa akses biasa saja
Tapi kalau Admin, dia bisa mengedit barang yang ada, dan bisa juga menghapus/menambah barang

Untuk Testing webnya ada di youtube saya berikut
``` https://youtu.be/lAkYKs82ddc ```

Baik mungkin sekian yang sudah saya jelaskan tentang Membuat Aplikasi Sederhana tentang Alat Rumah Tangga
Kurang lebihnya mohon maaf,  terima kasih
