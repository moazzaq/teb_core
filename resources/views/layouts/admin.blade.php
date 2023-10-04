<!DOCTYPE html>
<html
    dir="{{session()->get('locale') == 'ar' ? 'rtl' : ''}}"
    lang="{{session()->get('locale')}}"
     data-framework="laravel"
    class="light-style layout-navbar-fixed layout-menu-fixed {{session()->get('locale') == 'ar' ? 'rtl' : ''}}"
    data-theme="theme-default"
    data-assets-path="{{asset('backend')}}/"
    data-template="vertical-menu-template"
>
<head>
    <meta charset="utf-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>{{ env('APP_NAME') }} - @yield('title','Cpanel')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{asset('backend')}}/img/favicon/favicon.ico" />

    <!-- Fonts -->
   @include('backend.partials.css')
    @stack('style')
</head>

<body>
<!-- Layout wrapper -->
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
        <!-- Menu -->
    @include('backend.partials.sidebar')

        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
            <!-- Navbar -->

        @include('backend.partials.header')

            <!-- / Navbar -->

            <!-- Content wrapper -->
            <div class="content-wrapper">
                <!-- Content -->

               @yield('admin')
                <!-- / Content -->

                <!-- Footer -->
            @include('backend.partials.footer')
                <!-- / Footer -->

                <div class="content-backdrop fade"></div>
            </div>
            <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
    </div>

    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>

    <!-- Drag Target Area To SlideIn Menu On Small Screens -->
    <div class="drag-target"></div>
</div>
<!-- / Layout wrapper -->

<!-- Core JS -->
<!-- build:js assets/vendor/js/core.js -->
@include('backend.partials.js')
<script>
    {{--window.templateCustomizer = new TemplateCustomizer({--}}
    {{--    lang: '{{ app()->getLocale() }}',--}}
    {{--});--}}
</script>
</body>
</html>
