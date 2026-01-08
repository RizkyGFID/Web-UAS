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