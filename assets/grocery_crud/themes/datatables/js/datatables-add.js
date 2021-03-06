$(function(){
  $('.ptogtitle').click(function () {
    if ($(this).hasClass('vsble')) {
      $(this).removeClass('vsble');
      $('#main-table-box').slideDown("slow");
    } else {
      $(this).addClass('vsble');
      $('#main-table-box').slideUp("slow");
    }
  });

  var save_and_close = false;

  $('#save-and-go-back-button').click(function () {
    save_and_close = true;

    $('#crudForm').trigger('submit');
  });

  $('#crudForm').submit(function () {
    var fullName = $(this).find('#field-fullName').val()
      , teamName = $(this).find('#field-teamName').val()
      , that = this
      , title = $('#crudForm').parent().parent().parent().parent().find('.x_title')[0]
      , titleText = $(title).find('h1').html()
      , isCompetitorSubmitForm = titleText === 'Competitors'
      , isTeamSubmitForm = titleText === 'Teams'

    $.ajax({
        url: check_url
      , method: 'GET'
      , data: { fullName: fullName
        , teamName: teamName }
      , dataType: 'json'
      , success: function (result) {
          var confirmMessage = 'There are already ' + result.count + ' similar record(s), are you sure you want to continue?'
          if (result.count && result.rows.length) {
            result.rows.map(function (row) {
              if (isCompetitorSubmitForm) {
                confirmMessage += '\n' + row.title + ' ' + row.fullName + ', ' + row.teamName + ', ' + row.role
              }
            })
          }

          if (isTeamSubmitForm) {
            window.alert('Team `' + teamName + '` already exists in the database.')
          } else if (isCompetitorSubmitForm && result.count) {
              if (window.confirm(confirmMessage)) {
                formSubmit.apply(that)
              }
          } else {
            formSubmit.apply(that)
          }
        }
    })
    return false;
  });

  $('.ui-input-button').button();
  $('.gotoListButton').button({
    icons: {
      primary: "ui-icon-triangle-1-w"
    }
  });

  if ($('#cancel-button').closest('.ui-dialog').length === 0) {
    $('#cancel-button').click(function () {
      if (confirm(message_alert_add_form)) {
        // window.location = list_url;
        window.history.back();
      }
      return false;
    });
  }

  function formSubmit() {
    $(this).ajaxSubmit({
      url: validation_url,
      dataType: 'json',
      beforeSend: function () {
        $("#buttons").hide();
        $("#FormLoading").show();

      },
      success: function (data) {
        $("#FormLoading").hide();
        if (data.success) {
          $('#crudForm').ajaxSubmit({
            dataType: 'text',
            cache: 'false',
            beforeSend: function () {
              $("#FormLoading").show();
            },
            success: function (result) {
              $("#FormLoading").fadeOut("slow");

              data = $.parseJSON(result);
              if (data.success) {
                if (save_and_close) {
                  if ($('#save-and-go-back-button').closest('.ui-dialog').length === 0) {
                    // window.location = data.success_list_url;
                    window.history.back();
                  } else {
                    $(".ui-dialog-content").dialog("close");
                    success_message(data.success_message);
                  }
                  return true;
                }

                $('.field_error').removeClass('field_error');

                form_success_message(data.success_message);

                clearForm();
              } else {
                form_error_message('An error has been occured at the insert.');
              }
            }
          });
        } else {
          $("#buttons").show();
          $('.field_error').removeClass('field_error');
          form_error_message(data.error_message);
          $.each(data.error_fields, function (index, value) {
            $('#crudForm input[name='+index+']').addClass('field_error');
          });
        }
      }
    });
  }
});

function clearForm() {
  $('#crudForm').find(':input').each(function () {
    switch (this.type) {
      case 'password':
      case 'select-multiple':
      case 'select-one':
      case 'text':
      case 'textarea':
        $(this).val('');
        break;
      case 'checkbox':
      case 'radio':
        this.checked = false;
    }
  });

  /* Clear upload inputs  */
  $('.open-file,.gc-file-upload,.hidden-upload-input').each(function () {
    $(this).val('');
  });

  $('.upload-success-url').hide();
  $('.fileinput-button').fadeIn("normal");
  /* -------------------- */

  $('.remove-all').each(function () {
    $(this).trigger('click');
  });

  $('.chosen-multiple-select, .chosen-select, .ajax-chosen-select').each(function () {
    $(this).trigger("liszt:updated");
  });
}
