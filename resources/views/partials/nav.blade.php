<header>
    <div class="top-nav container">
        <div class="top-nav-left">
            <div class="logo"><a href="/">Ecommerce</a></div>
            @if (! ( request()->is('checkout') || request()->is('guestCheckout') ) )
            {{menu('main','partials.menus.main')}}
            @endif
        </div>
        <div class="top-nav-left">
            @if (! ( request()->is('checkout') || request()->is('guestCheckout') ) )
                {{menu('main','partials.menus.main-right')}}
            @endif
        </div>

    </div> <!-- end top-nav -->
</header>
