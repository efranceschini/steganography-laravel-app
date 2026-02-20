<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>{{ config('app.name') }}</title>
    <meta name="description" content="" />
    <meta name="robots" content="index, follow" />

    @vite(['resources/css/app.css'])
  </head>
  <body class="min-h-screen flex flex-col sm:bg-gray-200">
    {{ $slot }}
  </body>
</html>