$(document).ready(function(){   
    // Open modal
    $('.sheet-handle').on('click', function () {
		$('.modal-cart').toggleClass('active_bottom');		
    });
	
    // Close modal on overlay click
    $('.sheet-overlay').on('click', function () {
        closeSheet();
    });   

	$('.scroll-order').on('wheel', function (e) {
		if (e.originalEvent.deltaY < 0) {
			// Already at the top
			if ($(this).scrollTop() === 0) {
				closeSheet();
			}
		}
	});

    function closeSheet() {
		$('.modal-cart').removeClass('active_bottom');
    }

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
    			$('.cart-total').text('₹' + response.cartTotal);				

				// show/hide cart count
				if(response.cartCount > 0){
					//$('.default-count').show();
					//$('.default-count').removeClass('d-none');
					//$('.control-count').addClass('d-none');
				} else {
					//$('.control-count').addClass('d-none');
				}

				$('.modal-cart').addClass('refresh');
			
				setTimeout(function () {
					$('.modal-cart').removeClass('refresh');
				}, 700);

			
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

	function checkFields() {
		let activeTab  = $('.tab-link.active').data('type');
		let notes      = $.trim($('textarea[name="notes"]').val());		
		let phone      = $.trim($('input[name="phone"]').val());
		let seatId     = $('select[name="seat_id"]').val();		
		let outlet_id  = $('select[name="active_outlet_id"]').val() || '';
		let name       = $.trim($('input[name="active_name"]').val());
		let email      = $.trim($('input[name="active_email"]').val());
		//let phone      = $.trim($('input[name="active_phone"]').val());
		let address    = $.trim($('textarea[name="address"]').val());

		let valid = false;
		let baseTotal = parseFloat($('#baseTotal').val()) || 0;
		let deliveryCharge = 50;
		let finalTotal = baseTotal;

		// Dine in
		if (activeTab == 'Dinein') {
			if (
				notes.trim() !== '' &&
				phone.trim() !== '' &&
				seatId.trim() !== ''
			) {
				valid = true;
			}
		}

		// Takeaway
		else if (activeTab == 'Takeaway') {
			if (
				notes.trim() !== '' &&
				name.trim() !== '' &&
				email.trim() !== '' &&				
				outlet_id.trim() !== '' 
			) {
				valid = true;
			}
		}

		// Delivery
		else if (activeTab == 'Delivery') {
			if (
				notes.trim() !== '' &&
				name.trim() !== '' &&
				email.trim() !== '' &&				
				outlet_id.trim() !== '' &&
				address.trim() !== ''
			) {
				valid = true;
			}

			// Add delivery charge
			finalTotal += deliveryCharge;
		}

		if (valid) {
			$('.btn--brand').removeClass('basket-page__content__order-btn--disabled');
		} else {
			$('.btn--brand').addClass('basket-page__content__order-btn--disabled');
		}

		// Update total
		$('.grandTotal').text('₹' + Math.round(finalTotal));

		// Optional delivery fee text
		if(activeTab == 'Delivery'){
			$('#deliveryFeeText').show();
		}else{
			$('#deliveryFeeText').hide();
		}		
	}
		
	// Inputs / textarea / select
	$('input, textarea, select').on('keyup change', checkFields);

	// Custom tab click
	$('.tab-link').on('click', function () {
		let type = $(this).data('type');

		$('.tab-link').removeClass('active');
		$(this).addClass('active');

		// Active content
		$('.tab-content').removeClass('active');
		$('.' + type).addClass('active');

		// Update order type
		$('#order_type').val(type);

		$('.field').each(function () {
			$(this).attr('name', $(this).data('name'));
		});
		
		$('.' + type).find('.field').each(function () {
			$(this).attr('name', 'active_' + $(this).data('name'));
		});		

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
		cartTarget: '.bottom-sheet .sheet-handle',
		topMove: 200,
		firstDuration: 300,
		secondDuration: 700,
		finalWidth: 40,
		finalHeight: 40,
		opacity: 0.5
	};

	// merge custom options
	let settings = $.extend({}, defaults, options);
	let productImage = button.closest(settings.productWrapper).find(settings.imageSelector);
	let cartTarget = $(settings.cartTarget);
	let startOffset = productImage.offset();
	let endOffset = cartTarget.offset();
	let centerLeft = startOffset.left + ($(cartTarget).width() / 3);
	let clone = productImage.clone();

	clone.css({
		position: 'absolute',
		top: startOffset.top,
		left: startOffset.left,
		width: productImage.width(),
		height: productImage.height(),
		zIndex: 9999,
		borderRadius: '5px',
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
	$('.modal-cart').addClass('refresh');
		
	setTimeout(function () {
		$('.modal-cart').removeClass('refresh');
	}, 1100);
}

function flyToCartTrash() {
	$('.trash').addClass('refresh');
		
	setTimeout(function () {
		$('.trash').removeClass('refresh');
	}, 1500);
}


$(document).on('click', '.add-to-cart', function () {	
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
			$('.manage-qty-' + productId).text(response.qty);

			let qtyBtn = $('.sub-icon-control-' + productId);

			if (parseInt(response.qty) <= 1) {
				qtyBtn.removeClass('qty-decrease').addClass('qty-remove');
			} else {
				qtyBtn.removeClass('qty-remove').addClass('qty-decrease');
			}

			$('.modal-' + productId).find('.manage-modal-qty').text(response.qty);			
			$('.cart-count').show().text(response.cartCount);
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
			$('.cart-section-' + productId)
				$('.manage-qty-' + productId).text(response.qty);

			$('.modal-' + productId)
				.find('.manage-modal-qty').text(response.qty);

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

//Variant products
$(document).on('change', '.product-variant', function () {
	let price = $(this).val();
	let name  = $(this).data('name');

	// Change visible price
	$('.product-price-show').text(price);

	// Update hidden fields
	$('.variant_name').val(name);
	$('.variant_price').val(price);
});