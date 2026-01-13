<?php
header('Content-Type: application/json');
include "../koneksi.php";

$data = [];

$q = mysqli_query($koneksi, "
  SELECT 
  j.id_jadwal,
  j.topik,
  j.judul_kegiatan,
  j.tanggal_penugasan,
  j.target_rilis,
  j.tim,
  j.keterangan,
  j.status,
  j.dokumentasi,
  j.link_instagram,
  j.link_facebook,
  j.link_youtube,
  j.link_website,

  u1.nama AS pic_desain,
  u2.nama AS pic_medsos,
  u3.nama AS pic_narasi

FROM jadwal j
LEFT JOIN user u1 ON j.pic_desain = u1.id_user
LEFT JOIN user u2 ON j.pic_medsos = u2.id_user
LEFT JOIN user u3 ON j.pic_narasi = u3.id_user

WHERE j.target_rilis IS NOT NULL
");

while ($row = mysqli_fetch_assoc($q)) {

  if ($row['status'] == 0) $color = '#e84118';
  else if ($row['status'] == 1) $color = '#fbc531';
  else if ($row['status'] == 2) $color = '#44bd32';
  else $color = '#718093';

  $data[] = [
    'id'    => $row['id_jadwal'],
    'title' => $row['judul_kegiatan'],
    'start' => $row['target_rilis'],
    'color' => $color,
    'extendedProps' => [
        'topik' => $row['topik'],
        'tanggal_penugasan' => $row['tanggal_penugasan'],
        'tim' => $row['tim'],
        'pic_desain' => $row['pic_desain'],
        'pic_medsos' => $row['pic_medsos'],
        'pic_narasi' => $row['pic_narasi'],
        'keterangan' => $row['keterangan'],
        'status' => $row['status'],
        'dokumentasi' => $row['dokumentasi'],
        'link_instagram' => $row['link_instagram'],
        'link_facebook' => $row['link_facebook'],
        'link_youtube' => $row['link_youtube'],
        'link_website' => $row['link_website']
    ]
  ];
}

echo json_encode($data);