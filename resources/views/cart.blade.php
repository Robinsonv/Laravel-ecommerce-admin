@extends('layout')

@section('title', 'Shopping Cart')

@section('extra-css')

@endsection

@section('content')

    @component('components.breadcrumbs')
        <a href="/">Home</a>
        <i class="fa fa-chevron-right breadcrumb-separator"></i>
        <span>Shopping Cart</span>
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

    <div class="cart-section container">
        <div>

            @if( Cart::count() > 0 )
                

                <h2>{{ Cart::count() }} item(s) in Shopping Cart</h2>

                <div class="cart-table">
                    @foreach( Cart::content() as $item)
                        <div class="cart-table-row">
                            <div class="cart-table-row-left">
                                <a href="{{ route('shop.show', $item->model->slug) }}">
                                @if( $item->model->image == NULL )
                                    <img src="{{asset('img/products/'.$item->model->slug.'.jpg')}}" alt="item" class="cart-table-img">
                                @else
                                    <img src="{{asset('storage/'.$item->model->image)}}" alt="item" class="cart-table-img">
                                @endif
                                
                                </a>
                                <div class="cart-item-details">
                                    <div class="cart-table-item"><a href="{{ route('shop.show', $item->model->slug) }}">{{ $item->model->name }}</a></div>
                                    <div class="cart-table-description">{{ $item->model->details }}</div>
                                </div>
                            </div>
                            <div class="cart-table-row-right">
                                <div class="cart-table-actions">
                                    <!-- <a href="#">Remove</a> <br> -->
                                    <form action="{{ route('cart.destroy', $item->rowId) }}" method="POST">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}

                                        <button type="submit" class="cart-options">Remove</button>
                                    </form>
                                    <!-- <a href="#">Save for Later</a> -->
                                    <form action="{{ route('cart.switchToSaveForLater', $item->rowId) }}" method="POST">
                                        {{ csrf_field() }}

                                        <button type="submit" class="cart-options">Save for later</button>
                                    </form>

                                </div>
                                <div>
                                    <select class="quantity" data-id="{{ $item->rowId }}">
                                        <option {{ $item->qty == 1 ? 'selected' : '' }} >1</option>
                                        <option {{ $item->qty == 2 ? 'selected' : '' }} >2</option>
                                        <option {{ $item->qty == 3 ? 'selected' : '' }} >3</option>
                                        <option {{ $item->qty == 4 ? 'selected' : '' }} >4</option>
                                        <option {{ $item->qty == 5 ? 'selected' : '' }} >5</option>
                                    </select>
                                </div>
                                <div>{{ presetPrice( $item->subtotal ) }}</div>
                            </div>
                        </div> <!-- end cart-table-row -->
                    @endforeach

                </div> <!-- end cart-table -->

                <a href="#" class="have-code">Have a Code?</a>

                <!-- <div class="have-code-container">
                    <form action="#">
                        <input type="text">
                        <button type="submit" class="button button-plain">Apply</button>
                    </form>
                </div> end have-code-container -->

                <div class="cart-totals">
                    <div class="cart-totals-left">
                        Shipping is free because we’re awesome like that. Also because that’s additional stuff I don’t feel like figuring out :).
                    </div>

                    <div class="cart-totals-right">
                        <div>
                            Subtotal <br>
                            Tax (13%) <br>
                            <span class="cart-totals-total">Total</span>
                        </div>
                        <div class="cart-totals-subtotal">
                            {{ presetPrice( Cart::subtotal() ) }} <br>
                            {{ presetPrice( Cart::tax() ) }} <br>
                            <span class="cart-totals-total">{{ presetPrice( Cart::total() ) }}</span>
                        </div>
                    </div>
                </div> <!-- end cart-totals -->

                <div class="cart-buttons">
                    <a href="{{ route('shop.index') }}" class="button">Continue Shopping</a>
                    <a href="{{ route('checkout.index') }}" class="button-primary">Proceed to Checkout</a>
                </div>
            @else
                <h3>No items in Cart!</h3> <br>
                <div class="spacer">

                    <a href="{{ route('shop.index') }}" class="button">Continue Shopping</a>
                </div>
            @endif
            

            @if( Cart::instance('saveForLater')->count() > 0 )
                

                <h2>{{ Cart::instance('saveForLater')->count() }} item(s) Saved For Later</h2>

                <div class="saved-for-later cart-table">
                    @foreach( Cart::instance('saveForLater')->content() as $itemIns)
                            
                        <div class="cart-table-row">
                            <div class="cart-table-row-left">
                                <a href="{{ route('shop.show', $itemIns->model->slug) }}">
                                @if( $itemIns->model->image == NULL )
                                    <img src="{{asset('img/products/'.$itemIns->model->slug.'.jpg')}}" alt="item" class="cart-table-img">
                                @else
                                    <img src="{{asset('storage/'.$itemIns->model->image)}}" alt="item" class="cart-table-img">
                                @endif
                                
                                </a>
                                <div class="cart-item-details">
                                    <div class="cart-table-item"><a href="{{ route('shop.show', $itemIns->model->slug) }}">{{$itemIns->model->name}}</a></div>
                                    <div class="cart-table-description">{{$itemIns->model->details}}</div>
                                </div>
                            </div>
                            <div class="cart-table-row-right">
                                <div class="cart-table-actions">
                                     <!-- <a href="#">Remove</a> <br> -->
                                     <form action="{{ route('saveForLater.destroy', $itemIns->rowId) }}" method="POST">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}

                                        <button type="submit" class="cart-options">Remove</button>
                                    </form>
                                    <!-- <a href="#">Save for Later</a> -->
                                    <form action="{{ route('saveForLater.switchToCart', $itemIns->rowId) }}" method="POST">
                                        {{ csrf_field() }}

                                        <button type="submit" class="cart-options">Move to Cart</button>
                                    </form>
                                </div>
                                {{-- <div>
                                    <select class="quantity">
                                        <option selected="">1</option>
                                        <option>2</option>
                                        <option>3</option>
                                        <option>4</option>
                                        <option>5</option>
                                    </select>
                                </div> --}}
                                <div>{{$itemIns->model->presetPrice()}}</div>
                            </div>
                        </div> <!-- end cart-table-row -->
                    @endforeach
                    
                    
                </div> <!-- end saved-for-later -->
            @else
                <h3>You have no items saved for later !</h3> <br>
            @endif

        </div>

    </div> <!-- end cart-section -->

    @include('partials.might-like')


@endsection

@section('extra-js')
<script src="{{asset('js/app.js')}}"></script>
<script>
    (function() {
        const classname = document.querySelectorAll('.quantity')

        Array.from(classname).forEach(function(element) {
            element.addEventListener('change', function(){

                const id = element.getAttribute('data-id')

                axios.patch(`/cart/${id}`, {
                    quantity: this.value
                })
                .then(function (response) {
                    // handle success
                    console.log(response);
                    window.location.href = '{{ route('cart.index') }}'

                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
                .finally(function () {
                    // always executed
                });
            })
        });
    })();
</script>

@endsection
