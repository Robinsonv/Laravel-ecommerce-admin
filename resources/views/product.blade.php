@extends('layout')

@section('title', $product->name)

@section('extra-css')

@endsection

@section('content')

    @component('components.breadcrumbs')
        <a href="/">Home</a>
        <i class="fa fa-chevron-right breadcrumb-separator"></i>
        <span>Product</span>
    @endcomponent
    <!-- end breadcrumbs -->

    <div class="container">
        @if (session()->has('success_message'))
            <div class="alert alert-success">
                {{ session()->get('success_message') }}
            </div>
        @endif

        @if(count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <div class="product-section container">
        <div>
            <div class="product-section-image">
                <img style="max-height: 300px;" src="{{ productImage( $product->image ) }}" alt="product" class="active" id="currentImage">
            </div>
            <div class="product-section-images">
                <div class="product-section-thumbnail">
                    <img src="{{ productImage( $product->image ) }}" alt="prodict">
                </div>
                @if( $product->images )
                    @foreach( json_decode($product->images, true) as $image)
                        <div class="product-section-thumbnail">
                            <img src="{{ productImage( $image ) }}" alt="prodict">
                        </div>
                    @endforeach
                @endif
            </div>
        </div>


        <div class="product-section-information">
            <h1 class="product-section-title">{{ $product->name }}</h1>
            <div class="product-section-subtitle">{{ $product->details }}</div>
            <div>{!! $stockLevel !!} </div>
            <div class="product-section-price">{{ $product->presetPrice() }}</div>

            <p>
                {!! $product->description !!}
            </p>

            
            <p>&nbsp;</p>

            @if( $product->quantity != 0 )

                <!-- <a href="#" class="button">Add to Cart</a> -->
                <form action="{{ route('cart.store') }}" method="POST">
                    {{ csrf_field() }}

                    <input type="hidden" name="id" value="{{ $product->id }}">
                    <input type="hidden" name="name" value="{{ $product->name }}">
                    <input type="hidden" name="price" value="{{ $product->price }}">

                    <button type="submit" class="button button-plain">Add to Cart</button>
                </form>
            @endif

            

        </div>
    </div> <!-- end product-section -->

    @include('partials.might-like')


@endsection
@section('extra-js')
    <script>
        (function () {
            const currentImage = document.querySelector('#currentImage');
            const images = document.querySelectorAll('.product-section-thumbnail');

            images.forEach((element) => element.addEventListener('click', thumbnailClick));

            function thumbnailClick(e) {

                currentImage.classList.remove('active');

                currentImage.addEventListener('transitionend', () => {
                    currentImage.src = this.querySelector('img').src;
                    currentImage.classList.add('active');
                })

                images.forEach((element) => element.classList.remove('selected'));

                this.classList.add('selected');
            }

        })();
    </script>
@endsection
