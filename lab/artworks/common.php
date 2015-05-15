<?php
/*
 * header
 */
function print_header($title, $app, $css_file) {
?>
<!DOCTYPE html>
<html ng-app="<?php echo $app; ?>">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<title><?php echo $title; ?></title>
	<style  type='text/css'></style>
	<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.10/angular.js"></script>
	<script src="//angular-ui.github.io/bootstrap/ui-bootstrap-tpls-0.11.0.js"></script>
	<script src="app.js"></script>
	<link   href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet">
	<link   href="<?php echo $css_file; ?>" rel="stylesheet">
</head>
<body>

<?php
} // print_header

/*
 * footer
 */
function print_footer() {
?>	
<div style="clear:both"></div>
<hr />
<small>by /* / */, on a tool under the <a href="http://creativecommons.org/licenses/by-sa/4.0/">Creative Commons Attribution-Sharealike (BY-SA) license</a> made by <a href="https://www.wikidata.org/wiki/User:Poulpy">Poulpy</a>.</small>
</div>
</body>
</html>

<?php
} // print_footer

