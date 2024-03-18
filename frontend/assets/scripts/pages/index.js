// If user is not signed in, redirect to auth page
if (!getLoggedInUser()) {
  window.location.href = "./pages/auth.html";
}
