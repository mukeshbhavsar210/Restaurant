<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Laravel Shop :: Administrative Panel</title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
<link rel="stylesheet" href="{{ asset('admin-assets/plugins/fontawesome-free/css/all.min.css') }}">

<link href="{{ asset('admin-assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('admin-assets/css/icons.min.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('admin-assets/css/app.min.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('admin-assets/css/custom.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('admin-assets/plugins/summernote/summernote-bs4.min.css') }}" rel="stylesheet" type="text/css" >
<link href="{{ asset('admin-assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" >
<meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body data-sidebar-size="collapsed">
<div class="topbar d-print-none">
    <div class="container-xxl">
        <nav class="topbar-custom d-flex justify-content-between nav-sticky" id="topbar-custom">    
            <ul class="topbar-item list-unstyled d-inline-flex align-items-center">                        
                <li>
                    <button class="nav-link mobile-menu-btn nav-icon" id="togglemenu">
                        <i class="iconoir-menu-scale"></i>
                    </button>
                </li> 
                <li class="mx-1 welcome-text">
                    <h3 class="mb-0 fw-bold text-truncate">Good Morning, Admin!</h3>
                    <h6 class="mb-0 fw-normal text-muted text-truncate fs-14">Here's your overview this week.</h6>
                </li>                   
            </ul>
            <ul class="topbar-item list-unstyled d-inline-flex align-items-center mb-0">
                <li class="hide-phone app-search">
                    <form role="search" action="#" method="get">
                        <input type="search" name="search" class="form-control top-search mb-0" placeholder="Search here...">
                        <button type="submit"><i class="iconoir-search"></i></button>
                    </form>
                </li>                             
                <li class="topbar-item">
                    <a class="nav-link nav-icon" href="javascript:void(0);" id="light-dark-mode">
                        <i class="icofont-moon dark-mode"></i>
                        <i class="icofont-sun light-mode"></i>
                    </a>                    
                </li>
                <li class="dropdown topbar-item">
                    <a class="nav-link dropdown-toggle arrow-none nav-icon" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        <img src="{{ asset('admin-assets/images/avatar-1.jpg') }}" alt="" class="thumb-lg rounded-circle">
                    </a>
                    <div class="dropdown-menu dropdown-menu-end py-0" style="">
                        <div class="d-flex align-items-center dropdown-item py-2 bg-secondary-subtle">                                    
                            <div class="flex-grow-1 text-truncate align-self-center">
                                <h6 class="my-0 fw-medium text-dark fs-13"></h6>
                                <small class="text-muted"></small>
                            </div>
                        </div>

                        {{ Auth::guard()->user()->email }}
						<p>{{ Auth::user()->roles()->pluck('name')->implode(', ') }}</p>
                        
                        <a class="dropdown-item" href="">
                            <i class="las la-user fs-18 me-1 align-text-bottom"></i>
                            Change Password
                        </a>
                        
                        <a href="{{ route('profile.edit') }}" class="dropdown-item">
							<i class="las la-user fs-18 me-1 align-text-bottom"></i>
							Profile
						</a>

                        <form method="POST" action="{{ route('logout') }}" >
							@csrf
                                <a href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                    this.closest('form').submit();" class="dropdown-item text-danger">
                                <i class="las la-power-off fs-18 me-1 align-text-bottom"></i>
                                {{ __('Log Out') }}
						    </a>
						</form>
                    </div>
                </li>
            </ul>
        </nav>
    </div>
</div>

<div class="startbar d-print-none">
    <div class="brand">
        <a href="" class="logo" title=""><span><img class="logo-sm" src="{{ asset('front-assets/images/logo.png') }}" alt="" /></span></a>
    </div>
    <div class="startbar-menu">
        <div class="startbar-collapse simplebar-scrollable-y" id="startbarCollapse" data-simplebar="init">
            <div class="simplebar-wrapper" style="margin: 0px -16px -16px;">
                <div class="simplebar-height-auto-observer-wrapper">
                    <div class="simplebar-height-auto-observer"></div>
                </div>
                <div class="simplebar-mask">
                    <div class="simplebar-offset" style="right: 0px; bottom: 0px;">
                        <div class="simplebar-content-wrapper" tabindex="0" role="region" aria-label="scrollable content" style="height: 100%; overflow: hidden scroll;">
                            <div class="simplebar-content" style="padding: 0px 16px 16px;">
                                <div class="d-flex align-items-start flex-column w-100">
                                    @include('admin/layouts/sidebar')                                            
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="simplebar-placeholder" style="width: 70px; height: 657px;"></div>
            </div>
            <div class="simplebar-track simplebar-horizontal" style="visibility: hidden;">
                <div class="simplebar-scrollbar" style="width: 0px; transform: translate3d(0px, 0px, 0px); display: none;"></div>
            </div>
            <div class="simplebar-track simplebar-vertical" style="visibility: visible;">
                <div class="simplebar-scrollbar" style="height: 413px; transform: translate3d(0px, 0px, 0px); display: block;"></div>
            </div>
        </div>
    </div>
</div>

<div class="startbar-overlay d-print-none"></div>

<div class="page-wrapper">
    <div class="page-content">
        <div class="container-xxl">
            @yield('content')
        </div>                
        
        <footer class="footer text-center text-sm-start d-print-none">
            <div class="container-xxl">
                <div class="row">
                    <div class="col-12">
                        <div class="card mb-0 rounded-bottom-0">
                            <div class="card-body">
                                <p class="text-muted mb-0"> © <script> document.write(new Date().getFullYear()) </script> Online Shopping </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</div>	

<script src="{{ asset('admin-assets/js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('admin-assets/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('admin-assets/js/simplebar.min.js') }}"></script>
<script src="{{ asset('admin-assets/js/app.js') }}"></script>
<script src="{{ asset('admin-assets/js/admin_documentReady.js') }}"></script>
<script src="{{ asset('admin-assets/plugins/dropzone/min/dropzone.min.js') }}"></script>
<script src="{{ asset('admin-assets/plugins/summernote/summernote-bs4.min.js') }}"></script>
<script src="{{ asset('admin-assets/plugins/select2/js/select2.min.js') }}"></script>
<script src="{{ asset('admin-assets/js/datetimepicker.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script type="text/javascript">
$(document).ready(function(){
        $("#variant_checkbox").change(function(){
            if($(this).is(":checked")){
                $("#variant-wrapper").show();
                $("#add-variant").show();
                $("#original-price").hide();
            } else {
                $("#variant-wrapper").hide();
                $("#add-variant").hide();
                $("#original-price").show();
            }
        });

        let variantIndex = 1;
            $("#add-variant").click(function(){
                let html = `
                    <div class="row mb-1 variant-row">
                        <div class="col-6">
                            <select name="variants[${variantIndex}][name]" class="form-select">
                                <option value="">Select Variant</option>
                                <option value="Oil">Oil</option>
                                <option value="Butter">Butter</option>
                            </select>
                        </div>

                        <div class="col-6">
                            <div class="flex">
                                <input type="text" name="variants[${variantIndex}][price]" class="form-control" placeholder="Price">
                                <a href="javascript:0" class="remove-variant mt-1 delete-icon">
                                    <span class="sprites"></span>
                                </a>                            
                            </div>
                        </div>
                    </div>
                `;
                $("#variant-container").append(html);
                variantIndex++;
            });

            $(document).on("click", ".remove-variant", function(){
                $(this).closest(".variant-row").remove();
            });
        });
        
    $(document).ready(function () {            
        // Toggle dropdown manually
        $('#dropdownMenuBtn').click(function (e) {
            e.stopPropagation();
            $('#dropdownMenu').toggleClass('show');
        });

        // Close dropdown when clicking outside
        $(document).click(function () {
            $('#dropdownMenu').removeClass('show');
        });

        // Open modal on click
        $('.open-modal').click(function (e) {
            e.preventDefault();
            let targetModal = $(this).data('target');
            // Close dropdown
            $('#dropdownMenu').removeClass('show');
            $(targetModal).modal('show');
        });
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function(){        
        $(".summernote").summernote({
            //height:250;
        });
    });

     $(document).on('input', '.slug-source', function () {
        let element = $(this);
        let form = element.closest('form');
        let target = element.data('target');
        let submitBtn = form.find("button[type=submit]");

        submitBtn.prop('disabled', true);

        $.ajax({
            url: '{{ route("getSlug") }}',
            type: 'GET',
            data: { title: element.val() },
            dataType: 'json',
            success: function (response) {

                submitBtn.prop('disabled', false);

                if (response.status) {
                    form.find(target).val(response.slug);
                }
            }
        });
    });


    function deletePage(id){        
        var url = '{{ route("pages.delete","ID") }}'
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
                    if(response["status"]){
                        window.location.href="{{ route('pages.index') }}"
                    }
                }
            });
        }
    }    

    //Alert timeout
    setTimeout(function () {
        $('.alert').fadeOut(300);
    }, 1500);

    window.addEventListener("scroll", function() {
        let header = document.getElementById("adminHeader");
        if (window.scrollY > 100) {
            header.classList.add("sticky-header");
        } else {
            header.classList.remove("sticky-header");
        }
    });

    $(document).on('input', '.slug-source', function () {
        let element = $(this);
        let form = element.closest('form');
        let target = element.data('target');
        let submitBtn = form.find("button[type=submit]");

        submitBtn.prop('disabled', true);

        $.ajax({
            url: '{{ route("getSlug") }}',
            type: 'GET',
            data: { title: element.val() },
            dataType: 'json',
            success: function (response) {

                submitBtn.prop('disabled', false);

                if (response.status === true) {
                    form.find(target).val(response.slug); // ✅ use slug
                }
            }
        });
    });
</script>

@yield('customJs')

</body>
</html>