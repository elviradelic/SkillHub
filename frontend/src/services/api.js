import axios from "axios";

const API = axios.create({
  baseURL: "https://skillhub-production-37e5.up.railway.app",
  headers: {
    "Content-Type": "application/json",
  },
});

export default API;