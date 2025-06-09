import config from "../JS/config.js";
import { Grid, html } from "../Vendor/gridjs.module.js";
import { apiRequest } from "./api.js";
import * as access from "./cek_access.js";

const gird_container_divisi = document.querySelector("#table_divisi");
if (gird_container_divisi) {
  new Grid({
    columns: [
      "Kode divisi",
      "Nama",
      "Bank",
      "Nama Rekening",
      "Nomor Rekening",
      {
        name: "Aksi",
        formatter: () => {
          const edit = access.hasAccess("tb_divisi", "edit");
          const can_delete = access.hasAccess("tb_divisi", "delete");
          let button = "";

          if (edit) {
            button += `<button type="button"  id ="update_divisi_button" class="btn btn-warning update_divisi btn-sm">
          <span id ="button_icon" class="button_icon"><i class="bi bi-pencil-square"></i></span>
          <span id="spinner_update" class="spinner-border spinner-border-sm spinner_update" style="display: none;" role="status" aria-hidden="true"></span>
        </button>`;
          }
          if (can_delete) {
            button += `<button type="button" class="btn btn-danger delete_divisi btn-sm">
                    <i class="bi bi-trash-fill"></i>
                    </button>`;
          }
          return html(button);
        },
      },
    ],
    search: {
      enabled: true,
      server: {
        url: (prev, keyword) => {
          if (keyword.length >= 3 && keyword !== "") {
            const separator = prev.includes("?") ? "&" : "?";
            return `${prev}${separator}search=${encodeURIComponent(keyword)}`;
          } else {
            return prev;
          }
        },
        method: "GET",
      },
    },
    sort: true,
    pagination: { limit: 15 },
    server: {
      url: `${
        config.API_BASE_URL
      }/PHP/API/divisi_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}`,
      method: "GET",
      headers: {
        "Content-Type": "application/json",
      },
      then: (data) =>
        data.map((divisi) => [
          divisi.divisi_id,
          divisi.nama,
          divisi.bank,
          divisi.nama_rekening,
          divisi.no_rekening,
          null, // Placeholder for the action buttons column
        ]),
    },
  }).render(document.getElementById("table_divisi"));
  setTimeout(() => {
    const grid_header = document.querySelector("#table_divisi .gridjs-head");
    const search_Box = grid_header.querySelector(".gridjs-search");

    // Create the button
    const btn = document.createElement("button");
    btn.type = "button";
    btn.className = "btn btn-primary btn_sm";
    btn.setAttribute("data-bs-toggle", "modal");
    btn.setAttribute("data-bs-target", "#modal_divisi");
    btn.innerHTML = '<i class="bi bi-plus-square"></i> divisi ';

    // Wrap both button and search bar in a flex container
    const wrapper = document.createElement("div");
    wrapper.className =
      "d-flex justify-content-between align-items-center mb-3";
    if (access.hasAccess("tb_divisi", "create")) {
      wrapper.appendChild(btn);
    }

    wrapper.appendChild(search_Box);

    // Replace grid header content
    grid_header.innerHTML = "";
    grid_header.appendChild(wrapper);
    const input = document.querySelector("#table_divisi .gridjs-input");
    grid_header.style.display = "flex";
    grid_header.style.justifyContent = "flex-end";

    search_Box.style.display = "flex";
    search_Box.style.justifyContent = "flex-end";
    search_Box.style.marginLeft = "auto";
    input.placeholder = "Cari divisi...";
    document.getElementById("loading_spinner").style.visibility = "hidden";
    $("#loading_spinner").fadeOut();
    attachEventListeners();
  }, 200);
}
function attachEventListeners() {
  document
    .getElementById("table_divisi")
    .addEventListener("click", function (event) {
      const delete_btn = event.target.closest(".delete_divisi");
      const update_btn = event.target.closest(".update_divisi");
      if (delete_btn) {
        handleDeletedivisi(delete_btn);
      } else if (update_btn) {
        handleUpdatedivisi(update_btn);
      }
    });
}

async function handleDeletedivisi(button) {
  const row = button.closest("tr");
  const divisi_id = row.cells[0].textContent;
  const result = await Swal.fire({
    title: "Apakah Anda Yakin?",
    text: "Anda tidak dapat mengembalikannya!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Iya, Hapus!",
    cancelButtonText: "Batalkan",
  });
  if (result.isConfirmed) {
    try {
      const response = await apiRequest(
        `/PHP/API/divisi_API.php?action=delete&user_id=${access.decryptItem(
          "user_id"
        )}`,
        "DELETE",
        { divisi_id: divisi_id }
      );
      if (response.ok) {
        row.remove();
        Swal.fire("Berhasil", response.message || "divisi dihapus.", "success");
      } else {
        Swal.fire(
          "Gagal",
          response.error || "Gagal menghapus divisi.",
          "error"
        );
      }
    } catch (error) {
      Swal.fire({
        icon: "error",
        title: "Gagal",
        text: error.message,
      });
    }
  }
}

async function handleUpdatedivisi(button) {
  const row = button.closest("tr");
  window.currentRow = row;
  const button_icon = button.querySelector(".button_icon");
  const spinner = button.querySelector(".spinner_update");
  button_icon.style.display = "none";
  spinner.style.display = "inline-block";

  const divisi_id = row.cells[0].textContent;
  const name = row.cells[1].textContent;
  const bank = row.cells[2].textContent;
  const nama_rekening = row.cells[3].textContent;
  const no_rekening = row.cells[4].textContent;

  document.getElementById("update_divisi_id").value = divisi_id;
  document.getElementById("update_divisi_nama").value = name;
  document.getElementById("update_nama_bank").value = bank;
  document.getElementById("update_nama_rekening").value = nama_rekening;
  document.getElementById("update_nomor_rekening").value = no_rekening;

  await new Promise((resolve) => setTimeout(resolve, 500));

  button_icon.style.display = "inline-block";
  spinner.style.display = "none";
  $("#modal_divisi_update").modal("show");
}
function validateField(field, pattern, errorMessage) {
  if (!pattern.test(field)) {
    toastr.error(errorMessage, {
      timeOut: 500,
      extendedTimeOut: 500,
    });
    return false;
  }
  return true;
}

const submit_divisi_update = document.getElementById("submit_divisi_update");
if (submit_divisi_update) {
  submit_divisi_update.addEventListener("click", async function () {
    if (!window.currentRow) {
      toastr.error("no row selected for update.");
      return;
    }

    const row = window.currentRow;
    const divisi_id = document.getElementById("update_divisi_id").value;
    const update_nama = document.getElementById("update_divisi_nama").value;
    const update_nama_bank = document.getElementById("update_nama_bank").value;
    const update_nama_rekening = document.getElementById(
      "update_nama_rekening"
    ).value;
    const update_nomor_rekening = document.getElementById(
      "update_nomor_rekening"
    ).value;

    if (
      !update_nama ||
      update_nama.trim() === "" ||
      !update_nama_bank ||
      update_nama_bank.trim() === "" ||
      !update_nama_rekening ||
      update_nama_rekening.trim() === "" ||
      !update_nomor_rekening ||
      update_nomor_rekening.trim() === ""
    ) {
      toastr.error("Harap isi semua kolom sebelum simpan.");
      return;
    }
    const is_valid =
      validateField(update_nama, /^[a-zA-Z\s]+$/, "Format nama tidak valid") &&
      validateField(
        update_nama_bank,
        /^[a-zA-Z\s]+$/,
        "Format nama bank tidak valid"
      ) &&
      validateField(
        update_nama_rekening,
        /^[a-zA-Z\s.]+$/,
        "Format nama rekening tidak valid"
      ) &&
      validateField(
        update_nomor_rekening,
        /^[0-9]+$/,
        "Format nomor rekening tidak valid"
      );
    if (is_valid) {
      try {
        const data_divisi_update = {
          divisi_id: divisi_id,
          nama: update_nama,
          bank: update_nama_bank,
          nama_rekening: update_nama_rekening,
          no_rekening: update_nomor_rekening,
        };

        const response = await apiRequest(
          `/PHP/API/divisi_API.php?action=update&user_id=${access.decryptItem(
            "user_id"
          )}`,
          "POST",
          data_divisi_update
        );

        row.cells[1].textContent = update_nama;
        row.cells[2].textContent = update_nama_bank;
        row.cells[3].textContent = update_nama_rekening;
        row.cells[4].textContent = update_nomor_rekening;

        $("#modal_divisi_update").modal("hide");
        Swal.fire("Berhasil", response.message, "success");
      } catch (error) {
        Swal.fire({
          icon: "error",
          title: "Gagal",
          text: error.message,
        });
      }
    }
  });
}
