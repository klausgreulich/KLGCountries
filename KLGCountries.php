<?php

/*
 * (c) Klaus L. Greulich <greulich@klausgreulich.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code (BSD License).
 */

class KLGCountries
{
	protected $language = 'EN';
	protected $keepAllCaps = false;
	protected $textFileContents = null;
	protected $useUTF8 = true; 
	
	public function __construct()
	{
		
	}
	
	public static function getInstance()
	{
		return new KLGCountries();
	}
	
	public function getCountryHTMLSelectOptions($selectedCountry = null)
	{
		$countries = $this->getCountries();
		foreach($countries as $alpha2=>$countryName) {
			if ($this->useUTF8 == false) $countryName = utf8_decode($countryName);
			$countryName = htmlentities($countryName);
			$selectedCountry = strtoupper($selectedCountry);
			$selected = ($selectedCountry == $alpha2) ? ' selected="selected"' : '';
			$output.="<option name=\"$alpha2\"$selected>$countryName</option>".PHP_EOL;
		}
		return $output;
	}
	
	public function setUseUTF8($flag = true)
	{
		$this->useUTF8 = ($flag == true) ? true : false;
		
		return $this;
	}
	
	public function setKeepAllCaps($flag = true)
	{
		$this->keepAllCaps = ($flag == true) ? true : false;
		
		return $this;
	}
	
	public function setLanguage($lang)
	{
		$lang = strtoupper($lang);
		if($lang != 'EN') throw new Exception('Only english is supported at the momentent');
		$this->language = $lang;
		$this->textFileContents = null;
		
		return $this;
	}
	
	public function getCountries($nameAsKey = false)
	{
		$iso3166 = $this->getTextFileContents();
		$lines = explode("\n",$iso3166);
		$countries = array();
		foreach($lines as $lineNumber=>$line) {
			$line = trim($line);
			if (substr($line,0,1) != '#' && strlen($line)>0) {
				$parts = explode(';',$line);
				if (count($parts) != 2) throw new Exception('Error reading ISO-3166-CODE-LIST file at line '.($lineNumber+1));
				if ($this->keepAllCaps == false) {
					$parts[0] = strtolower($parts[0]);
					$parts[0] = ucwords($parts[0]);
					$parts[0] = $this->correctCountryName($parts[0],$this->language);
				}
				if ($nameAsKey) {
					$countries[$parts[0]] = $parts[1];
				} else {
					$countries[$parts[1]] = $parts[0];
				}
			}
		}
		
		return $countries;
	}
	
	public function getCountriesWithNameAsKey()
	{
		// parameter: name as key
		return $this->getCountries(true);
	}
	
	public function getCountriesWithAlpha2AsKey()
	{
		// parameter: name as key
		return $this->getCountries(false);
	}
	
	public function getISO3166Raw()
	{
		$iso3166 = $this->getTextFileContents();
		$lines = explode('\n',$iso3166);
		$output = '';
		foreach($lines as $line) {
			$line = trim($line);
			if (substr($line,0,1) != '#') {
				$output.=$line.PHP_EOL;
			}
		}
		return $output;
	}
	
	public function setISO3166($iso3166)
	{
		$this->textFileContents = $iso3166;
		
		return $this;
	}
	
	protected function getTextFileContents()
	{
		if ($this->textFileContents == null) {
			$filepath = dirname(__FILE__).'/res/ISO-3166-CODE-LIST-'.$this->language.'.txt';
			if (!is_file($filepath)) throw new Exception('ISO-3166-CODE-LIST file not found.');
			$this->textFileContents = file_get_contents($filepath);
		}
		if (strlen($this->textFileContents) == 0) throw new Exception('Requested ISO-3166-CODE-LIST file is empty.');
		return $this->textFileContents;
	}
	
	protected function correctCountryName($name, $language)
	{
		if ($language == 'EN') {
			$name = str_replace(' Of',' of',$name);
			$name = str_replace(' U.s.',' U.S.',$name);
		}
		
		// We want the character after an opening bracket '(' to be upper case ...
		$s = strpos($name,'(');
		if ($s !== false) {
			$name[($s+1)] = strtoupper($name[($s+1)]);
		}
		
		return $name;
	}
	

}
