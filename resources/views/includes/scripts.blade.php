@section('script')
    <script>
        const apiToken = '{{ Session::get("api_token") }}';
        const APP_URL = "{{ config('app.url') }}";
    </script>
    <script src="{{ asset('assets/js/vendor/jquery-library.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/bootstrap.min.js') }}"></script>
    <script src="https://maps.google.com/maps/api/js?key=AIzaSyCR-KEWAVCn52mSdeVeTqZjtqbmVJyfSus&language=en"></script>
    <script src="{{ asset('assets/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.vide.min.js') }}"></script>
    <script src="{{ asset('assets/js/countdown.js') }}"></script>
    <script src="{{ asset('assets/js/jquery-ui.js') }}"></script>
    <script src="{{ asset('assets/js/parallax.js') }}"></script>
    <script src="{{ asset('assets/js/countTo.js') }}"></script>
    <script src="{{ asset('assets/js/appear.js') }}"></script>
    <script src="{{ asset('assets/js/gmap3.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    @stack('js-scripts')
@show
