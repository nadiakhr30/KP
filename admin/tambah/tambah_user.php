<?php
include("../../koneksi.php");

$error = "";
$success = "";

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input
    $nip = trim($_POST["nip"] ?? "");
    $nama = trim($_POST["nama"] ?? "");
    $password = $_POST["password"] ?? "";
    $email = trim($_POST["email"] ?? "");
    $status = isset($_POST["status"]) ? (int)$_POST["status"] : "";
    $nomor_telepon = trim($_POST["nomor_telepon"] ?? "");
    $id_jabatan = isset($_POST["id_jabatan"]) ? (int)$_POST["id_jabatan"] : "";
    $id_role = isset($_POST["id_role"]) ? (int)$_POST["id_role"] : "";
    $id_ppid = isset($_POST["id_ppid"]) ? (int)$_POST["id_ppid"] : "";
    $id_halo_pst = isset($_POST["id_halo_pst"]) ? (int)$_POST["id_halo_pst"] : "";
    $skills_str = trim($_POST["skills"] ?? "");
    $skills = !empty($skills_str) ? array_map('intval', explode(',', $skills_str)) : [];
    
    // Validate required input
    if (empty($nip) || empty($nama) || empty($password) || empty($email) || empty($status) || empty($id_jabatan) || empty($id_role) || empty($id_ppid) || empty($id_halo_pst)) {
        $error = "Semua field harus diisi!";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid!";
    } else if (!empty($nomor_telepon) && !is_numeric($nomor_telepon)) {
        $error = "Nomor telepon harus berupa angka!";
    } else {
        // Check if email already exists
        $check_email_query = "SELECT email FROM user WHERE email = '" . mysqli_real_escape_string($koneksi, $email) . "'";
        $check_email_result = mysqli_query($koneksi, $check_email_query);
        
        if (mysqli_num_rows($check_email_result) > 0) {
            $error = "Email sudah ada.";
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert into database
            $query = "INSERT INTO user (nip, nama, password, email, status, nomor_telepon, id_jabatan, id_role) 
                      VALUES (
                        '" . mysqli_real_escape_string($koneksi, $nip) . "',
                        '" . mysqli_real_escape_string($koneksi, $nama) . "',
                        '" . mysqli_real_escape_string($koneksi, $hashed_password) . "',
                        '" . mysqli_real_escape_string($koneksi, $email) . "',
                        " . $status . ",
                        " . (!empty($nomor_telepon) ? "'" . mysqli_real_escape_string($koneksi, $nomor_telepon) . "'" : "NULL") . ",
                        " . $id_jabatan . ",
                        " . $id_role . "
                      )";
            
            if (mysqli_query($koneksi, $query)) {
                // Insert into user_ppid
                $insert_ppid_query = "INSERT INTO user_ppid (id_ppid, nip) VALUES (" . $id_ppid . ", '" . mysqli_real_escape_string($koneksi, $nip) . "')";
                
                if (!mysqli_query($koneksi, $insert_ppid_query)) {
                    $error = "Gagal menambahkan data PPID: " . mysqli_error($koneksi);
                } else {
                    // Insert into user_halo_pst
                    $insert_halo_pst_query = "INSERT INTO user_halo_pst (id_halo_pst, nip) VALUES (" . $id_halo_pst . ", '" . mysqli_real_escape_string($koneksi, $nip) . "')";
                    
                    if (!mysqli_query($koneksi, $insert_halo_pst_query)) {
                        $error = "Gagal menambahkan data Halo PST: " . mysqli_error($koneksi);
                    } else {
                        // Insert into user_skill
                        $skills_insert_success = true;
                        foreach ($skills as $id_skill) {
                            $insert_skill_query = "INSERT INTO user_skill (id_skill, nip) VALUES (" . $id_skill . ", '" . mysqli_real_escape_string($koneksi, $nip) . "')";
                            
                            if (!mysqli_query($koneksi, $insert_skill_query)) {
                                $error = "Gagal menambahkan data skill: " . mysqli_error($koneksi);
                                $skills_insert_success = false;
                                break;
                            }
                        }
                        
                        if ($skills_insert_success) {
                            $success = "User berhasil ditambahkan!";
                            // Redirect after 1 second
                            header("Refresh: 1; url=../index.php");
                        }
                    }
                }
            } else {
                $error = "Gagal menambahkan user: " . mysqli_error($koneksi);
            }
        }
    }
}

// Get jabatan data
$jabatan_query = "SELECT id_jabatan, nama_jabatan FROM jabatan";
$jabatan_result = mysqli_query($koneksi, $jabatan_query);
$jabatan_data = [];
if ($jabatan_result) {
    while ($row = mysqli_fetch_assoc($jabatan_result)) {
        $jabatan_data[] = $row;
    }
}

// Get role data
$role_query = "SELECT id_role, nama_role FROM role";
$role_result = mysqli_query($koneksi, $role_query);
$role_data = [];
if ($role_result) {
    while ($row = mysqli_fetch_assoc($role_result)) {
        $role_data[] = $row;
    }
}

// Get ppid data
$ppid_query = "SELECT id_ppid, nama_ppid FROM ppid";
$ppid_result = mysqli_query($koneksi, $ppid_query);
$ppid_data = [];
if ($ppid_result) {
    while ($row = mysqli_fetch_assoc($ppid_result)) {
        $ppid_data[] = $row;
    }
}

// Get halo_pst data
$halo_pst_query = "SELECT id_halo_pst, nama_halo_pst FROM halo_pst";
$halo_pst_result = mysqli_query($koneksi, $halo_pst_query);
$halo_pst_data = [];
if ($halo_pst_result) {
    while ($row = mysqli_fetch_assoc($halo_pst_result)) {
        $halo_pst_data[] = $row;
    }
}

// Get skill data
$skill_query = "SELECT id_skill, nama_skill FROM skill";
$skill_result = mysqli_query($koneksi, $skill_query);
$skill_data = [];
if ($skill_result) {
    while ($row = mysqli_fetch_assoc($skill_result)) {
        $skill_data[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah User</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <style>
        .skill-badge {
            display: inline-block;
            margin: 5px;
            padding: 8px 12px;
            background-color: #007bff;
            color: white;
            border-radius: 20px;
            font-size: 14px;
        }
        .skill-badge .remove-skill {
            cursor: pointer;
            margin-left: 8px;
            font-weight: bold;
        }
        .skill-badge .remove-skill:hover {
            color: #ffcccc;
        }
        #selected-skills {
            min-height: 50px;
            padding: 10px;
            background-color: #f8f9fa;
            border: 1px solid #ced4da;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Tambah User</h4>
                    </div>
                    <div class="card-body">
                        <?php if ($error): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?php echo htmlspecialchars($error); ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($success): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <?php echo htmlspecialchars($success); ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" action="">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="nip">NIP <span class="text-danger">*</span></label>
                                    <input 
                                        type="number" 
                                        class="form-control" 
                                        id="nip" 
                                        name="nip"
                                        placeholder="Masukkan NIP"
                                        required
                                        value="<?php echo isset($_POST["nip"]) ? htmlspecialchars($_POST["nip"]) : ''; ?>"
                                    >
                                </div>
                                
                                <div class="form-group col-md-6">
                                    <label for="nama">Nama <span class="text-danger">*</span></label>
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        id="nama" 
                                        name="nama"
                                        placeholder="Masukkan nama"
                                        required
                                        maxlength="255"
                                        value="<?php echo isset($_POST["nama"]) ? htmlspecialchars($_POST["nama"]) : ''; ?>"
                                    >
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="email">Email <span class="text-danger">*</span></label>
                                    <input 
                                        type="email" 
                                        class="form-control" 
                                        id="email" 
                                        name="email"
                                        placeholder="Masukkan email"
                                        required
                                        maxlength="255"
                                        value="<?php echo isset($_POST["email"]) ? htmlspecialchars($_POST["email"]) : ''; ?>"
                                    >
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="password">Password <span class="text-danger">*</span></label>
                                    <input 
                                        type="password" 
                                        class="form-control" 
                                        id="password" 
                                        name="password"
                                        placeholder="Masukkan password"
                                        required
                                        maxlength="255"
                                    >
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="nomor_telepon">Nomor Telepon</label>
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        id="nomor_telepon" 
                                        name="nomor_telepon"
                                        placeholder="Masukkan nomor telepon (opsional)"
                                        value="<?php echo isset($_POST["nomor_telepon"]) ? htmlspecialchars($_POST["nomor_telepon"]) : ''; ?>"
                                    >
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="status">Status <span class="text-danger">*</span></label>
                                    <select class="form-control" id="status" name="status" required>
                                        <option value="">-- Pilih Status --</option>
                                        <option value="1" <?php echo isset($_POST["status"]) && $_POST["status"] == '1' ? 'selected' : ''; ?>>Aktif</option>
                                        <option value="0" <?php echo isset($_POST["status"]) && $_POST["status"] == '0' ? 'selected' : ''; ?>>Tidak Aktif</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="id_jabatan">Jabatan <span class="text-danger">*</span></label>
                                    <select class="form-control" id="id_jabatan" name="id_jabatan" required>
                                        <option value="">-- Pilih Jabatan --</option>
                                        <?php foreach ($jabatan_data as $jabatan): ?>
                                            <option value="<?php echo $jabatan['id_jabatan']; ?>" <?php echo isset($_POST["id_jabatan"]) && $_POST["id_jabatan"] == $jabatan['id_jabatan'] ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($jabatan['nama_jabatan']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="id_role">Role <span class="text-danger">*</span></label>
                                    <select class="form-control" id="id_role" name="id_role" required>
                                        <option value="">-- Pilih Role --</option>
                                        <?php foreach ($role_data as $role): ?>
                                            <option value="<?php echo $role['id_role']; ?>" <?php echo isset($_POST["id_role"]) && $_POST["id_role"] == $role['id_role'] ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($role['nama_role']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="id_ppid">PPID Team <span class="text-danger">*</span></label>
                                    <select class="form-control" id="id_ppid" name="id_ppid" required>
                                        <option value="">-- Pilih PPID Team --</option>
                                        <?php foreach ($ppid_data as $ppid): ?>
                                            <option value="<?php echo $ppid['id_ppid']; ?>" <?php echo isset($_POST["id_ppid"]) && $_POST["id_ppid"] == $ppid['id_ppid'] ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($ppid['nama_ppid']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="id_halo_pst">Halo PST Team <span class="text-danger">*</span></label>
                                    <select class="form-control" id="id_halo_pst" name="id_halo_pst" required>
                                        <option value="">-- Pilih Halo PST Team --</option>
                                        <?php foreach ($halo_pst_data as $halo_pst): ?>
                                            <option value="<?php echo $halo_pst['id_halo_pst']; ?>" <?php echo isset($_POST["id_halo_pst"]) && $_POST["id_halo_pst"] == $halo_pst['id_halo_pst'] ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($halo_pst['nama_halo_pst']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="skill_select">Skills</label>
                                <select class="form-control" id="skill_select">
                                    <option value="">-- Pilih Skill --</option>
                                    <?php foreach ($skill_data as $skill): ?>
                                        <option value="<?php echo $skill['id_skill']; ?>" data-name="<?php echo htmlspecialchars($skill['nama_skill']); ?>">
                                            <?php echo htmlspecialchars($skill['nama_skill']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Skill yang Dipilih <span class="text-danger">*</span></label>
                                <div id="selected-skills"></div>
                                <input type="hidden" id="skills_input" name="skills" value="">
                            </div>
                            
                            <div class="form-group mt-4">
                                <button type="submit" class="btn btn-primary mr-2">
                                    Simpan
                                </button>
                                <a href="../index.php" class="btn btn-secondary">
                                    Batal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let selectedSkills = {};

        document.getElementById('skill_select').addEventListener('change', function() {
            const skillId = this.value;
            const skillName = this.options[this.selectedIndex].getAttribute('data-name');

            if (skillId && !selectedSkills[skillId]) {
                selectedSkills[skillId] = skillName;
                updateSkillDisplay();
                this.value = '';
            }
        });

        function updateSkillDisplay() {
            const container = document.getElementById('selected-skills');
            const input = document.getElementById('skills_input');

            container.innerHTML = '';
            const skillIds = Object.keys(selectedSkills);

            skillIds.forEach(skillId => {
                const skillName = selectedSkills[skillId];
                const badge = document.createElement('span');
                badge.className = 'skill-badge';
                badge.innerHTML = skillName + ' <span class="remove-skill" onclick="removeSkill(' + skillId + ')">×</span>';
                container.appendChild(badge);
            });

            input.value = skillIds.join(',');
        }

        function removeSkill(skillId) {
            delete selectedSkills[skillId];
            updateSkillDisplay();
        }
    </script>
</body>
</html>