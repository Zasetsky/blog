import axios from "axios";

const httpClient = axios.create({
  baseURL: process.env.VUE_APP_API_BASE_URL || "http://localhost:8000/api",
  headers: {
    "Content-Type": "application/json",
  },
});

// Интерцептор для обработки ошибок
httpClient.interceptors.response.use(
  (response) => response,
  (error) => {
    console.error("Ошибка API:", error);
    return Promise.reject(error);
  }
);

export default httpClient;
