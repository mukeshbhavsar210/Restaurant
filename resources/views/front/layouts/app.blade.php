<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <title>@yield('title')</title>
	{{-- <link rel="stylesheet" type="text/css" href="{{ asset('front-assets/css/style.min.css') }}" />	 --}}
	<link rel="stylesheet" type="text/css" href="{{ asset('front-assets/css/style.css') }}" />
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

	<link rel="stylesheet" type="text/css" href="{{ asset('front-assets/css/slick.css') }}" />
	<link rel="stylesheet" type="text/css" href="{{ asset('front-assets/css/slick-theme.css') }}" />

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;500&family=Raleway:ital,wght@0,400;0,600;0,800;1,200&family=Roboto+Condensed:wght@400;700&family=Roboto:wght@300;400;700;900&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
<div class="app-wrapper">	
	<header id="sticky-header">
		<div class="header">
			<div class="header__restaurant-name">
				<a href="{{ route('front.home') }}" class="logo" >
					<img style="width: 120px" src="{{ asset('front-assets/images/logo.jpg') }} " alt="" />
				</a>
			</div>
		</div>	
	</header>

	<section class="categories-section categories-section--medium-photo">
		<ul class="categories-section__container">										
			@if(session('wishlist'))				
				<li class="{{ request()->routeIs('front.wishlist') ? 'menu_active' : '' }}">
					<a href="{{ route('front.wishlist') }}" class="favourite-icon">
						<span class="sprites"></span>
					</a>
					<p>Favourites</p>
				</li>
			@endif

			@if (getCategories()->isNotEmpty())
				@foreach (getCategories() as $value )	
					<li class="
							{{
								(request()->routeIs('front.menu') && request()->segment(2) == $value->slug) ||
								(request()->routeIs('front.home') && $loop->first)
								? 'menu_active'
								: ''
							}}">
						<a href="{{ route('front.menu',[$value->slug])}}">
							@if ($value->image != "")
								<img src="{{ asset('uploads/category/'.$value->image) }} " alt="">								
							@endif									
						</a>								
						<p>{{ $value->name }}</p>						
					</li>
				@endforeach				
			@endif
		</ul>
	</section>
	@yield('content')		
</div>

<script src="{{ asset('front-assets/js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('front-assets/js/slick.min.js') }}"></script>
<script src="{{ asset('front-assets/js/documentReady.js') }}"></script>
<script>
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
	});


    $("#placeOrder").submit(function(event){
        event.preventDefault();

        var element = $(this);

        element.find("button[type=submit]").prop('disabled', true);

        $.ajax({
            url: '{{ route("submit.order") }}',
            type: 'POST',
            data: element.serialize(),
            dataType: 'json',
            success: function(response){
                element.find("button[type=submit]").prop('disabled', false);
                if(response.status){
                    // store alert message
                    sessionStorage.setItem(
                        'successMessage',
                        response.message
                    );

                    // redirect
                    window.location.href = "{{ route('front.menu') }}";
                }else{
                    console.log(response.errors);
                }
            },

            error: function(){
                element.find("button[type=submit]").prop('disabled', false);
                console.log("Something went wrong");
            }
        });
    });


	$(document).ready(function () {
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
	});
	

	// Increase
	$(document).on('click', '.qty-increase', function () {
		let productId = $(this).data('id');
		let button = $(this);

		$.ajax({
			url: '/cart/increase/' + productId,
			type: 'GET',

			success: function (response) {
				// update only current product qty
				$('.cart-section-' + productId)
					$('.manage-qty-' + productId).text(response.qty);

				// modal qty
				$('.modal-' + productId)
					.find('.manage-modal-qty').text(response.qty);

				// target decrease buttons
				let modalDecreaseBtn = $('.modal-' + productId).find('.qty-decrease');				
				let decreaseBtn = $('.cart-' + productId).find('.qty-decrease');

				// add/remove disabled class
				if(response.qty <= 1){
					modalDecreaseBtn.addClass('disabled');					
				} else {
					modalDecreaseBtn.removeClass('disabled');					
				}

				if(response.qty <= 1){
					decreaseBtn.addClass('disabled');
				} else {
					decreaseBtn.removeClass('disabled');
				}

				// total cart count
				$('.cart-count')
					.show().text(response.cartCount);

				// total amount
				$('.cart-total')
					.text('₹' + response.cartTotal);
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
				let modalDecreaseBtn = $('.modal-' + productId).find('.qty-decrease');				
				let decreaseBtn = $('.cart-' + productId).find('.qty-decrease');

				// add/remove disabled class
				if(response.qty <= 1){
					modalDecreaseBtn.addClass('disabled');					
				} else {
					modalDecreaseBtn.removeClass('disabled');					
				}

				if(response.qty <= 1){
					decreaseBtn.addClass('disabled');
				} else {
					decreaseBtn.removeClass('disabled');
				}

				// total cart count
				$('.cart-count')
					.show().text(response.cartCount);

				// total amount
				$('.cart-total')
					.text('₹' + response.cartTotal);				
			}
		});
	});

	
	
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

	function addToWishlist(id){
        $.ajax({
            url: '{{ route("front.addToWishlist",) }}',
            type: 'post',
            data: {id:id},
            dataType: 'json',
            success: function(response){
                if(response.status == true){
                    $("#wishlistModal .modal-body").html(response.message);
                    $("#wishlistModal").modal('show');
                } else {
                    window.location.href= "{{ route('front.home') }}";
                    alert(response.message);
                }
            }
        })
    }
		
	//Variant products
	$(document).on('change', '.product-variant', function () {
		let price = $(this).val();
		let name  = $(this).data('name');

		// Change visible price
		$('.product-price-show').text(price);

		// Update hidden fields
		$('#variant_name').val(name);
		$('#variant_price').val(price);
	});

	// $(window).scroll(function () {
	// 	if ($(this).scrollTop() > 10) {
	// 		$('#bottomSheet').removeClass('active_bottom');
	// 	} else {
	// 		$('#bottomSheet').addClass('active_bottom');
	// 	}
	// });	  
</script>

@yield('customJs')

</body>
</html>