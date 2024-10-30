<?php
/*
Plugin Name: Metatag
Plugin URI: http://hayatikodla.com
Description: Bu eklenti Hayatı Kodla dan. Googlenden gelen kelimeyi ve o sayfadaki keyword yapar
Version: 0.1
Author: Hasan Yüksektepe
Author URI: http://www.hayatikodla.com
*/

//Bu kodu buldum wordpress ile mysql den çektiğimiz arrayı normal arraya çeviriyor
function object2Array($d)
    {
        if (is_object($d))
        {
            $d = get_object_vars($d);
        }
 
        if (is_array($d))
        {
            return array_map(__FUNCTION__, $d);
        }
        else
        {
            return $d;
        }
    }
////////////////////////////////////////////////////////////////////////////////////////////

//Burası googleden gelen aramayı alıyor
function google(){
//Googleden gelen kelimeyi ekliyoruz
$gelen = $_SERVER["HTTP_REFERER"];
if (strstr($gelen, "google")){
preg_match("#(.+)q=(.*?)&#", $gelen, $sonuc);
$kelime = rawurldecode($sonuc[2]);
$seodandegis = str_replace(' ', ',', $kelime);
echo $seodandegis;
}
}
//Burası seolu linkten yazının başlığını alıp çeviriyor
function etiketyap(){
//Seolu linkten alıp keyword yapıyor
$urldenal = $_SERVER['REQUEST_URI'];
$seodandegis = str_replace('-', ',', $urldenal);
$klasoral = explode("/",$seodandegis);
$bolumlerial = str_replace('/', ',', $klasoral);
return $bolumlerial[2];
}

//Burası mysql den etiketleri çekiyor
function al(){
global $wpdb;
$myrows = $wpdb->get_results( "SELECT * FROM $wpdb->terms" );
//print_r(object2Array($myrows));
$a = object2Array($myrows);
$say = count($a);
$rasgele = rand(0,$say);
echo '<meta name="keywords" content="';
for ($i; $i<10; $i++){
if($a[$i+$rasgele]["name"]!=""){
echo $a[$i+$rasgele]["name"].",";
}
}
echo '" />';
}
//Burası son kısım eğer bir yazıyı seçmemiş ise etiketleri yazdırıyor eğer yazı açıkca yazının başlığınını 
//yazdırıyor
function etiketler(){
if(etiketyap()==""){
//Ana sayfa
al();
echo '<meta name="description" content="';
bloginfo('description');
echo '" />';
}else{
$uzantial = explode(".",etiketyap());
//Yazı seçilmiş
echo '<meta name="Keywords" content="'.$uzantial[0].google().'" />';
echo '<meta name="Description" content="';
the_title();
echo '" />';
}

echo '
<!--Seo--> 
	<meta name="url" content="'.get_permalink().'" />
	<meta name="distribution" content="global" />
	<meta name="Rating" content="General" />
	<meta name="robots" content="yes, all, index, follow" />
	<meta http-equiv="EXPIRES" content="now" />
	<meta name="Revisit-after" content="3" />
	<meta name="Revisit" content="After 3 days" />
	<meta name="audience" content="all" />
	<meta name="allow-search" content="yes" />
	<meta name="googlerobot" content="index,all,follow" />
	<meta http-equiv="Copyright" content="Copyright © 2012 Hayatı Kodla" />
	<meta name="creator" content="Hayatı Kodla" />
	<meta name="publisher" content="Hayatı Kodla" />
	<meta http-equiv="Reply-to" content="hasanhasokeyk@hotmail.com" />
	<meta name="Design" content="www.hayatikodla.com" />
	<meta name="author" content="Hayatı Kodla" />
	<link rel="canonical" href="http://www.hayatikodla.com" />	
';

}

?>