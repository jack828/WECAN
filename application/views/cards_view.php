<?php
foreach($css_files as $file): ?>
  <link type='text/css' rel='stylesheet' href='<?php echo $file; ?>' />
<?php endforeach; ?>

<?php foreach($js_files as $file): ?>
  <script src='<?php echo $file; ?>'></script>
<?php endforeach; ?>

<div class='right_col' role='main'>
  <div class=''>
    <div class='x_panel'>
      <div class='x_title'>
        <h1>Cards</h1>
        <div class='clearfix'></div>
      </div>
      <div class='x_content'>
        <?php echo $output; ?>
      </div>
    </div>

    <?php if(isset($accessLogs)): ?>
    <div class='x_panel'>
      <div class='x_title'>
        <h1>Access Logs</h1>
        <div class='clearfix'></div>
      </div>
      <div class='x_content'>
        <?php echo $accessLogs->output; ?>
      </div>
    </div>
    <?php endif; ?>

    <?php if(isset($venues)): ?>
    <div class='x_panel'>
      <div class='x_title'>
        <h1>Authorised Venues</h1>
        <div class='clearfix'></div>
      </div>
      <div class='x_content'>
        <?php echo $venues->output; ?>
      </div>
    </div>
    <?php endif; ?>
  </div>
</div>
