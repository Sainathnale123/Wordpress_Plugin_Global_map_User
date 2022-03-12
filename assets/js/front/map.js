$(document).ready(function(){

 jQuery('#onsubmitbutton').on('click', function (e) {
  e.preventDefault();
  var name = jQuery('input#fname').val();
  var email = jQuery('input#email').val();
  var phone_number = jQuery('input#phone_number').val();
  var address = jQuery('input#address').val();
  var country = jQuery('select#country').val();
  jQuery.ajax({
    type: 'post',
    url: scriptParams.ajaxurl + '/?action=fn_save_partner_form',
    data: {'name': name , 'email': email ,'phone_number': phone_number ,'address': address ,'country': country , },
    success: function (msg) {
      console.log(msg);

    }
  }) 
});


 function addLabelText(bgPath, labelText ,class_name)
 {
   let bbox = bgPath.getBBox();
   let x = bbox.x + bbox.width / 2;
   let y = bbox.y + bbox.height / 2;
   
   // Create a <text> element
   let textElem = document.createElementNS(bgPath.namespaceURI, "text");
   textElem.setAttribute("x", x);
   textElem.setAttribute("y", y);
   // Centre text horizontally at x,y
   textElem.setAttribute("text-anchor", "middle");
   // Give it a class that will determine the text size, colour, etc
   textElem.classList.add(`${class_name}label`);
   // Set the text
   textElem.textContent = labelText;
   // Add this text element directly after the label background path
   bgPath.after(textElem);
 }

// variable declartion

// let lable1 = document.querySelector("#India");



$("#Refresh_button").click(function(e){
 // document.onmouseover = function(e) {

  e.preventDefault();
  jQuery.ajax({
    type: 'post',
    url: scriptParams.ajaxurl + '/?action=fn_get_map_data',
    data: {'name': 'test' },
    success: function (data) {
      var jsondata = JSON.parse(data);
          // console.log(jsondata);
          jsondata.forEach(function(data, index) {
            console.log(data);
            var country_name =`${data.Country}`
            var lable1 = document.querySelector(`#${data.Country}`);
            console.log("query"+lable1);
            addLabelText(lable1 ,data.NUM,country_name);
            $(`.${data.Country}label`).css("display", "none");
          });
        }
      })    
});
});

document.onmouseover = function(e) {
 $(`.${e.target.id}label`).css("display", "unset");

 setTimeout(function() {
   $(`.${e.target.id}label`).css("display", "none");  
 }, 3500);
}


// onclick get id 
var Map_clicked = document.getElementById('World_map_svg');
Map_clicked.addEventListener('click', function(e) {

 console.log("fsdfdsfsdf");
 var linkeofsite = window.location.href ; 
 var finallink = linkeofsite.split('.php/')[0];
 console.log(`${finallink}.php/Map_single_Page`);

 $.ajax({  
  type: 'POST',  
  url: scriptParams.ajaxurl + '/?action=fn_map_process', 
  data: { 'Country': e.target.id },
  success: function(response) {
   console.log(response);
   if (!$.trim(response)){   
    alert("What follows is blank: " + response);
  }
  else{ 

    window.location.href = `${finallink}.php/Map_single_Page`;
  }

}
});

}, false);