// If user is not signed in, redirect to auth page
if (!getLoggedInUser()) {
  window.location.href = "./pages/auth.html";
}

const searchResultContainer = document.getElementById("search-result");
const flightsContainer = document.getElementById("flights");
const responseMessage = document.getElementById("response-message");
const form = document.querySelector(".search-form");
// Fields
const [
  destinationToInput,
  departureDateInput,
  departureTimeInput,
  returnDateInput,
  returnTimeInput,
  passengersInput,
] = [
  document.getElementById("destination-to"),
  document.getElementById("departure-date"),
  document.getElementById("departure-time"),
  document.getElementById("return-date"),
  document.getElementById("return-time"),
  document.getElementById("passengers"),
];

form.addEventListener("submit", async (e) => {
  e.preventDefault();
  responseMessage.classList.remove("hidden");
  responseMessage.innerHTML = "";
  flightsContainer.innerHTML = "";
  const response = await fetch(API_URL.flights.search, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      Authorization: getLoggedInUser().token,
    },
    body: JSON.stringify({
      destination: destinationToInput.value,
      departureDate: departureDateInput.value,
      departureTime: departureTimeInput.value,
      arrivalDate: returnDateInput.value,
      arrivalTime: returnTimeInput.value,
      numPassengers: passengersInput.value,
    }),
  });
  const data = await response.json();
  searchResultContainer.classList.remove("hidden");
  searchResultContainer.scrollIntoView();
  if (data.error) {
    responseMessage.innerHTML = data.error;
    return;
  }
  const flights = data;
  flights.forEach((flight) => {
    flightsContainer.innerHTML += generateFlightCard({
      id: flight.id,
      airline: flight.airline_name,
      flight_num: flight.flight_number,
      depart_datetime: flight.depart_datetime,
      arrival_datetime: flight.arrival_datetime,
      price: flight.flight_price,
      seats: flight.available_seats,
      airport: flight.airport_name,
    });
  });
  // Add event listener to buttons
  document.querySelectorAll(".book-button").forEach((button) =>
    button.addEventListener("click", () => {
      const flight_id = button.dataset.flight;
      const amount = button.dataset.amount;
      bookFlight(flight_id, amount);
    })
  );
});

const bookFlight = async (flight_id, amount) => {
  try {
    const response = await fetch(API_URL.flights.book, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Authorization: getLoggedInUser().token,
      },
      body: JSON.stringify({
        flight_id: flight_id,
        amount: amount,
        payment_method: "credit_card",
      }),
    });
    const data = await response.text();
    responseMessage.innerHTML = data;
  } catch (e) {
    console.log(e);
  }
};

const generateFlightCard = ({
  id,
  airline,
  flight_num,
  depart_datetime,
  arrival_datetime,
  price,
  seats,
  airport,
}) => {
  const airlineLetter = airline.substr(0, 1).toUpperCase();
  return `<div class="flight-card">
  <div class="airline">
    <div class="logo" style="${
      airlinesColors[airlineLetter]
        ? `background-color:${airlinesColors[airlineLetter]};`
        : ""
    }">${airlineLetter}</div>
    <div class="details">
      <p class="font-bold">${airline}</p>
      <p class="text-muted airport">${airport}</p>
    </div>
  </div>
  <div class="dates">
    <div class="date">
      <p class="text-muted">Departs at</p>
      <h3>${depart_datetime}</h3>
    </div>
    <div class="date">
      <p class="text-muted">Arrives at</p>
      <h3>${arrival_datetime}</h3>
    </div>
  </div>
  <div class="price">
    <h3>$${price}</h3>
  </div>
  <button class="button button-primary book-button" data-flight="${id}" data-amount="${price}">Book</button>
</div>`;
};
