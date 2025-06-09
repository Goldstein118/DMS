import config from "./config.js";

export async function apiRequest(endpoint, method = "GET", body = null) {
  try {
    const options = {
      method,
      headers: {},
    };

    if (body && !(body instanceof FormData)) {
      options.headers["Content-Type"] = "application/json";
      options.body = JSON.stringify(body);
    } else if (body instanceof FormData) {
      options.body = body;
    }

    const response = await fetch(`${config.API_BASE_URL}${endpoint}`, options);
    const contentType = response.headers.get("Content-Type");

    let responseData;
    if (contentType && contentType.includes("application/json")) {
      responseData = await response.json();
    } else {
      responseData = { message: await response.text() };
    }

    if (response.status === 403) {
      Swal.fire({
        icon: "error",
        title: "Gagal",
        text: responseData.error || "Akses ditolak",
      });
      throw new Error("Access Denied");
    }

    if (!response.ok) {
      Swal.fire({
        icon: "error",
        title: "Gagal",
        text: responseData.error || "Terjadi kesalahan server",
      });

      throw new Error(responseData.error || "Server error");
    }
    if (Array.isArray(responseData)) {
      return {
        ok: true,
        status: response.status,
        data: responseData,
      };
    }
    return { ...responseData, ok: true, status: response.status };
  } catch (error) {
    console.error("API Request Error:", error.message);
    throw error;
  }
}
