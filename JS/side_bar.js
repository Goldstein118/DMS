const sidebar = document.getElementById('mySidebar');
const resizer = document.getElementById('resizer');
const toggleBtn = document.getElementById('toggleSidebar');
const main = document.getElementById('main');

let isResizing = false;

// ⏪ Load saved width on page load
window.addEventListener('DOMContentLoaded', () => {
  const savedWidth = localStorage.getItem('sidebarWidth');
  const isHidden = localStorage.getItem('sidebarHidden') === 'true';

  if (savedWidth && !isHidden) {
    sidebar.style.width = savedWidth + 'px';
  }

  if (isHidden) {
    sidebar.style.display = 'none';
    resizer.style.display = 'none';
  }
});

// 🖱️ Start resize
resizer.addEventListener('mousedown', () => {
  isResizing = true;
  document.body.style.cursor = 'ew-resize';
});

// 🏗 Resize in real time
document.addEventListener('mousemove', (e) => {
  if (!isResizing) return;
  const newWidth = e.clientX;
  if (newWidth >= 150 && newWidth <= 400) {
    sidebar.style.width = newWidth + 'px';
    localStorage.setItem('sidebarWidth', newWidth); // 💾 Save width
  }
});

// 🖐 Stop resize
document.addEventListener('mouseup', () => {
  if (isResizing) {
    isResizing = false;
    document.body.style.cursor = 'default';
  }
});

// 📥 Collapse / expand
toggleBtn.addEventListener('click', () => {
  const isCurrentlyVisible = sidebar.style.display !== 'none';

  if (isCurrentlyVisible) {
    sidebar.style.display = 'none';
    resizer.style.display = 'none';
    localStorage.setItem('sidebarHidden', 'true');
  } else {
    sidebar.style.display = 'block';
    resizer.style.display = 'block';

    const savedWidth = localStorage.getItem('sidebarWidth') || '250';
    sidebar.style.width = savedWidth + 'px';
    localStorage.setItem('sidebarHidden', 'false');
  }
});
