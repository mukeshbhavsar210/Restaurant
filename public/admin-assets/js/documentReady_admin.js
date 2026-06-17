$(document).ready(function(){
	$('.link').hover(
		function () {
			$(this).addClass('active');
		},
		function () {
			$(this).removeClass('active');
		}
	);

	$('.product-card').hover(
		function () {
			$(this).addClass('active');
		},
		function () {
			$(this).removeClass('active');
		}
	);

    function checkValue(element){
        if($(element).val() !== ''){
            $(element).closest('.form-group').addClass('active');
        } else {
            $(element).closest('.form-group').removeClass('active');
        }
    }

    // On Focus
    $(document).on('focus', '.form-control', function(){        
        $(this).closest('.form-group').addClass('active');
    });

    // On Blur
    $(document).on('blur', '.form-control', function(){
        checkValue(this);
    });

    // On Page Load (Edit Mode / Prefilled)
    $('.form-control').each(function(){
        checkValue(this);
    });          
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

$('input, textarea, select').on('keyup change', checkFields);

$('.tab-link').on('click', function () {    
    let type = $(this).data('type');

    $('.tab-link').removeClass('active');
    $(this).addClass('active');

    // Active content
    $('.tab-content-custom').removeClass('active');
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


$(document).on('click', '.qty-increase-small, .added', function () {	    	
    let productId = $(this).data('id');
	let seatId = $(this).data('seat');

    $.ajax({
		url: '/kot/' + seatId + '/increase/' + productId,        
        type: 'GET',

        success: function (response) {
            let qty = parseInt(response.qty);					
            $('.manage-qty-' + productId).text(qty);  				
            
			$('.cart-count').text(response.kotCount);
            $('.cart-total').text('₹' + response.kotTotal);
			$('#baseTotal').val(response.kotTotal);
			$('#baseTotal').val(response.kotTotal).trigger('change');

			//alert(response.message);
        }
    });
});

$(document).on('click', '.qty-decrease-small', function () {
    let productId = $(this).data('id');
    let seatId = $(this).data('seat');

    $.ajax({
        url: '/kot/' + seatId + '/decrease/' + productId,
        type: 'GET',

        success: function (response) {
            $('.manage-qty-' + productId).text(response.qty);
			$('.cart-count').text(response.kotCount);
            $('.cart-total').text('₹' + response.kotTotal);
			$('#baseTotal').val(response.kotTotal);
			$('#baseTotal').val(response.kotTotal).trigger('change');
        }
    });
});


