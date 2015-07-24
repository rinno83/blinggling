<?php echo $heading; ?>

<?php echo preg_replace('/(<p>|<\/p>)/', '
', addslashes($message)); ?>