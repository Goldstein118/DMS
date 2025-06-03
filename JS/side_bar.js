document.addEventListener("DOMContentLoaded", () => {
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
});
document.addEventListener("DOMContentLoaded", function () {
  const name = localStorage.getItem("nama");
  if (name) {
    document.getElementById("username").textContent = name;
  }
});
