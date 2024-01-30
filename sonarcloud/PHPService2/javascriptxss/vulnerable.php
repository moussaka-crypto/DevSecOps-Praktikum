<?php
require_once('../template/head.php');
$q =  isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '';

print <<<END
<script type="text/javascript">
var search_term = " $q";
</script>

Sorry, we could not find any results for:
<script type="text/javascript">document.write(search_term);</script>
END;

require_once('../template/foot.php');
?>
