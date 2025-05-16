import config from "../JS/config.js";

$(document).ready(function () {
  $("#table_user").DataTable({
    paging: false,
    searching: false,
    ordering: false,
    info: false,
    language: {
      emptyTable: "",
      zeroRecords: "",
    },
  });
  fetchUser();
});

async function fetchUser() {
  try {
    const reponse = await fetch(`${config.API_BASE_URL}/PHP/API/user_API.php`);
    if (!reponse.ok) {
      throw new Error(`Failed to fetch user. Status: ${reponse.status}`);
    }
    const users = await reponse.json();
    populateUserTable(users);
  } catch (error) {
    console.error("Error fetching user:", error);
    toastr.error("Failed to load user.");
  }
}

function populateUserTable(users) {
  const tableBody = document.getElementById("user_table_body");
  tableBody.innerHTML = ""; // Clear existing rows
  users.forEach((user) => {
    const row = document.createElement("tr");

    row.innerHTML = `
                <td>${user.user_id}</td>
                <td>${user.karyawan_id}</td>
                <td>
                <button type="button" class="btn btn-warning update_user"
                data-toggle="modal" data-target="#modal_user_update"><i class="bi bi-pencil-square"></i></button>
                <button type="button" class="btn btn-danger delete_user"><i class="bi bi-trash-fill"></i></button>
                </td>
            `;

    tableBody.appendChild(row);
  }, attachEventListeners());
}

function attachEventListeners() {
  document
    .getElementById("user_table_body")
    .addEventListener("click", function (event) {
      if (event.target.classList.contains("delete_user")) {
        handleDeleteUser(event.target);
      } else if (event.target.classList.contains("update_user")) {
        handleUpdateUser(event.target);
      }
    });
}
async function handleDeleteUser(button) {
  const row = button.closest("tr");
  const userID = row.cells[0].textContent;
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
        `${config.API_BASE_URL}/PHP/delete_user.php`,
        {
          method: "DELETE",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({ user_id: userID }), // Convert the data object to JSON
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
      title: "Berhasil!",
      text: "Data User berhasil dihapus.",
      icon: "success",
    });
  }
}

async function handleUpdateUser(button) {
  const row = button.closest("tr");
  const userID = row.cells[0].textContent;
  const karyawanID = row.cells[1].textContent;

  document.getElementById("update_user_ID").value = userID;

  const karyawan_ID_field = $("#update_karyawan_ID");

  try {
    const response = await fetch(
      `${config.API_BASE_URL}/PHP/API/karyawan_API.php`
    );
    const karyawans = await response.json();

    karyawan_ID_field.empty();

    karyawans.forEach((karyawan) => {
      const isSelected = karyawan.karyawan_id === karyawanID;
      const option = new Option(
        `${karyawan.karyawan_id} - ${karyawan.nama}`,
        karyawan.karyawan_id,
        isSelected,
        isSelected
      );
      karyawan_ID_field.append(option);
    });

    karyawan_ID_field.trigger("change");

    $("#modal_user_update").modal("show");
  } catch (error) {
    console.error("Error fetching karyawan:", error);
    toastr.error("Failed to load karyawan.");
  }

  window.currentRow = row;
}

document
  .getElementById("submit_user_update")
  .addEventListener("click", async function () {
    if (!window.currentRow) {
      toastr.error("No row selected for update.", {
        timeOut: 500,
        extendedTimeOut: 500,
      });
      return;
    }
    const row = window.currentRow;
    const User_ID = document.getElementById("update_user_ID").value;
    const karyawan_ID_new = $("#update_karyawan_ID").val();

    try {
      const response = await fetch(
        `${config.API_BASE_URL}/PHP/update_user.php`,
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({
            user_id: User_ID,
            karyawan_id: karyawan_ID_new,
          }),
        }
      );
      if (response.ok) {
        row.cells[1].textContent = karyawan_ID_new;
        toastr.success("User updated successfully.", {
          timeOut: 500,
          extendedTimeOut: 500,
        });
        $("#modal_user_update").modal("hide");
      } else {
        throw new Error(`Failed to update user. Status: ${response.status}`);
      }
    } catch (error) {
      console.error("Error updating user:", error);
      toastr.error("Failed to update user.", {
        timeOut: 500,
        extendedTimeOut: 500,
      });
    }
  });
