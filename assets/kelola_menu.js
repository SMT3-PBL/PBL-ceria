let activeElementToEdit = null;

// 1. Toggle Button Stok (Tersedia / Habis)
function toggleStok(btn) {
  const isTersedia = btn.classList.contains('bg-ok-soft');
  const dot = btn.querySelector('span');
  const text = btn.querySelector('.stok-text');

  if (isTersedia) {
    // Ubah jadi Habis
    btn.classList.remove('bg-ok-soft', 'text-ok', 'border-emerald-300', 'hover:bg-emerald-100');
    btn.classList.add('bg-slate-200', 'text-slate-600', 'border-slate-300', 'hover:bg-slate-300');
    dot.className = 'size-2 rounded-full bg-slate-400';
    text.textContent = 'Habis';
  } else {
    // Ubah jadi Tersedia
    btn.classList.remove('bg-slate-200', 'text-slate-600', 'border-slate-300', 'hover:bg-slate-300');
    btn.classList.add('bg-ok-soft', 'text-ok', 'border-emerald-300', 'hover:bg-emerald-100');
    dot.className = 'size-2 rounded-full bg-ok';
    text.textContent = 'Tersedia';
  }
}

// 2. Modal Dialog Kontrol
function bukaModalTambah() {
  const dlg = document.getElementById('dlgMenu');
  document.getElementById('modalTitle').textContent = 'Tambah Menu Baru';
  document.getElementById('nama_menu').value = '';
  document.getElementById('harga').value = '';
  document.getElementById('kategori').value = '';
  activeElementToEdit = null;
  dlg.showModal();
}

function bukaModalEdit(btn) {
  const dlg = document.getElementById('dlgMenu');
  const li = btn.closest('li');
  activeElementToEdit = li;
  
  const nama = li.querySelector('.menu-nama').textContent;
  const kategori = li.querySelector('.menu-kategori').textContent;
  const hargaStr = li.querySelector('.menu-harga').textContent.replace(/[^0-9]/g, '');

  document.getElementById('modalTitle').textContent = 'Edit Menu';
  document.getElementById('nama_menu').value = nama;
  document.getElementById('harga').value = hargaStr;
  document.getElementById('kategori').value = kategori;
  
  dlg.showModal();
}

function tutupModal() {
  const dlg = document.getElementById('dlgMenu');
  dlg.close();
}

// 3. Simpan Menu (Tambah / Edit Frontend)
function simpanMenu(e) {
  e.preventDefault();
  const nama = document.getElementById('nama_menu').value;
  const harga = Number(document.getElementById('harga').value).toLocaleString('id-ID');
  const kategori = document.getElementById('kategori').value;
  const stokAwal = document.getElementById('stok_awal').value;

  if (activeElementToEdit) {
    // Edit elemen yang ada
    activeElementToEdit.querySelector('.menu-nama').textContent = nama;
    activeElementToEdit.querySelector('.menu-kategori').textContent = kategori;
    activeElementToEdit.querySelector('.menu-harga').textContent = 'Rp' + harga;
    activeElementToEdit.setAttribute('data-kategori', kategori);
    activeElementToEdit.setAttribute('data-nama', nama);
  } else {
    // Tambah elemen baru ke list
    const isTersedia = stokAwal === 'tersedia';
    const btnClass = isTersedia ? 'bg-ok-soft text-ok border-emerald-300 hover:bg-emerald-100' : 'bg-slate-200 text-slate-600 border-slate-300 hover:bg-slate-300';
    const dotClass = isTersedia ? 'size-2 rounded-full bg-ok' : 'size-2 rounded-full bg-slate-400';
    const textStok = isTersedia ? 'Tersedia' : 'Habis';

    const ul = document.getElementById('listDaftarMenu');
    const li = document.createElement('li');
    li.className = 'flex flex-wrap items-center gap-x-4 gap-y-3 rounded-xl bg-surface p-3 sm:p-4 menu-item';
    li.setAttribute('data-kategori', kategori);
    li.setAttribute('data-nama', nama);
    
    li.innerHTML = `
      <span class="grid size-16 shrink-0 place-items-center rounded-lg bg-soft text-primary/40 sm:size-20">
        <svg class="size-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
      </span>
      <div class="min-w-0 flex-1 basis-40">
        <p class="font-bold leading-snug menu-nama">${nama}</p>
        <p class="text-xs text-muted menu-kategori">${kategori}</p>
        <p class="mt-1 font-extrabold menu-harga">Rp${harga}</p>
      </div>
      <div class="ml-auto flex items-center gap-2 sm:gap-3">
        <button type="button" onclick="toggleStok(this)" class="inline-flex items-center gap-1.5 rounded-md px-3 py-1.5 text-xs font-extrabold uppercase transition border ${btnClass} stok-btn">
          <span class="${dotClass}"></span>
          <span class="stok-text">${textStok}</span>
        </button>
        <button type="button" onclick="bukaModalEdit(this)" class="grid size-10 place-items-center rounded-lg text-primary hover:bg-soft">
          <svg class="size-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/></svg>
        </button>
        <button type="button" onclick="hapusMenu(this)" class="grid size-10 place-items-center rounded-lg bg-danger-soft text-danger hover:brightness-95">
          <svg class="size-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
        </button>
      </div>
    `;
    ul.appendChild(li);
    perbaruiJumlahMenu();
  }
  tutupModal();
}

// 4. Hapus Menu
function hapusMenu(btn) {
  if (confirm('Hapus menu ini?')) {
    btn.closest('li').remove();
    perbaruiJumlahMenu();
  }
}

// 5. Pencarian & Filter Kategori 
function cariMenu() {
  const keyword = document.getElementById('inputPencarian').value.toLowerCase();
  const items = document.querySelectorAll('.menu-item');
  items.forEach(item => {
    const nama = item.getAttribute('data-nama').toLowerCase();
    if (nama.includes(keyword)) {
      item.style.display = '';
    } else {
      item.style.display = 'none';
    }
  });
}

function filterKategori(kat) {
  const items = document.querySelectorAll('.menu-item');
  items.forEach(item => {
    const kategoriItem = item.getAttribute('data-kategori');
    if (kat === '' || kategoriItem === kat) {
      item.style.display = '';
    } else {
      item.style.display = 'none';
    }
  });
}

function perbaruiJumlahMenu() {
  const total = document.querySelectorAll('.menu-item').length;
  document.getElementById('badgeTotalMenu').textContent = total + ' Menu';
  document.getElementById('totalMenuCount').textContent = total;
}