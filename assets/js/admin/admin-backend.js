$(document).ready(function () {

  // Approved functionality 
  jQuery('a#approve_btn').on('click', function (e) {
    e.preventDefault();
    var $this = jQuery(this);
    id = $this.attr("data-id");
    jQuery.ajax({
      type: 'post',
      url: scriptParams.ajaxurl + '/?action=fn_approve_btn_data',
      data: { 'id': id },
      success: function () {
      }
    });
  });

  // Edit Button functionality 
  jQuery('a#Edit_btn').on('click', function (e) {
    e.preventDefault();
    var adddata = new Array();
    var $this = jQuery(this);
    id = $this.attr("data-edit-id");
    console.log(id);
    jQuery(this).closest('tr').find('td').each(
      function (i) {
        adddata[i] = $(this).text();
      });
    Swal.fire({
      title: "<i>EDIT DATA AND SAVE</i></br>",
      html: '</br><input type="text" id="IDFR" name="IDFR" value="" ></br></br><input type="text" id="fname" name="fname" value="" ></br></br><input type="text" id="Email" name="Email" value="" ></br></br><input type="text" id="phone_no" name="phone_no" value="" ></br></br><input type="text" id="Address" name="Address" value="" ></br></br><input type="text" id="Country" name="Country" value="" >',
      confirmButtonText: "SUBMIT",
    });
    $('#IDFR').val(adddata[0]);
    $('#fname').val(adddata[1]);
    $('#Email').val(adddata[2]);
    $('#phone_no').val(adddata[3]);
    $('#Address').val(adddata[4]);
    $('#Country').val(adddata[5]);
    // popup submit 
    jQuery('button.swal2-confirm.swal2-styled').on('click', function (e) {
      var IDFR = $('#IDFR').val();
      var fname = $('#fname').val();
      var Email = $('#Email').val();
      var phone_no = $('#phone_no').val();
      var Address = $('#Address').val();
      var Country = $('#Country').val();
      jQuery.ajax({
        type: 'post',
        url: scriptParams.ajaxurl + '/?action=fn_popup_btn_data',
        data: {
          'db_id': id,
          'IDFR': IDFR,
          'fname': fname,
          'Email': Email,
          'phone_no': phone_no,
          'Address': Address,
          'Country': Country
        },
        success: function (msg) {
          console.log(msg);
        }
      });
    });
  });

  // Delete functionality 
  jQuery('a#Delete_btn').on('click', function (e) {
     e.preventDefault();
     var $this = jQuery(this);
     id = $this.attr("data-edit-id");
     console.log(id);
     // Ajax to send id for Delete 
     jQuery.ajax({
      type: 'post',
      url: scriptParams.ajaxurl + '/?action=fn_Delete_btn_data',
      data: {
        'delete_id': id,
      },
      success: function (msg) {
        console.log(msg);
      }
    });
  });

    // Unapproved functionality 
    jQuery('a#unapprove_btn').on('click', function (e) {
    var $this = jQuery(this);
    id_unapproved = $this.attr("data-id");
      // Ajax to send id for unapproved 
     jQuery.ajax({
      type: 'post',
      url: scriptParams.ajaxurl + '/?action=fn_unapproved_btn_data',
      data: {
        'unapproved': id_unapproved,
      },
      success: function (msg) {
        console.log(msg);
      }
    });
    });
    // End OF code for unapproved functionality  
});


