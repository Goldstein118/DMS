import config from "./config.js";
import { Grid, html } from "../Vendor/gridjs.module.js";
import { apiRequest } from "./api.js";
import * as access from "./cek_access.js";

const grid_container_karyawan = document.querySelector("#table_karyawan");
if (grid_container_karyawan) {
  $(document).ready(function () {
    $("#update_role_select").select2({
      allowClear: true,
      dropdownParent: $("#modal_karyawan_update"),
    });
  });

  window.karyawan_grid = new Grid({
    columns: [
      "Kode Karyawan",
      "Nama",
      "Role",
      "Departement",
      "Nomor Telepon",
      "Alamat",
      "NIK",
      "NPWP",
      "Status",
      "role_id",
      {
        name: "Aksi",
        formatter: (_cell, row) => {
          const current_user_id = access.decryptItem("user_id");
          const row_user_id = row.cells[10].data;
          let edit;
          let can_delete;
          if (access.isOwner()) {
            edit = true;
          } else {
            edit =
              access.hasAccess("tb_karyawan", "edit") &&
              row_user_id !== current_user_id;
          }
          if (access.isOwner()) {
            can_delete = true;
          } else {
            can_delete =
              access.hasAccess("tb_karyawan", "delete") &&
              row_user_id !== current_user_id;
          }
          let button = "";

          if (edit) {
            button += `<button
                type="button"
                id="update_karyawan_button"
                class="btn btn-warning update_karyawan btn-sm"
              >
                <span id="button_icon" class="button_icon">
                  <i class="bi bi-pencil-square"></i>
                </span>
                <span
                  id="spinner_update"
                  class="spinner-border spinner-border-sm spinner_update"
                  style="display: none;"
                  role="status"
                  aria-hidden="true"
                ></span>
              </button>`;
          }
          if (can_delete) {
            button += `
        <button type="button" class="btn btn-danger delete_karyawan btn-sm">
          <i class="bi bi-trash-fill"></i>
        </button>
        `;
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
      }/PHP/API/karyawan_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}`,
      method: "GET",
      headers: {
        "Content-Type": "application/json",
        "X-Requested-With": "XMLHttpRequest",
      },
      then: (data) =>
        data.map((karyawan) => [
          karyawan.karyawan_id,
          karyawan.nama,
          karyawan.role_nama,
          karyawan.departement,
          karyawan.no_telp,
          karyawan.alamat,
          karyawan.ktp,
          karyawan.npwp,
          karyawan.status,
          karyawan.role_id,
          karyawan.user_id,
          null,
        ]),
    },
  });
  window.karyawan_grid.render(document.getElementById("table_karyawan"));
  setTimeout(() => {
    const grid_header = document.querySelector("#table_karyawan .gridjs-head");
    const search_Box = grid_header.querySelector(".gridjs-search");

    // Create the button
    const btn = document.createElement("button");
    btn.type = "button";
    btn.className = "btn btn-primary btn-sm";
    btn.setAttribute("data-bs-toggle", "modal");
    btn.setAttribute("data-bs-target", "#modal_karyawan");
    btn.innerHTML = '<i class="bi bi-person-plus-fill"></i> Karyawan';

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
    const input = document.querySelector("#table_karyawan .gridjs-input");
    grid_header.style.display = "flex";
    grid_header.style.justifyContent = "flex-end";

    search_Box.style.display = "flex";
    search_Box.style.justifyContent = "flex-end";
    search_Box.style.marginLeft = "auto";
    input.placeholder = "Cari Karyawan...";
    document.getElementById("loading_spinner").style.visibility = "hidden";
    $("#loading_spinner").fadeOut();
    attachEventListeners();
  }, 200);
}

function attachEventListeners() {
  document
    .getElementById("table_karyawan")
    .addEventListener("click", function (event) {
      const delete_btn = event.target.closest(".delete_karyawan");
      const update_btn = event.target.closest(".update_karyawan");

      if (delete_btn) {
        handleDeleteKaryawan(delete_btn);
      } else if (update_btn) {
        handleUpdateKaryawan(update_btn);
      }
    });
}
function format_no_telp(str) {
  if (str.trim() === "") {
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
// Attach delete listeners
async function handleDeleteKaryawan(button) {
  const row = button.closest("tr");
  const karyawan_ID = row.cells[0].textContent;

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
        `/PHP/API/karyawan_API.php?action=delete&user_id=${access.decryptItem(
          "user_id"
        )}`,
        "DELETE",
        { karyawan_ID }
      );
      if (response.ok) {
        row.remove();
        Swal.fire(
          "Berhasil",
          response.message || "Karyawan dihapus.",
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

function populateRoleDropdown(data, currentrole_id) {
  const role_ID_Field = $("#update_role_select");
  role_ID_Field.empty();
  data.forEach((role) => {
    const option = new Option(
      `${role.role_id} - ${role.nama}`,
      role.role_id,
      false,
      role.role_id == currentrole_id
    );
    role_ID_Field.append(option);
  });

  role_ID_Field.trigger("change");
}

async function handleUpdateKaryawan(button) {
  const row = button.closest("tr");
  window.currentRow = row;
  const button_icon = button.querySelector(".button_icon");
  const spinner = button.querySelector(".spinner_update");
  button_icon.style.display = "none";
  spinner.style.display = "inline-block";

  const karyawan_ID = row.cells[0].textContent;
  const currentNama = row.cells[1].textContent;
  const currentrole_id = row.cells[9].textContent;
  const currentdivisi = row.cells[3].textContent;
  let current_phone = row.cells[4].textContent;
  const currentnoTelp = current_phone.replace(/\+62|-|\s/g, "");
  const currentalamat = row.cells[5].textContent;
  const currentKTP_NPWP = row.cells[6].textContent;
  const currentnpwp = row.cells[7].textContent;
  const currentstatus = row.cells[8].textContent;
  /*
  console.log("Button_pressed");
  console.log(karyawan_ID);
  console.log(currentrole_nama);
  console.log("current role_id:", currentrole_id);

*/

  // Populate the modal fields
  document.getElementById("update_karyawan_ID").value = karyawan_ID;
  document.getElementById("update_name_karyawan").value = currentNama;
  document.getElementById("update_divisi_karyawan").value = currentdivisi;
  document.getElementById("update_phone_karyawan").value = currentnoTelp;
  document.getElementById("update_address_karyawan").value = currentalamat;
  document.getElementById("update_nik_karyawan").value = currentKTP_NPWP;
  document.getElementById("update_npwp_karyawan").value = currentnpwp;
  document.getElementById("update_status_karyawan").value = currentstatus;

  await new Promise((resolve) => setTimeout(resolve, 500));
  try {
    const response = await apiRequest(
      `/PHP/API/role_API.php?action=select&user_id=${access.decryptItem(
        "user_id"
      )}&target=tb_karyawan&context=edit`
    );
    populateRoleDropdown(response.data, currentrole_id);

    button_icon.style.display = "inline-block";
    spinner.style.display = "none";
    $("#modal_karyawan_update").modal("show");
  } catch (error) {
    toastr.error("Gagal mengambil data role: " + error.message);
    const button_icon = button.querySelector(".button_icon");
    const spinner = button.querySelector(".spinner_update");
    button_icon.style.display = "none";
    spinner.style.display = "inline-block";
  }
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
const submit_karyawan_update = document.getElementById(
  "submit_karyawan_update"
);

if (submit_karyawan_update) {
  submit_karyawan_update.addEventListener("click", async function () {
    if (!window.currentRow) {
      toastr.error("No row selected for update.");
      return;
    }

    const row = window.currentRow;
    const karyawan_ID = document.getElementById("update_karyawan_ID").value;
    const karyawan_nama_new = document.getElementById(
      "update_name_karyawan"
    ).value;
    const role_ID_new = $("#update_role_select").val();
    const divisi_new = document.getElementById("update_divisi_karyawan").value;
    let noTelp_new = document.getElementById("update_phone_karyawan").value;
    const alamat_new = document.getElementById("update_address_karyawan").value;
    const KTP_new = document.getElementById("update_nik_karyawan").value;
    const npwp_new = document.getElementById("update_npwp_karyawan").value;
    const status_new = document.getElementById("update_status_karyawan").value;
    if (
      !karyawan_nama_new ||
      karyawan_nama_new.trim() === "" ||
      !role_ID_new ||
      role_ID_new.trim() === "" ||
      !divisi_new ||
      divisi_new.trim() === "" ||
      !status_new ||
      status_new.trim() === ""
    ) {
      toastr.error("Kolom * wajib diisi.");
      return;
    }

    const is_valid =
      validateField(
        karyawan_nama_new,
        /^[a-zA-Z\s]+$/,
        "Format nama tidak valid"
      ) &&
      validateField(
        alamat_new,
        /^[a-zA-Z0-9,. ]+$/,
        "Format alamat tidak valid"
      ) &&
      validateField(
        noTelp_new,
        /^[0-9]{9,13}$/,
        "Format nomor telepon tidak valid"
      ) &&
      validateField(KTP_new, /^[0-9]+$/, "Format NIK tidak valid") &&
      validateField(npwp_new, /^[0-9 .-]+$/, "Format NPWP tidak valid");
    if (is_valid) {
      const no_telp_update = format_no_telp(noTelp_new);

      try {
        const data_karyawan_update = {
          karyawan_id: karyawan_ID,
          nama: karyawan_nama_new,
          role_id: role_ID_new,
          departement: divisi_new,
          no_telp: no_telp_update,
          alamat: alamat_new,
          ktp: KTP_new,
          npwp: npwp_new,
          status: status_new,
        };
        const response = await apiRequest(
          `/PHP/API/karyawan_API.php?action=update&user_id=${access.decryptItem(
            "user_id"
          )}`,
          "POST",
          data_karyawan_update
        );
        if (response.ok) {
          row.cells[1].textContent = karyawan_nama_new;
          const role_name_new = $("#update_role_select option:selected").text();
          const role_name_only = role_name_new.split(" - ")[1];
          row.cells[2].textContent = role_name_only;
          row.cells[3].textContent = divisi_new;
          row.cells[4].textContent = no_telp_update;
          row.cells[5].textContent = alamat_new;
          row.cells[6].textContent = KTP_new;
          row.cells[7].textContent = npwp_new;
          row.cells[8].textContent = status_new;

          $("#modal_karyawan_update").modal("hide");
          Swal.fire("Berhasil", response.message, "success");
          window.karyawan_grid.forceRender();
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
