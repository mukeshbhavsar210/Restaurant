$(document).ready(function(){   
	let message = sessionStorage.getItem('successMessage');
	if(message){
		$('#customAlert').html(
			'<div class="custom-success-alert">'+message+'</div>'
		);

		setTimeout(function(){
			$('.custom-success-alert').fadeOut();
		}, 3000);

		sessionStorage.removeItem('successMessage');
	}

	let openModal = localStorage.getItem('openModal');
	if(openModal){
		let modal = $('#' + openModal);
		// remove animation
		modal.removeClass('fade');
		// open instantly
		modal.modal('show');
		localStorage.removeItem('openModal');
		// optional add fade back
		setTimeout(function(){
			modal.addClass('fade');
		}, 300);
	}

	// OPEN MODAL
	$(document).on('click', '.open-modal', function () {
		let modalId = $(this).data('modal');
		$('#' + modalId).addClass('active');
		localStorage.setItem('activeModal', modalId);
	});

	// CLOSE MODAL
	$(document).on('click', '.close-modal', function () {
		let modalId = $(this).data('modal');
		$('#' + modalId).removeClass('active');
		localStorage.removeItem('activeModal');

		setTimeout(function () {
			location.reload();
		}, 100);
	});	

	if(localStorage.getItem('cartModal') == 'active'){
        $('#cartModal').addClass('active');
    }

	// $(document).on('submit', '.cart-form', function () {
	// 	let modalId = $(this).data('modal');
	// 	localStorage.setItem('openModal', modalId);
	// });

	//Page refresh keep open modal
	let activeModal = localStorage.getItem('activeModal');
    if(activeModal){
        $('#' + activeModal).addClass('active');

        // optional remove storage after reopen
        localStorage.removeItem('activeModal');
    }


	$(document).on('submit', '.cart-form', function (e) {
		e.preventDefault();

		let form = $(this);
		let url = form.attr('action');
		let productId = form.data('id');

		$.ajax({
			url: url,
			type: 'GET',
			data: form.serialize(),

			success: function (response) {
				$('.cart-count').text(response.cartCount);

				 // cart total
    			$('.cart-total').text('₹' + response.cartTotal);				

				// show/hide cart count
				if(response.cartCount > 0){
					//$('.default-count').show();
					//$('.default-count').removeClass('d-none');
					//$('.control-count').addClass('d-none');
				} else {
					//$('.control-count').addClass('d-none');
				}

				$('.bottom-sheet').addClass('refresh');
			
				setTimeout(function () {
					$('.bottom-sheet').removeClass('refresh');
				}, 1000);

				// refresh page after 1 second
				// setTimeout(function () {
				// 	location.reload();
				// }, 900);
						
				// KEEP MODAL OPEN
				let modalId = form.data('modal');

				if(modalId){
					$('#' + modalId).addClass('active');
				}
			}
		});
	});
	

	//Slick gallery
	$('.product-slider').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        dots: true,
        infinite: true,
        autoplay: true,
        autoplaySpeed: 3000
    });

    var slider_width = $('.orderDetails').height();

    $('#cartDetails').click(function() {
        if($(this).css("margin-bottom") == slider_width+"px" && !$(this).is(':animated')) {
            $('.orderDetails,#cartDetails').animate({"margin-bottom": '-='+slider_width});	
            $('body').removeClass("open");		
        }
        else {
            $('body').addClass("open");
                if(!$(this).is(':animated')) {
                    $('.orderDetails,#cartDetails').animate({"margin-bottom": '+='+slider_width});				
            }
        }
    });

    $('.handle').on('click', function () {
		$('#bottomSheet').toggleClass('active_bottom');
	});  

	$('.sheet-overlay').on('click', function () {
		$('#bottomSheet').removeClass('active_bottom');
	}); 

	$('.tab-link').click(function () {
		var tabID = $(this).data('tab');

		// remove active from all tabs
		$('.tab-link').removeClass('active');
		$('.tab-content').removeClass('active');

		// add active to clicked tab
		$(this).addClass('active');
		$('.' + tabID).addClass('active');			
	});

	function checkFields() {
		let activeTab 			= $('.tab-link.active').data('tab');
		let notes      			= $.trim($('textarea[name="notes"]').val());
		let dineinTime 			= $('select[name="dinein_time"]').val();
		let seatId     			= $('select[name="seat_id"]').val();
		let ready_time  	    = $('select[name="ready_time"]').val();
		let delivery_address    = $.trim($('textarea[name="delivery_address"]').val());
		let customer_name       = $.trim($('input[name="customer_name"]').val());
		let customer_email      = $.trim($('input[name="customer_email"]').val());
		let customer_phone      = $.trim($('input[name="customer_phone"]').val());			
		let valid = false;
		let baseTotal = parseFloat($('#baseTotal').val()) || 0;
		let deliveryCharge = 50;
		let finalTotal = baseTotal;

		// Dine in
		if (activeTab == 'tab1') {
			if (
				notes !== '' &&
				dineinTime !== '' &&
				seatId !== ''
			) {
				valid = true;
			}
		}

		// Takeaway
		else if (activeTab == 'tab2') {
			if (
				notes !== '' &&
				ready_time !== '' &&
				customer_name !== '' &&
				customer_email !== '' &&
				customer_phone !== ''
			) {
				valid = true;
			}
		}

		// Delivery
		else if (activeTab == 'tab3') {
			if (
				notes !== '' &&
				ready_time !== '' &&
				//delivery_address !== '' &&
				customer_name !== '' &&
				customer_email !== '' &&
				customer_phone !== ''										
			) {
				valid = true;
			}

			// Add delivery charge
			finalTotal += deliveryCharge;
		}

		// Update total
		$('.grandTotal').text('₹' + Math.round(finalTotal));

		// Optional delivery fee text
		if(activeTab == 'tab3'){
			$('#deliveryFeeText').show();
		}else{
			$('#deliveryFeeText').hide();
		}

		// Button state
		if (valid) {
			$('.btn-primary').removeClass('disabled');
		} else {
			$('.btn-primary').addClass('disabled');
		}
	}
		
	// Inputs / textarea / select
	$('input, textarea, select').on('keyup change', checkFields);

	// Custom tab click
	$('.tab-link').on('click', function () {
		$('.tab-link').removeClass('active');
		$(this).addClass('active');
		checkFields();
	});

	// Initial check
	checkFields();
});

// COMMON FLY TO CART FUNCTION
function flyToCart(buttonSelector, options = {}) {
	let button = $(buttonSelector);
	let defaults = {
		imageSelector: '.product-img',
		productWrapper: '.custom-modal',
		cartTarget: '.bottom-sheet .handle',
		topMove: 150,
		firstDuration: 400,
		secondDuration: 700,
		finalWidth: 40,
		finalHeight: 40,
		opacity: 0.4
	};

	// merge custom options
	let settings = $.extend({}, defaults, options);
	let productImage = button.closest(settings.productWrapper).find(settings.imageSelector);
	let cartTarget = $(settings.cartTarget);
	let startOffset = productImage.offset();
	let endOffset = cartTarget.offset();
	let centerLeft = startOffset.left + ($(cartTarget).width() / 2);
	let clone = productImage.clone();

	clone.css({
		position: 'absolute',
		top: startOffset.top,
		left: startOffset.left,
		width: productImage.width(),
		height: productImage.height(),
		zIndex: 9999,
		borderRadius: '50%',
		pointerEvents: 'none'
	});

	$('body').append(clone);

	// STEP 1 → move UP + CENTER
	clone.animate({
		top: startOffset.top - settings.topMove,
		left: centerLeft
	}, settings.firstDuration);

	// STEP 2 → move DOWN to cart
	clone.animate({
		top: endOffset.top + 20,
		left: endOffset.left + ($(cartTarget).width() / 2),
		width: settings.finalWidth,
		height: settings.finalHeight,
		opacity: settings.opacity

	}, settings.secondDuration, 'swing', function () {
		clone.remove();
	});
}

function flyToCartBottom() {
	$('.bottom-sheet').addClass('refresh');
		
	setTimeout(function () {
		$('.bottom-sheet').removeClass('refresh');
	}, 1500);
}

function flyToCartTrash() {
	$('.trash').addClass('refresh');
		
	setTimeout(function () {
		$('.trash').removeClass('refresh');
	}, 1500);
}


$(document).on('click', '.add-to-cart-button', function () {
	flyToCart(this);
	flyToCartBottom(this);

	setTimeout(function () {
		location.reload();
	}, 1500);
});

// Increase
$(document).on('click', '.add-icon, .add-icon-big', function () {
	if ($(this).hasClass('add-icon-big')) {
        flyToCart(this);
		flyToCartBottom(this);
    }
	
	let productId = $(this).data('id');

	$.ajax({
		url: '/cart/increase/' + productId,
		type: 'GET',

		success: function (response) {
			// update qty
			$('.manage-qty-' + productId).text(response.qty);

			let qtyBtn = $('.sub-icon-control-' + productId);

			if (parseInt(response.qty) <= 1) {
				qtyBtn.removeClass('qty-decrease').addClass('qty-remove');
			} else {
				qtyBtn.removeClass('qty-remove').addClass('qty-decrease');
			}

			// modal qty
			$('.modal-' + productId).find('.manage-modal-qty').text(response.qty);			
			// cart count
			$('.cart-count').show().text(response.cartCount);
			// cart total
			$('.cart-total').text('₹' + response.cartTotal);
		}
	});
});

// Decrease
$(document).on('click', '.qty-decrease', function () {
	let productId = $(this).data('id');
	let button = $(this);

	$.ajax({
		url: '/cart/decrease/' + productId,
		type: 'GET',

		success: function (response) {
			// update only current product qty
			$('.cart-section-' + productId)
				$('.manage-qty-' + productId).text(response.qty);

			// modal qty
			$('.modal-' + productId)
				.find('.manage-modal-qty').text(response.qty);

			// target decrease buttons
			let qtyBtn = $('.sub-icon-control-' + productId);

			if (parseInt(response.qty) <= 1) {
				qtyBtn.removeClass('qty-decrease').addClass('qty-remove');
			} else {
				qtyBtn.removeClass('qty-remove').addClass('qty-decrease');
			}			

			// total cart count
			$('.cart-count').show().text(response.cartCount);

			// total amount
			$('.cart-total').text('₹' + response.cartTotal);				
		}
	});
});

$(document).on('click', '.qty-remove', function () {

	flyToCart(this);
	flyToCartTrash(this);

    let productId = $(this).data('id');

    $.ajax({
        url: '/cart/remove/' + productId,
        type: 'GET',
    });

	setTimeout(function () {
		location.reload();
	}, 1800);
});