<?php require_once('Connections/testmed.php'); ?>
<?php $query_spec = "SELECT * FROM tm_spec  where actiiv=1 ORDER BY dat ASC";
$spec =  /* fixed MMiC */ DB::Query($query_spec, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_spec =  /* fixed MMiC */ mysqli_fetch_assoc($spec);
$totalRows_spec =  /* fixed MMiC */ mysqli_num_rows($spec); ?>
<?php do { ?>
<?php echo $row_spec['nazv']; ?>=<?php echo $row_spec['num']." \n"; ?><?php } while ($row_spec =  /* fixed MMiC */ mysqli_fetch_assoc($spec)); ?>