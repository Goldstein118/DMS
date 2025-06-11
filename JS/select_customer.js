import config from "./config.js";
import { Grid, html } from "../Vendor/gridjs.module.js";
import { apiRequest } from "./api.js";
import * as access from "./cek_access.js";
const grid_container_customer = document.querySelector("#table_customer");
if (grid_container_customer) {
  $(document).ready(function () {
    $("#update_channel_id").select2({
      allowClear: true,
      dropdownParent: $("#modal_customer_update"),
    });
  });
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
      "Titik Koordinat",
      "Channel",
      "Channel_id",
      "Link Gambar",
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
      }/PHP/API/customer_API.php?action=select&user_id=${access.decryptItem(
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
          html(`
          ${
            customer.longitude
              ? `<span>Longitude: ${customer.longitude}</span>`
              : `<span>Longitude:</span>`
          }<br>
          <br>
          ${
            customer.latidude
              ? `<span>Latidude: ${customer.latidude}</span>`
              : `<span>Latidude:</span>`
          }
            `),
          customer.channel_nama,
          customer.channel_id,
          html(`
   ${
     customer.ktp_link
       ? `<a class = "link-dark d-inline-flex text-decoration-none rounded" href="${customer.ktp_link}" target="_blank"><i class="bi bi-person-vcard-fill"> KTP</i></a>`
       : `<i class="bi bi-x-circle">  KTP</i>`
   }<br>
  ${
    customer.npwp_link
      ? `<a class = "link-dark d-inline-flex text-decoration-none rounded" href="${customer.npwp_link}" target="_blank"><i class="bi bi-person-vcard-fill">  NPWP</i></a>`
      : `<i class="bi bi-x-circle">  NPWP</i>`
  }
`),
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
function format_angka(str) {
  if (str === null || str === undefined || str === "") {
    return str;
  }

  const cleaned = str.toString().replace(/[.,\s]/g, "");

  if (!/^\d+$/.test(cleaned)) {
    return str;
  }

  return cleaned.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

function unformat_angka(formattedString) {
  if (
    formattedString === null ||
    formattedString === undefined ||
    formattedString === ""
  ) {
    return formattedString;
  }

  return formattedString.toString().replace(/[.,\s]/g, "");
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
  if (!field || field.trim() === "") {
    return true;
  }
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
  if (!str || str.trim() === "") {
    let result = str;
    return result;
  } else {
    if (7 > str.length) {
      return "Invalid index";
    }
    let format = str.slice(0, 3) + "-" + str.slice(3, 7) + "-" + str.slice(7);
    let result = "+62 " + format;
    return result;
  }
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
        `/PHP/API/customer_API.php?action=delete&user_id=${access.decryptItem(
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
      Swal.fire({
        icon: "error",
        title: "Gagal",
        text: error.message,
      });
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
  let current_max_piutang = row.cells[10].textContent;
  current_max_piutang = unformat_angka(current_max_piutang);

  const titik_koordinat = row.cells[11].textContent;

  const longMatch = titik_koordinat.match(/Longitude:\s*(-?\d+(\.\d+)?)/);
  const latMatch = titik_koordinat.match(/Latidude:\s*(-?\d+(\.\d+)?)/);

  const longitude = longMatch ? longMatch[1].trim() : null;
  const latidude = latMatch ? latMatch[1].trim() : null;

  const current_channel_id = row.cells[13].textContent;

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
  document.getElementById("update_longitude").value = longitude;
  document.getElementById("update_latidude").value = latidude;
  await new Promise((resolve) => setTimeout(resolve, 500));
  try {
    const response = await apiRequest(
      `/PHP/API/channel_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}&target=tb_customer&context=edit`
    );
    const channel_id_field = $("#update_channel_id");
    channel_id_field.empty();
    response.data.forEach((channel) => {
      const option = new Option(
        `${channel.channel_id} - ${channel.nama}`,
        channel.channel_id,
        false,
        channel.channel_id === current_channel_id
      );
      channel_id_field.append(option);
    });
    channel_id_field.trigger("change");
    button_icon.style.display = "inline-block";
    spinner.style.display = "none";
    $("#modal_customer_update").modal("show");
  } catch (error) {
    console.error("Error fetching channel:", error);
    const button_icon = button.querySelector(".button_icon");
    const spinner = button.querySelector(".spinner_update");
    button_icon.style.display = "none";
    spinner.style.display = "inline-block";
  }
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
    let update_max_piutang =
      document.getElementById("update_max_piutang").value;
    update_max_piutang = unformat_angka(update_max_piutang);
    const update_longitude = document.getElementById("update_longitude").value;
    const update_latidude = document.getElementById("update_latidude").value;

    const channel_id_new = $("#update_channel_id").val();

    if (
      !update_nama ||
      update_nama.trim() === "" ||
      !update_status ||
      update_status.trim() === "" ||
      !channel_id_new ||
      channel_id_new.trim() === ""
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
        /^[0-9. ]+$/,
        "Format max piutang tidak valid"
      ) &&
      validateField(
        update_term_pembayaran,
        /^[a-zA-Z0-9,. ]+$/,
        "Format term pembayaran tidak valid"
      ) &&
      validateField(
        update_longitude,
        /^[-+]?((1[0-7]\d|\d{1,2})(\.\d{1,6})?|180(\.0{1,6})?)$/,
        "Format longitude tidak valid"
      ) &&
      validateField(
        update_latidude,
        /^[-+]?([1-8]?\d(\.\d{1,6})?|90(\.0{1,6})?)$/,
        "Format latidude tidak valid"
      );

    if (is_valid) {
      const update_no_telp = format_no_telp(update_phone);

      const formData = new FormData();
      formData.append("customer_id", customer_id);
      formData.append("nama", update_nama);
      formData.append("alamat", update_alamat);
      formData.append("no_telp", update_no_telp);
      formData.append("ktp", update_ktp);
      formData.append("npwp", update_npwp);
      formData.append("status", update_status);
      formData.append("nitko", update_nitko);
      formData.append("term_pembayaran", update_term_pembayaran);
      formData.append("max_invoice", update_max_invoice);
      formData.append("max_piutang", format_angka(update_max_piutang));
      formData.append("longitude", update_longitude);
      formData.append("latidude", update_latidude);
      formData.append("channel_id", channel_id_new);

      // Files
      const ktpFile = document.getElementById("update_ktp_image").files[0];
      const npwpFile = document.getElementById("update_npwp_image").files[0];
      if (ktpFile) formData.append("ktp_file", ktpFile);
      if (npwpFile) formData.append("npwp_file", npwpFile);

      // Send using fetch (not your apiRequest helper if it's JSON-only)
      try {
        const response = await apiRequest(
          `/PHP/API/customer_API.php?action=update&user_id=${access.decryptItem(
            "user_id"
          )}`,
          "POST",
          formData
        );

        if (response.ok) {
          row.cells[1].textContent = update_nama;
          row.cells[2].textContent = update_alamat;
          row.cells[3].textContent = update_no_telp;
          row.cells[4].textContent = update_ktp;
          row.cells[5].textContent = update_npwp;
          row.cells[6].textContent = update_status;
          row.cells[7].textContent = update_nitko;
          row.cells[8].textContent = update_term_pembayaran;
          row.cells[9].textContent = update_max_invoice;
          row.cells[10].textContent = format_angka(update_max_piutang);
          const channel_name = $("#update_channel_id option:selected").text();
          const channel_name_only = channel_name.split(" - ")[1];
          row.cells[12].textContent = channel_name_only;
          row.cells[11].innerHTML = `<span>Longitude: ${update_longitude}<br><br>Latidude: ${update_latidude}</span>`;
          Swal.fire("Berhasil", response.message, "success");
          $("#modal_customer_update").modal("hide");
        } else {
          Swal.fire("Gagal", result.error || "Update gagal.", "error");
        }
      } catch (error) {
        Swal.fire("Error", error.message, "error");
      }
    }
  });
}
