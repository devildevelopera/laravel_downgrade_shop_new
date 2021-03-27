<script src="{{ asset('resources/views/theme/js/vendor.min.js') }}"></script>
<script src="{{ asset('resources/views/theme/js/theme.min.js') }}"></script>
@if ($message = Session::get('success'))
<script type="text/javascript">
$('#cart-toast-success').toast('show')
</script>
@endif
@if ($message = Session::get('error'))
<script type="text/javascript">
$('#cart-toast-error').toast('show')
</script>
@endif
<!-- pagination --->
<script src="{{ URL::to('resources/views/theme/pagination/pagination.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {
      $(this).cPager({
            pageSize: {{ $allsettings->post_per_page }}, 
            pageid: "post-pager", 
            itemClass: "li-item",
			pageIndex: 1
 
        });
	$(this).cPager({
            pageSize: {{ $allsettings->comment_per_page }}, 
            pageid: "commpager", 
            itemClass: "commli-item",
			pageIndex: 1
 
        });	
		
	$(this).cPager({
            pageSize: {{ $allsettings->review_per_page }}, 
            pageid: "reviewpager", 
            itemClass: "review-item",
			pageIndex: 1
 
        });	
		
	$(this).cPager({
            pageSize: {{ $allsettings->product_per_page }}, 
            pageid: "itempager", 
            itemClass: "prod-item",
			pageIndex: 1
 
        });	
});
</script>
<!--- pagination --->
<!-- share code -->
<script src="{{ asset('resources/views/theme/share/share.js') }}"></script> 
<script type="text/javascript">
$(document).ready(function(){

		$('.share-button').simpleSocialShare();

	});
</script> 
<!-- share code -->
<!-- validation code -->
<script src="{{ URL::to('resources/views/theme/validate/jquery.bvalidator.min.js') }}"></script>
<script src="{{ URL::to('resources/views/theme/validate/themes/presenters/default.min.js') }}"></script>
<script src="{{ URL::to('resources/views/theme/validate/themes/red/red.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function () {
        
		var options = {
		
		offset:              {x:5, y:-2},
		position:            {x:'left', y:'center'},
        themes: {
            'red': {
                 showClose: true
            },
		
        }
    };

    $('#login_form').bValidator(options);
	$('#contact_form').bValidator(options);
	$('#subscribe_form').bValidator(options);
	$('#footer_form').bValidator(options);
	$('#comment_form').bValidator(options);
	$('#reset_form').bValidator(options);
	$('#support_form').bValidator(options);
	$('#item_form').bValidator(options);
	$('#search_form').bValidator(options);
	$('#checkout_form').bValidator(options);
	$('#profile_form').bValidator(options);
	$('#withdrawal_form').bValidator(options);
    });
</script>
<!-- validation code -->
<!-- countdown -->
<script type="text/javascript" src="{{ asset('resources/views/theme/countdown/jquery.countdown.js?v=1.0.0.0') }}"></script>
<!-- countdown -->
<!--- video code --->
<script type="text/javascript" src="{{ URL::to('resources/views/theme/video/video.js') }}"></script>
<script type="text/javascript">
		jQuery(function(){
			jQuery("a.popupvideo").YouTubePopUp( { autoplay: 0 } ); // Disable autoplay
		});
</script>
<!--  video code --->
<!--- auto search -->
<script src="{{ URL::to('resources/views/theme/autosearch/jquery-ui.js') }}"></script>
<script type="text/javascript">
   $(document).ready(function() {
    src = "{{ route('searchajax') }}";
     $("#product_item").autocomplete({
        source: function(request, response) {
            $.ajax({
                url: src,
                dataType: "json",
                data: {
                    term : request.term
                },
                success: function(data) {
                    response(data);
                   
                }
            });
        },
        minLength: 1,
       
    });
});
</script>
<script type="text/javascript">
   $(document).ready(function() {
    src = "{{ route('searchajax') }}";
     $("#product_item_top").autocomplete({
        source: function(request, response) {
            $.ajax({
                url: src,
                dataType: "json",
                data: {
                    term : request.term
                },
                success: function(data) {
                    response(data);
                   
                }
            });
        },
        minLength: 1,
       
    });
});
</script>
<!--- auto search -->
<!--- common code -->
<script type="text/javascript">

$(document).ready(function() {


  var $tabButtonItem = $('#tab-button li'),
      $tabSelect = $('#tab-select'),
      $tabContents = $('.tab-contents'),
      activeClass = 'is-active';

  $tabButtonItem.first().addClass(activeClass);
  $tabContents.not(':first').hide();

  $tabButtonItem.find('a').on('click', function(e) {
    var target = $(this).attr('href');

    $tabButtonItem.removeClass(activeClass);
    $(this).parent().addClass(activeClass);
    $tabSelect.val(target);
    $tabContents.hide();
    $(target).show();
    e.preventDefault();
  });

  $tabSelect.on('change', function() {
    var target = $(this).val(),
        targetSelectNum = $(this).prop('selectedIndex');

    $tabButtonItem.removeClass(activeClass);
    $tabButtonItem.eq(targetSelectNum).addClass(activeClass);
    $tabContents.hide();
    $(target).show();
  });

/* Reply comment area js goes here */
    var $replyForm = $('.reply-comment'),
        $replylink = $('.reply-link');

    $replyForm.hide();
    $replylink.on('click', function (e) {
        e.preventDefault();
        $(this).parents('.media').siblings('.reply-comment').toggle().find('textarea').focus();
    });

}); 


$(function () {
$("#ifstripe").hide();
$("#ifpaystack").hide();
        $("input[name='withdrawal']").click(function () {
		
            if ($("#withdrawal-paypal").is(":checked")) 
			{
			   $("#ifpaypal").show();
			   $("#ifstripe").hide();
			   $("#ifpaystack").hide();
			}
			else if ($("#withdrawal-stripe").is(":checked"))
			{
			  $("#ifpaypal").hide();
			  $("#ifstripe").show();
			  $("#ifpaystack").hide();
			}
			else if ($("#withdrawal-paystack").is(":checked"))
			{
			  $("#ifpaypal").hide();
			  $("#ifstripe").hide();
			  $("#ifpaystack").show();
			}
			else
			{
			$("#ifpaypal").hide();
			$("#ifstripe").hide();
			$("#ifpaystack").hide();
			}
		});
    });
</script>
<!--- common code -->
@if($view_name == 'checkout')
<!-- stripe code -->
<script src="https://js.stripe.com/v3/"></script>
<script>
$(function () {
$("#ifYes").hide();
        $("input[name='payment_method']").click(function () {
		
            if ($("#opt1-stripe").is(":checked")) {
                $("#ifYes").show();
				
				/* stripe code */
				
				var stripe = Stripe('{{ $stripe_publish_key }}');
   
				var elements = stripe.elements();
					
				var style = {
				base: {
					color: '#32325d',
					lineHeight: '18px',
					fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
					fontSmoothing: 'antialiased',
					fontSize: '14px',
					'::placeholder': {
					color: '#aab7c4'
					}
				},
				invalid: {
					color: '#fa755a',
					iconColor: '#fa755a'
				}
				};
			 
				
				var card = elements.create('card', {style: style, hidePostalCode: true});
			 
				
				card.mount('#card-element');
			 
			   
				card.addEventListener('change', function(event) {
					var displayError = document.getElementById('card-errors');
					if (event.error) {
						displayError.textContent = event.error.message;
					} else {
						displayError.textContent = '';
					}
				});
			 
				
				var form = document.getElementById('checkout_form');
				form.addEventListener('submit', function(event) {
					/*event.preventDefault();*/
			        if ($("#opt1-stripe").is(":checked")) { event.preventDefault(); }
					stripe.createToken(card).then(function(result) {
					
						if (result.error) {
						
						var errorElement = document.getElementById('card-errors');
						errorElement.textContent = result.error.message;
						
						
						} else {
							
							document.querySelector('.token').value = result.token.id;
							 
							document.getElementById('checkout_form').submit();
						}
						/*document.querySelector('.token').value = result.token.id;
							 
							document.getElementById('checkout_form').submit();*/
						
					});
				});
							
						
			/* stripe code */	
				
				
				
            } else {
                $("#ifYes").hide();
            }
        });
    });
	

</script>
<!-- stripe code -->
@endif
<!-- cookie -->
<script type="text/javascript" src="{{ asset('resources/views/theme/cookie/cookiealert.js') }}"></script>
<!-- cookie -->
<!-- loading gif code -->
@if($allsettings->site_loader_display == 1)
<script type='text/javascript' src="{{ URL::to('resources/views/theme/loader/jquery.LoadingBox.js') }}"></script>
<script>
    $(function(){
        var lb = new $.LoadingBox({loadingImageSrc: "{{ url('/') }}/public/storage/settings/{{ $allsettings->site_loader_image }}",});

        setTimeout(function(){
            lb.close();
        }, 1000);
    });
</script>
@endif
<!-- loading gif code -->
<!-- animation code -->
<script src="{{ URL::to('resources/views/theme/animate/aos.js') }}"></script>
<script>
      AOS.init({
        easing: 'ease-in-out-sine'
      });
</script>
<!-- animation code -->
@if($allsettings->site_google_translate == 1)
<script>
    function googleTranslateElementInit() {
        new google.translate.TranslateElement(
            {pageLanguage: 'en'},
            'google_translate_element'
        );
    }
</script>
<script src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
@endif