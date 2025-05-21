import config from "../JS/config.js";
import { Grid, html } from "https://unpkg.com/gridjs?module";
$(document).ready(function () {
  $("#loading_spinner").fadeOut();
});

new Grid({
  columns: [
    "Kode Role",
    "Nama",
    "Akses",
    {
      name: "Aksi",
      formatter: () => {
        return html(`
        <button type="button"  id ="update_role_button" class="btn btn-warning update_role">
          <span id ="button_icon" class="button_icon"><i class="bi bi-pencil-square"></i></span>
          <span id="spinner_update" class="spinner-border spinner-border-sm spinner_update" style="display: none;" role="status" aria-hidden="true"></span>
        </button>
        
        <button type="button" class="btn btn-danger delete_role">
                    <i class="bi bi-trash-fill"></i>
        </button>
        `);
      },
    },
  ],
  search: {
    enabled: true,
  },
  server: {
    url: (prev, keyword) => `${prev}?search=${keyword}`,
    method: "GET",
  },
  sort: true,
  pagination: { limit: 10 },
  server: {
    url: `${config.API_BASE_URL}/PHP/API/role_API.php`,
    method: "GET",
    headers: {
      "Content-Type": "application/json",
    },
    then: (data) =>
      data.map((role) => [
        role.role_id,
        role.nama,
        role.akses,
        null, // Placeholder for the action buttons column
      ]),
  },
}).render(document.getElementById("table_role"));
const input = document.querySelector("#table_role .gridjs-input");
if (input) input.placeholder = "Cari Role...";
attachEventListeners();
document.getElementById("loading_spinner").style.visibility = "hidden";

function attachEventListeners() {
  document
    .getElementById("table_role")
    .addEventListener("click", function (event) {
      const delete_btn = event.target.closest(".delete_role");
      const update_btn = event.target.closest(".update_role");

      if (delete_btn) {
        handleDeleteRole(delete_btn);
      } else if (update_btn) {
        handleUpdateRole(update_btn);
      }
    });
}

async function handleDeleteRole(button) {
  const row = button.closest("tr");
  const roleId = row.cells[0].textContent;
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
      const response = await fetch(
        `${config.API_BASE_URL}/PHP/delete_role.php`,
        {
          method: "DELETE",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({ role_id: roleId }),
        }
      );

      if (response.ok) {
        row.remove();
      } else {
        throw new Error(
          `Failed to delete role. Status: ${response.status}`,
          toastr.error("Failed to delete user.", {
            timeOut: 500,
            extendedTimeOut: 500,
          })
        );
      }
    } catch (error) {
      console.error("Error deleting role:", error);
      toastr.error("Failed to delete role."),
        {
          timeOut: 500,
          extendedTimeOut: 500,
        };
    }
    Swal.fire({
      title: "Berhasil !",
      icon: "success",
    });
  }
}

async function handleUpdateRole(button) {
  const row = button.closest("tr");
  window.currentRow = row;
  const button_icon = button.querySelector(".button_icon");
  const spinner = button.querySelector(".spinner_update");
  button_icon.style.display = "none";
  spinner.style.display = "inline-block";
  const role_ID = row.cells[0].textContent;
  const currentNama = row.cells[1].textContent;
  const currentAkses = row.cells[2].textContent;

  document.getElementById("update_role_ID").value = role_ID;
  document.getElementById("update_role_name").value = currentNama;
  document.getElementById("update_role_akses").value = currentAkses;
  await new Promise((resolve) => setTimeout(resolve, 1000));
  button_icon.style.display = "inline-block";
  spinner.style.display = "none";
  $("#modal_role_update").modal("show");
}

document
  .getElementById("submit_role_update")
  .addEventListener("click", async function () {
    if (!window.currentRow) {
      toastr.error("No row selected for update.", {
        timeOut: 500,
        extendedTimeOut: 500,
      });
      return;
    }

    const row = window.currentRow;
    const role_ID = document.getElementById("update_role_ID").value;
    const newNama = document.getElementById("update_role_name").value;
    const newAkses = document.getElementById("update_role_akses").value;

    try {
      const response = await fetch(
        `${config.API_BASE_URL}/PHP/update_role.php`,
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({
            role_id: role_ID,
            nama: newNama,
            akses: newAkses,
          }),
        }
      );

      if (response.ok) {
        row.cells[1].textContent = newNama;
        row.cells[2].textContent = newAkses;

        $("#modal_role_update").modal("hide");
        Swal.fire({
          title: "Berhasil",
          icon: "success",
        });
      } else {
        throw new Error(`Failed to update role. Status: ${response.status}`);
      }
    } catch (error) {
      console.error("Error updating role:", error);
      toastr.error("Failed to update role.", {
        timeOut: 500,
        extendedTimeOut: 500,
      });
    }
  });
