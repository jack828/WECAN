<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>WECAN | LOGIN</title>

    <!-- Bootstrap -->
    <link href="/WECAN/assets/gentelella/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="/WECAN/assets/gentelella/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- Animate.css -->
    <link href="/WECAN/assets/gentelella/vendors/animate.css/animate.min.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="/WECAN/assets/gentelella/build/css/custom.min.css" rel="stylesheet">
  </head>

  <body class="login">
    <div>
      <a class="hiddenanchor" id="signup"></a>
      <a class="hiddenanchor" id="signin"></a>

      <div class="login_wrapper">
        <div class="form login_form">
          <section class="login_content">
            <?php echo form_open('verifyLogin'); ?>
              <h1>WECAN Login</h1>
              <div>
                <input id="username" name="username" type="text" class="form-control" placeholder="Username" required />
              </div>
              <div>
                <input id="password" name="password" type="password" class="form-control" placeholder="Password" required />
              </div>
              <div>
                <button class="btn btn-default submit" type="submit">Log in</button>
                <a class="reset_pass" href="#">Lost your password?</a>
              </div>

              <div class="clearfix"></div>

              <div class="separator">
              <?php if (validation_errors()): ?>
                <div class="alert alert-error alert-dismissible fade in" role="alert" style="text-shadow: none;">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                  </button>
                  <?php echo validation_errors(); ?>
                </div>
              <?php endif; ?>
                <div class="clearfix"></div>
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
    <script src="/WECAN/assets/gentelella/vendors/jquery/dist/jquery.min.js"></script>
    <script src="/WECAN/assets/gentelella/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
  </body>
</html>
