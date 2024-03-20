// If user isn't admin, redirect to home
if (getLoggedInUser().role !== "admin") {
  window.location.href = "../../index.html";
}

const sideLinksEl = document.querySelectorAll(
  ".sidebar .side-menu li a:not(.logout)"
);

sideLinksEl.forEach((links) => {
  const li = links.parentElement;
  links.addEventListener("click", () => {
    sideLinksEl.forEach((i) => {
      i.parentElement.classList.remove("active");
    });
    li.classList.add("active");
  });
});

const menuBar = document.querySelector(".content nav .bx.bx-menu");
const sideBarEl = document.querySelector(".sidebar");

menuBar.addEventListener("click", () => {
  sideBarEl.classList.toggle("close");
});

const searchBtn = document.querySelector(
  ".content nav form .form-input button"
);
const searchIcon = document.querySelector(
  ".content nav form .form-input button .bx"
);
const searchForm = document.querySelector(".content nav form");

searchBtn.addEventListener("Click", function (e) {
  if (window.innerWidth < 576) {
    e.preventDefault;
    searchForm.classList.toggle("show");
    if (searchForm.classList.contains("show")) {
      searchIcon.classList.replace("bx-search", "bx-x");
    } else {
      searchIcon.classList.replace("bx-x", "bx-search");
    }
  }
});

window.addEventListener("resize", () => {
  if (window.innerWidth < 768) {
    sideBarEl.classList.add("close");
  } else {
    sideBarEl.classList.remove("close");
  }
});

const darkEl = document.querySelector(".side-menu ul li a");
const darkIcon = document.querySelector(".side-menu ul li .bx.bx-moon");

darkEl.addEventListener("click", () => {
  document.body.classList.toggle("dark");

  if (document.body.classList.contains("dark")) {
    darkIcon.classList.replace("bx-moon", "bx-sun");
  } else {
    darkIcon.classList.replace("bx-sun", "bx-moon");
  }
});

function fetchBookings() {
  fetch(API_URL.admin.fetchBookings)
    .then((response) => {
      if (!response.ok) {
        throw new Error("Failed to fetch bookings");
      }
      return response.json();
    })
    .then((data) => {
      displayBookings(data);
      document.getElementById("total-bookings").textContent = data.length ?? 0;
    })
    .catch((error) => {
      console.error("Error fetching bookings:", error.message);
    });
}

function displayBookings(bookings) {
  const bookingsContainer = document.getElementById("bookings-table");
  bookingsContainer.innerHTML = "";
  let id = 0;
  bookings.forEach((booking) => {
    id++;
    const bookingElement = document.createElement("tr");
    bookingElement.classList.add("booking");

    const html = `
        <td>${id}</td>
        <td>${booking.booking_id}</td>
        <td>${booking.airline_name} ${booking.flight_number}</td>
        <td>${new Date(booking.depart_datetime).toLocaleString()} - ${new Date(
      booking.arrival_datetime
    ).toLocaleString()}</td>
        <td><span class="status completed">Active</span></td>
      `;
    bookingElement.innerHTML = html;

    bookingsContainer.appendChild(bookingElement);
  });
}

function fetchUserCount() {
  fetch(API_URL.admin.countUsers)
    .then((response) => {
      if (!response.ok) {
        throw new Error("Failed to fetch user count");
      }
      return response.json();
    })
    .then((data) => {
      displayUserCount(data.user_count);
    })
    .catch((error) => {
      console.error("Error fetching user count:", error.message);
    });
}
function displayUserCount(userCount) {
  const userCountElement = document.getElementById("user-count");
  userCountElement.textContent = userCount;
}

function fetchTotalRevenue() {
  fetch(API_URL.admin.countRevenue)
    .then((response) => {
      if (!response.ok) {
        throw new Error("Failed to fetch total revenue");
      }
      return response.json();
    })
    .then((data) => {
      displayTotalRevenue(data.total_revenue);
    })
    .catch((error) => {
      console.error("Error fetching total revenue:", error.message);
    });
}

function displayTotalRevenue(totalRevenue) {
  const totalRevenueElement = document.getElementById("total-revenue");
  totalRevenueElement.textContent = new Intl.NumberFormat("en-US", {
    style: "currency",
    currency: "USD",
    maximumFractionDigits: 0,
  }).format(totalRevenue);
}

fetchTotalRevenue();
fetchUserCount();
fetchBookings();
