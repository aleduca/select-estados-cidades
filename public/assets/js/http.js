import axios from 'axios';

const axiosInstance = axios.create({
  headers: {
    'Content-type': 'application/json',
    HTTP_X_REQUESTED_WITH: 'XMLHttpRequest',
  },
  baseURL: 'http://localhost:8000',
});

export default axiosInstance;
