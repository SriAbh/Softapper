document.addEventListener("DOMContentLoaded", function () {
  const sign_in_btn = document.querySelector("#sign-in-btn");
  const sign_up_btn = document.querySelector("#sign-up-btn");
  const container = document.querySelector(".container");
  const SignUp = document.querySelector("#signUpSubmit");

  sign_up_btn.addEventListener("click", () => {
    container.classList.add("sign-up-mode");
  });

  sign_in_btn.addEventListener("click", () => {
    container.classList.remove("sign-up-mode");
  });

  SignUp.onclick = function (event) {
    if (!validateForm()) {
      event.preventDefault(); // Prevent form submission if validation fails
    }
  };

  function validateForm() {
    let pwd1 = document.getElementById("first-password").value;
    let pwd2 = document.getElementById("second-password").value;

    if (pwd1 !== pwd2) {
      alert("Confirm password does not match");
      return false;
    }

    // Additional validation logic can be added here if needed

    // If the form is valid, don't redirect here
    return true;
  }
});
