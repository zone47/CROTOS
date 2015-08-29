<?php

$fp = fopen ($fold_crotos."dateupdate.txt", "r");
$udpate=fgets ($fp, 255);
fclose ($fp);
header('Location:/crotos/?d='.$udpate);

?>