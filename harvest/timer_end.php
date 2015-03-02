<?php

list($g2_usec, $g2_sec) = explode(" ",microtime());
$t_end= (float)$g2_usec + (float)$g2_sec;
print "\nDuration:".gmdate("H:i:s",round ($t_end-$t_start, 1))." secondes";	

?>