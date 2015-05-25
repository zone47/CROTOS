<div id="timer"><?php
// Timer end and print
list($g2_usec, $g2_sec) = explode(" ",microtime());
define ("t_end", (float)$g2_usec + (float)$g2_sec);
 print round (t_end-t_start, 1)." secondes"; ?>
</div>