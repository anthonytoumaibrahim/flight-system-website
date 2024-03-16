let authState = "SIGNUP";

// Auth state elements
const [authTitle, authToggler, authTogglerText] = [
  document.getElementById("auth-title"),
  document.getElementById("auth-toggler"),
  document.getElementById("auth-toggler-text"),
];
// Auth form elements
const [
  inputName,
  inputNameWrapper,
  inputEmail,
  inputEmailWrapper,
  inputPassword,
  inputPasswordWrapper,
  authButton,
] = [
  document.getElementById("name"),
  document.getElementById("name").parentElement,
  document.getElementById("email"),
  document.getElementById("email").parentElement,
  document.getElementById("password"),
  document.getElementById("password").parentElement,
  document.getElementById("auth-button"),
];

// Events
authToggler.addEventListener("click", () => {
  if (authState === "SIGNUP") {
    authState = "LOGIN";
    authTitle.textContent = "Login";
    authTogglerText.textContent = "Not a member?";
    authToggler.textContent = "Create an account";
    inputNameWrapper.classList.add("hidden");
    authButton.textContent = "Login";
    return;
  }
  authState = "SIGNUP";
  authTitle.textContent = "Create an account";
  authTogglerText.textContent = "Already a member?";
  authToggler.textContent = "Login instead";
  inputNameWrapper.classList.remove("hidden");
  authButton.textContent = "Create account";
});
