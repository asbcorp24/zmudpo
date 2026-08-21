	<?php require_once('Connections/testmed.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists(" /* fixed MMiC */ DB::escape") ?  /* fixed MMiC */ DB::escape($theValue) :  /* fixed MMiC */ DB::escape($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}


$query_spec = "SELECT 
  `tm_spec`.`num`
FROM
  `tm_spec`
  WHERE
  `tm_spec`.`zap` = 1 
  ";
$spec1 =  /* fixed MMiC */ DB::Query($query_spec, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$totalRows_spec1 =  /* fixed MMiC */ mysqli_num_rows($spec1);
$tmp=0;
if (isset($_POST['num']))$tmp=$_POST['num'];
$tmp--;
$razdel="";
$kateg="";
if (isset($_POST['razdel']) and ($_POST['razdel']!=-1))$razdel=" and razdel='".$_POST['razdel']."'";
if (isset($_POST['kateg']) and ($_POST['kateg']!=-1))$kateg=" and kategor='".$_POST['kateg']."'";
if ($tmp<1)$tmp=0;
$tmp=($tmp)*8;
$query_spec = "SELECT 
  `tm_spec`.`num`,
  `tm_spec`.`nazv`,
  `tm_spec`.`dat`,
  `tm_spec`.`img`,
  `tm_spec`.`actiiv`,
  `tm_spec`.`zap`,
  `tm_spec`.`kr`,
  `tm_spec`.`razdel`,
  `tm_spec`.`kategor`,
  `tm_spec`.`chas`, `tm_spec`.`cena`
FROM
  `tm_spec`
  WHERE num>0 $razdel $kateg
  and `tm_spec`.`zap` = 1 limit $tmp,8
  ";
//echo $query_spec;
$spec1 =  /* fixed MMiC */ DB::Query($query_spec, $testmed) or die( /* fixed MMiC */ mysqli_error(DB::$link));
$row_spec1 =  /* fixed MMiC */ mysqli_fetch_assoc($spec1);
$totalRows_spec1 =  /* fixed MMiC */ mysqli_num_rows($spec1);
if ($totalRows_spec1<1){echo "С такими запросами нет результатов";exit();}
?>

<?php do { ?>
             
            
          
                    <div class="col-md-4 col-lg-3 col-sm-6">
                        <div class="blog-all mrg-xs">
                            <div class="blog-img">
                                <a href="#"><img src="timg/<?php echo $row_spec1['img'];?>" alt="" height="204px"></a>
                            </div>
                            <div class="blog-details gray-bg card-shadow reveal">
                                <h3 style="min-height: 80px"><a href="#"><?php echo mb_substr($row_spec1['nazv'],0,50);?>...</a></h3>
                                <div class="blog-meta">
                                    <span class="published3">
										<a href="#"><i class="icofont icofont-calendar"></i><?php echo $row_spec1['dat'];?></a>
                                    </span>
                                    <span class="published4">
                                        <a href="#"><i class="icofont icofont-abacus"></i> <?php echo $row_spec1['cena'];?><?php if($row_spec1['cena']==null) echo "Беспл"; ?></a>
                                    </span>
                                </div>
                                <p><i class="icofont icofont-pills"></i> <?php if (isset($row_spec1['kategor'])) {echo $row_spec1['kategor'];}else {echo "Без раздела";} ?></p>
                                <a class="button extra-small" href="course-details.php?curs=<?php echo $row_spec1['num'];?>#reg">
                                    <span>подробности</span>
                                </a>
                            </div>
                        </div>
                    </div>
                     <?php } while ($row_spec1 =  /* fixed MMiC */ mysqli_fetch_assoc($spec1)); ?>
                   
                    <div class="col-md-12 text-center pt-30">
                        <div class="pages2">
                            <ul>
								<?php 
								for($x=1;$x<=$totalRows_spec1/8+1;$x++){
								
								?>
                                <li <?php if ($x==($tmp/8+1) )echo 'class="active"'; ?>><a href="#" class="tbt" data-num="<?php echo $x;?>"><?php echo $x;?></a></li>
                                <?php } ?>
                            <!--    <li class="active"><a href="#">03</a></li>-->
                                
                            </ul>
                        </div>
                    </div>