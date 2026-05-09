
<h1>Branch</h1>
<form action="" method="post" id="branchForm" name="branchForm">
    <div class="row">

        <div class="col-md-6">
            <div class="form-group">
                <label for="branch_name" class="mb-2">Branch Name*</label>
                <input type="text" id="branch_name" name="branch_name" placeholder="branch Name" class="form-control" value="{{ $user->branch_name }}">
                <p></p>
            </div>
        </div>  
        <div class="col-md-12">
            <div class="form-group">
                <label for="branch_address" class="mb-2">Branch Address*</label>
                <textarea type="text" id="branch_address" rows="4" cols="50" name="branch_address" placeholder="restaurant_address" class="form-control" value="">{{ $user->branch_address }}</textarea>
                <p></p>
            </div>
        </div>
    </div>        
    <div class="pb-5 pt-3">
        <button type="submit" class="btn btn-primary">Add branch</button>
    </div>
</form>


@section('customJs')
    <script>

        $("#branchForm").submit(function(event){
            event.preventDefault();
            var element = $(this);
            $("button[type=submit]").prop('disabled', true);
            $.ajax({
                url: '{{ route("settings.branch") }}',
                type: 'post',
                data: element.serializeArray(),
                dataType: 'json',
                success: function(response){
                    $("button[type=submit]").prop('disabled', false);

                    if(response["status"] == true){
                        $("#name").removeClass('is-invalid').siblings("p").removeClass('invalid-feedback').html('');
                        $("#email").removeClass('is-invalid').siblings("p").removeClass('invalid-feedback').html('');

                        window.location.href="{{ route('settings.index') }}"

                    } else {
                        var errors = response['errors']
                        if(errors.name){
                        $("#name").addClass('is-invalid').siblings("p").addClass('invalid-feedback').html(errors.name);
                    } else {
                        $("#name").removeClass('is-invalid').siblings("p").removeClass('invalid-feedback').html('');
                    }
                    if(errors.email){
                        $("#email").addClass('is-invalid').siblings("p").addClass('invalid-feedback').html(errors.name);
                    } else {
                        $("#email").removeClass('is-invalid').siblings("p").removeClass('invalid-feedback').html('');
                    }
                    }

                }, error: function(jqXHR, exception) {
                    console.log("Something event wrong");
                }
            })
        });
    </script>
@endsection