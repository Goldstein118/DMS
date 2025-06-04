import * as access from "./cek_access.js";
import { apiRequest } from "./api.js";
document.addEventListener("DOMContentLoaded", async () => {
  document.querySelectorAll(".btn-toggle").forEach((btn) => {
    const icon = btn.querySelector(".toggle-icon");
    const targetId = icon?.getAttribute("data-bs-target");
    const target = targetId ? document.querySelector(targetId) : null;

    if (target) {
      target.addEventListener("show.bs.collapse", () => {
        icon.classList.remove("bi-chevron-right");
        icon.classList.add("bi-chevron-down");
      });
      target.addEventListener("hide.bs.collapse", () => {
        icon.classList.remove("bi-chevron-down");
        icon.classList.add("bi-chevron-right");
      });
    }
  });

  localStorage.setItem("user_id", "US0525-041");

  try {
    const response = await apiRequest(
      `/PHP/API/get_akses_data.php?user_id=${localStorage.getItem("user_id")}`
    );
    console.log(response);
    if (response.level && response.akses) {
      localStorage.setItem("level", response.level);
      localStorage.setItem("akses", response.akses);
      localStorage.setItem("nama", response.nama);
    } else {
      console.log(response);
    }
  } catch (error) {
    toastr.error(error.message);
  }
  console.log("Akses:", localStorage.getItem("akses"));
  console.log("Can Create:", access.hasAccess("tb_karyawan", "create"));
  console.log("Can Edit:", access.hasAccess("tb_karyawan", "edit"));
  console.log("Can Delete:", access.hasAccess("tb_karyawan", "delete"));

  const name = localStorage.getItem("nama");
  console.log("a");
  if (name) {
    document.getElementById("username").textContent = name;
    console.log("b");
  }
});
