var default_per_page = typeof default_per_page !== 'undefined' ? default_per_page : 25;
var oTable = null;
var oTableArray = [];
var oTableMapping = [];

function supports_html5_storage() {
  try {
    JSON.parse("{}");
    return 'localStorage' in window && window['localStorage'] !== null;
  } catch (e) {
    return false;
  }
}

var use_storage = supports_html5_storage();

var aButtons = [];
var mColumns = [];

$(document).ready(function() {

  $('table').each(function (index){
    var $table = $(this)
      , $container = $table.parent()
      , unset_export = $container.data('unset-export')
      , unset_print = $container.data('unset-print')
      , unset_add = $container.data('unset-add')
      , list_add = $container.data('list-add')
      , subject = $container.data('subject')
      , add_url = $container.data('add-url')
      , buttons = [ ]

    if (!unset_add) {
      buttons.push(
      { text: '<i class="fa fa-plus-circle"></i> ' + list_add + ' ' + subject,
        className: 'btn-sm btn-lightgrey',
        action: function () {
          window.location.href = '<?php echo $add_url; ?>'
        }
      })
    }
    if (!unset_export) {
      buttons.push(
      { extend: 'csv',
        className: 'btn-sm btn-lightgrey'
      })
    }
    if (!unset_print) {
      buttons.push(
      { extend: 'print',
        className: 'btn-sm btn-lightgrey'
      })
    }

    if ($.fn.DataTable.isDataTable($table)) return
    var datatable = $table.DataTable({
      dom: 'Bfrtip',
      buttons: buttons,
      language: {
        search: ''
      , searchPlaceholder: 'Search all records...'
      },
      responsive: true
    })

    $table.find('th.actions').unbind('click').removeClass('sorting')

    $table.find('tfoot').find('input').on('keyup', function () {

      datatable.columns().every(function () {
        var that = this

        $('input', this.footer()).on('keyup change', function () {
          if (that.search() !== this.value) {
            that
              .search(this.value)
              .draw()
          }
        })
      })
    })
  });

  $('.clear-filtering').click(function () {
    localStorage.removeItem('DataTables_' + unique_hash);
    localStorage.removeItem('datatables_search_' + unique_hash);

    $(this).closest('table').find("tfoot tr th input").val("");
    $(this).closest('table').DataTable()
      .search('')
      .columns()
      .search('')
      .draw();
  });

  // loadListenersForDatatables();

  $('a[role=button],button[role=button]').live("mouseover mouseout", function(event) {
    if (event.type == "mouseover") {
      $(this).addClass('ui-state-hover');
    } else {
      $(this).removeClass('ui-state-hover');
    }
  });

  $('th.actions').unbind('click');
  $('th.actions>div .DataTables_sort_icon').remove();

});

function loadListenersForDatatables () {

  $('.refresh-data').click(function (){
    var this_container = $(this).closest('.dataTablesContainer');

    var new_container = $("<div/>").addClass('dataTablesContainer');

    this_container.after(new_container);
    this_container.remove();

    $.ajax({
      url: $(this).attr('data-url'),
      success: function (my_output) {
        new_container.html(my_output);

        loadDataTable(new_container.find('.groceryCrudTable'));

        loadListenersForDatatables();
      }
    });
  });
}

function loadDataTable (this_datatables) {
  return $(this_datatables).dataTable({
    "bJQueryUI": true,
    "sPaginationType": "full_numbers",
    "bStateSave": use_storage,
    "fnStateSave": function (oSettings, oData) {
      localStorage.setItem('DataTables_' + unique_hash, JSON.stringify(oData));
    },
    "fnStateLoad": function (oSettings) {
      return JSON.parse(localStorage.getItem('DataTables_' + unique_hash));
    },
    "iDisplayLength": default_per_page,
    "aaSorting": datatables_aaSorting,
    "oLanguage":{
      "sProcessing": list_loading,
      "sLengthMenu": show_entries_string,
      "sZeroRecords": list_no_items,
      "sInfo": displaying_paging_string,
      "sInfoEmpty": list_zero_entries,
      "sInfoFiltered": filtered_from_string,
      "sSearch": search_string + ":",
      "oPaginate": {
        "sFirst": paging_first,
        "sPrevious": paging_previous,
        "sNext": paging_next,
        "sLast": paging_last
      }
    },
    "bDestory": true,
    "bRetrieve": true,
    "fnDrawCallback": function () {
      $('.image-thumbnail').fancybox({
        'transitionIn': 'elastic',
        'transitionOut': 'elastic',
        'speedIn': 600,
        'speedOut': 200,
        'overlayShow': false
      });
      add_edit_button_listener();
    },
    "sDom": 'T<"clear"><"H"lfr>t<"F"ip>',
      "oTableTools": {
        "aButtons": aButtons,
          "sSwfPath": base_url + "assets/grocery_crud/themes/datatables/extras/TableTools/media/swf/copy_csv_xls_pdf.swf"
      }
  });
}

function datatables_get_chosen_table (table_as_object) {
  chosen_table_index = oTableMapping[table_as_object.attr('id')];
  return oTableArray[chosen_table_index];
}

function delete_row (delete_url, row_id){
  if (confirm(message_alert_delete)) {
    $.ajax({
      url: delete_url,
      dataType: 'json',
      success: function(data) {
        if (data.success) {
          success_message(data.success_message);

          var chosen_table = $('tr#row-' + row_id).closest('table').DataTable()
            , row = chosen_table.row('#row-' + row_id);

          row.remove();
          chosen_table.draw();
        } else {
          error_message(data.error_message);
        }
      }
    });
  }
  return false;
}

function fnGetSelected (oTableLocal) {
  var aReturn = new Array();
  var aTrs = oTableLocal.fnGetNodes();

  for (var i=0; i < aTrs.length; i++) {
    if ($(aTrs[i]).hasClass('row_selected')) {
      aReturn.push(aTrs[i]);
    }
  }
  return aReturn;
}
