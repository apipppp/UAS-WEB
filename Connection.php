<?php
$conn = mysqli_connect("localhost","root","","db_pustaka");
function query($query) {
    global $conn;
    $result = mysqli_query($conn, $query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)){
        $rows[] = $row;
    }
    return $rows;
}

function tambah($data){
    global $conn;
    $nama = htmlspecialchars ($data ["nama"]);
    $alamat = htmlspecialchars($data ["alamat"]);
    $email = htmlspecialchars($data ["email"]);
    $tanggal_lahir = ($data ["tanggal_lahir"]);
    $jenis_kelamin = ($data ["jenis_kelamin"]);
    $telepon = htmlspecialchars($data ["telepon"]);
    $jadwal_ujian = htmlspecialchars($data ["jadwal_ujian"]);
    $ruangan = htmlspecialchars($data ["ruangan"]);
    $mata_pelajaran = htmlspecialchars($data ["mata_pelajaran"]);
    $gambar = upload();
    if(!$gambar){
        return false;
    }

  $query = "INSERT INTO data_pinjam VALUES ('','$nama','$alamat','$email','$tanggal_lahir',
  '$jenis_kelamin','$telepon','$jadwal_ujian','$ruangan','$mata_pelajaran','$gambar')";
  mysqli_query($conn, $query);

  return mysqli_affected_rows($conn);
}

function upload(){

    $namaFile = $_FILES['gambar']['name'];
    $ukuranFile = $_FILES['gambar']['size'];
    $error = $_FILES['gambar']['error'];
    $tmpName = $_FILES['gambar']['tmp_name'];

    if ($error === 4){
        echo "<script>
            alert('Pilih gambar terlebih dahulu');
        </script>";
        return false;
    }
    $gambarvalid = ['jpg', 'jpeg', 'png'];
    $eksengambar = explode('.', $namaFile);
    $eksengambar = strtolower(end($eksengambar));
    if(!in_array($eksengambar, $gambarvalid)){
        echo "<script>
            alert('silakan pilih gambar kembali');
        </script>";
        return false;
    }

    if($ukuranFile > 1000000) {
        echo "<script>
        alert('Ukuran foto terlalu besar');
    </script>";
    return false;
    }

    $namaFileBaru = uniqid();
    $namaFileBaru .= '.';
    $namaFileBaru .= $eksengambar;
    move_uploaded_file($tmpName, 'image/'. $namaFileBaru);

    return $namaFileBaru;

}

function hapus($id){
    global $conn;
    mysqli_query($conn, "DELETE FROM data_pinjam WHERE id = $id");
    
    return mysqli_affected_rows($conn);
}

function edit($data){
    global $conn;
    $id = $data ["id"];
    $nama = htmlspecialchars ($data ["nama"]);
    $alamat = htmlspecialchars($data ["alamat"]);
    $telepon = htmlspecialchars($data ["telepon"]);
    $email = htmlspecialchars($data ["email"]);
    $tanggal_lahir = ($data ["tanggal_lahir"]);
    $jenis_kelamin = ($data ["jenis_kelamin"]);
    $jadwal_ujian = htmlspecialchars($data ["jadwal_ujian"]);
    $ruangan = htmlspecialchars($data ["ruangan"]);
    $mata_pelajaran = htmlspecialchars($data ["mata_pelajaran"]);
    $gambarlama = htmlspecialchars($data ["gambarlama"]);
    
    if( $_FILES['gambar']['error'] === 4){
        $gambar = $gambarlama;
    }else{
        $gambar = upload();
    }

  $query = "UPDATE data_pinjam SET nama ='$nama', alamat ='$alamat',
  email = '$email', tanggal_lahir= '$tanggal_lahir',
  jenis_kelamin = '$jenis_kelamin', telepon = '$telepon', jadwal_ujian = '$jadwal_ujian', ruangan = '$ruangan', mata_pelajaran = '$mata_pelajaran', gambar = '$gambar'
  WHERE id = $id";
  mysqli_query($conn, $query);

  return mysqli_affected_rows($conn);
}

function cari($key){
    $query = "SELECT * FROM data_pinjam WHERE 
    nama LIKE '%$key%' OR
    alamat LIKE '%$key%' OR
    email LIKE '%$key%' OR
    jadwal_ujian LIKE '%$key%' OR
    ruangan LIKE '%$key%'
    ";

    return query($query);
}
?>