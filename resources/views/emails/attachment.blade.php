<!DOCTYPE html>
<html>
<head>
    <title>{{ $subject }}</title>
</head>
<body>
    <h1>{{ $subject }}</h1>
    <!-- <p>{{ $body }}</p> -->
    @if (!empty($bodyContent))
        {!! $bodyContent !!}
    @endif
</body>
</html>
