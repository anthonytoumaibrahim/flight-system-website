// If user is not signed in, redirect to auth page
if (!getLoggedInUser()) {
  window.location.href = "./auth.html";
}

const responseMessage = document.getElementById("response-message");
const form = document.querySelector(".info-form");
const coinForm = document.querySelector(".coin-form");
const coinResponse = document.getElementById("coins-response");
// Fields
const [fullNameInput, phoneInput, genderInput, addressInput, dobInput] = [
  document.getElementById("full_name"),
  document.getElementById("phone"),
  document.getElementById("gender"),
  document.getElementById("address"),
  document.getElementById("dob"),
];

const getInfo = async () => {
  try {
    const response = await fetch(API_URL.profile.info, {
      headers: {
        Authorization: getLoggedInUser().token,
      },
    });
    const data = await response.json();
    if (!data.success) {
      throw new Error(
        "Couldn't fetch profile info. Try logging out and back in."
      );
    }
    // Populate fields
    const {
      fullname,
      gender,
      address,
      client_phonenumber,
      client_dob,
      coins_amount,
    } = data.data;
    fullNameInput.value = fullname;
    genderInput.value = gender ?? "other";
    addressInput.value = address;
    phoneInput.value = client_phonenumber;
    dobInput.value = client_dob;

    document
      .querySelectorAll(".coins-balance")
      .forEach((el) => (coins_amount ? (el.innerHTML = coins_amount) : ""));
  } catch (error) {
    showResponseMessage(
      responseMessage,
      true,
      "Sorry, something went wrong! The error was logged to console."
    );
    console.log(error);
  }
};

// Submit profile form
form.addEventListener("submit", async (e) => {
  e.preventDefault();
  hideResponseMessage(responseMessage);
  try {
    const response = await fetch(API_URL.profile.save, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Authorization: getLoggedInUser().token,
      },
      body: JSON.stringify({
        name: fullNameInput.value,
        gender: genderInput.value,
        address: addressInput.value,
        phone: phoneInput.value,
        dob: dobInput.value,
      }),
    });
    const data = await response.json();
    showResponseMessage(responseMessage, !data.success, data.message);
  } catch (e) {
    showResponseMessage(
      responseMessage,
      true,
      "Sorry, something went wrong! The error was logged to console."
    );
    console.log(e);
  }
});

// Submit coin form
coinForm.addEventListener("submit", async (e) => {
  e.preventDefault();
  const val = document.getElementById("coins").value;
  hideResponseMessage(coinResponse);
  try {
    const response = await fetch(API_URL.profile.requestCoins, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Authorization: getLoggedInUser().token,
      },
      body: JSON.stringify({
        amount: val,
      }),
    });
    const data = await response.json();
    coinForm.reset();
    showResponseMessage(coinResponse, !data.success, data.message);
  } catch (e) {
    showResponseMessage(
      coinResponse,
      true,
      "Sorry, your coin request couldn't be sent. The error has been logged to the console."
    );
    console.log(e);
  }
});

getInfo();
