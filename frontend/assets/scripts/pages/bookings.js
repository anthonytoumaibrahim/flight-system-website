const responseMessage = document.getElementById("response-message");
const bookingsContainer = document.getElementById("flights");

const getHistory = async () => {
  try {
    const response = await fetch(API_URL.flights.history + "?get_bookings", {
      headers: {
        Authorization: getLoggedInUser().token,
      },
    });
    const data = await response.json();
    if (data.error) {
      responseMessage.innerHTML = data.error;
      return;
    }
    const flights = data;
    if (flights.length > 0) {
      responseMessage.innerHTML = "";
    }
    flights.forEach((flight) => {
      bookingsContainer.innerHTML += generateFlightCard({
        id: flight.id,
        airline: flight.airline_name,
        flight_num: flight.flight_number,
        depart_datetime: flight.depart_datetime,
        arrival_datetime: flight.arrival_datetime,
        price: flight.flight_price,
        seats: flight.available_seats,
      });
    });
  } catch (e) {
    responseMessage.innerHTML =
      "Sorry, something went wrong! The error was logged to console.";
    console.log(e);
  }
};

getHistory();

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
  return `<div class="flight-card">
  <div class="airline">
    <div class="logo">${airline.substr(0, 1).toUpperCase()}</div>
    <div class="details">
      <p class="font-bold">${airline}</p>
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
</div>`;
};
