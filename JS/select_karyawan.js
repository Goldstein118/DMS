import config from "./config.js";
import { Grid, html } from "https://unpkg.com/gridjs?module";

$(document).ready(function () {
  $("#loading_spinner").fadeOut();
  fetch_karyawan();
});

async function fetch_karyawan() {
  try {
    // Start loading spinner or loading UI here
    document.getElementById("loading_spinner").style.visibility = "visible";

    // Fetch karyawan data and wait for the response
    await new Promise((resolve) => setTimeout(resolve, 1000));
    const response = await fetch(
      `${config.API_BASE_URL}/PHP/API/karyawan_API.php`
    );
    if (!response.ok) {
      throw new Error(`Failed to fetch user. Status: ${response.status}`);
    }
    const karyawan = await response.json();

    // Process the data and populate the table
    const tableBody = document.getElementById("karyawan_table_body");
    karyawan.forEach((karyawan) => {
      const row = document.createElement("tr");
      row.innerHTML = `
                <td>${karyawan.karyawan_id}</td>
                <td>${karyawan.nama}</td>
                <td data-role-id = "${karyawan.role_id}">${karyawan.role_nama}</td>
                <td>${karyawan.divisi}</td>
                <td>${karyawan.noTelp}</td>
                <td>${karyawan.alamat}</td>
                <td>${karyawan.ktp}</td>
                <td>${karyawan.npwp}</td>
                <td>${karyawan.status}</td>
                <td>
                  <button type="button"  id ="update_karyawan_button" class="btn btn-warning update_karyawan">
                    <span id ="button_icon" class="button_icon"><i class="bi bi-pencil-square"></i></span>
                    <span id="spinner_update" class="spinner-border spinner-border-sm spinner_update" style="display: none;" role="status" aria-hidden="true"></span>
                  </button>
                  <button type="button" class="btn btn-danger delete_karyawan">
                    <i class="bi bi-trash-fill"></i>
                  </button>
                </td>
            `;
      tableBody.appendChild(row);
    });
    $(document).ready(function () {
      let table = $("#table_karyawan").DataTable({
        dom: "lrtip",
        order: [
          [3, "desc"],
          [0, "asc"],
        ],
        paging: false,
        scrollCollapse: true,
        scrollY: "75vh",
        language: {
          emptyTable: "",
          zeroRecords: "",
        },
      });
      $("#search_karyawan").on("keyup", function () {
        table.search(this.value).draw();
      });
    });
    attachEventListeners();
    document.getElementById("loading_spinner").style.visibility = "hidden";
  } catch (error) {
    console.error("Error fetching karyawan:", error);

    // Hide loading spinner in case of error
    setTimeout();
  }
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
      const response = await fetch(
        `${config.API_BASE_URL}/PHP/delete_karyawan.php`,
        {
          method: "DELETE",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({ karyawan_id: karyawan_ID }),
        }
      );
      if (response.ok) {
        row.remove();
      } else {
        throw new Error(
          `Failed to delete karyawan. Status: ${response.status}`
        );
      }
    } catch (error) {
      console.error("Error deleting karyawan:", error);
      toastr.error("Failed to delete karyawan.", {
        timeOut: 500,
        extendedTimeOut: 500,
      });
    }
    Swal.fire({
      title: "Berhasil !",
      text: "Data Karyawan berhasil dihapus!",
      icon: "success",
    });
  }
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
  const currentrole_nama = row.cells[2].textContent;
  const roleCell = row.cells[2];
  const currentrole_id = roleCell.getAttribute("data-role-id");
  const currentdivisi = row.cells[3].textContent;
  const currentnoTelp = row.cells[4].textContent;
  const currentalamat = row.cells[5].textContent;
  const currentKTP_NPWP = row.cells[6].textContent;
  const currentnpwp = row.cells[7].textContent;
  const currentstatus = row.cells[8].textContent;

  console.log("Button_pressed");
  console.log(karyawan_ID);
  console.log(currentrole_nama);

  // Populate the modal fields
  document.getElementById("update_karyawan_ID").value = karyawan_ID;
  document.getElementById("update_name_karyawan").value = currentNama;
  document.getElementById("update_divisi_karyawan").value = currentdivisi;
  document.getElementById("update_phone_karyawan").value = currentnoTelp;
  document.getElementById("update_address_karyawan").value = currentalamat;
  document.getElementById("update_nik_karyawan").value = currentKTP_NPWP;
  document.getElementById("update_npwp_karyawan").value = currentnpwp;
  document.getElementById("update_status_karyawan").value = currentstatus;

  const role_ID_Field = $("#update_role_select");
  await new Promise((resolve) => setTimeout(resolve, 1000));
  try {
    // Fetch the roles data using async/await
    const response = await fetch(`${config.API_BASE_URL}/PHP/API/role_API.php`);
    const roles = await response.json(); // Wait for the JSON data

    // Clear the role options and populate the dropdown
    role_ID_Field.empty();
    roles.forEach((role) => {
      const option = new Option(
        `${role.role_id} - ${role.nama}`,
        role.role_id,
        false,
        role.role_id == currentrole_id
      );
      role_ID_Field.append(option);
    });

    role_ID_Field.trigger("change"); // Trigger change event after appending options
    console.log(document.getElementById("update_role_select").value);

    button_icon.style.display = "inline-block";
    spinner.style.display = "none";
    $("#modal_karyawan_update").modal("show");
  } catch (error) {
    console.error("Error fetching user:", error);
    const button_icon = button.querySelector(".button_icon");
    const spinner = button.querySelector(".spinner_update");
    button_icon.style.display = "none";
    spinner.style.display = "inline-block";
  }
}

// Submit updated karyawan
document
  .getElementById("submit_karyawan_update")
  .addEventListener("click", async function () {
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
    const noTelp_new = document.getElementById("update_phone_karyawan").value;
    const alamat_new = document.getElementById("update_address_karyawan").value;
    const KTP_NPWP_new = document.getElementById("update_nik_karyawan").value;
    const npwp_new = document.getElementById("update_npwp_karyawan").value;
    const status_new = document.getElementById("update_status_karyawan").value;

    try {
      const response = await fetch(
        `${config.API_BASE_URL}/PHP/update_karyawan.php`,
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({
            karyawan_id: karyawan_ID,
            nama: karyawan_nama_new,
            role_id: role_ID_new,
            divisi: divisi_new,
            noTelp: noTelp_new,
            alamat: alamat_new,
            ktp: KTP_NPWP_new,
            npwp: npwp_new,
            status: status_new,
          }),
        }
      );
      if (response.ok) {
        row.cells[1].textContent = karyawan_nama_new;
        row.cells[2].textContent = role_ID_new;
        row.cells[3].textContent = divisi_new;
        row.cells[4].textContent = noTelp_new;
        row.cells[5].textContent = alamat_new;
        row.cells[6].textContent = KTP_NPWP_new;
        row.cells[7].textContent = npwp_new;
        row.cells[8].textContent = status_new;

        $("#modal_karyawan_update").modal("hide");
        Swal.fire({
          title: "Berhasil",
          icon: "success",
        });
        Grid.forceRender(); // re-fetch from API
      } else {
        throw new Error(
          `Failed to update karyawan. Status: ${response.status}`
        );
      }
    } catch (error) {
      console.error("Error updating karyawan:", error);
      toastr.error("Failed to update karyawan.", {
        timeOut: 500,
        extendedTimeOut: 500,
      });
    }
  });
