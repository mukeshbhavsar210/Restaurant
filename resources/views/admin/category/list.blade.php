@extends('admin.layouts.app')

@section('content')

@include('admin.layouts.message')

@include('components.common-modal', [
    'modal' => $categoryForm,
])

@include('components.common-modal', [
    'modal' => $menuForm,
])

<div class="card">
    <div class="card-body">
        <div class="row">                
            <div class="col-md-9 col-12">
                <div class="page-title"> 
                    <h4>Category</h4>        
                    <span class="counts">{{ $totalCategories }}</span>                    
                </div>
            </div>
            <div class="col-md-3 col-12">
                <div class="flex pull-right">
                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#{{ $categoryForm['modal_id'] }}">{{ $categoryForm['button_name'] }}</button>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#{{ $menuForm['modal_id'] }}">{{ $menuForm['button_name'] }}</button>
                </div>
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
                                    
                                    <div class="flex">
                                        <div class="thumb-category">
                                            @if($value->image)
                                                <img src="{{ asset('uploads/category/'.$value->image) }}" style="height: 50px; width:50px; margin-right:10px;" class="shadow-sm rounded" />
                                            @endif
                                            <p class="count-category">
                                                {{ $value->menus_count }}
                                            </p>
                                        </div>
                                        <h5 class="mb-0 mt-2">{{ $value->name }}</h5>
                                    </div>                                    
                            </button>
                        </div>
                        
                        <div id="collapse_{{ $value->id }}" 
                            class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}" 
                            aria-labelledby="heading_{{ $value->id }}" 
                            data-bs-parent="#categoryAccordion">

                            <div class="accordion-body">                                 
                                <div class="row">
                                    <div class="col-12">
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

                                            <a href="javascript:void(0)" class="btn btn-outline-danger commonDeleteBtn"
                                                data-bs-toggle="modal" data-bs-target="#commonDeleteModal"
                                                data-url="{{ route('category.delete', $value->id) }}" data-title="{{ $value->name }}">
                                                Delete {{ $value->name }}
                                            </a>                               
                                        </div>
                                    </div>                                    
                                </div>
                            </div>
                        </div>
                    </div>                    
                @endforeach
            @endif
        </div>
    </div>
</div>
@endsection

@section('customJs')
<script>    
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
