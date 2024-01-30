<?php
require_once('../template/head.php');
$q = isset($_GET['q']) ? htmlspecialchars($_GET['q'], ENT_QUOTES, 'UTF-8') : '';;

print <<<END
Here are the results for $q:
<ol>
  <li><a href="account.php?user=neo">My Account</a> - account.php (0.5KB)</a></li>
  <li><a href="index.htm">Home</a> - index.htm (0.4KB)</a></li>
</ol>
END;

require_once('../template/foot.php');
?>
