<?php
//kode 9 digit untuk id transaksi sirkulasi
$carikode = mysqli_query($koneksi, "SELECT id_sk FROM tb_sirkulasi ORDER BY id_sk DESC");
$datakode = mysqli_fetch_array($carikode);
$kode = $datakode['id_sk'];
$urut = substr($kode, 1, 3);
$tambah = (int) $urut + 1;

// Format ID sirkulasi
if (strlen($tambah) == 1) {
	$format = "S" . "00" . $tambah;
} else if (strlen($tambah) == 2) {
	$format = "S" . "0" . $tambah;
} else {
	$format = "S" . $tambah;
}
?>
<section class="content-header">
	<h1>
		Sirkulasi
		<small>Buku</small>
	</h1>
	<ol class="breadcrumb">
		<li>
			<a href="index.php">
				<i class="fa fa-home"></i>
				<b>E-LIBRARY</b>
			</a>
		</li>
	</ol>
</section><br>

<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="box box-info">
				<div class="box-header with-border">
					<h3 class="box-title">Tambah Peminjaman</h3>
				</div>

				<!-- Formulir Peminjaman -->
				<form action="" method="post">
					<div class="box-body">
						<div class="form-group">
							<label>Id Sirkulasi</label>
							<input type="text" name="id_sk" class="form-control" value="<?php echo $format; ?>"
								readonly />
						</div>

						<div class="form-group">
							<label>Nama Peminjam</label>
							<select name="id_anggota" class="form-control select2" required>
								<option value="" selected>-- Pilih --</option>
								<?php
								$query = "SELECT * FROM tb_anggota";
								$hasil = mysqli_query($koneksi, $query);
								while ($row = mysqli_fetch_array($hasil)) {
									echo "<option value='{$row['id_anggota']}'>{$row['id_anggota']} - {$row['nama']}</option>";
								}
								?>
							</select>
						</div>

						<div class="form-group">
							<label>Buku</label>
							<select name="id_buku" class="form-control select2" required>
								<option value="" selected>-- Pilih --</option>
								<?php
								$query = "SELECT * FROM tb_buku WHERE stok > 0"; // Hanya buku yang masih ada stok
								$hasil = mysqli_query($koneksi, $query);
								while ($row = mysqli_fetch_array($hasil)) {
									echo "<option value='{$row['id_buku']}'>{$row['id_buku']} - {$row['judul_buku']} (Stok: {$row['stok']})</option>";
								}
								?>
							</select>
						</div>

						<div class="form-group">
							<label>Tgl Pinjam</label>
							<input type="date" name="tgl_pinjam" class="form-control" required />
						</div>

					</div>

					<div class="box-footer">
						<input type="submit" name="Simpan" value="Simpan" class="btn btn-info">
						<a href="?page=data_sirkul" class="btn btn-warning">Batal</a>
					</div>
				</form>
			</div>
		</div>
	</div>
</section>

<?php
if (isset($_POST['Simpan'])) {
	$id_buku = $_POST['id_buku'];
	$id_anggota = $_POST['id_anggota'];
	$tgl_p = $_POST['tgl_pinjam'];
	$tgl_k = date('Y-m-d', strtotime('+7 days', strtotime($tgl_p))); // Tgl kembali otomatis 7 hari dari tgl pinjam

	// Cek stok buku sebelum peminjaman
	$cek_stok = mysqli_query($koneksi, "SELECT stok FROM tb_buku WHERE id_buku='$id_buku'");
	$data_stok = mysqli_fetch_assoc($cek_stok);

	if ($data_stok['stok'] > 0) {
		// Simpan data peminjaman ke tb_sirkulasi
		$sql_simpan = "INSERT INTO tb_sirkulasi (id_sk, id_buku, id_anggota, tgl_pinjam, status, tgl_kembali) VALUES (
			'" . $_POST['id_sk'] . "',
			'" . $_POST['id_buku'] . "',
			'" . $_POST['id_anggota'] . "',
			'" . $_POST['tgl_pinjam'] . "',
			'PIN',
			'" . $tgl_k . "');";

		// Simpan log peminjaman
		$sql_simpan .= "INSERT INTO log_pinjam (id_buku, id_anggota, tgl_pinjam) VALUES (
			'" . $_POST['id_buku'] . "',
			'" . $_POST['id_anggota'] . "',
			'" . $_POST['tgl_pinjam'] . "');";

		// Kurangi stok buku
		$sql_simpan .= "UPDATE tb_buku SET stok = stok - 1 WHERE id_buku='$id_buku';";

		$query_simpan = mysqli_multi_query($koneksi, $sql_simpan);
		mysqli_close($koneksi);

		if ($query_simpan) {
			echo "<script>
				Swal.fire({title: 'Peminjaman Berhasil', icon: 'success', confirmButtonText: 'OK'})
				.then((result) => {
					if (result.value) {
						window.location = 'index.php?page=data_sirkul';
					}
				});
			</script>";
		} else {
			echo "<script>
				Swal.fire({title: 'Gagal Melakukan Peminjaman', icon: 'error', confirmButtonText: 'OK'})
				.then((result) => {
					if (result.value) {
						window.location = 'index.php?page=add_sirkul';
					}
				});
			</script>";
		}
	} else {
		// Jika stok habis, munculkan alert
		echo "<script>
			Swal.fire({title: 'Stok buku habis!', text: 'Silakan pilih buku lain.', icon: 'warning', confirmButtonText: 'OK'})
			.then((result) => {
				if (result.value) {
					window.location = 'index.php?page=add_sirkul';
				}
			});
		</script>";
	}
}
?>