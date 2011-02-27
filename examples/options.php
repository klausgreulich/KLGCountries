<?php
	require_once(dirname(__FILE__).'/../KLGCountries.php');
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
