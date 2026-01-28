<?php
session_start();
require '../koneksi.php';

if (!isset($_SESSION['user']) || $_SESSION['role'] != "Pegawai") {
    header("Location: ../index.php");
    exit;
}

$breadcrumbTitle = "Struktur Humas";
$subtitle = "Organisasi dan tim kehumasan BPS Kabupaten Bangkalan";

// Get filter parameters
$filterKategori = $_GET['kategori'] ?? null;
$filterSubKategori = $_GET['sub'] ?? null;

// Fetch data dari database
$skillData = [];
$qSkill = mysqli_query($koneksi, "SELECT id_skill, nama_skill FROM skill ORDER BY nama_skill ASC");
if ($qSkill) {
    while ($row = mysqli_fetch_assoc($qSkill)) {
        $skillData[$row['id_skill']] = $row['nama_skill'];
    }
}

$ppidData = [];
$qPPID = mysqli_query($koneksi, "SELECT id_ppid, nama_ppid FROM ppid ORDER BY nama_ppid ASC");
if ($qPPID) {
    while ($row = mysqli_fetch_assoc($qPPID)) {
        $ppidData[$row['id_ppid']] = $row['nama_ppid'];
    }
}

$haloPstData = [];
$qHaloPst = mysqli_query($koneksi, "SELECT id_halo_pst, nama_halo_pst FROM halo_pst ORDER BY nama_halo_pst ASC");
if ($qHaloPst) {
    while ($row = mysqli_fetch_assoc($qHaloPst)) {
        $haloPstData[$row['id_halo_pst']] = $row['nama_halo_pst'];
    }
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $breadcrumbTitle ?></title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>
* { font-family: Poppins, sans-serif; }

body {
  margin: 0;
  background: linear-gradient(180deg, #f8fafc, #eef2f7);
  padding: 32px;
  color: #0f172a;
}

.page-wrapper { max-width: 1200px; margin: auto; }

/* ===== BREADCRUMB ===== */
.breadcrumb-custom {
  display: flex;
  align-items: center;
  gap: 10px;
  font-size: 14px;
  margin-bottom: 24px;
}

.breadcrumb-custom i {
  background: #2563eb;
  color: #fff;
  padding: 8px;
  border-radius: 10px;
  font-size: 14px;
}

.breadcrumb-active {
  font-weight: 600;
  color: #0f172a;
}

/* ===== HEADER SECTION ===== */
.header {
  background: #fff;
  border-radius: 20px;
  padding: 28px 32px;
  box-shadow: 0 10px 30px rgba(15,23,42,.08);
  margin-bottom: 28px;
}

.header h2 {
  margin: 0 0 8px 0;
  font-size: 28px;
  font-weight: 700;
  color: #0f172a;
}

.header p {
  margin: 0;
  color: #64748b;
  font-size: 14px;
}

/* ===== TABS ===== */
.tabs-container {
  display: flex;
  gap: 8px;
  margin-bottom: 28px;
  border-bottom: 2px solid #e2e8f0;
  background: #fff;
  border-radius: 12px 12px 0 0;
  padding: 0 20px;
  box-shadow: 0 4px 12px rgba(15,23,42,.04);
}

.tab-button {
  background: none;
  border: none;
  padding: 16px 24px;
  font-size: 14px;
  font-weight: 600;
  color: #64748b;
  cursor: pointer;
  position: relative;
  transition: 0.25s ease;
}

.tab-button:hover {
  color: #0f172a;
}

.tab-button.active {
  color: #2563eb;
}

.tab-button.active::after {
  content: '';
  position: absolute;
  bottom: -2px;
  left: 0;
  right: 0;
  height: 2px;
  background: #2563eb;
}

.tab-content {
  display: none;
}

.tab-content.active {
  display: block;
}

/* ===== TAB 1: CANVA ORGANIGRAM ===== */
.organigram-container {
  background: #fff;
  border-radius: 0 12px 12px 12px;
  box-shadow: 0 4px 12px rgba(15,23,42,.04);
  overflow: hidden;
  display: flex;
  flex-direction: column;
}

.organigram-header {
  display: flex;
  justify-content: flex-start;
  align-items: center;
  padding: 24px 28px;
  border-bottom: 2px solid #f1f5f9;
  background: linear-gradient(90deg, #fff 0%, #f8fafc 100%);
}

.organigram-title {
  font-size: 18px;
  font-weight: 700;
  color: #0f172a;
  margin: 0;
  display: flex;
  align-items: center;
  gap: 10px;
}

.organigram-title i {
  color: #2563eb;
  font-size: 20px;
}

.btn-canva-link {
  background: none;
  border: none;
  padding: 8px 16px;
  border-radius: 8px;
  font-size: 12px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.25s ease;
  display: flex;
  align-items: center;
  gap: 6px;
  color: #2563eb;
  text-decoration: none;
}

.btn-canva-link:hover {
  background: #eff6ff;
  color: #1d4ed8;
}

.btn-canva-link img {
  width: 18px;
  height: 18px;
}

.canva-wrapper {
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #f8fafc;
  padding: 12px;
  min-height: 600px;
}

.canva-frame {
  width: 100%;
  height: 100%;
  border: none;
  border-radius: 8px;
  min-height: 600px;
  background: #fff;
}

@media (max-width: 1024px) {
  .canva-frame {
    min-height: 550px;
  }

  .canva-wrapper {
    min-height: 550px;
  }
}

@media (max-width: 768px) {
  .organigram-header {
    padding: 16px 12px;
    flex-direction: column;
    align-items: stretch;
  }

  .btn-edit-canva {
    justify-content: center;
  }

  .canva-frame {
    min-height: 500px;
  }

  .canva-wrapper {
    min-height: 500px;
    padding: 8px;
  }
}

/* ===== TAB 2: FILTERING ===== */
.filter-container {
  background: #fff;
  border-radius: 0 12px 12px 12px;
  padding: 28px;
  box-shadow: 0 4px 12px rgba(15,23,42,.04);
}

.filter-section {
  margin-bottom: 32px;
}

.filter-title {
  font-size: 16px;
  font-weight: 600;
  color: #0f172a;
  margin-bottom: 16px;
  display: flex;
  align-items: center;
  gap: 8px;
}

.filter-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
  gap: 12px;
  margin-bottom: 24px;
}

.filter-btn {
  background: #f1f5f9;
  border: 2px solid #e2e8f0;
  border-radius: 10px;
  padding: 12px 16px;
  font-size: 13px;
  font-weight: 500;
  color: #0f172a;
  cursor: pointer;
  transition: 0.25s ease;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 8px;
}

.filter-btn:hover {
  border-color: #2563eb;
  background: #eff6ff;
  color: #2563eb;
}

.filter-btn.active {
  background: #2563eb;
  color: #fff;
  border-color: #2563eb;
}

.badge-count {
  background: rgba(255,255,255,0.3);
  padding: 2px 8px;
  border-radius: 999px;
  font-size: 11px;
  font-weight: 600;
}

/* ===== RESULTS GRID ===== */
.results-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 20px;
  margin-top: 24px;
}

.result-card {
  background: #fff;
  border-radius: 12px;
  padding: 20px;
  border: 1px solid #e2e8f0;
  transition: 0.25s ease;
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.result-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 10px 30px rgba(15,23,42,.12);
  border-color: #2563eb;
}

.result-card-header {
  display: flex;
  align-items: center;
  gap: 12px;
}

.result-avatar {
  width: 50px;
  height: 50px;
  background: linear-gradient(135deg, #2563eb, #0ea5e9);
  color: #fff;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 600;
  font-size: 18px;
}

.result-info h4 {
  margin: 0;
  font-size: 15px;
  font-weight: 600;
  color: #0f172a;
}

.result-info p {
  margin: 0;
  font-size: 12px;
  color: #64748b;
}

.result-meta {
  display: flex;
  gap: 8px;
  flex-wrap: wrap;
  font-size: 12px;
}

.meta-badge {
  background: #eff6ff;
  color: #2563eb;
  padding: 4px 10px;
  border-radius: 6px;
  font-weight: 500;
}

.empty-state {
  text-align: center;
  padding: 40px 20px;
  color: #94a3b8;
}

.empty-state i {
  font-size: 48px;
  display: block;
  margin-bottom: 16px;
  opacity: 0.5;
}

.empty-state p {
  margin: 0;
  font-size: 14px;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
  .filter-container, .results-grid {
    grid-template-columns: 1fr;
  }
  
  .organigram-header {
    padding: 16px 12px;
    flex-direction: column;
    align-items: stretch;
    gap: 12px;
  }

  .organigram-title {
    font-size: 16px;
  }

  .btn-canva-link {
    justify-content: center;
  }

  .canva-frame {
    min-height: 500px;
  }

  .canva-wrapper {
    min-height: 500px;
    padding: 8px;
  }
  
  .tabs-container {
    padding: 0 12px;
  }
  
  .tab-button {
    padding: 12px 16px;
    font-size: 13px;
  }
  
  .filter-grid {
    grid-template-columns: 1fr;
  }
}

</style>
</head>

<body>
<div class="page-wrapper">

  <!-- BREADCRUMB -->
  <div class="breadcrumb-custom">
    <a href="index.php">
      <i class="bi bi-house-fill"></i>
    </a>
    <span>›</span>
    <span class="breadcrumb-active"><?= $breadcrumbTitle ?></span>
  </div>

  <!-- HEADER -->
  <div class="header">
    <h2><?= $breadcrumbTitle ?></h2>
    <p><?= $subtitle ?></p>
  </div>

  <!-- TABS -->
  <div class="tabs-container">
    <button class="tab-button active" data-tab="tab-gallery">
      <i class="bi bi-image"></i> Galeri Struktur
    </button>
    <button class="tab-button" data-tab="tab-filter">
      <i class="bi bi-funnel"></i> Filter Tim
    </button>
  </div>

  <!-- ===== TAB 1: CANVA ORGANIGRAM ===== -->
  <div id="tab-gallery" class="tab-content active">
    <div class="organigram-container">
      <div class="organigram-header">
        <h3 class="organigram-title">
          <i class="bi bi-diagram-3"></i> Struktur Organisasi Kehumasan
        </h3>
      </div>
      <div class="canva-wrapper">
        <iframe 
          class="canva-frame"
          src="https://www.canva.com/design/DAG_ftpWi2k/Zvddz08HAqisT44l2EM9lg/view?embed&mode=fullscreen" 
          allowfullscreen
          allow="autoplay">
        </iframe>
      </div>
    </div>
  </div>

  <!-- ===== TAB 2: FILTERING ===== -->
  <div id="tab-filter" class="tab-content">
    <div class="filter-container">

      <!-- KATEGORI UTAMA -->
      <div class="filter-section">
        <div class="filter-title">
          <i class="bi bi-collection"></i> Pilih Kategori
        </div>
        <div class="filter-grid">
          <button class="filter-btn <?= $filterKategori === 'skill' ? 'active' : '' ?>" 
                  data-category="skill"
                  onclick="filterByCategory('skill')">
            <span><i class="bi bi-star"></i> Skill</span>
          </button>
          <button class="filter-btn <?= $filterKategori === 'ppid' ? 'active' : '' ?>" 
                  data-category="ppid"
                  onclick="filterByCategory('ppid')">
            <span><i class="bi bi-file-earmark"></i> PPID</span>
          </button>
          <button class="filter-btn <?= $filterKategori === 'halopst' ? 'active' : '' ?>" 
                  data-category="halopst"
                  onclick="filterByCategory('halopst')">
            <span><i class="bi bi-telephone"></i> HALO PST</span>
          </button>
        </div>
      </div>

      <!-- SUB-KATEGORI (Dinamis berdasarkan kategori yang dipilih) -->
      <div id="subkategori-section" class="filter-section" style="display: <?= $filterKategori ? 'block' : 'none' ?>;">
        <div class="filter-title" id="subkategori-title"></div>
        <div id="subkategori-grid" class="filter-grid"></div>
      </div>

      <!-- HASIL FILTER -->
      <div id="results-section" style="display: <?= $filterKategori && $filterSubKategori ? 'block' : 'none' ?>;">
        <div class="filter-title">
          <i class="bi bi-people"></i> <span id="results-title">Hasil</span>
        </div>
        <div id="results-grid" class="results-grid"></div>
      </div>

    </div>
  </div>

</div>

<script>
// Data struktur Humas dari database
let humasData = {
  skill: {
    title: "Pilih Skill",
    items: []
  },
  ppid: {
    title: "Pilih Divisi PPID",
    items: []
  },
  halopst: {
    title: "Pilih Bagian HALO PST",
    items: []
  }
};

// Load data dari server saat page load
document.addEventListener('DOMContentLoaded', function() {
  loadCategoryData();
});

function loadCategoryData() {
  fetch('get_categories.php')
    .then(response => response.json())
    .then(data => {
      humasData = data;
      console.log('Data loaded:', humasData);
    })
    .catch(error => console.error('Error loading categories:', error));
}

function filterByCategory(category) {
  console.log('Filter by category:', category, humasData[category]);
  
  // Update tombol kategori
  document.querySelectorAll('.filter-btn').forEach(btn => {
    if (btn.getAttribute('data-category') === category) {
      btn.classList.add('active');
    } else if (btn.getAttribute('data-category')) {
      btn.classList.remove('active');
    }
  });

  // Reset sub-kategori
  document.getElementById('subkategori-section').style.display = 'block';
  document.getElementById('results-section').style.display = 'none';
  
  const categoryData = humasData[category];
  const subgrid = document.getElementById('subkategori-grid');
  subgrid.innerHTML = '';

  // Tampilkan sub-kategori
  document.getElementById('subkategori-title').textContent = categoryData.title;
  
  if (!categoryData.items || categoryData.items.length === 0) {
    subgrid.innerHTML = '<div style="color: #94a3b8; padding: 20px;">Tidak ada data</div>';
    return;
  }
  
  categoryData.items.forEach(item => {
    const btn = document.createElement('button');
    btn.className = 'filter-btn';
    btn.setAttribute('data-id', item.id);
    btn.innerHTML = `<span><i class="bi bi-tag"></i> ${item.name}</span><span class="badge-count">${item.count}</span>`;
    btn.onclick = (e) => filterBySubCategory(category, item.id, item.name);
    subgrid.appendChild(btn);
  });
}

function filterBySubCategory(category, itemId, itemName) {
  document.getElementById('results-section').style.display = 'block';
  
  const resultsGrid = document.getElementById('results-grid');
  const resultsTitle = document.getElementById('results-title');
  
  resultsTitle.textContent = `${itemName} (loading...)`;
  resultsGrid.innerHTML = '<div class="empty-state"><i class="bi bi-hourglass-split"></i><p>Memuat data...</p></div>';

  // Fetch data via AJAX
  fetch(`get_members.php?category=${category}&id=${itemId}`)
    .then(response => response.json())
    .then(data => {
      resultsTitle.textContent = `${itemName} (${data.members.length} orang)`;
      
      if (data.members.length === 0) {
        resultsGrid.innerHTML = '<div class="empty-state"><i class="bi bi-inbox"></i><p>Tidak ada anggota tim</p></div>';
      } else {
        resultsGrid.innerHTML = '';
        data.members.forEach(member => {
          const card = document.createElement('div');
          card.className = 'result-card';
          const initials = member.nama.split(' ').map(n => n[0]).join('').substring(0, 2);
          card.innerHTML = `
            <div class="result-card-header">
              <div class="result-avatar">${initials}</div>
              <div class="result-info">
                <h4>${member.nama}</h4>
                <p>${member.role || '-'}</p>
              </div>
            </div>
            <div class="result-meta">
              <span class="meta-badge"><i class="bi bi-envelope"></i> ${member.email || '-'}</span>
            </div>
          `;
          resultsGrid.appendChild(card);
        });
      }
    })
    .catch(error => {
      console.error('Error:', error);
      resultsGrid.innerHTML = '<div class="empty-state"><i class="bi bi-exclamation-triangle"></i><p>Error memuat data</p></div>';
    });
}

// Tab switching
document.querySelectorAll('.tab-button').forEach(button => {
  button.addEventListener('click', function() {
    const tabName = this.getAttribute('data-tab');
    
    document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
    
    this.classList.add('active');
    document.getElementById(tabName).classList.add('active');
  });
});
</script>

</body>
</html>
