// If user is already signed in, redirect to home
if (getLoggedInUser()) {
  window.location.href = "../index.html";
}

let authState = "SIGNUP";

// Auth state elements
const [authTitle, authToggler, authTogglerText] = [
  document.getElementById("auth-title"),
  document.getElementById("auth-toggler"),
  document.getElementById("auth-toggler-text"),
];
// Auth form elements
const [
  authForm,
  inputName,
  inputNameWrapper,
  inputEmail,
  inputEmailWrapper,
  inputPassword,
  inputPasswordWrapper,
  authButton,
  responseMessage,
] = [
  document.querySelector(".auth-form"),
  document.getElementById("name"),
  document.getElementById("name").parentElement,
  document.getElementById("email"),
  document.getElementById("email").parentElement,
  document.getElementById("password"),
  document.getElementById("password").parentElement,
  document.getElementById("auth-button"),
  document.getElementById("response-message"),
];

const toggleState = () => {
  // Remove errors
  [inputNameWrapper, inputEmailWrapper, inputPasswordWrapper].forEach((el) =>
    el.classList.remove("form-error")
  );
  hideResponseMessage();
  if (authState === "SIGNUP") {
    authState = "LOGIN";
    document.title = "Login";
    authTitle.textContent = "Login";
    authTogglerText.textContent = "Not a member?";
    authToggler.textContent = "Create an account";
    inputNameWrapper.classList.add("hidden");
    authButton.textContent = "Login";
    return;
  }
  authState = "SIGNUP";
  document.title = "Create an account";
  authTitle.textContent = "Create an account";
  authTogglerText.textContent = "Already a member?";
  authToggler.textContent = "Login instead";
  inputNameWrapper.classList.remove("hidden");
  authButton.textContent = "Create account";
};

const submit = () => {
  hideResponseMessage();
  const [fullName, email, password] = [
    inputName.value.trim(),
    inputEmail.value.trim(),
    inputPassword.value,
  ];
  // Validation
  let [nameError, emailError, passwordError] = [false, false, false];

  if (authState === "SIGNUP" && fullName === "") {
    nameError = true;
  }

  if (
    !email.match(
      /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/
    )
  ) {
    emailError = true;
  }

  if (password.length < 8) {
    passwordError = true;
  }

  inputNameWrapper.classList.toggle("form-error", nameError);
  inputEmailWrapper.classList.toggle("form-error", emailError);
  inputPasswordWrapper.classList.toggle("form-error", passwordError);

  if (nameError || emailError || passwordError) return;

  // Validation passed
  auth(fullName, email, password);
};

const auth = async (fullName, email, password) => {
  try {
    authButton.disabled = true;
    const response = await fetch(API_URL.auth, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        auth_type: authState,
        name: fullName,
        email: email,
        password: password,
      }),
    });
    const data = await response.json();
    showResponseMessage(!data.success, data.message);
    if (!data.success) {
      authButton.disabled = false;
      return;
    }
    // Success, add info to localstorage and redirect to home
    const { id, token } = data.data;
    setLoggedInUser(id, token);
    window.location.href = "../index.html";
  } catch (error) {
    authButton.disabled = false;
    showResponseMessage(
      true,
      "Sorry, something went wrong. The error has been logged to the console."
    );
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
// Set to login if URL has query
const urlParams = new URLSearchParams(window.location.search);
const stateParam = urlParams.get("state");
if (stateParam === "login") toggleState();

// Events
authToggler.addEventListener("click", toggleState);
authForm.addEventListener("submit", (e) => {
  e.preventDefault();
  submit();
});
