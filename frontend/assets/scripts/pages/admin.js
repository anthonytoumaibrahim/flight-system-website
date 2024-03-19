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
    fetch('countusers.php', {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json'
      }
    })
    .then(response => {
      if (!response.ok) {
        throw new Error('Failed to fetch user count');
      }
      return response.json();
    })
    .then(data => {
      console.log('User count:', data.user_count);
      displayUserCount(data.user_count);
    })
    .catch(error => {
      console.error('Error fetching user count:', error.message);
    });
  }
  function displayUserCount(userCount) {
    const userCountElement = document.getElementById('user-count');
    userCountElement.textContent = userCount;
  }
  
  document.addEventListener('DOMContentLoaded', fetchUserCount);
  