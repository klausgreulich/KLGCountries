<?php
        require_once(dirname(__FILE__).'/../KLGCountries.php');
	//use lower case 2 character representation for the selected country
	$selectedCountry = 'de';	
?>

<html>
<head></head>
<body>

<select name="countries">
<?php echo(KLGCountries::getInstance()->setUseUTF8(false)->getCountryHTMLSelectOptions($selectedCountry)); ?>
</select>

</body>
</html>
