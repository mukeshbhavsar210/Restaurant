<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>
    window.onload = function() {
        var options = {
            key: "{{ config('services.razorpay.key') }}",
            amount: "{{ $order->total * 100 }}",
            currency: "INR",
            order_id: "{{ $order->razorpay_order_id }}",
            name: "{{ $config->name }}",
            image: "{{ asset('uploads/logo/'.$config->logo) }}",

            handler: function (response) {
                window.location.href = "{{ route('payment.success', $order->id) }}" + 
                "?payment_id=" + response.razorpay_payment_id;
            }
        };

        var rzp = new Razorpay(options);
        rzp.open();

        rzp.on('payment.failed', function (response) {
            window.location.href =
                "{{ route('payment.failed', $order->id) }}" +
                "?error=" + encodeURIComponent(response.error.description);

        });
    };
</script>