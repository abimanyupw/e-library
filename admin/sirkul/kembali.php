<?php
if (isset($_GET['kode'])) {
    $id_sk = mysqli_real_escape_string($koneksi, $_GET['kode']);

    // Ambil data buku dan peminjaman dengan JOIN ke tb_buku
    $query_buku = mysqli_query($koneksi, "
        SELECT s.id_buku, s.tgl_kembali, b.harga_beli
        FROM tb_sirkulasi s
        JOIN tb_buku b ON s.id_buku = b.id_buku
        WHERE s.id_sk='$id_sk'
    ");

    if (mysqli_num_rows($query_buku) == 0) {
        echo "<script>alert('ID Sirkulasi tidak ditemukan!'); window.location.href='index.php?page=MyApp/data_sirkulasi';</script>";
        exit;
    }

    $data_buku = mysqli_fetch_assoc($query_buku);
    $id_buku = $data_buku['id_buku'];
    $tgl_kembali = $data_buku['tgl_kembali'];
    $harga_beli = $data_buku['harga_beli'];

    // Hitung denda jika telat
    $tgl_dikembalikan = date('Y-m-d');
    $denda_per_hari = 1000;
    $terlambat = max(0, ceil((strtotime($tgl_dikembalikan) - strtotime($tgl_kembali)) / (60 * 60 * 24)));
    $denda = $terlambat * $denda_per_hari;

    // Tambahan denda untuk kondisi buku
    $denda_rusak_ringan = 5000;
    $denda_rusak_berat = 20000;
    $denda_hilang = $harga_beli;
    ?>
    <section class="content-header">
        <ol class="breadcrumb">
            <li>
                <a href="index.php">
                    <i class="fa fa-home"></i>
                    <b>E-LIBRARY</b>
                </a>
            </li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Pengembalian Buku</h3>
                    </div>

                    <form action="" method="post">
                        <div class="box-body">
                            <div class="form-group">
                                <label>ID Sirkulasi</label>
                                <input type="text" name="id_sk" class="form-control" value="<?php echo $id_sk; ?>"
                                    readonly />
                            </div>
                            <div class="form-group">
                                <label>Tanggal Pengembalian</label>
                                <input type="date" name="tgl_pengembalian" class="form-control"
                                    value="<?php echo $tgl_dikembalikan; ?>" required />
                            </div>
                            <div class="form-group">
                                <label>Kondisi Buku</label>
                                <select name="kondisi_buku" id="kondisi" class="form-control" onchange="updateDenda()">
                                    <option value="Normal">Normal</option>
                                    <option value="Rusak Ringan">Rusak Ringan</option>
                                    <option value="Rusak Berat">Rusak Berat</option>
                                    <option value="Hilang">Hilang</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Denda</label>
                                <input type="number" name="denda" id="denda" class="form-control"
                                    value="<?php echo $denda; ?>" readonly />
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" name="submit" class="btn btn-info">Konfirmasi Pengembalian</button>
                            <a href="index.php?page=data_sirkul" class="btn btn-warning">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script>
        const dendaAwal = <?php echo $denda; ?>;
        const dendaRingan = <?php echo $denda_rusak_ringan; ?>;
        const dendaBerat = <?php echo $denda_rusak_berat; ?>;
        const dendaHilang = <?php echo $denda_hilang; ?>;

        function updateDenda() {
            const kondisi = document.getElementById("kondisi").value;
            let denda = dendaAwal;

            if (kondisi === "Rusak Ringan") {
                denda += dendaRingan;
            } else if (kondisi === "Rusak Berat") {
                denda += dendaBerat;
            } else if (kondisi === "Hilang") {
                denda += dendaHilang;
            }

            document.getElementById("denda").value = denda;
        }
    </script>

    <?php
    if (isset($_POST['submit'])) {
        $id_sk = mysqli_real_escape_string($koneksi, $_POST['id_sk']);
        $tgl_pengembalian = mysqli_real_escape_string($koneksi, $_POST['tgl_pengembalian']);
        $kondisi_buku = mysqli_real_escape_string($koneksi, $_POST['kondisi_buku']);

        // Hitung ulang denda di server-side untuk keamanan
        $terlambat = max(0, ceil((strtotime($tgl_pengembalian) - strtotime($tgl_kembali)) / (60 * 60 * 24)));
        $denda = $terlambat * $denda_per_hari;

        if ($kondisi_buku == "Rusak Ringan") {
            $denda += $denda_rusak_ringan;
        } elseif ($kondisi_buku == "Rusak Berat") {
            $denda += $denda_rusak_berat;
        } elseif ($kondisi_buku == "Hilang") {
            $denda += $denda_hilang;
        }

        $sql_update = "UPDATE tb_sirkulasi 
                       SET status='KEM', 
                           tgl_pengembalian='$tgl_pengembalian', 
                           kondisi_buku='$kondisi_buku', 
                           denda='$denda' 
                       WHERE id_sk='$id_sk'";

        if ($kondisi_buku !== 'Hilang') {
            $sql_update .= "; UPDATE tb_buku SET stok = stok + 1 WHERE id_buku='$id_buku'";
        }

        if (mysqli_multi_query($koneksi, $sql_update)) {
            echo "<script>alert('Buku berhasil dikembalikan!'); window.location.href='index.php?page=data_sirkul';</script>";
        } else {
            echo "<script>alert('Pengembalian gagal! Silakan coba lagi.');</script>";
        }
    }
}
?>