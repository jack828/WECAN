<!DOCTYPE html>
<html lang='en'>
  <head>
    <meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>

    <title>WECAN | FORGOTTEN PASSWORD</title>

    <!-- Bootstrap -->
    <link href='/WECAN/assets/gentelella/vendors/bootstrap/dist/css/bootstrap.min.css' rel='stylesheet'>
    <!-- Font Awesome -->
    <link href='/WECAN/assets/gentelella/vendors/font-awesome/css/font-awesome.min.css' rel='stylesheet'>
    <!-- Animate.css -->
    <link href='/WECAN/assets/gentelella/vendors/animate.css/animate.min.css' rel='stylesheet'>
    <!-- Custom Theme Style -->
    <link href='/WECAN/assets/gentelella/build/css/custom.min.css' rel='stylesheet'>
    <link href='/WECAN/assets/grocery_crud/css/custom.css' rel='stylesheet' type='text/css'>
  </head>

  <body class='login'>
    <div>
      <br />
      <img src='/WECAN/assets/images/WECAN_LogoBannerIcon.png' class='img-responsive center-block' alt='WECAN Logo' width='220' />
      <div class='login_wrapper'>
        <div class='form login_form'>
          <section class='login_content'>
            <?php echo form_open('reset/assign', 'method="post" class="js-reset-form"'); ?>
              <h1>FORGOT</h1>

              <?php if (isset($set)) { ?>
                <h1>Please login with your new credentials.</h1>
              <?php } else { ?>
              <div>
                <input name='password' type='password' class='form-control password' placeholder='Password' required />
                <input name='passwordConfirm' type='password' class='form-control password' placeholder='Confirm Password' required />
                <input name='token' type='hidden' class='form-control hidden' value='<?php echo $token; ?>' />
                <input name='expiry' type='hidden' class='form-control hidden' value='<?php echo $expiry; ?>' />
                <input name='email' type='hidden' class='form-control hidden' value='<?php echo $email; ?>' />
              </div>
              <div>
                <a href='<?php echo site_url('login'); ?>' class='btn btn-default submit'>Go back</a>
                <button class='btn btn-default submit' type='submit'>Reset password</button>
              </div>

              <div class='clearfix'></div>
              <?php } ?>

              <div class='separator'>
              <?php if (isset($error)): ?>
                <div class='alert alert-error alert-dismissible fade in' role='alert' style='text-shadow: none;'>
                  <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                    <span aria-hidden='true'>×</span>
                  </button>
                  <?php echo $error; ?>
                </div>
              <?php endif; ?>
                <div class='clearfix'></div>
                <br />

                <div>
                  <p>
                    © <?php echo date('Y'); ?> All Rights Reserved.
                    <br />
                    Gentelella Alela! is a Bootstrap 3 template.
                    <br />
                    Built by CS29.
                  </p>
                </div>
              </div>
            </form>
          </section>
        </div>
      </div>
    </div>
    <script src='/WECAN/assets/gentelella/vendors/jquery/dist/jquery.min.js'></script>
    <script src='/WECAN/assets/gentelella/vendors/bootstrap/dist/js/bootstrap.min.js'></script>
    <script>
      // $('.js-reset-form').on('submit', function () {
      //   var password = $(this).find('input[name=password]').val()
      //     , passwordConfirm = $(this).find('input[name=passwordConfirm]').val()
      //   console.log(password, passwordConfirm)
      //   return false
      // })
    </script>
  </body>
</html>
