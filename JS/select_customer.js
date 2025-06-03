import config from "./config.js";
import { Grid, html } from "../Vendor/gridjs.module.js";
import { apiRequest } from "./api.js";
import * as access from "./cek_access.js";
const gird_container_customer = document.querySelector("#table_customer");
if (gird_container_customer) {
  new Grid({
    columns: [
      "Kode Customer",
      "Nama",
      "Alamat",
      "Nomor Telepon",
      "NIK",
      "NPWP",
      "Status",
      "NITKO",
      "Term Pembayaran",
      "Maksimun Invoice",
      "Maksimun Nominal Piutang",
      {
        name: "Aksi",
        formatter: () => {
          const edit = access.hasAccess("tb_karyawan", "edit");
          const can_delete = access.hasAccess("tb_karyawan", "delete");
          let button = "";

          if (edit) {
            button += `<button type="button"  id ="update_customer_button" class="btn btn-warning update_customer btn-sm">
            <span id ="button_icon" class="button_icon"><i class="bi bi-pencil-square"></i></span>
            <span id="spinner_update" class="spinner-border spinner-border-sm spinner_update" style="display: none;" role="status" aria-hidden="true"></span>
        </button>`;
          }
          if (can_delete) {
            button += `
        <button type="button" class="btn btn-danger delete_customer btn-sm">
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
      }/PHP/API/customer_API.php?action=select&user_id=${localStorage.getItem(
        "user_id"
      )}`,
      method: "GET",
      headers: { "Content-Type": "application/json" },
      then: (data) =>
        data.map((customer) => [
          customer.customer_id,
          customer.nama,
          customer.alamat,
          customer.no_telp,
          customer.ktp,
          customer.npwp,
          customer.status,
          customer.nitko,
          customer.term_pembayaran,
          customer.max_invoice,
          customer.max_piutang,
          null,
        ]),
    },
  }).render(document.getElementById("table_customer"));
  setTimeout(() => {
    const grid_header = document.querySelector("#table_customer .gridjs-head");
    const search_Box = grid_header.querySelector(".gridjs-search");

    // Create the button
    const btn = document.createElement("button");
    btn.type = "button";
    btn.className = "btn btn-primary btn-sm";
    btn.setAttribute("data-bs-toggle", "modal");
    btn.setAttribute("data-bs-target", "#modal_customer");
    btn.innerHTML = '<i class="bi bi-plus-square"></i> Customer ';

    // Wrap both button and search bar in a flex container
    const wrapper = document.createElement("div");
    wrapper.className =
      "d-flex justify-content-between align-items-center mb-3";
    if (access.hasAccess("tb_customer", "create")) {
      wrapper.appendChild(btn);
    }

    wrapper.appendChild(search_Box);

    // Replace grid header content
    grid_header.innerHTML = "";
    grid_header.appendChild(wrapper);
    const input = document.querySelector("#table_customer .gridjs-input");
    grid_header.style.display = "flex";
    grid_header.style.justifyContent = "flex-end";

    search_Box.style.display = "flex";
    search_Box.style.justifyContent = "flex-end";
    search_Box.style.marginLeft = "auto";
    input.placeholder = "Cari Customer...";
    document.getElementById("loading_spinner").style.visibility = "hidden";
    $("#loading_spinner").fadeOut();
    attachEventListeners();
  }, 200);
}
function attachEventListeners() {
  document
    .getElementById("table_customer")
    .addEventListener("click", function (event) {
      const delete_btn = event.target.closest(".delete_customer");
      const update_btn = event.target.closest(".update_customer");

      if (delete_btn) {
        handleDeleteCustomer(delete_btn);
      } else if (update_btn) {
        handleUpdateCustomer(update_btn);
      }
    });
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
async function handleDeleteCustomer(button) {
  const row = button.closest("tr");
  const customer_id = row.cells[0].textContent;
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
        `/PHP/API/customer_API.php?action=delete&user_id=${localStorage.getItem(
          "user_id"
        )}`,
        "DELETE",
        { customer_id: customer_id }
      );
      if (response.ok) {
        row.remove();
        Swal.fire(
          "Berhasil",
          response.message || "Customer dihapus.",
          "success"
        );
      } else {
        Swal.fire(
          "Gagal",
          response.error || "Gagal menghapus customer.",
          "error"
        );
      }
    } catch (error) {
      toastr.error(error.message);
    }
  }
}

async function handleUpdateCustomer(button) {
  const row = button.closest("tr");
  window.currentRow = row;
  const button_icon = button.querySelector(".button_icon");
  const spinner = button.querySelector(".spinner_update");
  button_icon.style.display = "none";
  spinner.style.display = "inline-block";

  const customer_id = row.cells[0].textContent;
  const current_nama = row.cells[1].textContent;
  const current_alamat = row.cells[2].textContent;
  let no_telp = row.cells[3].textContent;
  const current_phone = no_telp.replace(/\+62|-|\s/g, "");
  const current_ktp = row.cells[4].textContent;
  const current_npwp = row.cells[5].textContent;
  const current_status = row.cells[6].textContent;
  const current_nitko = row.cells[7].textContent;
  const current_term_pembayaran = row.cells[8].textContent;
  const current_max_invoice = row.cells[9].textContent;
  const current_max_piutang = row.cells[10].textContent;

  document.getElementById("update_customer_id").value = customer_id;
  document.getElementById("update_name_customer").value = current_nama;
  document.getElementById("update_address_customer").value = current_alamat;
  document.getElementById("update_phone_customer").value = current_phone;
  document.getElementById("update_nik_customer").value = current_ktp;
  document.getElementById("update_npwp_customer").value = current_npwp;
  document.getElementById("update_status_customer").value = current_status;
  document.getElementById("update_nitko").value = current_nitko;
  document.getElementById("update_term_payment").value =
    current_term_pembayaran;
  document.getElementById("update_max_invoice").value = current_max_invoice;
  document.getElementById("update_max_piutang").value = current_max_piutang;
  await new Promise((resolve) => setTimeout(resolve, 500));
  button_icon.style.display = "inline-block";
  spinner.style.display = "none";
  $("#modal_customer_update").modal("show");
}

const submit_customer_update = document.getElementById(
  "submit_customer_update"
);
if (submit_customer_update) {
  submit_customer_update.addEventListener("click", async function () {
    if (!window.currentRow) {
      toastr.error("no row selected for update.");
      return;
    }
    const row = window.currentRow;
    const customer_id = document.getElementById("update_customer_id").value;
    const update_nama = document.getElementById("update_name_customer").value;
    const update_alamat = document.getElementById(
      "update_address_customer"
    ).value;
    let update_phone = document.getElementById("update_phone_customer").value;
    const update_ktp = document.getElementById("update_nik_customer").value;
    const update_npwp = document.getElementById("update_npwp_customer").value;
    const update_status = document.getElementById(
      "update_status_customer"
    ).value;
    const update_nitko = document.getElementById("update_nitko").value;
    const update_term_pembayaran = document.getElementById(
      "update_term_payment"
    ).value;
    const update_max_invoice =
      document.getElementById("update_max_invoice").value;
    const update_max_piutang =
      document.getElementById("update_max_piutang").value;
    if (
      !update_nama ||
      update_nama.trim() === "" ||
      !update_alamat ||
      update_alamat.trim() === "" ||
      !update_phone ||
      update_phone.trim() === "" ||
      !update_ktp ||
      update_ktp.trim() === "" ||
      !update_npwp ||
      update_npwp.trim() === "" ||
      !update_status ||
      update_status.trim() === "" ||
      !update_nitko ||
      update_nitko.trim() === "" ||
      !update_max_invoice ||
      update_max_invoice.trim() === "" ||
      !update_max_piutang ||
      update_max_piutang.trim() === "" ||
      !update_term_pembayaran ||
      update_term_pembayaran.trim() === ""
    ) {
      toastr.error("Harap isi semua kolom debelum simpan.");
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
        update_phone,
        /^[0-9]{9,13}$/,
        "Format nomor telepon tidak valid"
      ) &&
      validateField(update_ktp, /^[0-9]+$/, "Format NIK tidak valid") &&
      validateField(update_npwp, /^[0-9 .-]+$/, "Format NPWP tidak valid") &&
      validateField(
        update_nitko,
        /^[a-zA-Z0-9,. ]+$/,
        "Format NITKO tidak valid"
      ) &&
      validateField(
        update_max_invoice,
        /^[a-zA-Z0-9,. ]+$/,
        "Format max invoice tidak valid"
      ) &&
      validateField(
        update_max_piutang,
        /^[a-zA-Z0-9,. ]+$/,
        "Format max piutang tidka valid"
      ) &&
      validateField(
        update_term_pembayaran,
        /^[a-zA-Z0-9,. ]+$/,
        "Format term pembayaran tidak valid"
      );

    if (is_valid) {
      const update_no_telp = format_no_telp(update_phone);

      const data_customer_update = {
        customer_id: customer_id,
        nama: update_nama,
        alamat: update_alamat,
        no_telp: update_no_telp,
        ktp: update_ktp,
        npwp: update_npwp,
        status: update_status,
        nitko: update_nitko,
        term_pembayaran: update_term_pembayaran,
        max_invoice: update_max_invoice,
        max_piutang: update_max_piutang,
      };
      try {
        const response = await apiRequest(
          `/PHP/API/customer_API.php?action=update&user_id=${localStorage.getItem(
            "user_id"
          )}`,
          "POST",
          data_customer_update
        );

        row.cells[1].textContent = update_nama;
        row.cells[2].textContent = update_alamat;
        row.cells[3].textContent = update_no_telp;
        row.cells[4].textContent = update_ktp;
        row.cells[5].textContent = update_npwp;
        row.cells[6].textContent = update_status;
        row.cells[7].textContent = update_nitko;
        row.cells[8].textContent = update_term_pembayaran;
        row.cells[9].textContent = update_max_invoice;
        row.cells[10].textContent = update_max_piutang;

        $("#modal_customer_update").modal("hide");
        Swal.fire("Berhasil", response.message, "success");
      } catch (error) {
        toastr.error(error.message);
      }
    }
  });
}
