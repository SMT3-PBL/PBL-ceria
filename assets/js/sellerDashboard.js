document.addEventListener("DOMContentLoaded", () => {
  const dlgMenu = document.getElementById("dlgMenu");

  if (!dlgMenu) {
    console.error(
      "ERROR: Elemen <dialog id='dlgMenu'> tidak ditemukan di halaman ini!",
    );
    return;
  }

  document.querySelectorAll("[data-menu-form]").forEach((btn) => {
    btn.addEventListener("click", () => {
      const id = btn.getAttribute("data-id");
      console.log("Tombol diklik, ID Menu:", id);

      // Cek satu-satu elemen input di dalam modal
      const elId = document.getElementById("modal_id_menu");
      const elNama = document.getElementById("modal_nama_menu");
      const elDesk = document.getElementById("modal_deskripsi");
      const elHarga = document.getElementById("modal_harga");
      const elKat = document.getElementById("modal_kategori");
      const elStatus = document.getElementById("modal_status_menu");

      // Print status ke console untuk melihat mana yang null
      console.log({ elId, elNama, elDesk, elHarga, elKat, elStatus });

      if (id) {
        document.getElementById("modal-title").textContent = "Edit Menu";
        if (elId) elId.value = id;
        if (elNama) elNama.value = btn.getAttribute("data-nama") || "";
        if (elDesk) elDesk.value = btn.getAttribute("data-deskripsi") || "";
        if (elHarga) elHarga.value = btn.getAttribute("data-harga") || "";
        if (elKat) elKat.value = btn.getAttribute("data-kategori") || "";
        if (elStatus)
          elStatus.value = btn.getAttribute("data-status") || "Tersedia";
      } else {
        document.getElementById("modal-title").textContent = "Tambah Menu Baru";
        if (elId) elId.value = "";
        const form = document.querySelector("#dlgMenu form");
        if (form) form.reset();
      }

      dlgMenu.showModal();
    });
  });

  document.querySelectorAll("[data-close]").forEach((btn) => {
    btn.addEventListener("click", () => {
      dlgMenu.close();
    });
  });

  const flashMessage = document.getElementById("flash-message");

  if (flashMessage) {
    // Tunggu selama 3 detik (3000 milidetik)
    setTimeout(() => {
      // Efek transisi memudar perlahan (fade out)
      flashMessage.style.transition = "opacity 0.6s ease, transform 0.6s ease";
      flashMessage.style.opacity = "0";
      flashMessage.style.transform = "translateY(-10px)"; // Sedikit naik ke atas

      // Hapus elemen dari halaman setelah animasinya selesai (0.6 detik)
      setTimeout(() => {
        flashMessage.remove();
      }, 600);
    }, 3000); // Pesan akan bertahan selama 3 detik sebelum mulai menghilang
  }
});

// Fungsi untuk membuka modal konfirmasi hapus kustom
function bukaModalHapus(idMenu) {
  const modal = document.getElementById("dlgConfirmDelete");
  const inputId = document.getElementById("delete_id_menu");

  if (inputId) {
    inputId.value = idMenu; // Memasukkan ID menu spesifik ke input hidden
  }

  if (modal) {
    modal.showModal(); // Memunculkan modal dialog kustom
  }
}
