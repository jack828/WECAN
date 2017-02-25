<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <title>WECAN</title>
  <link rel="stylesheet" type="text/css" href="/WECAN/assets/grocery_crud/css/custom.css">
  <link rel="stylesheet" type="text/css" href="/WECAN/assets/grocery_crud/css/ui/bootstrap.min.css">
  <script src="/WECAN/assets/grocery_crud/js/jquery-1.10.2.min.js"></script>
  <script src="/WECAN/assets/grocery_crud/js/bootstrap.min.js"></script>
</head>
<body>
  <div>
    <div class="nav-group">
      <h2>WECAN</h2>
    </div>
    <hr class="hr-thick">
  </div>

  <nav class="navbar navbar-default">
    <div class="container-fluid">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="<?php echo site_url('main')?>">Home</a>
      </div>

      <div class="collapse navbar-collapse" id="navbar">
        <ul class="nav navbar-nav">
          <li class="<?php echo (!preg_match('/matches/', uri_string()) ?: 'active'); ?>">
            <a href="<?php echo site_url('main/matches')?>">Matches</a>
          </li>
          <li class="<?php echo (!preg_match('/teams/', uri_string()) ?: 'active'); ?>">
            <a href="<?php echo site_url('main/teams')?>">Teams</a>
          </li>
          <li class="<?php echo (!preg_match('/competitors/', uri_string()) ?: 'active'); ?>">
            <a href="<?php echo site_url('main/competitors')?>">Competitors</a>
          </li>
          <li class="<?php echo (!preg_match('/venues/', uri_string()) ?: 'active'); ?>">
            <a href="<?php echo site_url('main/venues')?>">Venues</a>
          </li>
          <li class="<?php echo (!preg_match('/cards/', uri_string()) ?: 'active'); ?>">
            <a href="<?php echo site_url('main/cards')?>">Cards</a>
          </li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
          <li><p class="navbar-text">Logged in as <?php echo $username; ?></p></li>
          <li><a href="<?php echo site_url('main/logout')?>">Logout</a></li>
        </ul>
      </div>
    </div>
  </nav>
</body>
</html>
