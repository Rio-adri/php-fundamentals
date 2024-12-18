<?php 

// fungsi menampilkan data
function select ($query) {
    // pangggil dtbs
    global $db;

    $result = mysqli_query($db, $query);
    $rows = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }

    return $rows;
}

// fungsi menambahkan data barang
function create_barang ($post) {
    global $db;

    $nama    = strip_tags($post['nama']);
    $jumlah  = strip_tags($post['jumlah']);
    $harga   = strip_tags($post['harga']);
    $barcode = rand(100000,999999);

    // query tambat data
    $query = "INSERT INTO barang VALUES(null, '$nama', '$jumlah','$harga','$barcode', CURRENT_TIMESTAMP())";
    
    mysqli_query($db, $query);

    return mysqli_affected_rows($db);
}

// fungsi mengubah data barang
function update_barang($post) {
    global $db;

    $id_barang  = $post['id_barang'];
    $nama       = strip_tags($post['nama']);
    $jumlah     = strip_tags($post['jumlah']);
    $harga      = strip_tags($post['harga']);
    $barcode    = $post['barcode'];

    $query = "UPDATE barang SET nama = '$nama', jumlah = '$jumlah', harga = '$harga', barcode = '$barcode' WHERE id_barang = $id_barang";

    mysqli_query($db, $query);

    return mysqli_affected_rows($db);
}

// fungsi menghapus barang
function delete_barang($id_barang) {
    global $db;

    // query hapus data barang
    $query = "DELETE FROM barang WHERE id_barang = $id_barang";

    mysqli_query($db, $query);

    return mysqli_affected_rows($db);
}


// fungsi menambahkan data mahasiswa 
function create_mahasiswa ($post) {
    global $db;

    if (preg_match("/^[a-zA-Z-'. ]*$/", $post['nama'])) {
        $nama = strip_tags($post['nama']);
    } else {
        echo "<script>
            alert('Penulisan nama harus benar');
            document.location.href = 'tambah-mahasiswa.php';
            </script>";
        exit;
    }

    $prodi          = strip_tags($post['prodi']);
    $jenis_kelamin  = strip_tags($post['jk']);
    $telepon        = strip_tags($post['telepon']);
    $alamat         = $post['alamat'];
    
    if (filter_var($post['email'], FILTER_VALIDATE_EMAIL)) {
        $email = strip_tags($post['email']);
    } else {
        echo "<script>
            alert('Penulisan email harus benar');
            document.location.href = 'tambah-mahasiswa.php';
            </script>";
        exit;
    }
    $foto           = upload_file();
    // check upload file
    if(!$foto) {
        return false;
    }

    // query tambat data
    $query = "INSERT INTO mahasiswa VALUES(null, '$nama', '$prodi','$jenis_kelamin', '$telepon', '$alamat', '$email', '$foto')";
    
    mysqli_query($db, $query);

    return mysqli_affected_rows($db);
}

// fungsi untuk mengupload file
function upload_file() {
    $ukuranFile = $_FILES['foto']['size'];
    $error = $_FILES['foto']['error'];
    $tmpName = $_FILES['foto']['tmp_name'];

    // check file yang diupload
    $extensifileValid = ['jpg','jpeg','png'];

    $extensiFile = strtolower(pathinfo($_FILES['foto']['full_path'],PATHINFO_EXTENSION));
    
    // check format ekstensi file
    if(!in_array($extensiFile,$extensifileValid)) {
        // pesan gagal
        echo "<script> 
                alert('Ekstensi file tidak valid');
                document.location.href = 'tambah-mahasiswa.php'
            </script>";
        die();
    }

    // check ukuran file 2mb
    if($ukuranFile> 2048000) {
        // pesan gagal
        echo "<script> 
                alert('Ukuran file terlalu besar');
                document.location.href = 'tambah-mahasiswa.php'
            </script>";
        die();
    }
    
    // generate nama file baru
    $namaFileBaru = uniqid();
    $namaFileBaru .= '.';
    $namaFileBaru .= $extensiFile;

    // pindahkan ke folder lokal
    move_uploaded_file($tmpName,'assets/img/'. $namaFileBaru);

    return $namaFileBaru;


}

// fungsi menghapus data mahasiswa
function delete_mahasiswa($id_mahasiswa) {
    global $db;

    // unlink foto
    $foto = select("SELECT * FROM mahasiswa WHERE id_mahasiswa = $id_mahasiswa")[0];
    unlink("assets/img/".$foto['foto']);

    // query hapus data mahasiswa
    $query = "DELETE FROM mahasiswa WHERE id_mahasiswa = $id_mahasiswa";

    mysqli_query($db, $query);

    return mysqli_affected_rows($db);
}

// fungsi mengubah data mahasiswa
function update_mahasiswa($post) {
    global $db;

    $id_mahasiswa   = strip_tags($post['id_mahasiswa']);

    if (preg_match("/^[a-zA-Z-'. ]*$/", $post['nama'])) {
        $nama = strip_tags($post['nama']);
    } else {
        echo "<script>
            alert('Penulisan nama harus benar');
            document.location.href = 'mahasiswa.php';
            </script>";
        exit;
    }
    $prodi          = strip_tags($post['prodi']);
    $jenis_kelamin  = strip_tags($post['jk']);
    $telepon        = strip_tags($post['telepon']);
    $alamat         = $post['alamat'];

    if (filter_var($post['email'], FILTER_VALIDATE_EMAIL)) {
        $email = strip_tags($post['email']);
    } else {
        echo "<script>
            alert('Penulisan email harus benar');
            document.location.href = 'mahasiswa.php';
            </script>";
        exit;
    }

    $fotoLama       = strip_tags($post['fotoLama']);

    if ($_FILES['foto']['error'] == 4) {
        $foto = $fotoLama;
    } else {
        $foto = upload_file();
    }

    //query ubah data mahasiswa
    $query = "UPDATE mahasiswa SET nama = '$nama', prodi = '$prodi', jk = '$jenis_kelamin', telepon = '$telepon', alamat = '$alamat', email = '$email', foto = '$foto' WHERE id_mahasiswa = $id_mahasiswa";

    mysqli_query($db, $query);

    return mysqli_affected_rows($db);
}

// fungsi untuk menambah akun

function create_akun ($post) {
    global $db;

    $nama     = strip_tags($post['nama']);
    $username = strip_tags($post['username']);
    $email    = strip_tags($post['email']);
    $password = strip_tags($post['password']);
    $level    = strip_tags($post['level']);

    // enkripsi password
    $password = password_hash($password, PASSWORD_DEFAULT);
    
    // query tambat data
    $query = "INSERT INTO akun VALUES(null, '$nama', '$username','$email', '$password', '$level')";
    
    mysqli_query($db, $query);

    return mysqli_affected_rows($db);
}

// fungsi menghapus barang
function delete_akun($id_akun) {
    global $db;

    // query hapus data barang
    $query = "DELETE FROM akun WHERE id_akun = $id_akun";

    mysqli_query($db, $query);

    return mysqli_affected_rows($db);
}

// fungsi update akun

function update_akun($post) {
    global $db;

    $id_akun  = strip_tags($post['id_akun']);
    $nama     = strip_tags($post['nama']);
    $username = strip_tags($post['username']);
    $email    = strip_tags($post['email']);
    $password = strip_tags($post['password']);
    $level    = strip_tags($post['level']);

    $password = password_hash($password, PASSWORD_DEFAULT);


    $query = "UPDATE akun SET nama = '$nama', username = '$username', email = '$email', password =  '$password', level =  '$level'  WHERE id_akun = $id_akun";

    mysqli_query($db, $query);

    return mysqli_affected_rows($db);
}


?>