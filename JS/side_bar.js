const sidebar = document.getElementById("mySidebar");
const resizer = document.getElementById("resizer");
const main = document.getElementById("main");

let isResizing = false;

window.addEventListener("DOMContentLoaded", () => {
  const savedWidth = localStorage.getItem("sidebarWidth");
  const isHidden = localStorage.getItem("sidebarHidden") === "true";

  if (savedWidth && !isHidden) {
    sidebar.style.width = savedWidth + "px";
  }

  if (isHidden) {
    sidebar.style.display = "none";
    resizer.style.display = "none";
  }
});

// Start resize
resizer.addEventListener("mousedown", () => {
  isResizing = true;
  document.body.style.cursor = "ew-resize";
});

// Resize in real time
document.addEventListener("mousemove", (e) => {
  if (!isResizing) return;
  const newWidth = e.clientX;
  if (newWidth >= 150 && newWidth <= 400) {
    sidebar.style.width = newWidth + "px";
    localStorage.setItem("sidebarWidth", newWidth); //Save width
  }
});

//  Stop resize
document.addEventListener("mouseup", () => {
  if (isResizing) {
    isResizing = false;
    document.body.style.cursor = "default";
  }
});
var dropdown = document.getElementsByClassName("dropdown-btn");
var i;

for (i = 0; i < dropdown.length; i++) {
  dropdown[i].addEventListener("click", function () {
    this.classList.toggle("active");
    var dropdownContent = this.nextElementSibling;
    if (dropdownContent.style.display === "block") {
      dropdownContent.style.display = "none";
    } else {
      dropdownContent.style.display = "block";
    }
  });
}
