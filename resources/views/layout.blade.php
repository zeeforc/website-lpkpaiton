<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet" />

    <!-- Icon -->
    <script src="https://unpkg.com/feather-icons"></script>


    <link rel="stylesheet" href="{{ asset('style/navbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('style/footer.css') }}" />
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    @stack('styles')
    @stack('script')

    <title>LPK Paiton Selaras - @yield('title')</title>
</head>

<body>

    @include('components.navbar')

    <div class="main-content w-full min-h-screen bg-amber-200">
        @yield('content')
    </div>

    @include('components.footer')

    <script src="{{ asset('javascript/index.js') }}"></script>
    <script src="{{ asset('javascript/script.js') }}"></script>
    <script src="{{ asset('javascript/berita.js') }}"></script>
    <script src="{{ asset('javascript/galeri.js') }}"></script>
    {{-- <script src="javascript/berita.js"></script>
    <script src="javascript/galeri.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>

    <script>
        feather.replace();
    </script>
</body>

</html>
