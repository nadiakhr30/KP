<?php
session_start();
require '../koneksi.php';

header('Content-Type: application/json');

$response = [
    'skill' => [
        'title' => 'Pilih Skill',
        'items' => []
    ],
    'ppid' => [
        'title' => 'Pilih Divisi PPID',
        'items' => []
    ],
    'halopst' => [
        'title' => 'Pilih Bagian HALO PST',
        'items' => []
    ]
];

// Fetch skills dengan count
$querySkill = "
    SELECT s.id_skill, s.nama_skill, COUNT(us.nip) as count
    FROM skill s
    LEFT JOIN user_skill us ON s.id_skill = us.id_skill
    GROUP BY s.id_skill, s.nama_skill
    ORDER BY s.nama_skill ASC
";
$resultSkill = mysqli_query($koneksi, $querySkill);
while ($row = mysqli_fetch_assoc($resultSkill)) {
    $response['skill']['items'][] = [
        'id' => $row['id_skill'],
        'name' => $row['nama_skill'],
        'count' => (int)$row['count']
    ];
}

// Fetch PPID dengan count
$queryPPID = "
    SELECT p.id_ppid, p.nama_ppid, COUNT(u.nip) as count
    FROM ppid p
    LEFT JOIN user u ON p.id_ppid = u.id_ppid
    WHERE u.id_role = 2
    GROUP BY p.id_ppid, p.nama_ppid
    ORDER BY p.nama_ppid ASC
";
$resultPPID = mysqli_query($koneksi, $queryPPID);
while ($row = mysqli_fetch_assoc($resultPPID)) {
    $response['ppid']['items'][] = [
        'id' => $row['id_ppid'],
        'name' => $row['nama_ppid'],
        'count' => (int)$row['count']
    ];
}

// Fetch HALO PST dengan count
$queryHaloPst = "
    SELECT hp.id_halo_pst, hp.nama_halo_pst, COUNT(uhp.nip) as count
    FROM halo_pst hp
    LEFT JOIN user_halo_pst uhp ON hp.id_halo_pst = uhp.id_halo_pst
    GROUP BY hp.id_halo_pst, hp.nama_halo_pst
    ORDER BY hp.nama_halo_pst ASC
";
$resultHaloPst = mysqli_query($koneksi, $queryHaloPst);
while ($row = mysqli_fetch_assoc($resultHaloPst)) {
    $response['halopst']['items'][] = [
        'id' => $row['id_halo_pst'],
        'name' => $row['nama_halo_pst'],
        'count' => (int)$row['count']
    ];
}

echo json_encode($response);
mysqli_close($koneksi);
?>
