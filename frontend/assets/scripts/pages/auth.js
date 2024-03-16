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
] = [
  document.querySelector(".auth-form"),
  document.getElementById("name"),
  document.getElementById("name").parentElement,
  document.getElementById("email"),
  document.getElementById("email").parentElement,
  document.getElementById("password"),
  document.getElementById("password").parentElement,
  document.getElementById("auth-button"),
];

const toggleState = () => {
  // Remove errors
  [inputNameWrapper, inputEmailWrapper, inputPasswordWrapper].forEach((el) =>
    el.classList.remove("form-error")
  );
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
  const [name, email, password] = [
    inputName.value.trim(),
    inputEmail.value.trim(),
    inputPassword.value,
  ];
  // Validation
  let [nameError, emailError, passwordError] = [false, false, false];

  if (authState === "SIGNUP" && name === "") {
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
