<?php
// регистрационная информация (Идентификатор магазина, пароль #1)
// registration info (Merchant ID, password #1)
$mrh_login = "zmudpo.ru";
$mrh_pass1 = "ELLHtI2ch3FiY72db3rz";
$inv_id =  $username_test;
if (!isset($dopid))$dopid=-1;
	$inv_desc = "Оплата обучения";
$out_summ = $row_name['cena'];
$IsTest = 1;
$crc = md5("$mrh_login:$out_summ:$inv_id:$mrh_pass1:Shp_Id=$dopid");
print "<html><script language=JavaScript ".
    "src='https://auth.robokassa.ru/Merchant/PaymentForm/FormS.js?".
      "MerchantLogin=$mrh_login&OutSum=$out_summ&InvoiceID=$inv_id&Shp_Id=$dopid".
      "&Description=$inv_desc&SignatureValue=$crc&IsTest=$IsTest'></script></html>";
?>
