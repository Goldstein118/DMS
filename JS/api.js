import config from "./config.js";

export async function apiRequest(endpoint, method = "GET", body = null) {
  try {
    const options = {
      method,
      headers: {
        "Content-Type": "application/json",
      },
    };

    if (body) {
      options.body = JSON.stringify(body);
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
      toastr.error(responseData.error || "Akses ditolak");
      throw new Error("Access Denied");
    }

    if (!response.ok) {
      toastr.error(responseData.error || "Terjadi kesalahan server");
      throw new Error(responseData.error || "Server error");
    }

    return { ...responseData, ok: response.ok, status: response.status };
  } catch (error) {
    console.error("API Request Error:", error.message);
    throw error;
  }
}
