<!DOCTYPE html>
<html class="no-js" lang="">

<head>
    @include('includes.head')
</head>

<body class="tg-home tg-homeone">
    <div id="tg-wrapper" class="tg-wrapper tg-haslayout">
        @include('includes.header')
        @yield('page-content')
        @include('includes.footer')
    </div>
    @include('includes.scripts')
</body>

</html>
