document.addEventListener("DOMContentLoaded", function () {
  let generatedOTP;

  const submit = document.getElementById("btnSubmit");
  const hideOTP = document.getElementById("hideOTP");
  const signinEmail = document.getElementById("signinEmail");
  const SignInPage = document.getElementById("sign-up-btn");
  const oneTimePassInput = document.getElementById("oneTimePass");

  submit.addEventListener("click", function () {
    if (signinEmail !== null && signinEmail.value.trim() !== "") {
      hideOTP.style.display = "grid";
      submit.value = "Submit";
      
    } else {
      hideOTP.style.display = "none";
    }
  });

  SignInPage.addEventListener("click", function () {
    window.location.href = "login.html";
  });
});
