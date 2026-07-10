<?php
session_start();

// Menghapus semua data session
session_unset();
session_destroy();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout...</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="assets/style.css?v=<?php echo time(); ?>">
</head>
<body class="login-body">

    <script>
        // Memunculkan pop-up loading info sebelum dialihkan ke login
        let timerInterval;
        Swal.fire({
            title: 'Mengunci Sesi...',
            html: 'Berhasil keluar, mengalihkan dalam <b></b> milidetik.',
            timer: 1200,
            timerProgressBar: true,
            didOpen: () => {
                Swal.showLoading();
                const timer = Swal.getPopup().querySelector("b");
                timerInterval = setInterval(() => {
                    timer.textContent = Swal.getTimerLeft();
                }, 100);
            },
            willClose: () => {
                clearInterval(timerInterval);
            }
        }).then((result) => {
            // Setelah animasi selesai, lempar ke halaman login
            window.location.href = "login.php";
        });
    </script>

</body>
</html>