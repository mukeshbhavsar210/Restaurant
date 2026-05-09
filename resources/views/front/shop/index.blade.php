@extends('front.layouts.app')

@section('content')
    <section class="subcategories-section"> 
        <div class="subcategories-section__item no-wrap no-user-select">
            <a href="{{ route('front.menu', [$category->slug]) }}" class="{{ empty($menuSlug) ? 'active' : '' }}">
                All
            </a>
        </div>

        @foreach($menus as $menu)
            <div class="subcategories-section__item text-overflow">
                <a href="{{ route('front.menu', [$category->slug, $menu->slug]) }}" class="{{ $menuSlug == $menu->slug ? 'active' : '' }}">
                    {{ $menu->name }}
                </a>
            </div>
        @endforeach
    </section>

    <section class="menu-products-section menu-products-section--grid">
        <div class="menu-grid">                    
            @if ($products->isNotEmpty())
                @foreach ($products as $product)                                    
                    <x-products :product="$product" :seats="$seats" :variants="$variants" />
                @endforeach
            @endif
        
            <div class="col-md-12 pt-5">
                {{-- {{ $products->withQueryString()->links() }} --}}
            </div>
        </div>                      
    </section>

    <x-cart :seats="$seats"  />
@endsection

@section('customJs')
<script>
        $(document).ready(function() {
            $('.lab-slide-up').find('a').attr('data-toggle', 'modal');
            $('.lab-slide-up').find('a').attr('data-target', '#lab-slide-bottom-popup');
        });
</script>
@endsection