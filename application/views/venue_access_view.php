<?php
foreach($css_files as $file): ?>
  <link type='text/css' rel='stylesheet' href='<?php echo $file; ?>' />
<?php endforeach; ?>
  <link type='text/css' rel='stylesheet' href='/WECAN/assets/gentelella/vendors/select2/dist/css/select2.min.css' />
  <link type='text/css' rel='stylesheet' href='/WECAN/assets/gentelella/vendors/select2/dist/css/select2.bootstrap.min.css' />

<?php foreach($js_files as $file): ?>
  <script src='<?php echo $file; ?>'></script>
<?php endforeach; ?>
  <script src='/WECAN/assets/gentelella/vendors/select2/dist/js/select2.min.js'></script>
  <script src='/WECAN/assets/grocery_crud/js/jquery_plugins/config/jquery.select2.config.js'></script>

<div class='right_col' role='main'>
  <div class=''>
    <div class='x_panel'>
      <div class='x_title'>
        <h1>Test Venue Access</h2>
        <div class='clearfix'></div>
      </div>
      <div class='x_content center'>

        <form class='form-horizontal form-label-left js-test-venue-form' method='post' enctype='multipart/form-data'>

          <div class='form-group'>
            <label class='control-label col-md-3 col-sm-3 col-xs-12' for='card_numbers'>
              Card Number
              <span class='required'>*</span>
            </label>
            <div class='col-md-6 col-sm-6 col-xs-12'>
              <select class='select2-select form-control js-card' data-placeholder='Select Card Number' required>
                <option></option>
                <?php foreach($cards as $card): ?>
                  <option value='<?php echo $card->ID; ?>'><?php echo $card->ID . ' - ' . $card->fullName; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <div class='form-group'>
            <label class='control-label col-md-3 col-sm-3 col-xs-12' for='venue'>
              Venue
              <span class='required'>*</span>
            </label>
            <div class='col-md-6 col-sm-6 col-xs-12'>
              <select class='select2-select form-control js-venue' data-placeholder='Select Venue' required>
                <option></option>
                <?php foreach($venues as $venue): ?>
                  <option value='<?php echo $venue->ID; ?>'><?php echo $venue->ID . ' - ' . $venue->venueName; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <div class='form-group'>
            <label class='control-label col-md-3 col-sm-3 col-xs-12' for='date'>
              Date
              <span class='required'>*</span>
            </label>
            <div class='col-md-6 col-sm-6 col-xs-12'>
          		<input type='text' value='<?php echo date("Y-m-d"); ?>' maxlength='10' class='datepicker-input form-control js-date' required />
              (YYYY-MM-DD)
            </div>
          </div>

          <div class='ln_solid'></div>

          <div class='form-group'>
            <div class='col-md-6 col-sm-6 col-xs-12 col-md-offset-3'>
              <div id='buttons'>
                <input role='button' type='submit' value='Test Card' class='btn btn-primary' id='test-card-button' />
                <input role='button' type='button' value='Cancel' class='btn btn-primary js-cancel-button' id='cancel-button' />
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<script>
  var js_date_format = 'yy-mm-dd'
  var granted = '<?php echo $granted; ?>'

  $(document).on('ready', function () {
    switch (granted) {
      case '2':
        $('.x_content').html('<h1 class="red">INVALID REQUEST</h1>')
        break;
      case '1':
        $('.x_content').html('<h1 class="green">ACCESS GRANTED</h1>')
        break;
      case '0':
        $('.x_content').html('<h1 class="red">ACCESS DENIED</h1>')
        break;
      default:
        break;
    }
  })

  $('.js-test-venue-form').on('submit', function (e) {
    e.preventDefault()
    var cardID = $(this).find('.js-card').val()
      , venueID = $(this).find('.js-venue').val()
      , date = $(this).find('.js-date').val()

    window.location.pathname += '/' + cardID + '/' + venueID + '/' + date
  })

  $('.js-cancel-button').on('click', function () {
    window.history.back()
  })
</script>
