<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" style="position: relative;width: 100%; margin: 0;">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=no" name="viewport">
    <title id="TituloDePagina"> BoliTube - @yield('Title')</title>
    <link rel="icon" href="{{ asset('css/img/favicon-bo.png') }}">

    <!-- Boostrar - JQuery -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    @yield("links")
</head>
<body>

@yield("body")

@yield("pie")


@include('layouts/footer')
<!-- Scripts Boostrap -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
@yield('afterbootstrap')
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="http://malsup.github.com/jquery.form.js"></script>
<script>
    var i = 0;
    function change() {
        var doc = document.getElementsByClassName("navbar")[0];
        var color = ["#2ec2ca", "#37c6e6", "#37a5e6"];
        doc.style.borderTopColor = color[i];
        i = (i + 1) % color.length;
    }
    setInterval(change, 2000);
</script>
@yield('afterjquery')

</body>
</html>