<footer class="app-footer bg-white border-top py-3 text-sm">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start">
                <strong>
                    &copy; <?php echo date('Y'); ?>
                    <a href="#" class="text-decoration-none text-primary fw-bold">Aplikasi Persil</a>.
                </strong>
                Manajemen Data Tanah.
            </div>

            <div class="col-md-6 text-center text-md-end d-none d-md-block">
                <i class="fas fa-leaf text-success me-1"></i> Sistem Bina Desa
            </div>
        </div>
    </div>

    <style>
        .app-footer {
            /* Pastikan Footer berada di bawah konten utama, bukan fixed menutupi layar */
            position: relative;
            width: 100%;
            z-index: 1000; /* Lebih rendah dari Sidebar (1060) */
        }

        /* Jika template Anda menggunakan fixed-bottom untuk footer, style ini akan memperbaikinya */
        /* Footer tidak boleh menutupi Sidebar yang fixed-left */
        body:not(.sidebar-collapse) .app-footer {
            z-index: 1000;
        }
    </style>
</footer>
