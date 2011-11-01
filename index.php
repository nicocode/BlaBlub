<?php
// Das Templatesystem einbinden
include("template.class.php");
include("include/session.php");
include("include/constants.php");

$tpl = new Template();
$page = $_GET['page'];

if(isset($page)) 
	{
		if(file_exists($page.'.php'))
		{
			include($page.'.php');
			$tpl->load($page.'.tpl');
			$tpl->assign("content", showlogin());
			$tpl->assign("title", "Willkommen");
			$tpl->out();
		} else {
			$tpl->load('error.tpl');
			$tpl->assign("pagename", $page);
			$tpl->out();
		}
	} else {
		$tpl->load('index.tpl');
		$tpl->assign("title", "Startseite");
		$tpl->out();
	}
?>