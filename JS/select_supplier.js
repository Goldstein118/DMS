import config from "../JS/config.js";
import { Grid, html } from "../Vendor/gridjs.module.js";
import { apiRequest } from "./api.js";
import * as access from "./cek_access.js";
import * as helper from "./helper.js";
const gird_container_supplier = document.querySelector("#table_supplier");
if (gird_container_supplier) {
  window.supplier_grid = new Grid({
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
          const edit = access.hasAccess("tb_supplier", "edit");
          const can_delete = access.hasAccess("tb_supplier", "delete");
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
        "X-Requested-With": "XMLHttpRequest",
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
  });
  window.supplier_grid.render(document.getElementById("table_supplier"));
  setTimeout(() => {
    helper.custom_grid_header("supplier", handle_delete, handle_update);
  }, 200);
}

async function handle_delete(button) {
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
          response.error || "Gagal menghapus supplier.",
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

async function handle_update(button) {
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
    let update_npwp = document.getElementById("update_supplier_npwp").value;
    const update_status = document.getElementById(
      "update_supplier_status"
    ).value;
    if (
      !update_nama ||
      update_nama.trim() === "" ||
      !update_status ||
      update_status.trim() === ""
    ) {
      toastr.error("Harap isi semua kolom sebelum simpan.");
      return;
    }
    const is_valid =
      helper.validateField(
        update_nama,
        /^[a-zA-Z\s]+$/,
        "Format nama tidak valid"
      ) &&
      helper.validateField(
        update_alamat,
        /^[a-zA-Z0-9,. ]+$/,
        "Format alamat tidak valid"
      ) &&
      helper.validateField(
        update_no_telp,
        /^[0-9]{9,13}$/,
        "Nomor Telepon harus terdiri dari 10-12 digit angka"
      ) &&
      helper.validateField(
        update_ktp,
        /^[0-9]{16}$/,
        "NIK harus terdiri dari 16 digit angka"
      ) &&
      helper.validateField(
        update_npwp,
        /^[0-9]{15,16}$/,
        "NPWP harus terdiri dari 15-16 digit angka"
      );
    if (is_valid) {
      const new_no_telp = helper.format_no_telp(update_no_telp);
      update_npwp = helper.format_npwp(update_npwp);
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
        if (response.ok) {
          row.cells[1].textContent = update_nama;
          row.cells[2].textContent = update_alamat;
          row.cells[3].textContent = new_no_telp;
          row.cells[4].textContent = update_ktp;
          row.cells[5].textContent = update_npwp;
          row.cells[6].textContent = update_status;

          $("#modal_supplier_update").modal("hide");
          Swal.fire("Berhasil", response.message, "success");
          window.supplier_grid.forceRender();
          setTimeout(() => {
            helper.custom_grid_header("supplier", handle_delete, handle_update);
          }, 200);
        }
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
