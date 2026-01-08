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