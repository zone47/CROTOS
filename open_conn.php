<?php
$link = mysqli_connect ($host,$user,$pass,$db) or die ('Erreur : '.mysqli_error());
mysqli_query($link,"SET NAMES 'utf8'");
?>