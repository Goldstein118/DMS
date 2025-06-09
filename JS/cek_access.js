/*
function generateSecretKey() {
  if (window.crypto && window.crypto.getRandomValues) {
    const entropyArray = new Uint32Array(32);
    window.crypto.getRandomValues(entropyArray);
    sjcl.random.addEntropy(entropyArray, 256);
  }

  const keyBits = sjcl.random.randomWords(8);
  return sjcl.codec.hex.fromBits(keyBits);
}

export function clear_local_storage() {
  const keysToClear = ["user_id", "level", "akses", "nama"];
  keysToClear.forEach((key) => localStorage.removeItem(key));
}
*/
export const secretKey =
  "D8842wrd&~l;X??]gQD]~+4}E2EAAFCA9D28[lY[`k#~b!2j94C2344491B58";
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
  tb_divisi: 36,
};

const actionIndex = {
  view: 0,
  create: 1,
  edit: 2,
  delete: 3,
};

const level = decryptItem("level");

// Checks if user is an owner
export function isOwner() {
  return (level || "").toLowerCase() === "owner";
}

// Check access for user-level (based on binary string)
export function hasAccess(table, action) {
  if (isOwner()) return true;

  const akses = decryptItem("akses") || "";
  const baseIndex = accessMap[table];
  const offset = actionIndex[action];

  if (baseIndex === undefined || offset === undefined) return false;

  const index = baseIndex + offset;
  return akses.charAt(index) === "1";
}
export function decryptItem(key) {
  const encrypted = localStorage.getItem(key);
  if (!encrypted) return null;
  try {
    return sjcl.decrypt(secretKey, encrypted);
  } catch (e) {
    console.error("Decryption error:", e);
    return null;
  }
}
