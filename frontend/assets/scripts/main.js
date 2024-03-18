const BASE_API_URL = "http://localhost:8000/api";
const API_URL = {
  auth: BASE_API_URL + "/auth.php",
};

const getLoggedInUser = () => {
  const user = JSON.parse(localStorage.user ?? "[]");
  if (user?.id && user?.token) {
    return user;
  }
  return false;
};
const setLoggedInUser = (id, token) => {
  localStorage.user = JSON.stringify({
    id: id,
    token: token,
  });
};
