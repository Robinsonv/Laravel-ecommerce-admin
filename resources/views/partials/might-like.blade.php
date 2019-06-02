<div class="might-like-section">
    <div class="container">
        <h2>You might also like...</h2>
        <div class="might-like-grid">
            @foreach( $mightAlsolike as $product)

                
                <a href="{{ route('shop.show', $product->slug) }}" class="might-like-product">
                @if( $product->image == NULL )
                    <img src="{{asset('img/products/'.$product->slug.'.jpg')}}" alt="product">
                @else
                    <img src="{{asset('storage/'.$product->image)}}" alt="product">
                @endif
                    <div class="might-like-product-name">{{ $product->name }}</div>
                    <div class="might-like-product-price">{{ $product->presetPrice() }}</div>
                </a>
            @endforeach
            
        </div>
    </div>
</div>
