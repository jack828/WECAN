<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <title>WECAN - LOGIN</title>
  <link rel="stylesheet" type="text/css" href="/WECAN/assets/grocery_crud/css/custom.css">
</head>
<body>
  <div class="nav-group">
    <h2>WECAN</h2>
  </div>
  <hr class="hr-thick">

  <div class="center">
    <h1>Login</h1>
    <?php echo validation_errors(); ?>
    <?php echo form_open('verifyLogin'); ?>
    <label for="username">Username:</label>
    <input type="text" size="20" id="username" name="username"/>
    <br/>
    <label for="password">Password:</label>
    <input type="password" size="20" id="password" name="password"/>
    <br/>
    <input type="submit" value="Login"/>
  </div>
</form>
</body>
</html>
