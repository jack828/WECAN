<!DOCTYPE html>
<html lang='en'>
  <head>
    <meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>

    <title>WECAN</title>

    <!-- Bootstrap -->
    <link href='/WECAN/assets/gentelella/vendors/bootstrap/dist/css/bootstrap.min.css' rel='stylesheet'>
    <!-- Font Awesome -->
    <link href='/WECAN/assets/gentelella/vendors/font-awesome/css/font-awesome.min.css' rel='stylesheet'>
    <!-- NProgress -->
    <link href='/WECAN/assets/gentelella/vendors/nprogress/nprogress.css' rel='stylesheet'>
    <!-- jQuery custom content scroller -->
    <link href='/WECAN/assets/gentelella/vendors/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.min.css' rel='stylesheet'/>
    <!-- Custom Theme Style -->
    <link href='/WECAN/assets/gentelella/build/css/custom.css' rel='stylesheet'>
    <link href='/WECAN/assets/grocery_crud/css/custom.css' rel='stylesheet' type='text/css'>

    <!-- jQuery -->
    <script src='/WECAN/assets/gentelella/vendors/jquery/dist/jquery.min.js'></script>
  </head>

  <body class='nav-md footer_fixed'>
    <div class='container body'>
      <div class='main_container'>
        <div class='col-md-3 left_col menu_fixed'>
          <div class='left_col scroll-view'>
            <div class='navbar nav_title' style='border: 0;'>
              <a class='site_title'>
                <img src='/WECAN/assets/images/WECAN_LogoSmallWhite.png' alt='WECAN Logo' width='50' />
                <span>WECAN</span>
              </a>
            </div>

            <div class='clearfix'></div>

            <!-- menu profile quick info -->
            <div class='profile clearfix'>
              <div class='profile_pic'>
                <img src='/WECAN/assets/gentelella/production/images/user.png' alt='...' class='img-circle profile_img'>
              </div>
              <div class='profile_info'>
                <span>Welcome,</span>
                <h2><?php echo $username; ?></h2>
              </div>
            </div>
            <!-- /menu profile quick info -->

            <br />

            <!-- sidebar menu -->
            <div id='sidebar-menu' class='main_menu_side hidden-print main_menu'>
              <div class='menu_section'>
                <h3>Sections</h3>
                <ul class='nav side-menu'>
                  <li>
                    <a href='<?php echo site_url('main'); ?>'>
                      <i class='fa fa-fw fa-home'></i> Home
                    </a>
                  </li>
                  <li>
                    <a href='<?php echo site_url('main/matches'); ?>'>
                      <i class='fa fa-fw fa-flag'></i> Matches
                    </a>
                  </li>
                  <li>
                    <a href='<?php echo site_url('main/teams'); ?>'>
                      <i class='fa fa-fw fa-users'></i> Teams
                    </a>
                  </li>
                  <li>
                    <a href='<?php echo site_url('main/competitors'); ?>'>
                      <i class='fa fa-fw fa-user'></i> Competitors
                    </a>
                  </li>
                  <li>
                    <a href='<?php echo site_url('main/venues'); ?>'>
                      <i class='fa fa-fw fa-map-marker'></i> Venues
                    </a>
                  </li>
                  <li>
                    <a href='<?php echo site_url('main/cards'); ?>'>
                      <i class='fa fa-fw fa-credit-card-alt'></i> Cards
                    </a>
                  </li>
                </ul>
              </div>
              <div class='menu_section'>
                <h3>Admin</h3>
                <ul class='nav side-menu'>
                  <li>
                    <a href='<?php echo site_url('main/end_competition'); ?>'>
                      <i class='fa fa-fw fa-warning'></i> End Competition
                    </a>
                  </li>
                  <li>
                    <a href='<?php echo site_url('main/venue_access'); ?>'>
                      <i class='fa fa-fw fa-unlock'></i> Test Venue Access
                    </a>
                  </li>
                </ul>
              </div>
            </div>
            <!-- /sidebar menu -->

          </div>
        </div>

        <!-- top navigation -->
        <div class='top_nav'>
          <div class='nav_menu'>
            <nav>
              <div class='nav toggle'>
                <a id='menu_toggle'><i class='fa fa-bars'></i></a>
              </div>

              <ul class='nav navbar-nav navbar-right'>
                <li>
                  <a href='javascript:;' class='user-profile dropdown-toggle' data-toggle='dropdown' aria-expanded='false'>
                    <img src='/WECAN/assets/gentelella/production/images/user.png' alt=''>
                    <?php echo $username; ?>
                    <span class='fa fa-angle-down'></span>
                  </a>
                  <ul class='dropdown-menu dropdown-usermenu pull-right'>
                    <li>
                      <a href='<?php echo site_url('main/logout')?>'>
                        <i class='fa fa-sign-out pull-right'></i> Log Out
                      </a>
                    </li>
                  </ul>
                </li>
              </ul>
            </nav>
          </div>
        </div>
        <!-- /top navigation -->
