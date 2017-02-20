<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <link rel="stylesheet" type="text/css" href="/WECAN/assets/grocery_crud/css/custom.css">
</head>
<body>
  <div>
    <div class="nav-group">
      <h2>WECAN</h2>
      <div class="user-control">
        <p><?php echo 'Hello $user' ?></p>
        <a class="nav-btn" href='<?php echo site_url('main/querynav')?>'>Logout</a>
      </div>
    </div>
    <hr class="hr-thick">
    <ul class="nav">
      <li><a class="nav-btn" href='<?php echo site_url('')?>'>Home</a></li>
      <li><a class="nav-btn" href='<?php echo site_url('main/orders')?>'>Matches</a></li>
      <li><a class="nav-btn" href='<?php echo site_url('main/items')?>'>Teams</a></li>
      <li><a class="nav-btn" href='<?php echo site_url('main/customers')?>'>Competitors</a></li>
      <li><a class="nav-btn" href='<?php echo site_url('main/orderline')?>'>Venues</a></li>
    </ul>
  </div>
</body>
</html>
