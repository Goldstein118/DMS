import config from "../JS/config.js";
import { Grid, html } from "../Vendor/gridjs.module.js";
import { apiRequest } from "./api.js";
import * as access from "./cek_access.js";

const gird_container_supplier = document.querySelector("#table_supplier");
if (gird_container_supplier) {
  new Grid({
    columns: [
      "Kode Supplier",
      "Nama",
      "Alamat",
      "Nomor Telepon",
      "NIK",
      "NPWP",
      "Status",
      {
        name: "Aksi",
        formatter: () => {
          const edit = access.hasAccess("tb_karyawan", "edit");
          const can_delete = access.hasAccess("tb_karyawan", "delete");
          let button = "";

          if (edit) {
            button += `<button type="button"  id ="update_supplier_button" class="btn btn-warning update_supplier btn-sm">
          <span id ="button_icon" class="button_icon"><i class="bi bi-pencil-square"></i></span>
          <span id="spinner_update" class="spinner-border spinner-border-sm spinner_update" style="display: none;" role="status" aria-hidden="true"></span>
        </button>`;
          }
          if (can_delete) {
            button += `<button type="button" class="btn btn-danger delete_supplier btn-sm">
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
      }/PHP/API/supplier_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}`,
      method: "GET",
      headers: {
        "Content-Type": "application/json",
      },
      then: (data) =>
        data.map((supplier) => [
          supplier.supplier_id,
          supplier.nama,
          supplier.alamat,
          supplier.no_telp,
          supplier.ktp,
          supplier.npwp,
          supplier.status,
          null, // Placeholder for the action buttons column
        ]),
    },
  }).render(document.getElementById("table_supplier"));
  setTimeout(() => {
    const grid_header = document.querySelector("#table_supplier .gridjs-head");
    const search_Box = grid_header.querySelector(".gridjs-search");

    // Create the button
    const btn = document.createElement("button");
    btn.type = "button";
    btn.className = "btn btn-primary btn_sm";
    btn.setAttribute("data-bs-toggle", "modal");
    btn.setAttribute("data-bs-target", "#modal_supplier");
    btn.innerHTML = '<i class="bi bi-plus-square"></i> Supplier ';

    // Wrap both button and search bar in a flex container
    const wrapper = document.createElement("div");
    wrapper.className =
      "d-flex justify-content-between align-items-center mb-3";
    if (access.hasAccess("tb_karyawan", "create")) {
      wrapper.appendChild(btn);
    }

    wrapper.appendChild(search_Box);

    // Replace grid header content
    grid_header.innerHTML = "";
    grid_header.appendChild(wrapper);
    const input = document.querySelector("#table_supplier .gridjs-input");
    grid_header.style.display = "flex";
    grid_header.style.justifyContent = "flex-end";

    search_Box.style.display = "flex";
    search_Box.style.justifyContent = "flex-end";
    search_Box.style.marginLeft = "auto";
    input.placeholder = "Cari Supplier...";
    document.getElementById("loading_spinner").style.visibility = "hidden";
    $("#loading_spinner").fadeOut();
    attachEventListeners();
  }, 200);
}
function attachEventListeners() {
  document
    .getElementById("table_supplier")
    .addEventListener("click", function (event) {
      const delete_btn = event.target.closest(".delete_supplier");
      const update_btn = event.target.closest(".update_supplier");
      if (delete_btn) {
        handleDeleteSupplier(delete_btn);
      } else if (update_btn) {
        handleUpdateSupplier(update_btn);
      }
    });
}

async function handleDeleteSupplier(button) {
  const row = button.closest("tr");
  const supplier_id = row.cells[0].textContent;
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
        `/PHP/API/supplier_API.php?action=delete&user_id=${access.decryptItem(
          "user_id"
        )}`,
        "DELETE",
        { supplier_id: supplier_id }
      );
      if (response.ok) {
        row.remove();
        Swal.fire(
          "Berhasil",
          response.message || "Supplier dihapus.",
          "success"
        );
      } else {
        Swal.fire(
          "Gagal",
          response.error || "Gagal menghapus karyawan.",
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

async function handleUpdateSupplier(button) {
  const row = button.closest("tr");
  window.currentRow = row;
  const button_icon = button.querySelector(".button_icon");
  const spinner = button.querySelector(".spinner_update");
  button_icon.style.display = "none";
  spinner.style.display = "inline-block";

  const supplier_id = row.cells[0].textContent;
  const name = row.cells[1].textContent;
  const alamat = row.cells[2].textContent;
  let phone_supplier = row.cells[3].textContent;
  const no_telp = phone_supplier.replace(/\+62|-|\s/g, "");
  const ktp = row.cells[4].textContent;
  const npwp = row.cells[5].textContent;
  const status = row.cells[6].textContent;

  document.getElementById("update_supplier_id").value = supplier_id;
  document.getElementById("update_supplier_nama").value = name;
  document.getElementById("update_supplier_alamat").value = alamat;
  document.getElementById("update_supplier_no_telp").value = no_telp;
  document.getElementById("update_supplier_ktp").value = ktp;
  document.getElementById("update_supplier_npwp").value = npwp;
  document.getElementById("update_supplier_status").value = status;

  await new Promise((resolve) => setTimeout(resolve, 500));

  button_icon.style.display = "inline-block";
  spinner.style.display = "none";
  $("#modal_supplier_update").modal("show");
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
function format_no_telp(str) {
  if (7 > str.length) {
    return "Invalid index";
  }

  let format = str.slice(0, 3) + "-" + str.slice(3, 7) + "-" + str.slice(7);
  let result = "+62 " + format;
  return result;
}
const submit_supplier_update = document.getElementById(
  "submit_supplier_update"
);
if (submit_supplier_update) {
  submit_supplier_update.addEventListener("click", async function () {
    if (!window.currentRow) {
      toastr.error("no row selected for update.");
      return;
    }

    const row = window.currentRow;
    const supplier_id = document.getElementById("update_supplier_id").value;
    const update_nama = document.getElementById("update_supplier_nama").value;
    const update_alamat = document.getElementById(
      "update_supplier_alamat"
    ).value;
    let update_no_telp = document.getElementById(
      "update_supplier_no_telp"
    ).value;
    const update_ktp = document.getElementById("update_supplier_ktp").value;
    const update_npwp = document.getElementById("update_supplier_npwp").value;
    const update_status = document.getElementById(
      "update_supplier_status"
    ).value;
    if (
      !update_nama ||
      update_nama.trim() === "" ||
      !update_alamat ||
      update_alamat.trim() === "" ||
      !update_no_telp ||
      update_no_telp.trim() === "" ||
      !update_ktp ||
      update_ktp.trim() === "" ||
      !update_npwp ||
      update_npwp.trim() === "" ||
      !update_status ||
      update_status.trim() === ""
    ) {
      toastr.error("Harap isi semua kolom sebelum simpan.");
      return;
    }
    const is_valid =
      validateField(update_nama, /^[a-zA-Z\s]+$/, "Format nama tidak valid") &&
      validateField(
        update_alamat,
        /^[a-zA-Z0-9,. ]+$/,
        "Format alamat tidak valid"
      ) &&
      validateField(
        update_no_telp,
        /^[0-9]{9,13}$/,
        "Format nomor telepon tidak valid"
      ) &&
      validateField(update_ktp, /^[0-9]+$/, "Format NIK tidak valid") &&
      validateField(update_npwp, /^[0-9 .-]+$/, "Format NPWP tidak valid");
    if (is_valid) {
      const new_no_telp = format_no_telp(update_no_telp);
      try {
        const data_supplier_update = {
          supplier_id: supplier_id,
          nama: update_nama,
          alamat: update_alamat,
          no_telp: new_no_telp,
          ktp: update_ktp,
          npwp: update_npwp,
          status: update_status,
        };

        const response = await apiRequest(
          `/PHP/API/supplier_API.php?action=update&user_id=${access.decryptItem(
            "user_id"
          )}`,
          "POST",
          data_supplier_update
        );

        row.cells[1].textContent = update_nama;
        row.cells[2].textContent = update_alamat;
        row.cells[3].textContent = new_no_telp;
        row.cells[4].textContent = update_ktp;
        row.cells[5].textContent = update_npwp;
        row.cells[6].textContent = update_status;

        $("#modal_supplier_update").modal("hide");
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
