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