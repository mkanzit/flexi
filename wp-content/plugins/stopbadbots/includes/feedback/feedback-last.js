	jQuery(document).ready(function($) {
  	$('#imagewaitfbl').hide(); 
    $deactivateSearch = $(".active");
    $deactivateSearch.click(function (evt) {
        
    billtempclass = evt.target.parentNode.className;
    // billclass = $(event.target).parent().prop("class");
    if( billtempclass != "deactivate") 
          { return; }
          
   // http://boatplugin.com/wp-admin/plugins.php?
   //action=deactivate&plugin=boatdealer
   //%2Fboatdealer.php&plugin_status=all&paged=1&s&_wpnonce=fdd5f36af4
    billstring = evt.target.href;
    
    
    $deactivateLink = '';    
    if(billstring.includes('plugin=antihacker'))
      { 
        $deactivateLink = billstring; 
        product = 'antihacker';
        prodclass = 'anti_hacker';
      }
    else if (billstring.includes('plugin=boatdealer'))
      { 
        $deactivateLink = billstring; 
        product = 'Boat Dealer Plugin';
        prodclass = 'boat_dealer_plugin';
        
      }      
    else if (billstring.includes('plugin=reportattacks'))
      { 
        $deactivateLink = billstring; 
        product = 'reportattacks';
        prodclass = 'report_attacks';
      }        
    else if (billstring.includes('plugin=stopbadbots'))
      { 
        $deactivateLink = billstring; 
        product = 'stopbadbots';
        prodclass = 'stop_bad_bots';
      } 
      
      
    if($deactivateLink == '')
         { return; }  
         
    if(prodclass != 'boat_dealer_plugin')
       {$('.boat_dealer_plugin-wrap-deactivate').slideUp();}
       
    if(prodclass != 'anti_hacker')
      {$('.anti_hacker-wrap-deactivate').slideUp();}
      
    if(prodclass != 'report_attacks')
      {$('.report_attacks-wrap-deactivate').slideUp();}
      
    if(prodclass != 'stop_bad_bots')
      {$('.stop_bad_bots-wrap-deactivate').slideUp();}
      
      
     evt.preventDefault(billstring);
     
   //  console.log(prodclass);
     
        
     $billmodal = $('.'+prodclass+'-wrap-deactivate');
     
     $billmodal.show();
     
     $billmodal.prependTo($('#wpcontent')).slideDown();
     
     

     $('.'+prodclass+'-wrap-deactivate').prependTo($('#wpcontent')).slideDown();
     
     $('html, body').scrollTop(0);
        
     $( "."+prodclass+"-deactivate" ).click(function() {
             $('#imagewaitfbl').show();
             if( !$(this).hasClass('disabled')) 
                {  
                    $( "."+prodclass+"-close-submit" ).addClass('disabled');
                    $( "."+prodclass+"-close-dialog" ).addClass('disabled');
                    $( "."+prodclass+"-deactivate" ).addClass('disabled');
                    window.location.href = $deactivateLink; 
                }
          });
     $( "."+prodclass+"-close-submit" ).click(function() {
                     var isAnonymousFeedback = $(".anonymous").prop("checked");
                     var explanation = $("#"+prodclass+"-explanation").val();
                     var username = $('#username').val();
                     var version = $("#"+prodclass+"-version").val();
                     var email = $('#email').val();
                     var wpversion = $('#wpversion').val();
                     var dom = document.domain;
                     var limit = $('#limit').val();
                     var wplimit = $('#wplimit').val();
                     var usage = $('#usage').val(); 
                     $('#imagewaitfbl').show(); 
                     $( "."+prodclass+"-close-submit" ).addClass('disabled');
                     $( "."+prodclass+"-close-dialog" ).addClass('disabled');
                     $( "."+prodclass+"-deactivate" ).addClass('disabled');
                    if(isAnonymousFeedback)
                    {
                		    email = 'anonymous';
                            username = 'anonymous';
                            dom = 'anonymous';
                            version = 'anonymous';
                            wpversion = 'anonymous';                       
                    } 
                     $.ajax({
                	    url       : 'http://billminozzi.com/httpapi/httpapi.php',
                        withCredentials: true,
                        timeout: 15000,
                		method    : 'POST',
                        crossDomain: true,
                		data      : {
                		    email: email,
                            name: username,
                            obs: explanation,
                            dom: dom,
                            version: version,
                            produto: product,
                            limit: limit,
                            wplimit: wplimit,
                            usage: usage,
                            wpversion: wpversion
                			},
                		complete  : function () {
                			// Do not show the dialog box, deactivate the plugin.
                			window.location.href = $deactivateLink;
                		}
                	 }); // end ajax
         }); // end clicked button share ...
         $( "."+prodclass+"-close-dialog" ).click(function(evt) {
            if( ! $(this).hasClass('disabled')) 
            {
               $('#imagewaitfbl').hide();
               $billmodal = $('.'+prodclass+'-wrap-deactivate');
               $billmodal.slideUp();
            }
         });                       
   	}); // end clicked Deactivated ...
});  // end jQuery  