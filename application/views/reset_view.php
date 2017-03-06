<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <title>WECAN - Forgot Password</title>
  <link rel="stylesheet" type="text/css" href="/WECAN/assets/grocery_crud/css/custom.css">
</head>
<body>
  <div class="nav-group">
    <h2>WECAN</h2>
  </div>
  <hr class="hr-thick">

  <div class="center">
    <h1>Forgot Password</h1>
    <?php echo validation_errors(); ?>
    <?php echo form_open('reset_email'); ?>
      <div class="login-group">
        <label for="username">Username</label>
        <input type="text" size="20" id="username" name="username"/>
      </div>
      <br/>
      <div class="login-group">
        <label for="password">Password</label>
        <input type="password" size="20" id="password" name="password"/>
      </div>
      <br/>
      <input type="submit" value="Login"/>
    </form>
  </div>
</body>
</html>
