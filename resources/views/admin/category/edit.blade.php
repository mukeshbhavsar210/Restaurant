@extends('admin.layouts.app')

@section('content')
<section class="content-header">
        <div class="row">
            <div class="col-sm-6">
                <h1>Edit Menu</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('categories.index') }}" class="btn btn-primary">Back</a>
            </div>
        </div>
</section>
<section>
    @include('admin.layouts.message')
    <form action="{{ route('menu.update',$menu->id) }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="card mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 col-12">
                        <div class="form-group">
                            <label for="category">Choose Menu</label>
                            <select name="category" id="category" class="form-control">
                            <option value="">Select a category</option>
                                @if ($categories->isNotEmpty())
                                    @foreach ($categories as $value)
                                        <option {{ ($menu->category_id == $value->id) ? 'selected' : '' }} value="{{ $value->id }}">{{ $value->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <p class="error"></p>
                        </div>
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="form-group mb-0">
                            <label for="name">Menu Name</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Name" value="{{ $menu->name }}">
                            <input type="hidden" name="slug" id="slug" class="form-control" placeholder="slug">                                
                            <p class="error"></p>                                
                        </div>
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="form-group">                                                                
                            <label for="image">Picture</label>
                            <input type="file" class="form-control" name="image" />
                        </div> 
                    </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('categories.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </div>
        </div>
    </form>
</section>
@endsection

@section('customJs')
<script>
    $('#name').change(function(){
        element = $(this);
        $("button[type=submit]").prop('disabled', true);
        $.ajax({
            url: '{{ route("getSlug") }}',
            type: 'get',
            data: {title: element.val()},
            dataType: 'json',
            success: function(response){
                $("button[type=submit]").prop('disabled', false);
                if(response["status"] == true){
                    $("#slug").val(response["slug"]);
                }
            }
        });
    })
    

    $("#category").change(function(){
        var category_id = $(this).val();
        $.ajax({
            url: '{{ route("product-subcategories.index") }}',
            type: 'get',
            data: {category_id:category_id},
            dataType: 'json',
            success: function(response) {
                $("#sub_category").find("option").not(":first").remove();
                $.each(response["subCategories"],function(key,item){
                    $("#sub_category").append(`<option value='${item.id}' >${item.name}</option>`)
                })
            },
            error: function(){
                console.log("Something went wrong")
            }
        });
    })

   
</script>
@endsection