const BASE_API_URL = "http://localhost:8000/api";
const API_URL = {
  auth: BASE_API_URL + "/auth.php",
  profile: {
    info: BASE_API_URL + "/profile/info.php",
    save: BASE_API_URL + "/profile/save.php",
    requestCoins: BASE_API_URL + "/profile/requestCoins.php",
  },
  flights: {
    search: BASE_API_URL + "/searchFlight.php",
    book: BASE_API_URL + "/booking.php",
    history: BASE_API_URL + "/bookinghistory.php",
  },
};

const logoutLinks = document.querySelectorAll(".logout-link");

const getLoggedInUser = () => {
  const user = JSON.parse(localStorage.user ?? "[]");
  if (user?.id && user?.token) {
    return user;
  }
  return false;
};
const setLoggedInUser = (id, token, role = "user") => {
  if (id === -1) {
    localStorage.removeItem("user");
    return;
  }
  localStorage.user = JSON.stringify({
    id: id,
    token: token,
    role: role,
  });
};

const airlinesColors = {
  A: "#e11d48",
  D: "#2563eb",
  R: "#65a30d",
};

// Utility functions
const showResponseMessage = (responseElement, error = true, message = "") => {
  responseElement.textContent = message;
  responseElement.classList.remove("hidden");
  responseElement.classList.toggle("text-error", error);
  responseElement.classList.toggle("text-success", !error);
};
const hideResponseMessage = (responseElement) => {
  responseElement.classList.add("hidden");
  responseElement.textContent = "";
};

// Events
logoutLinks.forEach((element) =>
  element.addEventListener("click", (e) => {
    e.preventDefault();
    setLoggedInUser(-1);
    window.location.href = element.href;
  })
);
