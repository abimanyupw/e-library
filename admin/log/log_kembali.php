<?php
// Pastikan koneksi ke database sudah ada
if (!$koneksi) {
	die("Koneksi database gagal: " . mysqli_connect_error());
}
?>

<section class="content-header">
	<h1 style="text-align:center;">Riwayat Pengembalian Buku</h1>
	<ol class="breadcrumb">
		<li><a href="index.php"><i class="fa fa-home"></i> <b>E-LIBRARY</b></a></li>
	</ol>
</section><br>

<section class="content">
	<div class="box box-primary">
		<div class="box-body">
			<div class="table-responsive">
				<table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>No</th>
							<th>ID Pengembalian</th>
							<th>Buku</th>
							<th>Peminjam</th>
							<th>Jatuh Tempo</th>
							<th>Tgl Dikembalikan</th>
							<th>Kondisi Buku</th>
							<th>Status</th>
							<th>Denda</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = 1;
						$sql = $koneksi->query("SELECT 
                                s.id_sk,
                                b.judul_buku, 
                                a.id_anggota, 
                                a.nama, 
                                s.tgl_kembali, 
                                s.tgl_dikembalikan,
                                COALESCE(s.kondisi_buku, 'Tidak Diketahui') AS kondisi_buku,
                                s.denda
                            FROM tb_sirkulasi s 
                            LEFT JOIN tb_buku b ON s.id_buku = b.id_buku
                            LEFT JOIN tb_anggota a ON s.id_anggota = a.id_anggota 
                            WHERE s.status = 'KEM' 
                            ORDER BY s.tgl_dikembalikan ASC");

						while ($data = $sql->fetch_assoc()) {
							$tgl_kembali = !empty($data['tgl_kembali']) ? date("d-M-Y", strtotime($data['tgl_kembali'])) : '-';
							$tgl_dikembalikan = (!empty($data['tgl_dikembalikan']) && $data['tgl_dikembalikan'] != '0000-00-00')
								? date("d-M-Y H:i:s", strtotime($data['tgl_dikembalikan']))
								: '-';

							$terlambat_hari = (!empty($data['tgl_dikembalikan']) && strtotime($data['tgl_dikembalikan']) > strtotime($data['tgl_kembali']))
								? ceil((strtotime($data['tgl_dikembalikan']) - strtotime($data['tgl_kembali'])) / (60 * 60 * 24))
								: 0;

							$status = ($terlambat_hari > 0)
								? "<span class='badge badge-danger'>Terlambat $terlambat_hari hari</span>"
								: "<span class='badge badge-success'>Tepat Waktu</span>";

							$denda = number_format($data['denda'], 0, ',', '.');

							echo "<tr>
                                <td>{$no}</td>
                                <td>" . htmlspecialchars($data['id_sk']) . "</td>
                                <td>" . htmlspecialchars($data['judul_buku'] ?? 'Tidak Diketahui') . "</td>
                                <td>" . htmlspecialchars($data['id_anggota'] ?? 'Tidak Diketahui') . " - " . htmlspecialchars($data['nama'] ?? 'Tidak Diketahui') . "</td>
                                <td>{$tgl_kembali}</td>
                                <td>{$tgl_dikembalikan}</td>
                                <td>" . htmlspecialchars($data['kondisi_buku']) . "</td>
                                <td>{$status}</td>
                                <td>Rp. {$denda}</td>
                            </tr>";
							$no++;
						}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</section>