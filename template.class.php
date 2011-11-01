<?php
/**
 * Template.class.php
 * 
 * @Autor : Nico Vogt
 * @Projekt : Browsergame
 */
class Template
{
   	/**
	 * Der Ordner in dem sich die Template-Dateien befinden.
	 *
	 * @access public
	 * @var    string
	 */
	protected $templateDir = "templates/";
	
	/**
	 * Der Ordner in dem sich die Sprach-Dateien befinden
	 *
	 * @access public
	 * @var    string
	 */
	protected $languageDir = "language/";
	
	/**
	 * Der linke Delimter f�r einen Standard-Platzhalter
	 *
	 * @access public
	 * @var    string
	 */
	protected $leftDelimiter = '{$';
	
	/**
	 * Der rechte Delimter f�r einen Standard-Platzhalter
	 *
	 * @access public
	 * @var    string
	 */
	protected $rightDelimiter = '}';

	/**
	 * Der linke Delimter f�r eine Funktion
	 *
	 * @access public
	 * @var    string
	 */
	protected $leftDelimiterF = '{';
	
	/**
	 * Der rechte Delimter f�r eine Funktion
	 *
	 * @access public
	 * @var    string
	 */
	protected $rightDelimiterF = '}';

	/**
	 * Der linke Delimter f�r ein Kommentar
	 * Sonderzeichen m�ssen escaped werden, weil der Delimter in einem RegExp
	 * verwendet wird.
	 *
	 * @access public
	 * @var    string
	 */
	protected $leftDelimiterC = '\{\*';
	
	/**
	 * Der rechte Delimter f�r ein Kommentar
	 * Sonderzeichen m�ssen escaped werden, weil der Delimter in einem RegExp
	 * verwendet wird.
	 *
	 * @access public
	 * @var    string
	 */
	protected $rightDelimiterC = '\*\}';
	
	/**
	 * Der linke Delimter f�r eine Sprachvariable
	 * Sonderzeichen m�ssen escaped werden, weil der Delimter in einem RegExp
	 * verwendet wird.
	 *
	 * @access public
	 * @var    string
	 */
	protected $leftDelimiterL = '\{L_';
	
	/**
	 * Der rechte Delimter f�r eine Sprachvariable
	 * Sonderzeichen m�ssen escaped werden, weil der Delimter in einem RegExp
	 * verwendet wird.
	 *
	 * @access public
	 * @var    string
	 */
	protected $rightDelimiterL = '\}';
	

	/**
	 * Der komplette Pfad der Templatedatei.
	 *
	 * @access protected
	 * @var    string
	 */
	protected $templateFile = "";
	
	/**
	 * Der komplette Pfad der Sprachdatei.
	 *
	 * @access protected
	 * @var    string
	 */
	protected $languageFile = "";
	
	/**
	 * Der Dateiname der Templatedatei
	 *
	 * @access protected
	 * @var    string
	 */
	protected $templateName = "";
	
	/**
	 * Der Inhalt des Templates.
	 *
	 * @access protected
	 * @var    string
	 */
	protected $template = "";

	
	/**
	 * Ein paar Eigenschaften ihre Werte zuweisen
	 *
	 * @access    public
	 * @return    boolean
	 */
	public function template($tpl_dir = "", $lang_dir = "") 
	{
		// Template Ordner �ndern
		if (!empty($tpl_dir)) {
			$this->templateDir = $tpl_dir;
		}

		// Language Ordner �ndern
		if (!empty($lang_dir)) {
			$this->languageDir = $lang_dir;
		}
		
		return true;
	}

	
	/**
	 * Die Templatedatei �ffnen
	 *
	 * @access    public
	 * @param     string $file Dateiname des Templates
	 * @return    boolean
	 */
	public function load($file)
	{
	    $this->templateName = $file;
		$this->templateFile = $this->templateDir.$file;

		if(!empty($this->templateFile)) {
    		if($fp = @fopen($this->templateFile, "r")) {
    		    // Den Inhalt des Templates einlesen
    			$this->template = fread($fp, filesize($this->templateFile)); 
    			fclose ($fp); 
    		} else {
    		    return false;
    		}
		}

		$this->replaceFunctions();
		
		return true;
	}


	/**
	 * Die Standard-Platzhalter ersetzen
	 *
	 * @access    public
	 * @param     string $replace      Name von var der ersetzt werden soll
	 * @param     string $replacement  Welcher text dem var zugewiesen wird
	 * @return    boolean
	 */
	public function assign($replace, $replacement)
	{
		$this->template = str_replace($this->leftDelimiter.$replace.$this->rightDelimiter, $replacement, $this->template);
		return  true;
	}

	
	/**
	 * Die Sprachdateien �ffnen
	 *
	 * @access    public
	 * @param     array $files  Dateinamen der Sprachdateien
	 * @return    boolean
	 */
	public function loadLanguage($files)
	{
	    // Die Dateinamen der Sprachdateien
		$this->languageFiles = $files;
		
		for ($i=0;$i<count($this->languageFiles);$i++) {
		    if (!file_exists($this->languageDir.$this->languageFiles[$i])) {
		        return false;
		    }
			include($this->languageDir.$this->languageFiles[$i]);
		}
		$this->replaceLanguage($lang);
		
		// $lang zur�ckgeben, damit $lang auch im PHP-Code verwendet werden kann
		return $lang;
	}

	/**
	 * Ersetze language vars mi text
	 *
	 * @param string $lang
	 */
	function replaceLanguage($lang)
	{
		$this->template = preg_replace("/\{L_(.*)\}/isUe", "\$lang[strtolower('\\1')]", $this->template);

	}

	/**
	 * Die Funktionen ersetzen
	 *
	 * @access    protected
	 * @return    boolean
	 */
	protected function replaceFunctions()
	{
	    // Includes ersetzen ( {include file="..."} )
		while(preg_match("/".$this->leftDelimiterF."include file=\"(.*)\.(.*)\"".$this->rightDelimiterF."/isUe", $this->template)) {
			$this->template = preg_replace("/".$this->leftDelimiterF."include file=\"(.*)\.(.*)\"".$this->rightDelimiterF."/isUe", "file_get_contents(\$this->templateDir.'\\1'.'.'.'\\2')", $this->template);
		}

	
		// Kommentare l�schen
		$this->template = preg_replace("/".$this->leftDelimiterC."(.*)".$this->rightDelimiterC."/isUe", "", $this->template);
		
		return  true;
	}
	
      
   	/**
	 * Das fertige Template ausgeben
	 *
	 * @access    public
	 * @return    boolean
	 */
	public function out()
	{
        echo $this->template;
        return true;
	}
}
?>