<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="utf-8">
	<title><?php echo $data["title"] ?? "" ?></title>
	<link rel="shortcut icon" href="images/favicon.ico" type="image/png">
	<link rel="stylesheet" href="bootstrap-icons/bootstrap-icons.css">
	<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
	<script src="bootstrap/js/bootstrap.min.js"></script>
	<script src="js/axios.min.js" defer></script>
	<script src="js/vue.js" defer></script>
	<?php
		if ($content_view != "") {
			echo '<link rel="stylesheet" type="text/css" href="css/' . $content_view . '.css"></script>';
			echo '<script src="js/' . $content_view . '.js" defer></script>';
		}
	?>
</head>
<body>
	<div id="vueapp">
		<?php include 'app/views/'.$content_view. '.php'; ?>
	</div>
</body>
</html>