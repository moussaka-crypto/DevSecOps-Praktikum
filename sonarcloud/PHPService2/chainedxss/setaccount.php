<?php
header('Location: index.htm');
require_once('../template/head.php');
$q = $_GET['q'];

print <<<END
 Password Changed, redirecting you home.
END;

require_once('../template/foot.php');
?>
