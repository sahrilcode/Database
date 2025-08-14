<?php
// index.php
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>TikTok Search – Getsuzo</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
  <div class="wrap">
    <h1>TikTok Search</h1>

    <form id="search-form" class="search">
      <input id="q" type="text" placeholder="Cari video TikTok..." autocomplete="off" required />
      <button type="submit">Cari</button>
    </form>

    <div id="status" class="status"></div>

    <div id="card" class="card" style="display:none;">
      <div class="media">
        <img id="cover" alt="Cover" />
        <video id="video" controls playsinline style="display:none;"></video>
      </div>
      <div class="meta">
        <h2 id="title">Judul</h2>
        <p id="indexInfo">0 / 0</p>
      </div>
      <div class="actions">
        <button id="back" class="ghost">⬅️ Back</button>
        <button id="play">▶️ Play</button>
        <button id="music">🎵 Music</button>
        <button id="next" class="ghost">➡️ Next</button>
      </div>
      <div class="downloads">
        <a id="dlVideo" class="pill" href="#" download>⬇️ Download Video</a>
        <a id="dlAudio" class="pill" href="#" download>⬇️ Download Audio</a>
      </div>
    </div>

    <div id="empty" class="empty">Masukkan kata kunci untuk memulai.</div>
  </div>

<script>
const form = document.getElementById('search-form');
const qEl  = document.getElementById('q');
const statusEl = document.getElementById('status');
const card = document.getElementById('card');
const cover = document.getElementById('cover');
const video = document.getElementById('video');
const titleEl = document.getElementById('title');
const indexInfo = document.getElementById('indexInfo');
const btnBack = document.getElementById('back');
const btnNext = document.getElementById('next');
const btnPlay = document.getElementById('play');
const btnMusic = document.getElementById('music');
const dlVideo = document.getElementById('dlVideo');
const dlAudio = document.getElementById('dlAudio');
const emptyEl = document.getElementById('empty');

let results = [];
let idx = 0;
let isPlaying = false; // false = cover, true = video

function setLoading(msg) {
  statusEl.textContent = msg || 'Loading...';
  statusEl.style.display = 'block';
}
function clearLoading() {
  statusEl.textContent = '';
  statusEl.style.display = 'none';
}
function showCard(show) {
  card.style.display = show ? 'block' : 'none';
  emptyEl.style.display = show ? 'none' : 'block';
}
function updateUI() {
  if (!results.length) {
    showCard(false);
    return;
  }
  const item = results[idx];
  titleEl.textContent = item.title || 'Tanpa Judul';
  indexInfo.textContent = `${idx+1} / ${results.length}`;

  // toggle media
  if (isPlaying) {
    video.src = item.play;
    video.style.display = 'block';
    cover.style.display = 'none';
    btnPlay.textContent = '🖼️ Cover';
  } else {
    cover.src = item.cover;
    cover.style.display = 'block';
    video.pause();
    video.removeAttribute('src');
    video.style.display = 'none';
    btnPlay.textContent = '▶️ Play';
  }

  // buttons state
  btnBack.disabled = (idx === 0);
  btnNext.disabled = (idx >= results.length - 1);

  // download via server proxy (hindari CORS & pastikan konten tersaji dari server kamu)
  dlVideo.href = `api/download.php?type=video&url=${encodeURIComponent(item.play)}&title=${encodeURIComponent(item.title||'tiktok')}`;
  dlAudio.href = `api/download.php?type=audio&url=${encodeURIComponent(item.music)}&title=${encodeURIComponent(item.title||'tiktok-audio')}`;

  showCard(true);
}

form.addEventListener('submit', async (e) => {
  e.preventDefault();
  const query = qEl.value.trim();
  if (!query) return;

  setLoading('Mencari...');
  showCard(false);

  try {
    const res = await fetch(`api/search.php?q=${encodeURIComponent(query)}&count=100`, { cache: 'no-store' });
    if (!res.ok) throw new Error('Gagal mengambil data');
    const json = await res.json();

    results = Array.isArray(json.data) ? json.data : [];
    idx = 0;
    isPlaying = false;

    if (results.length === 0) {
      statusEl.textContent = 'Tidak ada hasil ditemukan.';
      return;
    }
    clearLoading();
    updateUI();
  } catch (err) {
    console.error(err);
    statusEl.textContent = 'Terjadi kesalahan saat mencari.';
  }
});

btnBack.addEventListener('click', () => {
  if (idx > 0) { idx--; isPlaying = false; updateUI(); }
});
btnNext.addEventListener('click', () => {
  if (idx < results.length - 1) { idx++; isPlaying = false; updateUI(); }
});
btnPlay.addEventListener('click', () => {
  isPlaying = !isPlaying;
  updateUI();
});
btnMusic.addEventListener('click', async () => {
  if (!results.length) return;
  const item = results[idx];
  // mainkan audio inline
  const a = new Audio(item.music);
  a.play().catch(()=>{});
});
</script>
</body>
</html>
