<section class="content-header">
	<h1 style="text-align:center;">
		Riwayat Peminjaman Buku
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

<!-- Main content -->
<section class="content">
	<div class="box box-primary">
		<!-- /.box-header -->
		<div class="box-body">
			<div class="table-responsive">
				<table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>No</th>
							<th>Buku</th>
							<th>Peminjam</th>
							<th>Tanggal Peminjaman</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = 1;
						$sql = $koneksi->query("SELECT 
                                    b.judul_buku, 
                                    a.id_anggota, 
                                    a.nama, 
                                    l.tgl_pinjam
                                FROM log_pinjam l 
                                INNER JOIN tb_buku b ON l.id_buku = b.id_buku
                                INNER JOIN tb_anggota a ON l.id_anggota = a.id_anggota 
                                ORDER BY l.tgl_pinjam ASC");

						while ($data = $sql->fetch_assoc()) {
							$tgl_pinjam = date("d-M-Y", strtotime($data['tgl_pinjam']));

							echo "<tr>
								<td>{$no}</td>
								<td>" . htmlspecialchars($data['judul_buku']) . "</td>
								<td>" . htmlspecialchars($data['id_anggota']) . " - " . htmlspecialchars($data['nama']) . "</td>
								<td>{$tgl_pinjam}</td>
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