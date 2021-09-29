jQuery(document).ready(function( $ ) {
    

var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = window.location.search.substring(1),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return typeof sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
        }
    }
    return false;
};

var msppParam = getUrlParameter('mspp');
console.log(msppParam);
if (msppParam == 'true') {
        $('#modal20a-shipping-screen').modal();
        msppParam = 'false';
} 
    
    $('#gotologin').on('click', function(){
        $('#login-form-modal').modal();
    });
    
    $('#gotoshipasguest').on('click', function(){
        $('#modal20a-shipping-screen').modal();
        $.ajax({
         type : "post",
         dataType : "json",
         url : msppfrontendajax.ajaxurl,
         data : {
             action: "mspp_setsession_checkoutas",
             setsession_checkoutas: "guest"
         },
         success: function(response) {
             console.log(response);
             alert(response);
         }
      });   
        
    });
    
    $('#gotoregister').on('click', function(){
        $('#modal12-registerform').modal();

    });
    
    var mspp_id;
    $('#mspp_gift_ship, #mspp_business_ship, #mspp_self_ship').click(function(){
           mspp_id = $(this).attr('id');
           mspp_id = mspp_id.replace('mspp_','');
           console.log(mspp_id);
    });
    
$('#modal20a-shipping-screen').on($.modal.OPEN, function(event, modal) {
       //alert(mspp_id); 
       if(mspp_id === 'self_ship'){
           $('#gift_sticker_block').hide();
           $('#gift_message_block').hide();
          $('#modal20a-shipping-screen').css('min-width', '50%');
           $('#shipping_address_block').css({
                   'width' : '100%',
                   'display' : 'block',
                   'float': 'none',
                   });
       } else {
           $('#gift_sticker_block').show();
           $('#gift_message_block').show();
          $('#modal20a-shipping-screen').css('min-width', '50%');
           $('#shipping_address_block').css({
                   'width' : '100%',
                   'display' : 'block',
                   'float': 'none',
                   });
           
       }
    });


    // show a dialog box when clicking on an element
    // $('#sendgiftrow').on('click', function(e) {
    //     e.preventDefault();
    //     new $.Zebra_Dialog({
    //         source: {
    //             inline: $("#sendgift-ex6-1").html()
    //         },
    //         title: '<div style="text-align:center; margin-top:10px;"><h3>Who Are You Shipping To?</h3></div>',
    //         height: 500, 
    //         width: 600,
    //         type: false
    //     });

        
    // });

    
    // $("#sendgift-ex6-1-next").on('click', function(){
    //         alert("form has been submitted.");
    //         console.log('a link clicked');
    // });
    
    
    $('#sendgiftrow, #shiptoclientsrow, #shiptome').hover(function () {
                  $(this).css({"color":"brown"});
                  $(this).css('cursor', 'pointer'); 
                  $(this).css('cursor', 'hand'); 
               }, 
				
               function () {
                  $(this).css({"color":"inherit"});
               }
    );
    
    // $('#mspp_set_address_btn').click(function(){

    //     $.get( msppfrontendajax.ajaxurl +"/?action=mspp_save_address", function( data ) {
    //         console.log(data);
    // });
    
    // });
    
    $('#mspp_set_address_btn').click( function(e) {
        var newaddress_formdata = $('form#mspp_addresses_form').serialize();
      e.preventDefault(); 
      $.ajax({
         type : "post",
         dataType : "json",
         url : msppfrontendajax.ajaxurl,
         data : {
             action: "mspp_save_address",
             newaddress_formdata: newaddress_formdata
         },
         success: function(response) {
             console.log(response);
             alert(response);
         }
      });   
   });
   
    $('#mspp_address_and_addtocart_btn').click( function(e) {
           e.preventDefault(); 
           var address_is_new = false;
           var newaddress_formdata;
           
           if($("#mspp_addresses_form").css("display") == "block"){
               newaddress_formdata = $('form#mspp_addresses_form').serialize();
               address_is_new = true;
           } else {
               newaddress_formdata = $('select[name=mspp_addresses] option').filter(':selected').val();
               address_is_new = false;
           }
     //close modal
        $.modal.close();
    //send to server
        $.ajax({
         type : "post",
         dataType : "json",
         url : msppfrontendajax.ajaxurl,
         data : {
             action: "mspp_save_address_addtocart",
             newaddress_formdata: newaddress_formdata,
             productid: msppfrontendajax.postID,
             address_is_new: address_is_new
         },
         success: function(response) {
            //  location.reload();
             console.log('success');
             console.log(response);
            //  alert(response);
            
        //  var fragments = response.fragments;
        //  if ( fragments ) {

        //         $.each(fragments, function(key, value) {
        //             $(key).replaceWith(value);
        //         });

        //     }
        
                    $(document.body).trigger("wc_fragment_refresh");
                    $('.woocommerce-error,.woocommerce-message').remove();
                    // $('input[name="quantity"]').val(1);
                    $('.content-area').before(response.fragments.notices_html);
                    // form.unblock();
         },
        error: function (error) {
                    // form.unblock();
                    // $('.content-area').before(error.responseText);
                    console.log('error');
                    console.log(error);
                }

      });

   });
   
   

    
    //add new row
    $('#addnewrow').click(function(){

        var newrow_html = "<tr> <td> <select name='mspp_qty' id='mspp_qty'> <option value='1'>1</option> <option value='2'>2</option> <option value='3'>3</option> <option value='4'>4</option> </select> </td> <td> <select name='mspp_addresses' id='mspp_addresses'> <option value='a'>abc</option> <option value='b'>bca</option> <option value='c'>cba</option> <option value='d'>dcb</option> </select> </td> <td> <input type='checkbox' name='mspp_gift' value='Gift'> <label for='mspp_gift'> This is a gift.</label><br> </td> <td><span class='delthisrow'><i class='fa fa-minus-circle'></i></span></td></tr>";
        
        $('#placeordertable tr:last').after(newrow_html);

    });
    
    //delete a row
    $("#placeordertable").on('click', '.delthisrow', function () {
    $(this).closest('tr').remove();
});

$('#mspp_addresses').change(function(){
    
    $("option[value=" + this.value + "]", this)
  .attr("selected", true).siblings()
  .removeAttr("selected");
    
   if($(this).val() === "addnewaddress"){
       console.log('add new address');
       $('#mspp_addresses_form').css('display', 'block');
        //show the modal
   } else {
       $('#mspp_addresses_form').css('display', 'none');
       
   }
});

$('div.packthumbnail').on('click', function(){
   $('div.packthumbnail').removeClass('active');
   $("input[name=giftbusinesspersonal]").val('reset');
   $(this).addClass('active');
   $("input[name=giftbusinesspersonal]").val($('div.packthumbnail.active').attr('data-package'));
   
});


//plus minus quantity input 
// $('.qtyminus').click(function () {
// 	var $input = $(this).parent().find('input');
// 	var count = parseInt($input.val())-1;
// 	count = count < 1 ? 1 : count;
// 	$input.val(count);
// 	$input.change();
// 	var minusunitprice = parseInt($input.parents('tr').find('.unitprice').html())*1;
// 	var minusqty = ($input.val())*1;
// 	var minuslinetotal = minusunitprice * minusqty;
// 	$input.parents('tr').find('.linetotal').html(minuslinetotal);
// 	console.log('minusqty:'+minusqty +'; minusunitprice:'+minusunitprice+'; linetotal:'+minuslinetotal);
// 	return false;
// 	});

	$('.qtyminus').click(function () {
	var minusinput = $(this).parent().find('input');
	var minuscount = parseInt(minusinput.val())-1;
	minuscount = minuscount < 1 ? 1 : minuscount;
	minusinput.val(minuscount);
	var minusqty = parseInt(minusinput.val())*1;
	minusinput.change(function(){
	    minusinput.attr('value', minusqty);
	});
	var minusunitprice = parseInt(minusinput.parents('tr').find('.unitprice').html())*1;
	var minuslinetotal = minusunitprice * minusqty;
	minusinput.parents('tr').find('.linetotal').html(minuslinetotal);
	console.log('minusqty:'+minusqty +'; minusunitprice:'+minusunitprice+'; linetotal:'+minuslinetotal);
	return false;
	});
	$('.qtyplus').click(function () {
	var plusinput = $(this).parent().find('input');
	var pluscount = parseInt(plusinput.val())+1;
	plusinput.val(pluscount);
	var plusqty = parseInt(plusinput.val())*1;
	plusinput.change(function(){
	    plusinput.attr('value', plusqty);
	});
	var plusunitprice = parseInt(plusinput.parents('tr').find('.unitprice').html())*1;
	var plusqty = parseInt(plusinput.val())*1;
	var pluslinetotal = plusunitprice * plusqty;
	plusinput.parents('tr').find('.linetotal').html(pluslinetotal);
	return false;
	});

}); //close main jquery $
