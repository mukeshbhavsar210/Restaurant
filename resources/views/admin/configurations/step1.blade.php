<div class="card">
    <div class="card-body">
        @if ($configurations->count())
            <div class="row">
                <div class="col-md-2">
                    <img style="width:100%;" src="{{ asset('uploads/logo/'.$configurations->pluck('image')->implode('')) }}" />
                </div>
                <div class="col-md-6">
                    <h4>{{ $configurations->pluck('name')->implode('') }}</h4>
                    <p>Address: {{ $configurations->pluck('address')->implode('') }}<br />
                    Email: {{ $configurations->pluck('email')->implode('') }}<br />
                    Mobile: {{ $configurations->pluck('phone')->implode('') }}</p>
                    <a href="{{ route('configurations.edit', $configurations->pluck('id')->implode('') ) }}" class="btn btn-primary">Edit</a>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h3>Theme</h3>
                                <form action="{{ route('configurations.theme') }}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">                                
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Primary</label>
                                                <input name="primary_color" type="color" class="form-control" value="{{ old('primary_color') }}" />
                                                <div class="theme-primary" style="background-color: {{ $theme->pluck('primary_color')->implode('') }}"></div>
                                                @error('primary_color')
                                                    <p class="text-red-400 font-small">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Secondary</label>
                                                <input name="secondary_color" type="color" class="form-control" value="{{ old('secondary_color') }}" />
                                                <div class="theme-primary" style="background-color: {{ $theme->pluck('secondary_color')->implode('') }}"></div>
                                                @error('secondary_color')
                                                    <p class="text-red-400 font-small">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Sidebar</label>
                                                <input name="sidebar_color" type="color" class="form-control" value="{{ old('sidebar_color') }}" />
                                                <div class="theme-primary" style="background-color: {{ $theme->pluck('sidebar_color')->implode('') }}"></div>
                                                @error('sidebar_color')
                                                    <p class="text-red-400 font-small">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                <button class="btn btn-primary">Submit</button>
                            </form>
                            
                        </div>
                    </div>
                </div>
            </div>
            
            @else
            <form action="{{ route('configurations.store') }}" method="post" enctype="multipart/form-data" >
                @csrf
                    <div class="row">   
                        <div class="col-md-8">
                            <div class="row">   
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input name="name" type="text" class="form-control" placeholder="Restaurant Name" value="{{ old('name') }}" />
                                        @error('name')
                                            <p class="alert alert-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="image">Logo</label>
                                        <input type="file" class="form-control" name="image" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input name="email" placeholder="email" type="email" class="form-control" value="{{ old('email') }}" />
                                        @error('email')
                                            <p class="text-red-400 font-small">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Phone</label>
                                        <input name="phone" placeholder="Phone" type="text" class="form-control" value="{{ old('phone') }}" />
                                        @error('phone')
                                            <p class="text-red-400 font-small">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Address</label>
                                        <textarea name="address" placeholder="Restaurant address" type="text" cols="3" rows="4" class="form-control">{{ old('address') }}</textarea>
                                        @error('address')
                                            <p class="text-red-400 font-small">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-primary">Submit</button>
                        </form>
                        </div>
                        <div class="col-md-4">
                            
                </div>                         
            </div>
        @endif                   
    </div>
</div>