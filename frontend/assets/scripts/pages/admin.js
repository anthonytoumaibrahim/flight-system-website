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

const searchBtn = document.querySelector(".content nav form .form-input button");
const searchIcon = document.querySelector(".content nav form .form-input button .bx");
const searchForm = document.querySelector('.content nav form')

searchBtn.addEventListener("Click", function(e){
  if(window.innerWidth < 576){
    e.preventDefault;
    searchForm.classList.toggle('show');
    if(searchForm.classList.contains('show')){
      searchIcon.classList.replace("bx-search" , "bx-x");
    }else{
      searchIcon.classList.replace("bx-x", "bx-search");
    }
  }
})


window.addEventListener("resize", () => {
  if(window.innerWidth < 768){
    sideBarEl.classList.add('close');
  }else{
    sideBarEl.classList.remove("close");
  }
});

const darkEl = document.querySelector(".side-menu ul li a");
const darkIcon = document.querySelector(".side-menu ul li .bx.bx-moon");

darkEl.addEventListener("click", () => {
  document.body.classList.toggle("dark");
  
  if(document.body.classList.contains('dark')){
    darkIcon.classList.replace("bx-moon", "bx-sun");
  }else{
    darkIcon.classList.replace("bx-sun", "bx-moon");

  }
})








function fetchBookings() {
  fetch("bookinginfo.php", {
    method: "GET",
    headers: {
      "Content-Type": "application/json",
    },
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error("Failed to fetch bookings");
      }
      return response.json();
    })
    .then((data) => {
      console.log("Bookings:", data);
      displayBookings(data);
    })
    .catch((error) => {
      console.error("Error fetching bookings:", error.message);
    });
}

function displayBookings(bookings) {
  const bookingsContainer = document.getElementById("bookings-container");
  bookingsContainer.innerHTML = "";

  bookings.forEach((booking) => {
    const bookingElement = document.createElement("div");
    bookingElement.classList.add("booking");

    const html = `
        <p><strong>Booking ID:</strong> ${booking.booking_id}</p>
        <p><strong>Airline Name:</strong> ${booking.airline_name}</p>
        <p><strong>Flight Number:</strong> ${booking.flight_number}</p>
        <p><strong>Departure Date/Time:</strong> ${new Date(
          booking.depart_datetime
        ).toLocaleString()}</p>
        <p><strong>Arrival Date/Time:</strong> ${new Date(
          booking.arrival_datetime
        ).toLocaleString()}</p>
        <p><strong>Flight Price:</strong> ${booking.flight_price}</p>
        <p><strong>Passenger Name:</strong> ${booking.fullname}</p>
        <p><strong>Email:</strong> ${booking.email}</p>
        <p><strong>Phone Number:</strong> ${booking.client_phonenumber}</p>
        <p><strong>Payment Status:</strong> ${booking.payment_status}</p>
      `;
    bookingElement.innerHTML = html;

    bookingsContainer.appendChild(bookingElement);
  });
}

document.addEventListener("DOMContentLoaded", fetchBookings);

function fetchUserCount() {
  fetch("countusers.php", {
    method: "GET",
    headers: {
      "Content-Type": "application/json",
    },
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error("Failed to fetch user count");
      }
      return response.json();
    })
    .then((data) => {
      console.log("User count:", data.user_count);
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

document.addEventListener("DOMContentLoaded", fetchUserCount);

function fetchTotalRevenue() {
  fetch("countrevenue.php", {
    method: "GET",
    headers: {
      "Content-Type": "application/json",
    },
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error("Failed to fetch total revenue");
      }
      return response.json();
    })
    .then((data) => {
      console.log("Total revenue:", data.total_revenue);
      displayTotalRevenue(data.total_revenue);
    })
    .catch((error) => {
      console.error("Error fetching total revenue:", error.message);
    });
}

function displayTotalRevenue(totalRevenue) {
  const totalRevenueElement = document.getElementById("total-revenue");
  totalRevenueElement.textContent = totalRevenue;
}

document.addEventListener("DOMContentLoaded", fetchTotalRevenue);
