const responseMessage = document.getElementById("response-message");

// Fields
const [
  fullNameInput,
  emailInput,
  phoneInput,
  genderInput,
  addressInput,
  dobInput,
] = [
  document.getElementById("full_name"),
  document.getElementById("email"),
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
    const { fullname, email, gender, address, client_phonenumber, client_dob } =
      data.data;
    fullNameInput.value = fullname;
    emailInput.value = email;
    genderInput.value = gender ?? "other";
    addressInput.value = address;
    phoneInput.value = client_phonenumber;
    dobInput.value = client_dob;
  } catch (error) {
    showResponseMessage(true, error);
    console.log(error);
  }
};

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
