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

  const secretKey = access.secretKey;
  const encrypt_user_id = sjcl.encrypt(secretKey, "US0525-041");
  localStorage.setItem("user_id", encrypt_user_id);

  try {
    const response = await apiRequest(
      `/PHP/API/get_akses_data.php?user_id=${access.decryptItem("user_id")}`
    );
    if (response.level && response.akses) {
      localStorage.setItem("level", sjcl.encrypt(secretKey, response.level));
      localStorage.setItem("akses", sjcl.encrypt(secretKey, response.akses));
      localStorage.setItem("nama", sjcl.encrypt(secretKey, response.nama));
      console.log(access.decryptItem("akses"));
    } else {
      console.log(response);
    }
  } catch (error) {
    toastr.error(error.message);
  }

  const nama = access.decryptItem("nama");

  if (nama) {
    document.getElementById("username").textContent = nama;
    document.getElementById("username_mobile").textContent = nama;
  }
});
