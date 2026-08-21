
<?php

 

?>
<html>
<body>
<script src="detect.js"></script>
<script>
s=navigator.userAgent;
if (s.indexOf("YaBrowser",1)>0) { console.log('яндекс браузер');}
if (s.indexOf("Amigo",1)>0) { console.log('Амиго');}
 
console.log(navigator.userAgent);
var ua = detect.parse(navigator.userAgent);


console.log(ua.browser.name); // "Mobile Safari 4.0.5"
</script></body>
</html>