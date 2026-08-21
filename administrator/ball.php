<?php 

function vball($s){
	if ($s<50) return 2;
		if (($s>=50)and($s<70)) return 3;
	if (($s>=70)and($s<90)) return 4;
	if ($s>=90) return 5;
}
?>