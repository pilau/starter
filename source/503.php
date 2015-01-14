<?php
header('HTTP/1.1 503 Service Temporarily Unavailable');
header('Status: 503 Service Temporarily Unavailable');
header('Retry-After: 3600');
?><!doctype html>
<html lang="en-GB">
<head>
	<meta charset="UTF-8">
	<title>Site maintenance in progress</title>
	<meta name="robots" content="none">
	<style>
		body {
			background-color: #fff;
			color: #333;
			margin: 0;
			font: 1em/1.3 Helvetica, Arial, sans-serif;
			text-align: center;
		}
		#wrapper {
			margin: 5% auto;
			width: 90%;
			max-width: 750px;
			text-align: left;
		}
	</style>
</head>

<body>

<div id="wrapper">

	<h1>[[site-title]]: [[production-domain]]</h1>

	<p><strong>Site maintenance in progress</strong></p>

	<p>We should be up and running very soon. Apologies for any inconvenience - please check back in a bit!</p>

</div>

</body>
</html>
