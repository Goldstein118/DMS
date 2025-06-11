import { apiRequest } from "./api.js";
import * as access from "./cek_access.js";
const submit_divisi = document.getElementById("submit_divisi");
if (submit_divisi) {
  submit_divisi.addEventListener("click", submitdivisi);
  $("#modal_divisi").on("shown.bs.modal", function () {
    $("#divisi_nama").trigger("focus");
  });
}
function validateField(field, pattern, errorMessage) {
  if (!pattern.test(field)) {
    toastr.error(errorMessage, {
      timeOut: 500,
      extendedTimeOut: 500,
    });
    return false;
  }
  return true;
}

async function submitdivisi() {
  const divisi_nama = document.getElementById("divisi_nama").value;
  const nama_bank = document.getElementById("nama_bank").value;
  const nama_rekening = document.getElementById("nama_rekening").value;
  const nomor_rekening = document.getElementById("nomor_rekening").value;

  if (
    !divisi_nama ||
    divisi_nama.trim() === "" ||
    !nama_bank ||
    nama_bank.trim() === "" ||
    !nama_rekening ||
    nama_rekening.trim() === "" ||
    !nomor_rekening ||
    nomor_rekening.trim() === ""
  ) {
    toastr.error("Kolom * wajib diisi..");
    return;
  }
  const is_valid =
    validateField(divisi_nama, /^[a-zA-Z\s]+$/, "Format nama tidak valid") &&
    validateField(nama_bank, /^[a-zA-Z\s]+$/, "Format nama bank tidak valid") &&
    validateField(
      nama_rekening,
      /^[a-zA-Z\s.]+$/,
      "Format nama rekening tidak valid"
    ) &&
    validateField(
      nomor_rekening,
      /^[0-9]+$/,
      "Format nomor rekening tidak valid"
    );

  if (is_valid) {
    const divisi_data = {
      user_id: `${access.decryptItem("user_id")}`,
      nama: divisi_nama,
      bank: nama_bank,
      nama_rekening: nama_rekening,
      no_rekening: nomor_rekening,
    };

    try {
      const response = await apiRequest(
        `/PHP/API/divisi_API.php?action=create`,
        "POST",
        divisi_data
      );

      document.getElementById("divisi_nama").value = "";
      document.getElementById("nama_bank").value = "";
      document.getElementById("nama_rekening").value = "";
      document.getElementById("nomor_rekening").value = "";
      $("#modal_divisi").modal("hide");
      swal.fire("Berhasil", response.message, "success");
    } catch (error) {
      toastr.error(error.message);
    }
  }
}
