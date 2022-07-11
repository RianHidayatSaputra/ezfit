<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>{{ $page_title }}</title>
	<meta name="csrf-token" content="{{ csrf_token() }}"/>
	<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
</head>
<body>
	{!! $content !!}
</body>
</html>