<?php
require_once __DIR__ . '/../init.php'
?>

<!-- Modal Tambah / Edit Menu -->
<dialog id="dlgMenu"
  class="m-auto w-[calc(100%-2rem)] max-w-md rounded-2xl bg-white p-0 text-ink shadow-2xl backdrop:bg-ink/40">
  <form method="post" action="<?= url('pages/seller/handlers/tambah_menu_process.php') ?>" class="space-y-4 p-5 sm:p-6">
    <div class="flex items-center justify-between">
      <h2 class="text-lg font-extrabold" id="modal-title">Tambah Menu Baru</h2>
      <button type="button" data-close aria-label="Tutup"
        class="grid size-9 place-items-center rounded-lg text-muted hover:bg-soft">
        <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
          stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <path d="M18 6 6 18" />
          <path d="m6 6 12 12" />
        </svg>
      </button>
    </div>

    <!-- Input Hidden ID Menu & ID Tenant -->
    <input type="hidden" name="id_menu" id="modal_id_menu" value="">
    <input type="hidden" name="id_tenant" value="<?= htmlspecialchars($id_tenant ?? '') ?>">

    <p>
      <label for="modal_nama_menu" class="mb-1.5 block text-sm font-semibold text-ink">Nama Menu</label>
      <input type="text" id="modal_nama_menu" name="nama_menu" required
        class="h-11 w-full rounded-lg border border-outline bg-white px-3 text-base text-ink focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
    </p>

    <!-- Deskripsi Menu -->
    <p>
      <label for="modal_deskripsi" class="mb-1.5 block text-sm font-semibold text-ink">Deskripsi Menu</label>
      <textarea id="modal_deskripsi" name="deskripsi" rows="2"
        class="w-full rounded-lg border border-outline bg-white p-3 text-base text-ink focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20"></textarea>
    </p>

    <div class="grid gap-4 sm:grid-cols-2">
      <p>
        <label for="modal_harga" class="mb-1.5 block text-sm font-semibold text-ink">Harga Jual (Rp)</label>
        <input type="number" id="modal_harga" name="harga" min="0" required inputmode="numeric"
          class="h-11 w-full rounded-lg border border-outline bg-white px-3 text-base text-ink focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
      </p>

      <!-- Status Menu -->
      <p>
        <label for="modal_status_menu" class="mb-1.5 block text-sm font-semibold text-ink">Status Menu</label>
        <select id="modal_status_menu" name="status_menu" required
          class="h-11 w-full rounded-lg border border-outline bg-white px-3 text-base text-ink focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
          <option value="Tersedia">Tersedia</option>
          <option value="Habis">Habis</option>
        </select>
      </p>
    </div>

    <p>
      <label for="modal_kategori" class="mb-1.5 block text-sm font-semibold text-ink">Kategori</label>
      <select id="modal_kategori" name="kategori" required
        class="h-11 w-full rounded-lg border border-outline bg-white px-3 text-base text-ink focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
        <option value="">Pilih kategori</option>
        <option value="Makanan">Makanan</option>
        <option value="Minuman">Minuman</option>
        <option value="Snack">Snack</option>
      </select>
    </p>

    <div class="flex justify-end gap-2 pt-1">
      <button type="button" data-close
        class="h-11 rounded-lg px-5 text-sm font-bold text-muted hover:bg-soft">Batal</button>
      <button type="submit"
        class="inline-flex h-11 items-center justify-center gap-2 rounded-lg bg-primary px-5 text-sm font-semibold text-white transition hover:bg-primary-dark">Simpan</button>
    </div>
  </form>
</dialog>