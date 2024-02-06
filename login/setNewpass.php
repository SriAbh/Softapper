<?php

$token = $_GET["token"];


$token_hash = hash("sha256", $token);

$mysqli = require __DIR__ . "/config.php";

$sql = "SELECT * FROM signup
        WHERE reset_token = ?";

$stmt = $mysqli->prepare($sql);

$stmt->bind_param("s", $token_hash);

$stmt->execute();

$result = $stmt->get_result();

$user = $result->fetch_assoc();

if ($user === null) {
  //die("Token not found"); // You can customize this message or remove it
  // Redirect to another page
  header("Location: login.html");
  exit(); // Ensure that the script stops executing after the redirection
}


if (strtotime($user["token_expiration"]) <= time()) {
   // die("token has expired");
    header("Location: login.html");
  exit();
}

?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
    />
    <link rel="stylesheet" href="setNewpass.css" />
    <title>Set New Password</title>
  </head>
  <body>
    <div class="container">
      <div class="forms-container">
        <div class="signin-signup">
          <form action="process-reset-password.php" method="post" class="sign-in-form">
            <h4 class="title">Set your Password</h4>
            <div class="input-field">
              <i class="fas fa-lock"></i>
              <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
              <input
                type="password"
                placeholder="New Password"
                name="fpassword"
                id="first-password"
                required
              />
              <label for="fpassword"></label>
            </div>
            <div class="input-field">
              <i class="fas fa-lock"></i>
              <input
                type="password"
                placeholder="Confirm Password"
                name="spassword"
                id="second-password"
                required
              />
              <label for="spassword"></label>
            </div>
            <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
         
            <button type="submit"value="reset" class="btn" id="signUpSubmit">Submit</button>
          </form>
        </div>
      </div>

      <div class="panels-container">
        <!-- Other content if needed -->
      </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
          const SignUp = document.querySelector("#signUpSubmit");
      
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
      </script>
      
  </body>
</html>
