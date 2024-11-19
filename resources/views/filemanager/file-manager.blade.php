<!DOCTYPE html>

<html>

<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  

    <!-- CSRF Token -->

    <meta name="csrf-token" content="{{ csrf_token() }}">

  

    <title>{{ config('app.name', 'File Manager') }}</title>

  

    <!-- Styles -->

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <link href="{{ asset('vendor/file-manager/css/file-manager.css') }}" rel="stylesheet">
 

</head>

<body>

    <div class="container">

        <h2>Laravel File Manager Tutorial Example - ItSolutionStuff.Com</h2>

        <div class="row">

            <div class="col-md-12" id="fm-main-block">

            <div id="fm-main-block">
            <div id="fm"></div>
        </div>

            </div>

        </div>

    </div>

  

    <script src="{{ asset('vendor/file-manager/js/file-manager.js') }}"></script>
    <script>
  document.addEventListener('DOMContentLoaded', function() {
   if (window.opener && typeof window.opener.fmSetLink === 'function') {
      document.getElementById('fm-main-block').setAttribute('style', 'height:' + window.innerHeight + 'px');
      fm.$store.commit('fm/setFileCallBack', function(fileUrl) {
         window.opener.fmSetLink(fileUrl);
         window.close();
      });
   } else {
      console.error('window.opener.fmSetLink is not available.');
   }
});

    </script>

</body>

</html>