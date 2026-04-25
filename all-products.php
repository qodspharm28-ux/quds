<?php
// معلومات الاتصال بقاعدة البيانات
include 'db.php';
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>كل المنتجات - Pharma Qods</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

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
    }

    * { box-sizing: border-box; }
    html, body { margin: 0; padding: 0; }
    
    body {
      font-family: "Tajawal", system-ui, -apple-system, Segoe UI, Arial, sans-serif;
      background: radial-gradient(circle at top, #ffffff 0%, var(--bg) 42%, #eaf1f8 100%);
      color: var(--text);
      min-height: 100vh;
      padding: 20px;
    }

    .container {
      max-width: 1000px;
      margin: 0 auto;
    }

    .header {
      display: flex;
      align-items: center;
      gap: 15px;
      margin-bottom: 30px;
      flex-wrap: wrap;
    }

    .header h1 {
      font-size: 2rem;
      margin: 0;
      color: var(--text);
      flex: 1;
      min-width: 200px;
    }

    .back-btn {
      padding: 12px 20px;
      background: linear-gradient(135deg, var(--primary), var(--primary-dark));
      color: white;
      border: none;
      border-radius: 12px;
      cursor: pointer;
      font-family: inherit;
      font-weight: 700;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      transition: transform 0.18s ease;
    }

    .back-btn:hover {
      transform: translateY(-2px);
    }

    .search-box {
      width: 100%;
      padding: 14px 18px;
      font-size: 16px;
      border: 1px solid var(--line);
      border-radius: 12px;
      background: var(--card);
      font-family: inherit;
      margin-bottom: 20px;
      box-shadow: var(--shadow);
    }

    .search-box:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(31, 138, 112, 0.1);
    }

    .table-wrap {
      background: var(--card);
      border: 1px solid var(--line);
      border-radius: 16px;
      overflow: auto;
      box-shadow: var(--shadow);
    }

    table {
      width: 100%;
      border-collapse: collapse;
      min-width: 400px;
    }

    thead th {
      background: linear-gradient(135deg, #1f8a70, #166b56);
      color: white;
      text-align: right;
      padding: 16px 18px;
      font-weight: 800;
      font-size: 0.96rem;
      white-space: nowrap;
    }

    tbody td {
      border-bottom: 1px solid var(--line);
      padding: 14px 18px;
      text-align: right;
      vertical-align: middle;
    }

    tbody tr:hover {
      background: #f7fbfa;
    }

    .qty-badge {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      min-width: 60px;
      padding: 8px 12px;
      border-radius: 10px;
      background: #eefbf4;
      color: #116b2e;
      font-weight: 800;
      font-size: 0.95rem;
    }

    .empty-state {
      text-align: center;
      padding: 40px 20px;
      color: var(--muted);
    }

    .empty-state p {
      margin: 0;
      font-size: 1.1rem;
    }

    .product-count {
      background: var(--primary);
      color: white;
      padding: 8px 12px;
      border-radius: 999px;
      font-size: 0.85rem;
      font-weight: 700;
    }

    @media (max-width: 620px) {
      .header {
        flex-direction: column;
      }

      .header h1 {
        font-size: 1.5rem;
      }

      .back-btn {
        width: 100%;
        justify-content: center;
      }

      thead th {
        font-size: 0.85rem;
        padding: 12px 10px;
      }

      tbody td {
        padding: 10px;
        font-size: 0.9rem;
      }
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="header">
      <h1>📦 كل منتجات المخزون</h1>
      <a href="index.php" class="back-btn">← العودة</a>
      <span class="product-count" id="productCount">0 منتج</span>
    </div>

    <input 
      type="text" 
      id="search" 
      class="search-box" 
      placeholder="🔍 ابحث عن منتج..."
    >

    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>اسم المنتج</th>
            <th>الكمية</th>
          </tr>
        </thead>
        <tbody id="tableBody"></tbody>
      </table>
    </div>

    <div id="emptyState" class="empty-state" style="display: none;">
      <p>لم يتم العثور على منتجات</p>
    </div>
  </div>

  <script>
    let allProducts = [];

    function normalizeText(value) {
      return String(value ?? '')
        .normalize('NFKD')
        .replace(/[\u0300-\u036f]/g, '')
        .replace(/\s+/g, ' ')
        .trim()
        .toLowerCase();
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

    async function loadProducts() {
      try {
        const res = await fetch('api-products.php', { cache: 'no-store' });
        if (!res.ok) throw new Error('فشل تحميل بيانات المنتجات');
        
        const data = await res.json();
        if (!data.success) throw new Error(data.error || 'خطأ في استرجاع البيانات');
        
        allProducts = data.products || [];
        render(allProducts);
        updateProductCount(allProducts.length);
      } catch (error) {
        console.error(error);
        document.getElementById('emptyState').style.display = 'block';
        document.querySelector('table').style.display = 'none';
      }
    }

    function render(data) {
      const tableBody = document.getElementById('tableBody');
      const emptyState = document.getElementById('emptyState');

      if (data.length === 0) {
        tableBody.innerHTML = '';
        emptyState.style.display = 'block';
        return;
      }

      emptyState.style.display = 'none';

      let html = '';
      data.forEach(p => {
        html += `
          <tr>
            <td>${String(p.name ?? '').replace(/</g, '&lt;').replace(/>/g, '&gt;')}</td>
            <td><span class="qty-badge">${p.quantity}</span></td>
          </tr>
        `;
      });
      tableBody.innerHTML = html;
    }

    function updateProductCount(count) {
      document.getElementById('productCount').textContent = count + ' منتج';
    }

    document.getElementById('search').addEventListener('input', function() {
      const value = normalizeText(this.value.trim());

      const filtered = allProducts.filter(p =>
        normalizeText(p.name.trim()).includes(value)
      );

      render(filtered);
      updateProductCount(filtered.length);
    });

    // Load products on page load
    document.addEventListener('DOMContentLoaded', loadProducts);
  </script>
</body>
</html>
