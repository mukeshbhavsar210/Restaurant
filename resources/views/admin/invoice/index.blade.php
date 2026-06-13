@extends('admin.layouts.app')

@section('content')

@include('admin.layouts.message')

<div class="card">               
    <div class="card-body mobile-padd">
        @php
            $tabs = [                
                [
                    'id' => 'tabs-1',
                    'title' => 'Ground Floor',
                    'active' => true,
                ],
                [
                    'id' => 'tabs-2',
                    'title' => 'Basement',
                ],
                [
                    'id' => 'tabs-3',
                    'title' => 'Pary Hall',
                ],                
            ];
        @endphp

        <ul class="nav nav-tabs" role="tablist">
            @foreach($tabs as $tab)
                <li class="nav-item" role="presentation">
                    <a class="nav-link pt-0 {{ !empty($tab['active']) ? 'active' : '' }}"
                        data-bs-toggle="tab" href="#{{ $tab['id'] }}" role="tab" aria-selected="{{ !empty($tab['active']) ? 'true' : 'false' }}" >
                        {{ $tab['title'] }}
                    </a>
                </li>
            @endforeach
        </ul>  
                                    
        <div class="tab-content mt-1 mt-md-3">
            <div class="tab-pane active" id="tabs-1" role="tabpanel">
                <div class="row mt-0 mt-md-2">
                    <div class="col-md-7 col-5">
                        <div class="page-title">
                            <h4>{{ $outlets->firstWhere('view', 1)?->area_name }}</h4> 
                            <span class="counts">{{ $outlets->firstWhere('view', 1)?->total_seats }}</span>
                        </div>
                    </div>
                    
                    <div class="col-md-5 col-7">
                        <div class="flex float-end">
                            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#{{ $branchForm['modal_id'] }}">{{ $branchForm['title'] }}</button>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#{{ $tableForm['modal_id'] }}">{{ $tableForm['title'] }}</button>

                            <form method="POST" action="{{ route('invoice.branch.store') }}" id="outletForm">
                                @csrf

                                <select name="outlet_id" class="form-select" id="outletSelect" required>
                                    <option value="">Select Outlet</option>
                                    @foreach($outlets as $outlet)
                                        <option value="{{ $outlet->id }}"
                                            {{ $selectedOutletId == $outlet->id ? 'selected' : '' }}>
                                            {{ $outlet->area_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                    </div>            
                </div>     

                @if($activeArea)                    
                    @foreach($tableTypes as $value)
                        <h5>{{ $value->name }}</h5>
                        @if($value->seats->count())                                
                            <div class="flex-2 mt-2 mb-2">
                                @foreach($value->seats as $seat)                                
                                <div class="kot-card 
                                            {{ $seat->status == 'running' ? 'running' : '' }}
                                            {{ $seat->status == 'available' ? 'available' : '' }}
                                            {{ $seat->status == 'printed' ? 'printed' : '' }}
                                            {{ $seat->status == 'kot-running' ? 'kot-running' : '' }}">
                                        <a href="{{ route('invoice.pos.order', $seat->id) }}" class="text-decoration-none">
                                            AC {{ $seat->table }}
                                            <div class="dropdown">
                                                <button class="btn btn-sm p-0 border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="bi bi-three-dots-vertical">Dot</i>
                                                </button>

                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <div class="qr-code">                                                              
                                                            @php
                                                                $qrUrl = url('/table/'.$value->area_slug.'/'.$seat->table);
                                                            @endphp

                                                            {!! DNS2D::getBarcodeHTML($qrUrl, 'QRCODE', 6.5, 6.5) !!}                                                
                                                        </div>  
                                                    </li>
                                                    <li>
                                                        <form class="dineineStatus mt-1" data-id="{{ $seat->id }}">
                                                            @csrf
                                                            <div class="form-check form-switch">
                                                                <input type="checkbox" class="form-check-input status-switch" {{ $seat->status == 'running' ? 'checked' : '' }} >                                                                            
                                                            </div>
                                                        </form>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('qr.table', $seat->id) }}" class="download-icon mt-1">
                                                            <span class="sprites"></span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:void(0)" class="btn btn-outline-danger btn-sm commonDeleteBtn"
                                                            data-bs-toggle="modal" data-bs-target="#commonDeleteModal"
                                                            data-url="{{ route('delete.table', $seat->id) }}" data-title="{{ $seat->table }}">
                                                            Delete
                                                        </a> 
                                                    </li>
                                                </ul>
                                            </div>
                                        </a>
                                    </div>                                    
                            @endforeach
                            </div>
                        @else
                            <p>No seats found.</p>
                        @endif
                    @endforeach                    
                @endif                           
            </div>    
        </div>           
    </div>
</div>

@include('components.common-modal', [
    'modal' => $branchForm,
])

@include('components.common-modal', [
    'modal' => $tableForm,
])
@endsection
        
@section('customJs')
<script type="text/javascript">  
    $(document).on("click", ".user_dialog", function () {
        alert("H");
        var UserName = $(this).data('id');
        $(".modal-body #user_name").val( UserName );
    });      

    $(document).ready(function () {
        $('.green').addClass('blink');
    });

    $('.accordion-button').on('click', function () {
        let id = $(this).data('id');
        let url = new URL(window.location.href);

        url.searchParams.set('open', id);
        window.history.replaceState({}, '', url);
    });    


    
</script>
@endsection