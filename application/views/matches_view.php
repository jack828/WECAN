<?php
foreach($css_files as $file): ?>
  <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
<?php endforeach; ?>

<?php foreach($js_files as $file): ?>
  <script src="<?php echo $file; ?>"></script>
<?php endforeach; ?>

<div class="right_col" role="main">
  <div class="">
    <div class="x_panel">
      <div class="x_title">
        <h1>Matches</h2>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
        <?php echo $output; ?>
      </div>
    </div>
  </div>
</div>
