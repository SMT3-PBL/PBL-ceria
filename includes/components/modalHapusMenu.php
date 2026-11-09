<!-- Modal Konfirmasi Hapus Kustom -->
<dialog id="dlgConfirmDelete"
  class="m-auto w-[calc(100%-2rem)] max-w-sm rounded-2xl bg-white p-6 text-ink shadow-2xl backdrop:bg-ink/40">
  <!-- Gunakan fungsi url() agar path-nya dinamis dan tidak pernah salah -->
  <form method="post" action="<?= url('pages/seller/handlers/hapus_menu_process.php') ?>" class="text-center space-y-4">
    <input type="hidden" name="id_menu" id="delete_id_menu" value="">
    <div class="mx-auto grid size-12 place-items-center rounded-full bg-danger-soft text-danger">
      <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
        stroke-linejoin="round">
        <path d="M3 6h18" />
        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6" />
        <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
      </svg>
    </div>
    <div>
      <h3 class="text-lg font-extrabold">Hapus Menu Ini?</h3>
      <p class="text-sm text-muted mt-1">Tindakan ini tidak dapat dibatalkan dan menu akan hilang dari katalog.</p>
    </div>
    <div class="flex justify-center gap-2 pt-2">
      <button type="button" onclick="document.getElementById('dlgConfirmDelete').close()"
        class="h-10 px-4 rounded-lg text-sm font-bold text-muted hover:bg-soft">Batal</button>
      <button type="submit"
        class="h-10 px-4 rounded-lg bg-danger text-sm font-semibold text-white hover:brightness-95">Ya, Hapus</button>
    </div>
  </form>
</dialog>