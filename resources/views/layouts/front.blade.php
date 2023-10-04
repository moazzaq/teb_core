<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>eTrade || Home-04</title>
    <meta name="robots" content="noindex, follow"/>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('frontend')}}/images/favicon.png">

    <!-- CSS
    ============================================ -->

    <!-- Bootstrap CSS -->
    @include('frontend.partials.css')
    @stack('css')

</head>


<body>
<!--[if lte IE 9]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade
    your browser</a> to improve your experience and security.</p>
<![endif]-->
<a href="#top" class="back-to-top" id="backto-top"><i class="fal fa-arrow-up"></i></a>
<!-- Start Header -->
@include('frontend.partials.header')

<main class="main-wrapper">

    @yield('content')

</main>


<div class="service-area">
    <div class="container">
        <div class="row row-cols-xl-4 row-cols-sm-2 row-cols-1 row--20">
            <div class="col">
                <div class="service-box service-style-2">
                    <div class="icon">
                        <img src="{{asset('frontend')}}/images/icons/service1.png" alt="Service">
                    </div>
                    <div class="content">
                        <h6 class="title">Fast &amp; Secure Delivery</h6>
                        <p>Tell about your service.</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="service-box service-style-2">
                    <div class="icon">
                        <img src="{{asset('frontend')}}/images/icons/service2.png" alt="Service">
                    </div>
                    <div class="content">
                        <h6 class="title">Money Back Guarantee</h6>
                        <p>Within 10 days.</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="service-box service-style-2">
                    <div class="icon">
                        <img src="{{asset('frontend')}}/images/icons/service3.png" alt="Service">
                    </div>
                    <div class="content">
                        <h6 class="title">24 Hour Return Policy</h6>
                        <p>No question ask.</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="service-box service-style-2">
                    <div class="icon">
                        <img src="{{asset('frontend')}}/images/icons/service4.png" alt="Service">
                    </div>
                    <div class="content">
                        <h6 class="title">Pro Quality Support</h6>
                        <p>24/7 Live support.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Start Footer Area  -->
@include('frontend.partials.footer')
<!-- End Footer Area  -->


<!-- Header Search Modal End -->
<div class="header-search-modal" id="header-search-modal">
    <button class="card-close sidebar-close"><i class="fas fa-times"></i></button>
    <div class="header-search-wrap">
        <div class="card-header">
            <form action="#">
                <div class="input-group">
                    <input type="search" class="form-control" name="prod-search" id="prod-search"
                           placeholder="Write Something...." onsearch="clearSearchResults()">
                    <button type="submit" class="axil-btn btn-bg-primary"><i class="far fa-search"></i></button>
                </div>
            </form>
        </div>
        <div class="card-body">
            <div class="search-result-header">
                <h6 class="title count_autocomplete"></h6>
                <a href="#" class="view-all"></a>
            </div>
            <div class="psearch-results">

            </div>
        </div>
    </div>
</div>
<!-- Header Search Modal End -->

@php
    $wishlists = Session::get('wishlist', []);
@endphp
<div class="cart-dropdown" id="cart-dropdown">
    <div class="cart-content-wrap">
        <div class="cart-header">
            <h2 class="header-title">Cart review</h2>
            <button class="cart-close sidebar-close"><i class="fas fa-times"></i></button>
        </div>
        <div class="cart-body">
            <ul class="cart-item-list">

            </ul>
        </div>
        <div class="cart-footer">
            <h3 class="cart-subtotal">
                <span class="subtotal-title">Subtotal:</span>
                <span class="subtotal-amount">${{$cart->total()['sub_total']}}</span>
            </h3>
            <div class="group-btn">
                <a href="{{route('front.cart.index')}}" class="axil-btn btn-bg-primary viewcart-btn">View Cart</a>
                <a href="{{route('front.checkout')}}" class="axil-btn btn-bg-secondary checkout-btn">Checkout</a>
            </div>
        </div>
    </div>
</div>

@include('frontend.partials.js')
@stack('js')

<script>
    $(document).ready(function () {
        function generateStarRating(rating) {
            var starsHtml = '';
            var maxRating = 5; // Assuming a maximum rating of 5 stars

            for (var i = 1; i <= maxRating; i++) {
                if (i <= rating) {
                    starsHtml += '<i class="fas fa-star"></i>';
                } else {
                    starsHtml += '<i class="fal fa-star"></i>';
                }
            }

            return starsHtml;
        }
        function updateCartItems(cartData) {
            var cartItemList = $('.cart-item-list');
            var lang = "{{app()->getLocale()}}"
            cartItemList.empty();

            $.each(cartData, function (index, cartItem) {
                var colorName = cartItem.color ? ` (${cartItem.color.name[lang]})` : '';
                var totalRating = 0;
                var numReviews = cartItem.product.reviews.length;

                for (var i = 0; i < numReviews; i++) {
                    totalRating += cartItem.product.reviews[i].rating;
                }

                var averageRating = numReviews > 0 ? (totalRating / numReviews).toFixed(2) : 0;

                var listItem = `
            <li class="cart-item">
                <div class="item-img">
                    <a href="single-product.html"><img src="/storage/${cartItem.product.main_image}" alt="${cartItem.product.name[lang]}"></a>
                    <button class="close-btn delete-from-cart" data-product-id="${cartItem.id}"><i class="fas fa-times"></i></button>
                </div>
                <div class="item-content">
                    <div class="product-rating">
                        <div class="product-rating">
                                <span class="rating-icon">
                                 ${generateStarRating(averageRating)}
                            </span>
                                <span class="rating-number"><span>${numReviews}</span> Reviews</span>
                    </div>

                    <h3 class="item-title"><a href="single-product-3.html">${cartItem.product.name[lang]} ${colorName}</a></h3>
                    <div class="item-price"><span class="currency-symbol">$</span>${cartItem.product.finalPrice}</div>
                </div>
            </li>
        `;
                cartItemList.append(listItem);
            });
        }

        function fetchAndDisplayCartItems() {
            $.ajax({
                url: '{{ route('front.cart.index') }}',
                method: 'GET',
                success: function (response) {
                    updateCartItems(response.cartData);
                },
                error: function (error) {
                    // Handle error
                }
            });
        }

        fetchAndDisplayCartItems();

        $(document).on('click', '.delete-from-cart', function () {
            var productId = $(this).data('product-id');


            $.ajax({
                url: `/cart/${productId}`,
                method: 'Delete',
                data: {
                    //   product_id: productId,
                    _token: '{{csrf_token()}}',
                },
                success: function (response) {
                    fetchAndDisplayCartItems();
                    $('.cart-count').html(response.cart_count);
                    $('.subtotal-amount').html(response.total.sub_total);
                    $(this).closest('.cart-item').remove();
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    })

                    Toast.fire({
                        icon: 'success',
                        title: 'Product deleted from cart!'
                    })
                },
                error: function (error) {

                }
            });
        });


        $(".move-to-cart").on("click", function () {
            var productId = $(this).data("product-id");
            var tr = $(this).closest("tr"); // Find the closest <tr> element
            $.ajax({
                url: `/move-to-cart`,
                method: 'POST',
                data: {
                    product_id: productId,
                    _token: '{{csrf_token()}}',
                },
                success: function (response) {
                    fetchAndDisplayCartItems();
                    $('.cart-count').html(response.cart_count);
                    $('.subtotal-amount').html(response.total.sub_total);
                    tr.remove();
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    })

                    Toast.fire({
                        icon: 'success',
                        title: 'Product moved to cart!'
                    })
                },
                error: function (error) {

                }
            });
        });

        // Add from Wishlist
        // $(".add-to-wishlist").on("click", function () {
        $(document).on("click", ".add-to-wishlist", function () {
            var productId = $(this).data("product-id");
            var heartIcon = $(this).find("i");
            $.ajax({
                url: `/wishlist`,
                method: 'POST',
                data: {
                    product_id: productId,
                    _token: '{{csrf_token()}}',
                },
                success: function (response) {
                    heartIcon.toggleClass("far fa-heart fas fa-heart");
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    })

                    Toast.fire({
                        icon: 'success',
                        title: response.message
                    })
                },
                error: function (error) {

                }
            });
        });

        // Remove from Wishlist
        $(".remove-from-wishlist").on("click", function () {
            var productId = $(this).data("product-id");
            var tr = $(this).closest("tr");

            $.post("/wishlist/remove", {product_id: productId, _token: '{{csrf_token()}}'}, function (data) {
                tr.remove();


                if (data.wishlist_count == 0) {
                    $('.empty-wishlist').show()
                } else {
                    $('.empty-wishlist').hide()
                }
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                })

                Toast.fire({
                    icon: 'success',
                    title: 'Product removed from wishlist!'
                })
            });
        });

    })

</script>

<script>
    function clearSearchResults() {
        $('#prod-search').val(''); // Reset the search input to an empty string
        $('.count_autocomplete').html(''); // Clear the "Result Found" text
        $('.view-all').html(''); // Clear the "View All" text
        $('.psearch-results').empty(); // Clear the search results
    }

    var wishlists = @json($wishlists);

    $(document).ready(function () {

        $('.sidebar-close').click(function () {
            $('#prod-search').val('');
            $('.count_autocomplete').html('');
            $('.view-all').html('');
            $('.psearch-results').empty()
        });

        $('#prod-search').keyup(function () {
            var query = $(this).val();

            if (query.length >= 2) {
                $.ajax({
                    url: '/autocomplete',
                    method: 'GET',
                    data: {query: query},
                    dataType: 'json',
                    success: function (data) {

                        var resultsContainer = $('.psearch-results');
                        resultsContainer.empty();

                        if (query.length > 0) {
                            $('.count_autocomplete').html(data.length + ' Result Found')
                            $('.view-all').html('View All')

                            $('.view-all').click(function (e) {
                                e.preventDefault();

                                window.location.href = '/shop?name=' + query;
                            });
                        } else {
                            $('.count_autocomplete').html('')
                            $('.view-all').html('')
                        }

                        $.each(data, function (key, product) {
                            var productId = product.id.toString(); // Get the product ID
                            var isProductInWishlist = wishlists.includes(productId);
                            console.log('is found ' + isProductInWishlist)
                            var productHTML = `
                                   <div class="axil-product-list">
                                    <div class="thumbnail">
                                        <a href="/single-product/${product.slug}">
                                            <img src="/storage/${product.main_image}" alt="${product.name}"
                                                style="max-width: 100px; height: auto;">
                                        </a>
                                    </div>
                                    <div class="product-content">
                                        <div class="product-rating">
                                                <span class="rating-icon">
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fal fa-star"></i>
                                            </span>
                                            <span class="rating-number"><span>100+</span> Reviews</span>
                                        </div>
                                        <h6 class="product-title"><a href="single-product.html">${product.name}</a></h6>
                                        <div class="product-price-variant">
                                           ${product.price_offer !== null ? `
                    <span class="price old-price">${product.price}</span>
                    <span class="price current-price">${product.price_offer}</span>
                ` : `
                    <span class="price current-price">${product.price}</span>
                `}
                                        </div>
                                        <div class="product-cart">
<!--                                            <a href="cart.html" class="cart-btn"><i class="fal fa-shopping-cart"></i></a>-->
                                         <a class="add-to-wishlist" data-product-id="${productId}">
                        <i class="${isProductInWishlist ? 'fas' : 'far'} fa-heart"></i>
                    </a>
                                        </div>
                                    </div>
                                </div>
                            `;

                            resultsContainer.append(productHTML);
                        });
                    }
                });
            } else {
                $('.psearch-results').empty();
            }
        });
    });
</script>

</body>
</html>
