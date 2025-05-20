import config from "../JS/config.js";

$(document).ready(function () {
  $("#loading_spinner").fadeOut();

  fetch_user();
});

async function fetch_user() {
  try {
    document.getElementById("loading_spinner").style.visibility = "visible";

    await new Promise((resolve) => setTimeout(resolve, 1000));
    const response = await fetch(`${config.API_BASE_URL}/PHP/API/user_API.php`);
    if (!response.ok) {
      throw new Error(`Failed to fetch user. Status: ${response.status}`);
    }
    const users = await response.json();

    const tableBody = document.getElementById("user_table_body");

    users.forEach((user) => {
      const row = document.createElement("tr");

      row.innerHTML = `
                <td>${user.user_id}</td>
                <td>${user.karyawan_nama}</td>
                <td>
                <button type="button" id= "update_user_button"class="btn btn-warning update_user">
                 <span id ="button_icon" class="button_icon"><i class="bi bi-pencil-square"></i></span>
                 <span id="spinner_update" class="spinner-border spinner-border-sm spinner_update" style="display: none;" role="status" aria-hidden="true"></span>
                 </button>
                <button type="button" class="btn btn-danger delete_user"><i class="bi bi-trash-fill"></i></button>
                </td>
            `;

      tableBody.appendChild(row);
    });
    attachEventListeners();
    $(document).ready(function () {
      let table = $("#table_user").DataTable({
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
      $("#search_user").on("keyup", function () {
        table.search(this.value).draw();
      });
    });
    document.getElementById("loading_spinner").style.visibility = "hidden";
  } catch (error) {
    console.error("Error fetching user:", error);
    toastr.error("Failed to load user.");
    setTimeout();
  }
}

function attachEventListeners() {
  document
    .getElementById("user_table_body")
    .addEventListener("click", function (event) {
      const delete_btn = event.target.closest(".delete_user");
      const update_btn = event.target.closest(".update_user");

      if (delete_btn) {
        handleDeleteUser(delete_btn);
      } else if (update_btn) {
        handleUpdateUser(update_btn);
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

      icon: "success",
    });
  }
}

async function handleUpdateUser(button) {
  const row = button.closest("tr");
  const button_icon = button.querySelector(".button_icon");
  const spinner = button.querySelector(".spinner_update");
  button_icon.style.display = "none";
  spinner.style.display = "inline-block";

  const userID = row.cells[0].textContent;
  const karyawanID = row.cells[1].textContent;

  document.getElementById("update_user_ID").value = userID;

  const karyawan_ID_field = $("#update_karyawan_ID");
  await new Promise((resolve) => setTimeout(resolve, 1000));
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

    button_icon.style.display = "inline-block";
    spinner.style.display = "none";
    $("#modal_user_update").modal("show");
  } catch (error) {
    console.error("Error fetching user:", error);
    const button_icon = button.querySelector(".button_icon");
    const spinner = button.querySelector(".spinner_update");
    button_icon.style.display = "none";
    spinner.style.display = "inline-block";
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
        $("#modal_user_update").modal("hide");
        Swal.fire({
          title: "Berhasil",
          icon: "success",
        });
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
