// access.js

const accessMap = {
  tb_karyawan: 0,
  tb_user: 4,
  tb_role: 8,
  tb_supplier: 12,
  tb_customer: 16,
  tb_channel: 20,
  tb_kategori: 24,
  tb_brand: 28,
  tb_produk: 32,
};

const actionIndex = {
  view: 0,
  create: 1,
  edit: 2,
  delete: 3,
};

// Checks if user is an owner
export function isOwner() {
  return (localStorage.getItem("level") || "").toLowerCase() === "owner";
}

// Check access for user-level (based on binary string)
export function hasAccess(table, action) {
  if (isOwner()) return true;

  const akses = localStorage.getItem("akses") || "";
  const baseIndex = accessMap[table];
  const offset = actionIndex[action];

  if (baseIndex === undefined || offset === undefined) return false;

  const index = baseIndex + offset;
  return akses.charAt(index) === "1";
}
