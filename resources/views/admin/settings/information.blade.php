<div class="row">
    <div class="col-md-7">
        <h4>Information</h4>
        <form action="" method="post" id="changeWebsite" name="changeWebsite">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="restaurant_name" class="mb-2">Restaurant Name*</label>
                        <input type="text" id="restaurant_name" name="restaurant_name" placeholder="Restaurant Name" class="form-control" value="{{ $user->restaurant_name }}">
                        <p></p>
                    </div>
                </div>                            
                 <div class="col-md-6">
                    <div class="form-group">
                        <label for="restaurant_email" class="mb-2">Email*</label>
                        <input type="restaurant_email" id="restaurant_email" name="restaurant_email" placeholder="Enter Email" class="form-control" value="{{ $user->restaurant_email }}">
                        <p></p>
                    </div>                        
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="restaurant_phone" class="mb-2">Phone*</label>
                        <input type="text" id="restaurant_phone" name="restaurant_phone" placeholder="restaurant_phone" class="form-control" value="{{ $user->restaurant_phone }}">
                        <p></p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="restaurant_phone" class="mb-2">Phone*</label>
                        <input type="text" id="restaurant_phone" name="restaurant_phone" placeholder="restaurant_phone" class="form-control" value="{{ $user->restaurant_phone }}">
                        <p></p>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="restaurant_address" class="mb-2">Address*</label>
                        <textarea type="text" id="restaurant_address" rows="4" cols="50" name="restaurant_address" placeholder="restaurant_address" class="form-control" value="">{{ $user->restaurant_address }}</textarea>
                        <p></p>
                    </div>
                </div>
                
            </div>        
            <div class="pb-5 pt-3">
                <button type="submit" class="btn btn-primary">Update details</button>
            </div>
        </form>
    </div>
    <div class="col-md-1"></div>
    <div class="col-md-4">
        <h4>Logo</h4>
        <form id="updateLogo" name="updateLogo" action="" method="post" class="my-3">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="" class="mb-2">Logo*</label>
                    <input type="file" class="form-control" id="image" name="image">
                    <p class="text-danger" id="image-error"></p>
                </div>   
            </div> 
            <button type="submit" class="btn btn-primary">Change Logo</button>                                                    
        </form> 
    </div>
</div>





@section('customJs')
    <script>

        $("#changeWebsite").submit(function(event){
            event.preventDefault();
            var element = $(this);
            $("button[type=submit]").prop('disabled', true);
            $.ajax({
                url: '{{ route("settings.websiteInformation") }}',
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

        $("#updateLogo").submit(function(event){
            event.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: '{{ route("website.logo") }}',
                type: 'post',
                data: formData,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function(response){
                    if(response.status == false){
                        var errors = response.errors;
                        if(errors.image){
                            $("#image-error").html(errors.name);
                        }
                    } else {
                        window.location.href='{{ url()->current() }}'
                    }
                },
                error: function(JQXHR, exception){
                    console.log("Something went wrong");
                }
            })
        });



        Dropzone.autoDiscover = false;
            const dropzone = $("#image").dropzone({
                init: function() {
                    this.on('addedfile', function(file) {
                        if (this.files.length > 1) {
                            this.removeFile(this.files[0]);
                        }
                    });
                },
                url:  "{{ route('temp-images.create') }}",
                maxFiles: 1,
                paramName: 'image',
                addRemoveLinks: true,
                acceptedFiles: "image/jpeg,image/png,image/gif",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }, success: function(file, response){
                    $("#image_id").val(response.image_id);
                    //console.log(response)
                }
            });
    </script>
@endsection
