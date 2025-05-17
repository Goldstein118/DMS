import config from "./config.js";

let is_ready = false;
$(document).ready(function () {
  $("#loading_spinner").fadeOut();
  document.getElementById("loading_spinner").classList.remove("hidden");
  let table = $("#table_karyawan").DataTable({
    dom: "lrtip",
    paging: false,
    scrollCollapse: true,
    scrollY: "78vh",
    language: {
      emptyTable: "",
      zeroRecords: "",
    },
  });
  $("#search_karyawan").on("keyup", function () {
    table.search(this.value).draw();
  });
});
$("#modal_karyawan_update").on("shown.bs.modal");

// Fetch and populate the karyawan table
fetch(`${config.API_BASE_URL}/PHP/API/karyawan_API.php`)
  .then((response) => response.json())
  .then((karyawan) => {
    const tableBody = document.getElementById("karyawan_table_body");
    karyawan.forEach((karyawan) => {
      const row = document.createElement("tr");
      row.innerHTML = `
                <td>${karyawan.karyawan_id}</td>
                <td>${karyawan.nama}</td>
                <td>${karyawan.role_nama}</td>
                <td>${karyawan.divisi}</td>
                <td>${karyawan.noTelp}</td>
                <td>${karyawan.alamat}</td>
                <td>${karyawan.ktp}</td>
                <td>${karyawan.npwp}</td>
                <td>${karyawan.status}</td>
                <td>
                <button type="button" class="btn btn-warning update_karyawan" data-bs-toggle="modal" data-bs-target="#modal_karyawan_update">
                <i class="bi bi-pencil-square"></i>
                </button>

                <button type="button" class="btn btn-danger delete_karyawan"><i class="bi bi-trash-fill"></i>
                </button>

                </td>
            `;

      tableBody.appendChild(row);
      console.log(karyawan.karyawan_id);
    });
    is_ready = true;
    console.log(is_ready);
    attachEventListeners();
    document.querySelector("table").classList.add("visible");
    document.getElementById("loading_spinner").classList.add("hidden");
  })
  .catch((error) => {
    console.error("Error fetching karyawan:", error);
    document.getElementById("loading_spinner").classList.add("hidden");
  });

function attachEventListeners() {
  document
    .getElementById("karyawan_table_body")
    .addEventListener("click", function (event) {
      if (event.target.classList.contains("delete_karyawan")) {
        handleDeleteKaryawan(event.target);
      } else if (event.target.classList.contains("update_karyawan")) {
        handleUpdateKaryawan(event.target);
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

// Attach update listeners
function handleUpdateKaryawan(button) {
  if (is_ready) {
    const row = button.closest("tr");
    const karyawan_ID = row.cells[0].textContent;
    const currentNama = row.cells[1].textContent;
    const currentrole_ID = row.cells[2].textContent;
    const currentdivisi = row.cells[3].textContent;
    const currentnoTelp = row.cells[4].textContent;
    const currentalamat = row.cells[5].textContent;
    const currentKTP_NPWP = row.cells[6].textContent;
    const currentnpwp = row.cells[7].textContent;
    const currentstatus = row.cells[8].textContent;
    console.log("Button_pressed");
    console.log(karyawan_ID);
    document.getElementById("update_karyawan_ID").value = karyawan_ID;
    document.getElementById("update_name_karyawan").value = currentNama;
    document.getElementById("update_divisi_karyawan").value = currentdivisi;
    document.getElementById("update_phone_karyawan").value = currentnoTelp;
    document.getElementById("update_address_karyawan").value = currentalamat;
    document.getElementById("update_nik_karyawan").value = currentKTP_NPWP;
    document.getElementById("update_npwp_karyawan").value = currentnpwp;
    document.getElementById("update_status_karyawan").value = currentstatus;
    const role_ID_Field = $("#update_role_select");
    fetch(`${config.API_BASE_URL}/PHP/API/role_API.php`)
      .then((response) => response.json())
      .then((roles) => {
        role_ID_Field.empty();
        roles.forEach((role) => {
          const option = new Option(
            role.nama,
            role.role_id,
            false,
            role.role_id === currentrole_ID
          );
          role_ID_Field.append(option);
        });
        role_ID_Field.trigger("change");
      })
      .catch((error) => {
        console.error("Error fetching roles:", error);
      });
    window.currentRow = row;
  } else {
    document.getElementById("loading_spinner").classList.add("hidden");
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

        toastr.success("Karyawan updated successfully", {
          timeOut: 500,
          extendedTimeOut: 500,
        });
        $("#modal_karyawan_update").modal("hide");
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
