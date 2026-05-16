@extends('admin.layouts.app')

@section('content')

@include('admin.layouts.message')

<div class="card">
    <div class="card-body">
        <div class="row">                
            <div class="col-md-10 col-12">
                <div class="page-title"> 
                    <h4>Category</h4>        
                    <span class="counts">{{ $totalCategories }}</span>                    
                </div>
            </div>
            <div class="col-md-2 col-12">
                <a href="javascript:0" class="btn btn-primary pull-right" data-bs-toggle="modal" data-bs-target="#addCategoryModal">Add Category</a>
            </div>
        </div>        
                
        <div class="accordion mt-2" id="categoryAccordion">
            @if ($categories->isNotEmpty())
                @foreach ($categories as $value)
                    <div class="accordion-item">                        
                        <div class="accordion-header" id="heading_{{ $value->id }}">                            
                            <button class="accordion-button p-xl-2 {{ !$loop->first ? 'collapsed' : '' }}" 
                                    type="button" 
                                    data-bs-toggle="collapse" 
                                    data-bs-target="#collapse_{{ $value->id }}" 
                                    aria-expanded="{{ $loop->first ? 'true' : 'false' }}" 
                                    aria-controls="collapse_{{ $value->id }}">

                                    <div class="container">
                                        <div class="row">
                                            <div class="col-11">
                                                <div class="flex">
                                                    @if($value->image)
                                                        <img src="{{ asset('uploads/category/'.$value->image) }}" style="height: 50px; width:50px; margin-right:10px;" class="shadow-sm rounded" />
                                                    @endif

                                                    <h5 class="mb-0 mt-2">{{ $value->name }}</h5>                                                    
                                                    <span class="counts_small mt-2">{{ $value->menus_count }}</span>  
                                                </div>
                                            </div>
                                            <div class="col-1">
                                                <div class="flex">
                                                    <a href="javascript:0" class="btn btn-outline-primary mt-1" data-bs-toggle="modal" data-bs-target="#addMenuModal_{{ $value->id }}">Add</a>
                                                    <a href="{{ route('category.delete', $value->id) }}" class="delete-icon mt-2"><span class="sprites"></span></a> 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            </button>
                        </div>
                        
                        <div id="collapse_{{ $value->id }}" 
                            class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}" 
                            aria-labelledby="heading_{{ $value->id }}" 
                            data-bs-parent="#categoryAccordion">

                            <div class="accordion-body">                                                                
                                <div class="chip-extra">
                                    @if ($value->menus->count())                                    
                                        @foreach ($value->menus as $menu)
                                            <div class="chip-unique">                                                                                                        
                                                <div>{{ $menu->name }}</div>
                                                <div class="icons">
                                                    <p>
                                                        @if($menu->veg_nonveg == 'Non-veg')
                                                            <span class="sprites nonveg-icon"></span>
                                                        @elseif($menu->veg_nonveg == 'Egg')
                                                            <span class="sprites egg-icon"></span>
                                                        @elseif($menu->veg_nonveg == 'Veg')
                                                            <span class="sprites veg-icon"></span>
                                                        @else                                                                
                                                        @endif
                                                    </p>
                                                    <p>
                                                        <a href="{{ route('menu.delete', $menu->id) }}" class="delete-icon">
                                                            <span class="sprites"></span>
                                                        </a>
                                                    </p>
                                                </div>                                                    
                                            </div>                                                
                                        @endforeach                                                                      
                                    @endif                                    
                                </div>                                
                            </div>
                        </div>
                    </div>

                    <div class="modal fade drawer right-align" id="addMenuModal_{{ $value->id }}" tabindex="-1" aria-labelledby="addMenuModalLabel" aria-hidden="true" style="display: none;">
                        <div class="modal-dialog modal-sm" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Add Menu Item</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>

                                <form action="{{ route('menu.store') }}" method="post"  enctype="multipart/form-data">                                        
                                    @csrf
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label>Item Name</label>
                                            <input type="text" name="name" id="name" class="form-control slug-source" data-target="#slug" placeholder="Enter menu name">
                                            <input type="hidden" name="slug" id="slug">                        
                                        </div>

                                        <input type="hidden" name="default_category_id" value="{{ $value->id }}">

                                        <div class="form-group">
                                            <label class="mb-2">Category</label>
                                            <div class="row">
                                                @foreach ($categories as $category)
                                                    <div class="col-md-4 mb-1">
                                                        <div class="form-check">
                                                            <input class="form-check-input category-checkbox" type="checkbox" name="categories[]" value="{{ $category->id }}" id="category{{ $category->id }}"
                                                                {{ $value->id == $category->id ? 'checked' : '' }}
                                                            >
                                                            <label class="form-check-label" for="category{{ $category->id }}">{{ $category->name }}</label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        <div class="form-group mb-0">
                                            <label for="name">Veg/Non-Veg?</label><br />
                                            <div class="btn-group mt-1" role="group" aria-label="Basic radio toggle button group">
                                                <input type="radio" class="btn-check" name="veg_nonveg" value="NA" id="btnradio1" autocomplete="off" checked>
                                                <label class="btn btn-outline-secondary" for="btnradio1">NA</label>

                                                <input type="radio" class="btn-check" name="veg_nonveg" value="Veg" id="btnradio2" autocomplete="off">
                                                <label class="btn btn-outline-secondary" for="btnradio2">Veg</label>
                                            
                                                <input type="radio" class="btn-check" name="veg_nonveg" value="Non-Veg" id="btnradio3" autocomplete="off">
                                                <label class="btn btn-outline-secondary" for="btnradio3">Non-Veg</label>
                                            
                                                <input type="radio" class="btn-check" name="veg_nonveg" value="Egg" id="btnradio4" autocomplete="off">
                                                <label class="btn btn-outline-secondary" for="btnradio4">Egg</label>
                                            </div>
                                        </div>                                       

                                        @error('name')
                                            <small class="text-danger">Please add Menu item</small>
                                        @enderror

                                        <button type="submit" class="btn btn-primary mt-2">Create Item</button>
                                    </div>
                                </form>        
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
                   
<div class="modal fade drawer right-align" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add Category</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <form action="{{ route('categories.store') }}" method="post" enctype="multipart/form-data" >
            @csrf
                <div class="modal-body">        
                    <div class="form-group">
                        <label for="name">Category Name</label>
                        <input type="text" name="name" id="name" class="form-control slug-source" placeholder="Category Name" data-target="#slug">
                        <input type="hidden" readonly name="slug" id="slug" class="form-control" >
                        <p></p>
                    </div> 

                    <div class="form-group">
                        <label for="image">Item Picture</label>
                        <input type="file" class="form-control" name="image" />
                    </div>
                        
                    <button type="submit" class="btn btn-primary mt-2">Create Category</button>            
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('customJs')
<script>    
    $(function(e){
        $("#select_all_ids").click(function(e){
            $('.checkbox_ids').prop('checked',$(this).prop('checked'));
        });

        $('#deleteAllSelectedRecord').click(function(e){
            e.preventDefault();
            var all_ids = [];
            $('input:checkbox[name=ids]:checked').each(function(){
                all_ids.push($(this).val());
            });

            $.ajax({
                url: "{{ route('menuall.delete') }}",
                type: 'delete',
                data:{
                    ids:all_ids,                    
                    _token:'{{ csrf_token() }}'
                },
                success:function(response){
                    $.each(all_ids,function(key,val){
                        $('#menu_ids_'+val).remove();
                    });
                }
            });
        });
    });

    $('#item_name').change(function(){
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
                    $("#item_slug").val(response["slug"]);
                }
            }
        });
    })   

    //DELETE
    function deleteCategory(id){
        var url = '{{ route("categories.delete","ID") }}'
        var newUrl = url.replace("ID",id)

        if(confirm("Are you sure you want to delete?")){
            $.ajax({
                url: newUrl,
                type: 'delete',
                data: {},
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response){
                    window.location.href="{{ route('categories.index') }}"
                }
            });
        }
    }
    </script>
@endsection
