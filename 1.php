<?php 
ini_set("smtp_port","25");
ini_set("SMTP","mail.agileblaze.com");
ini_set('display_errors',1);
if(mail('abdulshukkoor@agileblaze.com', 'My Subject', 'hai ','',''))
echo 's';
else
echo 'f';


?>
