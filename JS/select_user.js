import config from "../JS/config.js";
import { Grid, html } from "https://unpkg.com/gridjs?module";

const grid_container_user = document.querySelector("#table_user");
if (grid_container_user) {
  $(document).ready(function () {
    $("#update_karyawan_ID").select2({
      allowClear: true,
      dropdownParent: $("#modal_user_update"),
    });
  });
  new Grid({
    columns: [
      "Username",
      "Nama Karyawan",
      "karyawan_id",
      "Level",
      {
        name: "Aksi",
        formatter: () => {
          return html(`
        <button type="button"  id ="update_user_button" class="btn btn-warning update_user btn-sm">
          <span id ="button_icon" class="button_icon"><i class="bi bi-pencil-square"></i></span>
          <span id="spinner_update" class="spinner-border spinner-border-sm spinner_update" style="display: none;" role="status" aria-hidden="true"></span>
        </button>
        
        <button type="button" class="btn btn-danger delete_user btn-sm">
                    <i class="bi bi-trash-fill"></i>
        </button>
        `);
        },
      },
    ],
    search: {
      enabled: true,
      server: {
        url: (prev, keyword) => {
          if (keyword.length >= 5 && keyword !== "") {
            return `${prev}?search=${encodeURIComponent(keyword)}`;
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
      url: `${config.API_BASE_URL}/PHP/API/user_API.php`,
      method: "GET",
      headers: {
        "Content-Type": "application/json",
      },
      then: (data) =>
        data.map((user) => [
          user.user_id,
          user.karyawan_nama,
          user.karyawan_id,
          user.level,
          null,
        ]),
    },
  }).render(document.getElementById("table_user"));
  setTimeout(() => {
    const grid_header = document.querySelector("#table_user .gridjs-head");
    const search_Box = grid_header.querySelector(".gridjs-search");

    const btn = document.createElement("button");
    btn.type = "button";
    btn.className = "btn btn-primary btn-sm";
    btn.setAttribute("data-bs-toggle", "modal");
    btn.setAttribute("data-bs-target", "#modal_user");
    btn.innerHTML = '<i class="bi bi-person-plus-fill"></i> User';

    const wrapper = document.createElement("div");
    wrapper.className =
      "d-flex justify-content-between align-items-center mb-3";
    wrapper.appendChild(btn);
    wrapper.appendChild(search_Box);

    grid_header.innerHTML = "";
    grid_header.appendChild(wrapper);
    const input = document.querySelector("#table_user .gridjs-input");
    grid_header.style.display = "flex";
    grid_header.style.justifyContent = "flex-end";

    search_Box.style.display = "flex";
    search_Box.style.justifyContent = "flex-end";
    search_Box.style.marginLeft = "auto";
    input.placeholder = "Cari User...";
    document.getElementById("loading_spinner").style.visibility = "hidden";
    $("#loading_spinner").fadeOut();
    attachEventListeners();
  }, 200);
}

function attachEventListeners() {
  document
    .getElementById("table_user")
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
        Swal.fire({
          title: "Berhasil!",
          text: "Data User berhasil dihapus!",
          icon: "success",
        });
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
  }
}

async function handleUpdateUser(button) {
  const row = button.closest("tr");
  window.currentRow = row;
  const button_icon = button.querySelector(".button_icon");
  const spinner = button.querySelector(".spinner_update");
  button_icon.style.display = "none";
  spinner.style.display = "inline-block";

  const userID = row.cells[0].textContent;
  const karyawanID = row.cells[2].textContent;
  const level = row.cells[3].textContent;

  document.getElementById("update_user_ID").value = userID;
  document.getElementById("update_level").value = level;

  const karyawan_ID_field = $("#update_karyawan_ID");
  await new Promise((resolve) => setTimeout(resolve, 500));
  try {
    const response = await fetch(`${config.API_BASE_URL}/PHP/API/user_API.php`);
    const karyawans = await response.json();

    karyawan_ID_field.empty();

    karyawans.forEach((karyawan) => {
      const option = new Option(
        `${karyawan.karyawan_id} - ${karyawan.karyawan_nama}`,
        karyawan.karyawan_id,
        false,
        karyawan.karyawan_id === karyawanID
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
}
const submit_user_update = document.getElementById("submit_user_update");
if (submit_user_update) {
  submit_user_update.addEventListener("click", async function () {
    if (!window.currentRow) {
      toastr.error("No row selected for update.", {
        timeOut: 500,
        extendedTimeOut: 500,
      });
      return;
    }
    const row = window.currentRow;
    const User_ID = document.getElementById("update_user_ID").value;
    const level = document.getElementById("update_level").value;
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
            level: level,
          }),
        }
      );
      if (response.ok) {
        const karyawan_name_new = $(
          "#update_karyawan_ID option:selected"
        ).text();
        const karyawan_name_only = karyawan_name_new.split(" - ")[1];
        row.cells[1].textContent = karyawan_name_only;
        row.cells[3].textContent = level;
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
}
