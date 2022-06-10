<?php
 define('DBHOST','localhost');
 define('DBUSER','root');
 define('DBPASS','');
 define('DBNAME','demo');
 define('TB_PREF','cms_');

$db = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
if(!$db){
 die( "Sorry! There seems to be a problem connecting to our database.");
}


$myFile = "rss.xml";
$fh = fopen($myFile, 'w') or die("can't open file"); 

$rss_txt .= '<?xml version="1.0" encoding="utf-8"?>';
$rss_txt .= "<rss version='2.0'>".PHP_EOL;
$rss_txt .= '<addresses>'.PHP_EOL;
$query = mysqli_query($db, "SELECT * FROM ".TB_PREF."addresses");
while($values_query = mysqli_fetch_assoc($query)) {
$rss_txt .= '<address>';
$rss_txt .= '<ID>' .$values_query['ID']. '</ID>';
$rss_txt .= '<firstname>' .$values_query['firstname']. '</firstname>';
$rss_txt .= '<lastname>' .$values_query['lastname']. '</lastname>';
$rss_txt .= '<email>' .$values_query['email']. '</email>';
$rss_txt .= '<street>' .$values_query['street']. '</street>';
$rss_txt .= '<zipcode>' .$values_query['email']. '</zipcode>';
$rss_txt .= '<city>' .$values_query['role']. '</city>';
$rss_txt .= '</address>'.PHP_EOL;
}
$rss_txt .= '</address>'.PHP_EOL;
$rss_txt .= '</rss>';
fwrite($fh, $rss_txt);
fclose($fh);

?>