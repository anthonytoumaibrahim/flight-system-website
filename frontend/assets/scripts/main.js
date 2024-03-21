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
  admin: {
    fetchBookings: BASE_API_URL + "/bookinginfo.php",
    countUsers: BASE_API_URL + "/countusers.php",
    countRevenue: BASE_API_URL + "/countrevenue.php",
    addFlight: BASE_API_URL + "/add-delete-flights.php"
  },
};

const logoutLinks = document.querySelectorAll(".logout-link");
const menuHamburger = document.querySelector(".menu-hamburger");
const siteNav = document.querySelector(".site-nav");

const getLoggedInUser = () => {
  const user = JSON.parse(localStorage.user ?? "[]");
  if (user?.id && user?.token) {
    return user;
  }
  return false;
};
const setLoggedInUser = (id, token, role = "user") => {
  // For logout, reset localstorage item
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

menuHamburger?.addEventListener("click", () => {
  siteNav.classList.toggle("nav-mobile");
});

// Detect click outside menu to hide it
// Thanks to: https://stackoverflow.com/a/28432139
document.addEventListener("click", (e) => {
  if (!siteNav?.contains(e.target) && !menuHamburger?.contains(e.target)) {
    siteNav?.classList.toggle("nav-mobile", false);
  }
});
