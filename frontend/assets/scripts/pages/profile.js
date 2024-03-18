const responseMessage = document.getElementById("response-message");
const form = document.querySelector(".info-form");
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
    const { fullname, gender, address, client_phonenumber, client_dob } =
      data.data;
    fullNameInput.value = fullname;
    genderInput.value = gender ?? "other";
    addressInput.value = address;
    phoneInput.value = client_phonenumber;
    dobInput.value = client_dob;
  } catch (error) {
    showResponseMessage(true, error);
    console.log(error);
  }
};

// Submit form
form.addEventListener("submit", async (e) => {
  e.preventDefault();
  hideResponseMessage();
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
    showResponseMessage(!data.success, data.message);
  } catch (e) {
    showResponseMessage(
      true,
      "Sorry, something went wrong! The error was logged to console."
    );
    console.log(e);
  }
});

const showResponseMessage = (error = true, message = "") => {
  responseMessage.textContent = message;
  responseMessage.classList.remove("hidden");
  responseMessage.classList.toggle("text-error", error);
  responseMessage.classList.toggle("text-success", !error);
};
const hideResponseMessage = () => {
  responseMessage.classList.add("hidden");
  responseMessage.textContent = "";
};

getInfo();
