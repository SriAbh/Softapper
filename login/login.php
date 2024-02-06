<?php
    require_once('setup.php');
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
    <link rel="stylesheet" href="login.css" />
    <title>Sign in & Sign up Form</title>
  </head>
  <body>
    <div class="container">
      <div class="forms-container">
        <div class="signin-signup">
          <form action="process.php" class="sign-in-form" method="POST">
            <h2 class="title">Sign in</h2>
            <div class="input-field">
              <i class="fas fa-envelope"></i>
              <input
                type="email"
                placeholder="Email"
                name="Email"
                id="signinEmail"
                required
              />
              <label for="UserID"></label>
            </div>
            <div class="input-field">
              <i class="fas fa-lock"></i>
              <input
                type="password"
                placeholder="Password"
                name="Password"
                id="Password"
                required
              />
              <label for="Password"></label>
            </div>
            <input type="submit" value="Login" class="btn solid" name="login" />
          <!-- <p class="social-text">Or Sign in with social platforms</p> -->
            <!-- <div class="social-media">
              <a href="#" class="social-icon">
                <i class="fab fa-facebook-f"></i>
              </a>
              <a href="#" class="social-icon">
                <i class="fab fa-twitter"></i>
              </a>
              <a href="#" class="social-icon">
                <i class="fab fa-google"></i>
              </a>
              <a href="#" class="social-icon">
                <i class="fab fa-linkedin-in"></i>
              </a>
            </div> -->
            
            <div class="btn-group border shadow-sm mt-2">
              <button class="btn bg-white">
                  <img src="logo.png" width="35">
              </button>
              <button class="btn bg-light">
                  <a href="<?php echo $google->createAuthUrl();?>">LOGIN WITH GOOGLE</a>
              </button>
          </div>

            <div class="forgetPass">
              <a href="forgetpass.html">Forget Password?</a>
            </div>
          </form>
          <form action="process.php" method="POST" class="sign-up-form">
            <h2 class="title">Sign up</h2>
            <div class="input-field">
              <i class="fas fa-user"></i>
              <input
                type="text"
                placeholder="Name"
                name="Name"
                id="Nameame"
                required
              />
              <label for="Username"></label>
            </div>
            <div class="input-field">
              <i class="fas fa-envelope"></i>
              <input
                type="email"
                placeholder="Email"
                name="Email"
                id="Email"
                required
              />
              <label for="Email"></label>
            </div>
            <div class="input-field">
              <i class="fa-solid fa-phone"></i>
              <input
                type="number"
                placeholder="Phone No."
                name="phone"
                id="phone"
                required
              />
              <label for="phone"></label>
            </div>
            <div class="input-field">
              <i class="fa-regular fa-calendar-days"></i>
              <input
                type="text"
                placeholder="Birth Date"
                name="birthday"
                id="birthday"
                onfocus="(this.type = 'Date')"
                onblur="(this.type = 'text')"
                required
              />
              <label for="birthday"></label>
            </div>
            <div class="input-field">
              <i class="fas fa-lock"></i>
              <input
                type="password"
                placeholder="Password"
                name="fpassword"
                id="first-password"
                required
              />
              <div id="message1" style="color: red; width: 377px"></div>
              <label for="first-password"></label>
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
              <div id="message2" style="color: red; width: 377px"></div>
              <label for="second-password"></label>
            </div>
            <button
              type="submit"
              class="btn"
              id="signUpSubmit"
              name="signup"
            >
              sign up
            </button>
            <!-- <p class="social-text">Or Sign up with social platforms</p> -->
            <!-- <div class="social-media">
              <a href="#" class="social-icon">
                <i class="fab fa-facebook-f"></i>
              </a>
              <a href="#" class="social-icon">
                <i class="fab fa-twitter"></i>
              </a>
              <a href="#" class="social-icon">
                <i class="fab fa-google"></i>
              </a>
              <a href="#" class="social-icon">
                <i class="fab fa-linkedin-in"></i>
              </a>
            </div> -->
            <div>
            <div class="btn-group border shadow-sm mt-2">
              <button class="btn bg-white">
                  <img src="logo.png" width="35">
              </button>
              <button class="btn bg-light">
                  <a href="<?php echo $google->createAuthUrl();?>">LOGIN WITH GOOGLE</a>
              </button>
          </div>
</div>
          </form>
        </div>
      </div>

      <div class="panels-container">
        <div class="panel left-panel">
          <div class="content">
            <h3>New here ?</h3>
            <p>
              Lorem ipsum, dolor sit amet consectetur adipisicing elit. Debitis,
              ex ratione. Aliquid!
            </p>
            <button class="btn transparent" id="sign-up-btn">Sign up</button>
          </div>
          <img src="log.jpg" class="image" alt="" />
        </div>
        <div class="panel right-panel">
          <div class="content">
            <h3>One of us ?</h3>
            <p>
              Lorem ipsum dolor sit amet consectetur adipisicing elit. Nostrum
              laboriosam ad deleniti.
            </p>
            <button class="btn transparent" id="sign-in-btn">Sign in</button>
          </div>
          <img src="register.jpg" class="image" alt="" />
        </div>
      </div>
    </div>

    <script src="login.js"></script>
  </body>
</html>
