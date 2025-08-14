<?php
// index.php
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pinterest Search – Getsuzo</title>
  <link rel="stylesheet" href="assets/style.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      background: linear-gradient(135deg, #ff416c, #ff4b2b);
      margin: 0;
      padding: 0;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }
    .wrap {
      flex: 1;
      padding: 20px;
      text-align: center;
    }
    h1 {
      color: white;
      font-size: clamp(1.5rem, 4vw, 2.5rem);
    }
    form {
      margin: 20px auto;
      display: flex;
      max-width: 500px;
      gap: 10px;
    }
    input {
      flex: 1;
      padding: 10px;
      border: none;
      border-radius: 20px;
      font-size: 1rem;
    }
    button {
      background: white;
      color: #ff416c;
      border: none;
      padding: 10px 20px;
      border-radius: 20px;
      font-size: 1rem;
      cursor: pointer;
      transition: 0.3s;
    }
    button:hover {
      background: #ff416c;
      color: white;
    }
    .image-container {
      margin-top: 20px;
    }
    .image-container img {
      max-width: 90%;
      height: auto;
      border-radius: 10px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.3);
      transition: transform 0.3s;
    }
    .image-container img:hover {
      transform: scale(1.02);
    }
    .nav-buttons {
      margin-top: 10px;
      display: flex;
      justify-content: center;
      gap: 10px;
    }
    footer {
      text-align: center;
      padding: 10px;
      font-size: 14px;
      color: #fff;
      background: rgba(0,0,0,0.2);
    }
  </style>
</head>
<body>
  <div class="wrap">
    <h1>Pinterest Search</h1>
    <form id="search-form">
      <input type="text" id="query" placeholder="Cari gambar di Pinterest..." required>
      <button type="submit">Cari</button>
    </form>

    <div id="status" style="color:white; margin-top:10px;"></div>

    <div class="image-container" style="display:none;">
      <img id="pinterest-image" src="" alt="Pinterest Image">
      <div class="nav-buttons">
        <button id="prev">⬅️ Back</button>
        <button id="next">➡️ Next</button>
      </div>
      <p id="counter" style="color:white;"></p>
    </div>
  </div>

  <footer>© 2025 Dibuat oleh <strong>Nama Kamu</strong> – Powered by Getsuzo Pinterest Search</footer>

<script>
let results = [];
let index = 0;

document.getElementById('search-form').addEventListener('submit', async (e) => {
  e.preventDefault();
  const q = document.getElementById('query').value.trim();
  if (!q) return;
  
  document.getElementById('status').textContent = "🔎 Mencari...";
  document.querySelector('.image-container').style.display = 'none';

  try {
    const res = await fetch(`search.php?q=${encodeURIComponent(q)}`);
    const json = await res.json();

    if (!json.status || json.data.length === 0) {
      document.getElementById('status').textContent = "Tidak ditemukan gambar.";
      return;
    }

    results = json.data;
    index = 0;
    updateImage();
    document.getElementById('status').textContent = "";
    document.querySelector('.image-container').style.display = 'block';

  } catch (err) {
    document.getElementById('status').textContent = "Terjadi kesalahan saat mencari.";
    console.error(err);
  }
});

document.getElementById('prev').addEventListener('click', () => {
  if (results.length === 0) return;
  index = (index - 1 + results.length) % results.length;
  updateImage();
});

document.getElementById('next').addEventListener('click', () => {
  if (results.length === 0) return;
  index = (index + 1) % results.length;
  updateImage();
});

function updateImage() {
  const imgEl = document.getElementById('pinterest-image');
  imgEl.src = results[index];
  document.getElementById('counter').textContent = `${index+1} / ${results.length}`;
}
</script>
</body>
</html>
