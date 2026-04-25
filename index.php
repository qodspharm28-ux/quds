<?php
// معلومات الاتصال بقاعدة البيانات
include 'db.php';
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Pharma Qods - Professional Inventory</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>
  <style>
    :root {
      --bg: #f4f7fb;
      --card: #ffffff;
      --text: #163042;
      --muted: #6b7c93;
      --primary: #1f8a70;
      --primary-dark: #166b56;
      --accent: #2f80ed;
      --danger: #d64545;
      --success: #2ea44f;
      --line: #e5eaf1;
      --shadow: 0 18px 40px rgba(22, 48, 66, 0.08);
      --radius: 18px;
    }

    * { box-sizing: border-box; }
    html, body { margin: 0; padding: 0; }
    body {
      font-family: "Tajawal", system-ui, -apple-system, Segoe UI, Arial, sans-serif;
      background: radial-gradient(circle at top, #ffffff 0%, var(--bg) 42%, #eaf1f8 100%);
      color: var(--text);
      min-height: 100vh;
    }

    header {
      background: linear-gradient(135deg, var(--primary-dark), var(--primary));
      color: #fff;
      padding: 34px 18px;
      box-shadow: 0 14px 34px rgba(22, 48, 66, 0.18);
    }

    .header-inner {
      max-width: 1200px;
      margin: 0 auto;
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      justify-content: space-between;
      gap: 14px;
    }

    .brand h1 { margin: 0; font-size: 2rem; font-weight: 800; letter-spacing: 0.3px; }
    .brand p  { margin: 8px 0 0; opacity: 0.92; font-size: 1rem; line-height: 1.6; }

    .status-pill {
      padding: 12px 16px;
      border-radius: 999px;
      background: rgba(255,255,255,0.12);
      backdrop-filter: blur(8px);
      display: inline-flex;
      align-items: center;
      gap: 10px;
      font-weight: 700;
      border: 1px solid rgba(255,255,255,0.16);
      max-width: 100%;
    }

    .container { max-width: 1200px; margin: 0 auto; padding: 22px 16px 48px; }

    .grid { display: grid; grid-template-columns: repeat(12, 1fr); gap: 18px; }

    .card {
      background: var(--card);
      border: 1px solid rgba(229,234,241,0.95);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      overflow: hidden;
    }

    .card-hd {
      padding: 18px 20px;
      border-bottom: 1px solid var(--line);
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 12px;
      flex-wrap: wrap;
    }

    .card-hd h2 { margin: 0; font-size: 1.1rem; }
    .card-bd { padding: 18px 20px 20px; }

    .span-12 { grid-column: span 12; }

    .muted  { color: var(--muted); }
    .small  { font-size: 0.92rem; }

    .actions {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      align-items: center;
    }

    button {
      border: 0;
      cursor: pointer;
      border-radius: 12px;
      padding: 12px 16px;
      font-family: inherit;
      font-weight: 700;
      font-size: 0.95rem;
      transition: transform 0.18s ease, box-shadow 0.18s ease, opacity 0.18s ease;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
    }

    button:hover  { transform: translateY(-1px); }
    button:active { transform: translateY(0); }
    button:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }

    .btn-primary {
      color: #fff;
      background: linear-gradient(135deg, var(--primary), var(--primary-dark));
      box-shadow: 0 12px 26px rgba(31, 138, 112, 0.26);
    }

    .btn-secondary {
      color: var(--text);
      background: #edf3f8;
      border: 1px solid #dfe7ee;
    }

    .btn-export {
      color: #fff;
      background: linear-gradient(135deg, #2f80ed, #1a5fbf);
      box-shadow: 0 12px 26px rgba(47, 128, 237, 0.26);
    }

    .btn-mini {
      width: 34px;
      height: 34px;
      padding: 0;
      border-radius: 10px;
      font-size: 1rem;
    }

    .scanner-shell {
      border: 2px dashed #b9d8cf;
      background: linear-gradient(180deg, #fbfffd, #f6fbf9);
      border-radius: 16px;
      padding: 16px;
      margin-top: 14px;
    }

    #scannerWrap { display: none; }
    #video {
      width: 100%;
      max-width: 520px;
      border-radius: 16px;
      background: #000;
      display: block;
      margin: 0 auto;
      box-shadow: 0 16px 32px rgba(0,0,0,0.18);
    }

    .summary {
      display: grid;
      grid-template-columns: repeat(4, minmax(0, 1fr));
      gap: 12px;
    }

    .summary-card {
      border: 1px solid var(--line);
      border-radius: 16px;
      background: linear-gradient(180deg, #ffffff, #f8fbfd);
      padding: 16px;
      min-height: 104px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      gap: 8px;
    }

    .summary-card .label { color: var(--muted); font-size: 0.92rem; }
    .summary-card .value { font-size: 1.6rem; font-weight: 800; line-height: 1; }

    .summary-card.good    .value { color: var(--success); }
    .summary-card.warn    .value { color: var(--danger); }
    .summary-card.info    .value { color: var(--accent); }
    .summary-card.neutral .value { color: var(--text); }

    .table-wrap {
      overflow: auto;
      border: 1px solid var(--line);
      border-radius: 16px;
      background: #fff;
    }

    table { width: 100%; border-collapse: collapse; min-width: 920px; }

    thead th {
      position: sticky;
      top: 0;
      background: linear-gradient(135deg, #1f8a70, #166b56);
      color: #fff;
      text-align: center;
      padding: 14px 12px;
      font-size: 0.96rem;
      white-space: nowrap;
      z-index: 1;
    }

    tbody td {
      border-bottom: 1px solid var(--line);
      padding: 12px 10px;
      text-align: center;
      vertical-align: middle;
      background: #fff;
    }

    tbody tr:hover   td { background: #f7fbfa; }
    tbody tr.locked  td { background: #f3f7f6; }

    td.name-cell { text-align: right; min-width: 280px; }

    .product-name { font-weight: 700; font-size: 1rem; margin-bottom: 6px; }

    .tag {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 5px 10px;
      border-radius: 999px;
      font-size: 0.82rem;
      font-weight: 700;
    }

    .tag.ok      { color: #116b2e; background: #e8f7ed; }
    .tag.missing { color: #9b2d2d; background: #fdecec; }
    .tag.locked  { color: #7a5a11; background: #fff5dd; }

    .qty-box {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      min-width: 72px;
      padding: 9px 14px;
      border-radius: 12px;
      background: #eefbf4;
      color: #116b2e;
      font-weight: 800;
    }

    .delta { font-weight: 800; font-size: 1rem; }
    .delta.pos  { color: var(--success); }
    .delta.neg  { color: var(--danger); }
    .delta.zero { color: var(--muted); }

    .stepper {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      justify-content: center;
    }

    .stepper .delta-view {
      min-width: 52px;
      text-align: center;
      font-weight: 800;
      padding: 8px 10px;
      border-radius: 12px;
      border: 1px solid var(--line);
      background: #fff;
    }

    .checkbox-wrap {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      justify-content: center;
      font-weight: 700;
    }

    input[type="checkbox"] {
      width: 20px;
      height: 20px;
      cursor: pointer;
      accent-color: var(--primary);
    }

    .state-box {
      margin-top: 12px;
      padding: 14px 16px;
      border-radius: 14px;
      border: 1px solid var(--line);
      background: #fafcff;
      color: var(--muted);
      line-height: 1.7;
      white-space: pre-wrap;
    }

    .state-box.error   { border-color: #f0c1c1; background: #fff7f7; color: #b43939; }
    .state-box.success { border-color: #ccebd4; background: #f6fff8; color: #22733f; }

    .empty-state { padding: 24px 16px; text-align: center; color: var(--muted); }

    .hint-list { margin: 0; padding: 0; list-style: none; display: grid; gap: 10px; }
    .hint-list li { padding: 10px 12px; background: #f8fbfd; border: 1px solid var(--line); border-radius: 12px; }

    /* ───── filter bar ───── */
    .filter-bar {
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
      align-items: center;
      margin-bottom: 14px;
    }

    .filter-bar input {
      flex: 1;
      min-width: 180px;
      padding: 10px 14px;
      border: 1px solid var(--line);
      border-radius: 12px;
      font-family: inherit;
      font-size: 0.95rem;
      outline: none;
      transition: border-color 0.2s;
    }

    .filter-bar input:focus { border-color: var(--primary); }

    .filter-btn {
      padding: 10px 16px;
      border-radius: 12px;
      border: 1px solid var(--line);
      background: #edf3f8;
      font-family: inherit;
      font-weight: 700;
      font-size: 0.9rem;
      cursor: pointer;
      transition: background 0.18s;
    }

    .filter-btn.active { background: var(--primary); color: #fff; border-color: var(--primary); }

    @media (max-width: 980px) {
      .summary { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }

    @media (max-width: 620px) {
      .brand h1  { font-size: 1.55rem; }
      .summary   { grid-template-columns: 1fr; }
      .card-hd, .card-bd { padding-inline: 14px; }
      .actions   { width: 100%; }
      button     { width: 100%; }
    }
  </style>
</head>
<body>

<header>
  <div class="header-inner">
    <div class="brand">
      <h1>Pharma Qods</h1>
      <p>نظام جرد مخزون الصيدلية: قراءة QR، فتح ملف الفئة، ومطابقة المنتجات مع قاعدة <strong>list</strong> بشكل تلقائي.</p>
    </div>
    <div class="status-pill" id="topStatus">جاهز للتحميل</div>
  </div>
</header>

<main class="container">
  <div class="grid">

    <!-- ─── Control Panel ─── -->
    <section class="card span-12">
      <div class="card-hd">
        <h2>تشغيل النظام</h2>
        <div class="actions">
          <button class="btn-primary"    id="startScanBtn">📷 مسح QR</button>
          <button class="btn-secondary"  id="reloadDbBtn">🔄 إعادة تحميل list</button>
          <button class="btn-secondary"  id="uploadDbBtnTrigger">📤 رفع list</button>
          <button class="btn-export"     id="exportAllBtn"      disabled>📥 تصدير كل المنتجات</button>
          <button class="btn-export"     id="exportModifiedBtn" disabled>📥 تصدير المعدل فقط</button>
          <a href="all-products.php" style="text-decoration:none; color:inherit; padding:12px 16px; background:#edf3f8; border:1px solid #dfe7ee; border-radius:12px; font-weight:700; display:inline-flex; align-items:center; gap:8px;">
            📦 عرض كل المنتجات
          </a>
        </div>
      </div>
      <input type="file" id="dbFileUpload" style="display:none;" accept=".xlsx,.xls" onchange="handleDatabaseUpload(event)">
      <div class="card-bd">
        <div class="scanner-shell">
          <div id="scannerWrap">
            <video id="video" playsinline muted></video>
            <canvas id="canvas" style="display:none;"></canvas>
          </div>
          <div id="scanResult" class="state-box">لم يتم مسح أي QR بعد.</div>
        </div>
      </div>
    </section>

    <!-- ─── Summary ─── -->
    <section class="card span-12">
      <div class="card-hd">
        <h2>ملخص الجرد</h2>
        <span class="muted small" id="currentFileLabel">الملف الحالي: —</span>
      </div>
      <div class="card-bd">
        <div class="summary">
          <div class="summary-card neutral">
            <div class="label">عدد المنتجات</div>
            <div class="value" id="statProducts">0</div>
          </div>
          <div class="summary-card good">
            <div class="label">إجمالي الزيادة</div>
            <div class="value" id="statPositive">+0</div>
          </div>
          <div class="summary-card warn">
            <div class="label">إجمالي النقص</div>
            <div class="value" id="statNegative">-0</div>
          </div>
          <div class="summary-card info">
            <div class="label">المنتجات المفقودة</div>
            <div class="value" id="statMissing">0</div>
          </div>
        </div>
      </div>
    </section>

    <!-- ─── Products Table ─── -->
    <section class="card span-12">
      <div class="card-hd">
        <h2>قائمة المنتجات</h2>
        <span class="muted small">زر + أو − يغيّر الفرق، والتأكيد يقفل السطر.</span>
      </div>
      <div class="card-bd">

        <!-- Filter Bar -->
        <div class="filter-bar">
          <input type="text" id="searchInput" placeholder="🔍 ابحث عن منتج...">
          <button class="filter-btn active" id="filterAll"      onclick="setFilter('all')">الكل</button>
          <button class="filter-btn"        id="filterModified" onclick="setFilter('modified')">المعدل فقط</button>
          <button class="filter-btn"        id="filterMissing"  onclick="setFilter('missing')">غير موجود</button>
        </div>

        <div class="table-wrap">
          <table>
            <thead>
              <tr>
                <th>اسم المنتج</th>
                <th>الكمية من list</th>
                <th>الفرق</th>
                <th>الكمية بعد التعديل</th>
                <th>التعديل</th>
                <th>تأكيد</th>
              </tr>
            </thead>
            <tbody id="productsBody"></tbody>
          </table>
        </div>
        <div id="emptyState" class="empty-state">امسح QR لعرض المنتجات الخاصة بالملف المرتبط به.</div>
      </div>
    </section>

    <!-- ─── Hints ─── -->
    <section class="card span-12">
      <div class="card-hd"><h2>ملاحظات التشغيل</h2></div>
      <div class="card-bd">
        <ul class="hint-list">
          <li>ضع ملفات الفئات مثل <strong>a-01.xlsx</strong> داخل مجلد <strong>list/</strong>.</li>
          <li>ضع قاعدة المخزون الشاملة داخل <strong>list/list.xlsx</strong> أو <strong>list/list.xls</strong>.</li>
          <li>شغّل الصفحة عبر <strong>localhost</strong> أو أي سيرفر محلي؛ الفتح المباشر من الملف قد يمنع القراءة.</li>
          <li>بعد الجرد، استخدم أزرار <strong>التصدير</strong> لحفظ النتيجة كملف Excel.</li>
        </ul>
      </div>
    </section>

  </div>
</main>

<script>
// ═══════════════════════════════════════════════════
//  CONFIG
// ═══════════════════════════════════════════════════
const CONFIG = {
  categoryFolder: 'list',
  mainDbCandidates: [
    'list/list.xlsx',
    'list/list.xls',
    'list.xlsx',
    'list.xls'
  ],
  categoryExtensions: ['xlsx', 'xls']
};

// ═══════════════════════════════════════════════════
//  STATE
// ═══════════════════════════════════════════════════
const state = {
  stockMap      : new Map(),
  currentFile   : '',
  currentProducts: [],
  deltas        : new Map(),
  verified      : new Set(),
  scannerActive : false,
  cameraStream  : null,
  activeFilter  : 'all',   // 'all' | 'modified' | 'missing'
  searchQuery   : ''
};

// ═══════════════════════════════════════════════════
//  DOM REFS
// ═══════════════════════════════════════════════════
const els = {
  startScanBtn      : document.getElementById('startScanBtn'),
  reloadDbBtn       : document.getElementById('reloadDbBtn'),
  uploadDbBtnTrigger: document.getElementById('uploadDbBtnTrigger'),
  exportAllBtn      : document.getElementById('exportAllBtn'),
  exportModifiedBtn : document.getElementById('exportModifiedBtn'),
  scannerWrap       : document.getElementById('scannerWrap'),
  video             : document.getElementById('video'),
  canvas            : document.getElementById('canvas'),
  scanResult        : document.getElementById('scanResult'),
  topStatus         : document.getElementById('topStatus'),
  currentFileLabel  : document.getElementById('currentFileLabel'),
  productsBody      : document.getElementById('productsBody'),
  emptyState        : document.getElementById('emptyState'),
  statProducts      : document.getElementById('statProducts'),
  statPositive      : document.getElementById('statPositive'),
  statNegative      : document.getElementById('statNegative'),
  statMissing       : document.getElementById('statMissing'),
  searchInput       : document.getElementById('searchInput')
};

const canvasContext = els.canvas.getContext('2d', { willReadFrequently: true });

// ═══════════════════════════════════════════════════
//  UTILITIES
// ═══════════════════════════════════════════════════
function normalizeText(value) {
  return String(value ?? '')
    .normalize('NFKD')
    .replace(/[\u0300-\u036f]/g, '')
    .replace(/\s+/g, ' ')
    .trim()
    .toLowerCase();
}

function stripExtension(fileName) {
  return String(fileName ?? '').replace(/\.(xlsx|xls)$/i, '').trim();
}

function getBaseName(filePath) {
  const raw = String(filePath ?? '').trim();
  const lastPart = raw.split('/').pop().split('\\').pop();
  return stripExtension(lastPart);
}

function escapeHtml(value) {
  return String(value ?? '')
    .replace(/&/g,  '&amp;')
    .replace(/</g,  '&lt;')
    .replace(/>/g,  '&gt;')
    .replace(/"/g,  '&quot;')
    .replace(/'/g,  '&#39;');
}

function parseQuantity(value) {
  if (typeof value === 'number' && Number.isFinite(value)) return value;
  const cleaned = String(value ?? '').replace(/[^0-9.-]/g, '');
  const num = Number(cleaned);
  return Number.isFinite(num) ? num : 0;
}

function isHeaderLike(value) {
  const n = normalizeText(value);
  return [
    'اسم المنتج', 'المنتج', 'product', 'product name', 'name', 'item',
    'الكمية', 'quantity', 'qty', 'count', 'stock'
  ].some(label => n === normalizeText(label));
}

function uniq(arr) { return [...new Set(arr.filter(Boolean))]; }

function setTopStatus(text, tone = '') {
  els.topStatus.textContent = text;
  els.topStatus.style.background =
    tone === 'error'   ? 'rgba(214,69,69,0.16)'  :
    tone === 'success' ? 'rgba(46,164,79,0.16)'  :
    'rgba(255,255,255,0.12)';
}

function setScanResult(message, tone = '') {
  els.scanResult.textContent = message;
  els.scanResult.className   = 'state-box' + (tone ? ` ${tone}` : '');
}

// ═══════════════════════════════════════════════════
//  EXCEL PARSING HELPERS
// ═══════════════════════════════════════════════════
function findKeyByCandidates(row, candidates) {
  return Object.keys(row || {}).find(key => {
    const nk = normalizeText(key);
    return candidates.some(c => nk.includes(normalizeText(c)));
  }) || '';
}

function findFirstTextCell(row) {
  for (const value of Object.values(row || {})) {
    const text = String(value ?? '').trim();
    if (text && !isHeaderLike(text)) return text;
  }
  return '';
}

function findFirstNumberCell(row) {
  for (const value of Object.values(row || {})) {
    const asText = String(value ?? '').trim();
    if (!asText) continue;
    const num = parseQuantity(asText);
    if (Number.isFinite(num) && /\d/.test(asText)) return num;
  }
  return 0;
}

// ═══════════════════════════════════════════════════
//  DATABASE FETCHING
// ═══════════════════════════════════════════════════
async function fetchProductsFromDB() {
  const response = await fetch('api-products.php', { cache: 'no-store' });
  if (!response.ok) throw new Error(`خطأ في الاتصال بقاعدة البيانات`);
  const data = await response.json();
  if (!data.success) throw new Error(data.error || 'فشل جلب البيانات');
  return data.products;
}

// ═══════════════════════════════════════════════════
//  DATA EXTRACTION
// ═══════════════════════════════════════════════════
function buildStockMapFromData(products) {
  const map = new Map();
  
  products.forEach(product => {
    const name = String(product.name ?? '').trim();
    if (!name || isHeaderLike(name)) return;
    
    const key = normalizeText(name);
    if (!map.has(key)) {
      map.set(key, { name, quantity: product.quantity || 0 });
    }
  });
  
  return map;
}

function extractCategoryProducts(workbook) {
  const sheet = workbook.Sheets[workbook.SheetNames[0]];
  const rows  = XLSX.utils.sheet_to_json(sheet, { header: 1, defval: '', raw: false });
  const names = [];

  rows.forEach(row => {
    if (!Array.isArray(row)) return;
    const firstNonEmpty = row.find(cell => String(cell ?? '').trim() !== '');
    const name = String(firstNonEmpty ?? '').trim();
    if (!name || isHeaderLike(name)) return;
    names.push(name);
  });

  return uniq(names);
}

// ═══════════════════════════════════════════════════
//  LOAD MAIN DATABASE
// ═══════════════════════════════════════════════════
async function loadMainDatabase() {
  setTopStatus('جارٍ تحميل قاعدة البيانات...');
  setScanResult('جارٍ تحميل البيانات من SQL...', '');
  try {
    const products = await fetchProductsFromDB();
    state.stockMap = buildStockMapFromData(products);
    setTopStatus(`✅ تم تحميل ${state.stockMap.size} منتج من قاعدة البيانات`, 'success');
    setScanResult(`✅ تم تحميل البيانات من SQL بنجاح`, 'success');
  } catch (error) {
    setTopStatus('❌ تعذر تحميل البيانات من SQL', 'error');
    setScanResult(
`⚠️ خطأ في الاتصال بقاعدة البيانات:

تأكد من:
1. أن خادم MySQL يعمل
2. أن بيانات الاتصال في db.php صحيحة
3. أن جدول products موجود في قاعدة البيانات

التفاصيل: ${error.message}`, 'error');
    throw error;
  }
}

// ═══════════════════════════════════════════════════
//  CATEGORY RESOLUTION
// ═══════════════════════════════════════════════════
// ═══════════════════════════════════════════════════
//  CATEGORY RESOLUTION
// ═══════════════════════════════════════════════════
async function resolveCategoryProducts(qrText) {
  const categoryName = getBaseName(qrText) || String(qrText ?? '').trim();
  const response = await fetch(`api-category.php?category=${encodeURIComponent(categoryName)}`, { cache: 'no-store' });
  if (!response.ok) throw new Error(`تعذر العثور على الفئة: ${categoryName}`);
  const data = await response.json();
  if (!data.success) throw new Error(data.error || 'فشل جلب بيانات الفئة');
  return categoryName;
}

// ═══════════════════════════════════════════════════
//  PRODUCT LIST BUILDER
// ═══════════════════════════════════════════════════
function buildCurrentProducts(productNames) {
  return productNames.map(name => {
    const key   = normalizeText(name);
    const stock = state.stockMap.get(key);
    return { key, name, quantity: stock ? stock.quantity : 0, matched: Boolean(stock) };
  });
}

function resetSessionKeepFile() {
  state.deltas   = new Map();
  state.verified = new Set();
}

// ═══════════════════════════════════════════════════
//  DELTA / VERIFY ACTIONS
// ═══════════════════════════════════════════════════
function applyDelta(key, step) {
  if (state.verified.has(key)) return;
  state.deltas.set(key, (state.deltas.get(key) || 0) + step);
  renderTable();
}

function toggleVerified(key, checked) {
  checked ? state.verified.add(key) : state.verified.delete(key);
  renderTable();
}

function getDelta(key) { return state.deltas.get(key) || 0; }

// ═══════════════════════════════════════════════════
//  FILTER
// ═══════════════════════════════════════════════════
function setFilter(filter) {
  state.activeFilter = filter;
  ['all', 'modified', 'missing'].forEach(f => {
    document.getElementById('filter' + f.charAt(0).toUpperCase() + f.slice(1))
      ?.classList.toggle('active', f === filter);
  });
  renderTable();
}

function getFilteredProducts() {
  let list = state.currentProducts;
  const q  = state.searchQuery.trim().toLowerCase();

  if (q) list = list.filter(p => normalizeText(p.name).includes(normalizeText(q)));

  if (state.activeFilter === 'modified') list = list.filter(p => getDelta(p.key) !== 0);
  if (state.activeFilter === 'missing')  list = list.filter(p => !p.matched);

  return list;
}

// ═══════════════════════════════════════════════════
//  RENDER
// ═══════════════════════════════════════════════════
function renderSummary() {
  const total    = state.currentProducts.length;
  const deltas   = [...state.deltas.values()];
  const positive = deltas.filter(v => v > 0).reduce((s, v) => s + v, 0);
  const negative = deltas.filter(v => v < 0).reduce((s, v) => s + Math.abs(v), 0);
  const missing  = state.currentProducts.filter(p => !p.matched).length;

  els.statProducts.textContent = String(total);
  els.statPositive.textContent = `+${positive}`;
  els.statNegative.textContent = `-${negative}`;
  els.statMissing.textContent  = String(missing);
}

function renderTable() {
  renderSummary();

  const hasProducts = state.currentProducts.length > 0;
  els.exportAllBtn.disabled      = !hasProducts;
  els.exportModifiedBtn.disabled = !hasProducts;
  els.currentFileLabel.textContent = `الملف الحالي: ${state.currentFile || '—'}`;

  const filtered = getFilteredProducts();

  if (!hasProducts) {
    els.productsBody.innerHTML   = '';
    els.emptyState.style.display = 'block';
    return;
  }

  els.emptyState.style.display = filtered.length ? 'none' : 'block';
  if (!filtered.length) {
    els.productsBody.innerHTML = '';
    els.emptyState.textContent = 'لا توجد نتائج مطابقة للفلتر الحالي.';
    return;
  }

  els.emptyState.textContent = 'امسح QR لعرض المنتجات الخاصة بالملف المرتبط به.';

  els.productsBody.innerHTML = filtered.map(item => {
    const delta   = getDelta(item.key);
    const adjusted = item.quantity + delta;
    const locked   = state.verified.has(item.key);
    const dcls     = delta > 0 ? 'pos' : delta < 0 ? 'neg' : 'zero';
    const statusTag = item.matched
      ? '<span class="tag ok">مطابق مع list</span>'
      : '<span class="tag missing">غير موجود في list</span>';
    const lockTag = locked ? '<span class="tag locked">مؤكد ومغلق</span>' : '';
    const deltaStr = delta > 0 ? `+${delta}` : String(delta);

    return `
      <tr class="${locked ? 'locked' : ''}">
        <td class="name-cell">
          <div class="product-name">${escapeHtml(item.name)}</div>
          <div style="display:flex;gap:8px;flex-wrap:wrap;">${statusTag}${lockTag}</div>
        </td>
        <td><span class="qty-box">${item.quantity}</span></td>
        <td><span class="delta ${dcls}">${deltaStr}</span></td>
        <td><span class="qty-box">${adjusted}</span></td>
        <td>
          <div class="stepper">
            <button class="btn-secondary btn-mini" data-action="dec" data-key="${item.key}" ${locked ? 'disabled' : ''}>−</button>
            <span class="delta-view">${deltaStr}</span>
            <button class="btn-secondary btn-mini" data-action="inc" data-key="${item.key}" ${locked ? 'disabled' : ''}>+</button>
          </div>
        </td>
        <td>
          <label class="checkbox-wrap">
            <input type="checkbox" data-action="verify" data-key="${item.key}" ${locked ? 'checked' : ''}>
            <span>${locked ? 'مقفول' : 'تأكيد'}</span>
          </label>
        </td>
      </tr>`;
  }).join('');
}

// ═══════════════════════════════════════════════════
//  EXPORT TO EXCEL  ← الإصلاح الرئيسي
// ═══════════════════════════════════════════════════
function buildExportRows(onlyModified) {
  let products = state.currentProducts;
  if (onlyModified) products = products.filter(p => getDelta(p.key) !== 0);

  return products.map(p => {
    const delta   = getDelta(p.key);
    const adjusted = p.quantity + delta;
    return {
      'اسم المنتج'          : p.name,
      'الكمية الأصلية'      : p.quantity,
      'الفرق'               : delta > 0 ? `+${delta}` : String(delta),
      'الكمية بعد التعديل'  : adjusted,
      'الحالة'              : p.matched ? 'مطابق' : 'غير موجود في list',
      'مؤكد'                : state.verified.has(p.key) ? 'نعم' : 'لا'
    };
  });
}

function exportToExcel(onlyModified) {
  const rows = buildExportRows(onlyModified);

  if (!rows.length) {
    setScanResult(
      onlyModified ? 'لا توجد منتجات معدلة للتصدير.' : 'لا توجد منتجات للتصدير.',
      'error'
    );
    return;
  }

  const ws = XLSX.utils.json_to_sheet(rows, { skipHeader: false });

  // ضبط عرض الأعمدة تلقائياً
  const colWidths = Object.keys(rows[0]).map(key => ({
    wch: Math.max(key.length, ...rows.map(r => String(r[key] ?? '').length)) + 2
  }));
  ws['!cols'] = colWidths;

  const wb   = XLSX.utils.book_new();
  const date = new Date().toLocaleDateString('ar-DZ').replace(/\//g, '-');
  const sheetName = onlyModified ? 'المعدل' : 'كل المنتجات';
  XLSX.utils.book_append_sheet(wb, ws, sheetName);

  const fileName = `جرد_${state.currentFile || 'المخزون'}_${date}.xlsx`;
  XLSX.writeFile(wb, fileName);

  setScanResult(`✅ تم تصدير ${rows.length} منتج إلى: ${fileName}`, 'success');
}

// ═══════════════════════════════════════════════════
//  QR HANDLER
// ═══════════════════════════════════════════════════
async function handleQRCode(qrText) {
  try {
    setTopStatus('جارٍ فتح بيانات الفئة...', '');
    setScanResult(`تم التقاط QR: ${qrText}`, 'success');

    if (!state.stockMap.size) await loadMainDatabase();

    const categoryName = await resolveCategoryProducts(qrText);
    const response = await fetch(`api-category.php?category=${encodeURIComponent(categoryName)}`, { cache: 'no-store' });
    const data = await response.json();
    const productNames = data.products || [];
    
    state.currentFile     = categoryName;
    state.currentProducts = buildCurrentProducts(productNames);
    resetSessionKeepFile();
    renderTable();

    setTopStatus(`تم فتح ${state.currentFile} — ${state.currentProducts.length} منتج`, 'success');
    setScanResult(`تم تحميل بيانات الفئة: ${categoryName}`, 'success');
  } catch (error) {
    console.error(error);
    state.currentFile     = '';
    state.currentProducts = [];
    state.deltas          = new Map();
    state.verified        = new Set();
    renderTable();
    setTopStatus('حدث خطأ أثناء القراءة', 'error');
    setScanResult(error.message || 'تعذر العثور على بيانات الفئة', 'error');
  }
}

// ═══════════════════════════════════════════════════
//  CAMERA / SCANNER
// ═══════════════════════════════════════════════════
async function startScanner() {
  if (state.scannerActive) return;
  if (!navigator.mediaDevices?.getUserMedia) {
    setScanResult('الكاميرا غير مدعومة على هذا الجهاز.', 'error');
    return;
  }
  try {
    els.scannerWrap.style.display = 'block';
    setTopStatus('جاري فتح الكاميرا...');
    state.cameraStream = await navigator.mediaDevices.getUserMedia({
      video: { facingMode: 'environment' }, audio: false
    });
    els.video.srcObject = state.cameraStream;
    await els.video.play();
    state.scannerActive = true;
    setScanResult('وجّه الكاميرا إلى رمز QR...', '');
    requestAnimationFrame(scanLoop);
  } catch (error) {
    console.error(error);
    setTopStatus('فشل فتح الكاميرا', 'error');
    setScanResult(`تعذر الوصول للكاميرا: ${error.message}`, 'error');
    stopScanner();
  }
}

function stopScanner() {
  state.scannerActive = false;
  state.cameraStream?.getTracks().forEach(t => t.stop());
  state.cameraStream      = null;
  els.video.srcObject     = null;
  els.scannerWrap.style.display = 'none';
}

function scanLoop() {
  if (!state.scannerActive) return;
  if (els.video.readyState === els.video.HAVE_ENOUGH_DATA) {
    els.canvas.height = els.video.videoHeight;
    els.canvas.width  = els.video.videoWidth;
    canvasContext.drawImage(els.video, 0, 0, els.canvas.width, els.canvas.height);
    const imageData = canvasContext.getImageData(0, 0, els.canvas.width, els.canvas.height);
    const code = jsQR(imageData.data, imageData.width, imageData.height);
    if (code?.data) {
      stopScanner();
      handleQRCode(String(code.data).trim());
      return;
    }
  }
  requestAnimationFrame(scanLoop);
}

// ═══════════════════════════════════════════════════
//  UPLOAD DATABASE FROM BROWSER
// ═══════════════════════════════════════════════════
async function handleDatabaseUpload(event) {
  const file = event.target.files[0];
  if (!file) return;
  try {
    setTopStatus('جارٍ معالجة الملف...', '');
    const buffer   = await file.arrayBuffer();
    const workbook = XLSX.read(buffer, { type: 'array' });
    state.stockMap = extractStockMap(workbook);
    setTopStatus(`✅ تم تحميل ${state.stockMap.size} منتج من: ${file.name}`, 'success');
    setScanResult(`✅ تم رفع ملف الجرد بنجاح\n📊 عدد المنتجات: ${state.stockMap.size}`, 'success');
  } catch (error) {
    console.error(error);
    setTopStatus('❌ خطأ في معالجة الملف', 'error');
    setScanResult(`❌ تعذر معالجة الملف.\n\nالخطأ: ${error.message}`, 'error');
  } finally {
    event.target.value = '';
  }
}

// ═══════════════════════════════════════════════════
//  EVENT LISTENERS
// ═══════════════════════════════════════════════════
els.productsBody.addEventListener('click', event => {
  const btn = event.target.closest('button[data-action]');
  if (!btn) return;
  if (btn.dataset.action === 'inc') applyDelta(btn.dataset.key,  1);
  if (btn.dataset.action === 'dec') applyDelta(btn.dataset.key, -1);
});

els.productsBody.addEventListener('change', event => {
  const cb = event.target.closest('input[data-action="verify"]');
  if (cb) toggleVerified(cb.dataset.key, cb.checked);
});

els.startScanBtn.addEventListener('click', startScanner);

els.reloadDbBtn.addEventListener('click', async () => {
  try { await loadMainDatabase(); }
  catch (e) { console.error(e); }
});

els.uploadDbBtnTrigger.addEventListener('click', () =>
  document.getElementById('dbFileUpload').click()
);

els.exportAllBtn.addEventListener('click',      () => exportToExcel(false));
els.exportModifiedBtn.addEventListener('click', () => exportToExcel(true));

els.searchInput.addEventListener('input', e => {
  state.searchQuery = e.target.value;
  renderTable();
});

window.addEventListener('beforeunload', stopScanner);

// ═══════════════════════════════════════════════════
//  INIT
// ═══════════════════════════════════════════════════
document.addEventListener('DOMContentLoaded', async () => {
  renderTable();
  try {
    await loadMainDatabase();
  } catch (e) {
    console.error(e);
  }
});
</script>
</body>
</html>
