<?php
session_start();
// membatasi halaman login
if(!isset($_SESSION['login'])) {
    echo "<script>
            document.location.href = 'login'
        </script>";
    exit;
}

include 'config/app.php';

$id_akun = (int)$_GET['id_akun'];

if(delete_akun($id_akun) > 0) {
    echo "<script>
            alert('Data akun telah berhasil dihapus');  
            document.location.href= 'crud-modal'
        </script>";
} else {
    echo "<script>
            alert('Data akun gagal dihapus');  
            document.location.href= 'crud-modal'
        </script>";
}

?>

