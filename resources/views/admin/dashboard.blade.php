@extends('admin.layouts.app')

@section('content')

<div class="container-xxl">                    
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-3">
            <div class="card">
                <div class="card-body">                    
                    <img src="{{ asset('admin-assets/images/gdrive.png') }}" class="me-2 align-self-center thumb-xl" alt="..." />
                    
                    <a class="dropdown-item" href="{{ route('orders.index') }}" >
                        <h5 class="fw-semibold mt-2 fs-18">Orders</h5>
                    </a>
                    <div class="d-flex justify-content-between my-2">
                        <p class="text-muted mb-0 fs-13 fw-semibold"><span class="text-dark">{{ $orders_count }}</span> Orders</p>
                        <p class="text-muted mb-0 fs-13 fw-semibold"><span class="text-dark">₹{{ $total_sale }} </span></p>
                    </div>
                    {{-- <div class="d-flex align-items-center">
                        <div class="flex-grow-1 text-truncate"> 
                            <div class="d-flex align-items-center">
                                <div class="progress bg-secondary-subtle w-100" style="height:5px;" role="progressbar" aria-label="Success example" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                    <div class="progress-bar bg-secondary" style="width: 38%"></div>
                                </div> 
                                <small class="flex-shrink-1 ms-1">38%</small>
                            </div>                                                                                    
                        </div>
                    </div> --}}
                </div> 
            </div> 
        </div>  
        <div class="col-md-6 col-lg-3">
            <div class="card">
                <div class="card-body">                       
                    <img src="{{ asset('admin-assets/images/dropbox.png') }}" class="me-2 align-self-center thumb-xl" alt="..." />

                    <a class="dropdown-item" href="{{ route('categories.index') }}">
                        <h5 class="fw-semibold mt-2 fs-18">Categories</h5>
                    </a>
                    <div class="d-flex justify-content-between my-2">
                        <p class="text-muted mb-0 fs-13 fw-semibold"><span class="text-dark">{{ $total_categories }}</span> Categories</p>
                        {{-- <p class="text-muted mb-0 fs-13 fw-semibold"><span class="text-dark">500 </span>GB</p> --}}
                    </div>
                </div> 
            </div> 
        </div> 
        <div class="col-md-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <img src="{{ asset('admin-assets/images/onedrive.png') }}" class="me-2 align-self-center thumb-xl" alt="..." />                    
                    <a class="dropdown-item" href="{{ route('products.index') }}">
                        <h5 class="fw-semibold mt-2 fs-18">Products</h5>
                    </a>
                    <div class="d-flex justify-content-between my-2">
                        <p class="text-muted mb-0 fs-13 fw-semibold"><span class="text-dark">{{ $total_items }} </span>Products</p>                        
                    </div>
                </div> 
            </div> 
        </div> 
        <div class="col-md-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="dropdown float-end">
                        <a href="#" class="text-muted fs-16 dropdown-toggle p-1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa-solid fa-ellipsis-vertical"></i>
                        </a>                        
                    </div>   

                    <img src="{{ asset('admin-assets/images/server.png') }}" class="me-2 align-self-center thumb-xl" alt="..." />
                    
                    <a class="dropdown-item" href="{{ route('menu.index') }}">
                        <h5 class="fw-semibold mt-2 fs-18">Menu items</h5>
                    </a>
                    <div class="d-flex justify-content-between my-2">
                        <p class="text-muted mb-0 fs-13 fw-semibold"><span class="text-dark">{{ $total_menu }} </span>Items</p>                        
                    </div>
                </div> 
            </div> 
        </div>                                                                               
    </div>
                
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link fw-semibold active py-2" data-bs-toggle="tab" href="#documents" role="tab" aria-selected="true">Documents</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link fw-semibold py-2" data-bs-toggle="tab" href="#images" role="tab" aria-selected="false" tabindex="-1">Images</a>
        </li> 
    </ul>            
    
    <div class="card">              
        <div class="card-body">                    
            <div class="tab-content">
                <div class="tab-pane active" id="documents" role="tabpanel">
                    <div class="table-responsive browser_users">
                        <table class="table mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-top-0">Name</th>
                                    <th class="border-top-0 text-end">Last Modified</th>
                                    <th class="border-top-0 text-end">Size</th>
                                    <th class="border-top-0 text-end">Members</th>
                                    <th class="border-top-0 text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>                                                        
                                    <td>
                                        <div class="d-inline-flex justify-content-center align-items-center thumb-md bg-blue-subtle rounded mx-auto me-1">
                                            <i class="fa-solid fa-file-pdf fs-18 align-self-center mb-0 text-blue"></i>
                                        </div>
                                        <a href="#" class="text-body">payment.pdf</a>
                                    </td>
                                    <td class="text-end">18 Jul 2024</td>                                   
                                    <td class="text-end"> 2.3 MB</td>
                                    <td class="text-end">
                                        <div class="img-group d-flex justify-content-end">
                                            <a class="user-avatar position-relative d-inline-block" href="#">
                                                <img src="assets/images/users/avatar-2.jpg" alt="avatar" class="thumb-md shadow-sm rounded-circle">
                                            </a>
                                            <a class="user-avatar position-relative d-inline-block ms-n2" href="#">
                                                <img src="assets/images/users/avatar-5.jpg" alt="avatar" class="thumb-md shadow-sm rounded-circle">
                                            </a>
                                            <a class="user-avatar position-relative d-inline-block ms-n2" href="#">
                                                <img src="assets/images/users/avatar-3.jpg" alt="avatar" class="thumb-md shadow-sm rounded-circle">
                                            </a>                 
                                        </div>
                                    </td>
                                    <td class="text-end">   
                                        <a href="#"><i class="las la-download text-secondary fs-18"></i></a>                                                    
                                        <a href="#"><i class="las la-pen text-secondary fs-18"></i></a>
                                        <a href="#"><i class="las la-trash-alt text-secondary fs-18"></i></a>
                                    </td>
                                </tr>  
                            </tbody>
                        </table>                                                
                    </div> 
                </div>
                <div class="tab-pane" id="images" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-top-0">Name</th>
                                    <th class="border-top-0 text-end">Last Modified</th>
                                    <th class="border-top-0 text-end">Size</th>
                                    <th class="border-top-0 text-end">Members</th>
                                    <th class="border-top-0 text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>                                                        
                                    <td>
                                        <div class="d-inline-flex justify-content-center align-items-center thumb-md bg-danger-subtle rounded mx-auto me-1">
                                            <i class="fa-solid fa-image fs-18 align-self-center mb-0 text-danger"></i>
                                        </div>
                                        <a href="#" class="text-body">img52315.jpeg</a>
                                    </td>
                                    <td class="text-end">18 Jul 2024</td>                                   
                                    <td class="text-end"> 2.3 MB</td>
                                    <td class="text-end">
                                        <div class="img-group d-flex justify-content-end">
                                            <a class="user-avatar position-relative d-inline-block" href="#">
                                                <img src="assets/images/users/avatar-2.jpg" alt="avatar" class="thumb-md shadow-sm rounded-circle">
                                            </a>
                                            <a class="user-avatar position-relative d-inline-block ms-n2" href="#">
                                                <img src="assets/images/users/avatar-5.jpg" alt="avatar" class="thumb-md shadow-sm rounded-circle">
                                            </a>
                                            <a class="user-avatar position-relative d-inline-block ms-n2" href="#">
                                                <img src="assets/images/users/avatar-3.jpg" alt="avatar" class="thumb-md shadow-sm rounded-circle">
                                            </a>                 
                                        </div>
                                    </td>
                                    <td class="text-end">   
                                        <a href="#"><i class="las la-download text-secondary fs-18"></i></a>                                                    
                                        <a href="#"><i class="las la-pen text-secondary fs-18"></i></a>
                                        <a href="#"><i class="las la-trash-alt text-secondary fs-18"></i></a>
                                    </td>
                                </tr>  
                                                                                                                    
                            </tbody>
                        </table>                                                
                    </div> 
                </div>                                                
                
            </div>
        </div> 
    </div>                             

    <footer class="footer text-center text-sm-start d-print-none">
        <div class="container-xxl">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-0 rounded-bottom-0">
                        <div class="card-body">
                            <p class="text-muted mb-0">
                                ©
                                <script> document.write(new Date().getFullYear()) </script>2026
                                Rizz
                                <span class="text-muted d-none d-sm-inline-block float-end">
                                    Crafted with
                                    <i class="iconoir-heart text-danger"></i>
                                    by Mannatthemes</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</div>        
@endsection

@section('customJs')
    <script>
            console.log("Hello")
    </script>
@endsection
