<div class="modal fade {{ $modal['formConfig']['modal'] }}" id="{{ $modal['modal_id'] }}" tabindex="-1">
    <div class="modal-dialog {{ $modal['formConfig']['modalSize'] }}" {{ $modal['formConfig']['modal_size'] ?? '' }}">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $modal['title'] }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ $modal['formConfig']['action'] }}" method="{{ $modal['formConfig']['method'] == 'GET' ? 'GET' : 'POST' }}">
                @csrf

                @if($modal['formConfig']['method'] != 'POST')
                    @method($modal['formConfig']['method'])
                @endif

                <div class="modal-body">
                    <div class="row">
                        @foreach($modal['formConfig']['fields'] as $field)
                            <div class="{{ $field['col'] ?? 'col-md-12' }} mb-3">
                                @if(($field['type'] ?? '') != 'hidden')
                                    <label class="form-label">
                                        {{ $field['label'] ?? '' }}
                                    </label>
                                @endif
                                
                                @if($field['type'] == 'text') 
                                    <input type="{{ $field['type'] }}" name="{{ $field['name'] }}"
                                            id="{{ $field['id'] ?? '' }}" value="{{ $field['value'] ?? '' }}"
                                            placeholder="{{ $field['placeholder'] ?? '' }}" class="form-control {{ $field['class'] ?? '' }}"    

                                            @if(isset($field['data']))
                                                @foreach($field['data'] as $key => $value)
                                                    data-{{ $key }}="{{ $value }}"
                                                @endforeach
                                            @endif
                                    >

                                @elseif($field['type'] == 'radio')                                   
                                    <div class="row">
                                        @foreach($field['options'] as $option)
                                            <div class="col-6">
                                                <label class="custom-radio mt-1">
                                                    <input type="radio" name="{{ $field['name'] }}" value="{{ $option }}">
                                                    <span class="radio-mark"></span>
                                                    {{ $option }}
                                                    {{ $option == 1 ? 'Seat' : 'Seats' }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>

                                @elseif($field['type'] == 'checkbox')
                                    <div class="row">
                                        @foreach($field['options'] as $option)
                                            <div class="col-6">
                                                <label class="custom-checkbox mt-1" for="permission_{{ $option->{$field['option_value']} }}">
                                                    {{ $option->{$field['option_text']} }}
                                                    <input type="checkbox" id="permission_{{ $option->{$field['option_value']} }}" name="{{ $field['name'] }}[]" value="{{ $option->{$field['option_value']} }}">
                                                    <span class="checkmark"></span>                                                    
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>

                                @elseif($field['type'] == 'email')                                                                                    
                                    <input type="{{ $field['type'] }}" id="{{ $field['name'] }}" name="{{ $field['name'] }}" class="form-control" placeholder="{{ $field['placeholder'] ?? '' }}">                                            

                                @elseif($field['type'] == 'textarea')
                                    <textarea name="{{ $field['name'] }}" class="form-control {{ $field['summer_class'] }}" rows="4"></textarea>                                                                                
                                    
                                @elseif($field['type'] == 'color')                                        
                                    <input type="{{ $field['type'] }}" id="{{ $field['name'] }}" name="{{ $field['name'] }}" class="form-control" placeholder="{{ $field['placeholder'] ?? '' }}">                                            

                                @elseif($field['type'] == 'date')                                        
                                    <input type="{{ $field['type'] }}" id="{{ $field['name'] }}" name="{{ $field['name'] }}" class="form-control" placeholder="{{ $field['placeholder'] ?? '' }}">                                            

                                @elseif($field['type'] == 'file')                                        
                                    <input type="{{ $field['type'] }}" id="{{ $field['name'] }}" name="{{ $field['name'] }}" class="form-control" placeholder="{{ $field['placeholder'] ?? '' }}">                                        

                                @elseif($field['type'] == 'select')
                                    <select name="{{ $field['name'] }}" class="form-select">
                                        <option value="">Select Branch</option>
                                        @foreach($field['options'] as $option)
                                            <option value="{{ $option->{$field['option_value']} }}">
                                                {{ $option->{$field['option_label']} }}
                                            </option>
                                        @endforeach
                                    </select>                                                                                    
                                    
                                @elseif($field['type'] == 'category')                                                                                
                                    <select name="sub_category_id" id="sub_category" class="form-select" >
                                        <option value="">Sub Category</option>
                                    </select>    

                                @elseif($field['type'] == 'dropzone')
                                    <input type="hidden" id="{{ $field['name'] }}_id" name="{{ $field['name'] }}_id" value=" ">                                        
                                    <div id="{{ $field['name'] }}" data-input="{{ $field['name'] }}_id" class="dropzone custom-dropzone dz-clickable">
                                        <div class="dz-message needsclick">
                                            <br>Drop files here or click to upload.<br><br>
                                        </div>
                                    </div>                                       
                                @endif
                                
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary">
                        {{ $modal['formConfig']['button'] }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>