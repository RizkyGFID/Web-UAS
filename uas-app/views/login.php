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