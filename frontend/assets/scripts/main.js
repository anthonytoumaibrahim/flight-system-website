const BASE_API_URL = "http://localhost:8000/api";
const API_URL = {
  auth: BASE_API_URL + "/auth.php",
  profile: {
    info: BASE_API_URL + "/profile/info.php",
    save: BASE_API_URL + "/profile/save.php",
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

// Events
logoutLinks.forEach((element) =>
  element.addEventListener("click", (e) => {
    e.preventDefault();
    setLoggedInUser(-1);
    window.location.href = element.href;
  })
);
