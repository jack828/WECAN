
<div class="right_col" role="main">
  <div class="">
    <div class="x_panel">
      <div class="x_title">
        <h1>End Competition</h2>
        <div class="clearfix"></div>
      </div>
      <div class="x_content center">
        <h2>This will cancel all active cards. <strong>This action is irreversible!</strong></h2>

        <input class="btn btn-lg btn-primary js-cancel" type="button" role="button" value="Cancel & go back!" />
        <br>
        <br>
        <input class="btn btn-sm btn-danger js-terminate" type="button" role="button" value="Terminate ALL cards" />
      </div>
    </div>
  </div>
</div>
<script>
  var terminate = '<?php if (isset($terminate)) { echo $terminate; } ?>'

  if (terminate) {
    $('.x_content').html('<h2>All cards have been terminated.</h2>')
  }

  $('.js-cancel').on('click', function (e) {
    e.preventDefault()
    window.location.href = '<?php echo site_url('main'); ?>'
  })

  $('.js-terminate').on('click', function (e) {
    e.preventDefault()
    var conf = window.confirm('Are you absolutely sure? This action is irreversible!!')
    if (conf) {
      window.location.href = '<?php echo site_url('main/terminate'); ?>'
    }
  })

</script>
