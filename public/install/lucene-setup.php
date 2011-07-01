<!DOCTYPE html>
<html>
<head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
      <title>ZeroGravity CMS Setup</title>
	<link type="text/css" href="../css/spav-theme/jquery-ui-1.8.1.custom.css" rel="Stylesheet" />
	<script type="text/javascript" src="../js/jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="../js/jquery-ui-1.8.1.custom.min.js"></script>
	<script type="text/javascript">
	<!--
	$(function() {
		$("button, input:submit, a").button();
	});
	//-->
	</script>
	
	<style>
	html, form {
		height: 100%;
		width: 100%;
	}
	
	body{
		font-family: Verdana,Arial,sans-serif;
		font-size: 16px;
		height: 100%;
		width: 100%;
		background-color: #3c64a2;
		margin: 0px;
		color: #a0bfef;
	}
	.searchbox{
		margin: 20px auto;
		width: 300px;
		font-size: 24px;
		height: 36px;
	}
	.header{
		font-family: Tahoma,Verdana,Arial,sans-serif;
		font-size: 36px;
		font-weight: bold;
		height: 85px;
		width: 100%;
		line-height: 85px;
		padding-left: 30px;
	}
	.hr_light{
	  	background-color: #a0bfef;
	  	height: 10px;
	}
	.darkbox{
	  	background-color: #3a4556;
	  	min-height: 70%;
		padding: 20px;
	}
	.footer{
		height: 20px;
	}
	.container{
		margin-left:auto;
		margin-right:auto;
		width:800px;
	}
	.controls{
		text-align:center;
		margin-top: 40px;
	}
	#progressbar{
		width: 680px;
		margin: 20px auto 5px;
	}
	#progressdesc{
		font-weight: bold;
		width: 680px;
		margin: 0px auto 40px auto;
	}
	</style>
</head>
<body>
	<div class="header">Zero9ravity CMS Setup</div>
	<div class="hr_light"></div>
	<div class="darkbox">
		<div class="container">
			<div id="progressbar"></div>

<?php

/**
 * Script for creating and loading database etc
 */

// Initialize the application path and autoloading
define('APPLICATION_ENV', 'development');
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../../cms'));
set_include_path(implode(PATH_SEPARATOR, array(
    APPLICATION_PATH . '/../library',
    get_include_path(),
)));
require_once 'Zend/Loader/Autoloader.php';
Zend_Loader_Autoloader::getInstance();

//Initialize Zend_Application
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);
createLucene($application);

function createLucene()
{
	// products index
	echo 'Creating products index files...<br/>';
	$index = Zend_Search_Lucene::create( APPLICATION_PATH . '/../data/index-products' );
	echo 'Index created<br/>';

	// categories index
	echo 'Creating categories index files...<br/>';
	$index = Zend_Search_Lucene::create( APPLICATION_PATH . '/../data/index-categories' );
	echo 'Index created<br/>';
}

function testLucene()
{
	$index = Zend_Search_Lucene::open( APPLICATION_PATH . '/../data/index-products' );

	if( isset($_GET['search']) )
	{
		echo 'Wyniki:<br/>';
		$hits = $index->find( $_GET['search'] );
		foreach ($hits as $hit)
		{
			echo 'Score: ' .$hit->score . ' Url: ' . $hit->url . '<br/>';
		}
	}
	else
	{
		echo 'Creating 300 fake records...<br/>';
		flush();

		// fake data
		for($i = 0; $i < 100; $i++)
		{
			$doc = new Zend_Search_Lucene_Document();
			$doc->addField( Zend_Search_Lucene_Field::Text('url', '/test'.$i) );
			$doc->addField( Zend_Search_Lucene_Field::UnStored('contents', 'test wyszukiwarki lucene '.$i) );
			$index->addDocument($doc);

			$doc = new Zend_Search_Lucene_Document();
			$doc->addField( Zend_Search_Lucene_Field::Text('url', '/test'.$i) );
			$doc->addField( Zend_Search_Lucene_Field::UnStored('contents', 'drugi dokument testowy lucene '.$i) );
			$index->addDocument($doc);

			$doc = new Zend_Search_Lucene_Document();
			$doc->addField( Zend_Search_Lucene_Field::Text('url', '/test'.$i) );
			$doc->addField( Zend_Search_Lucene_Field::UnStored('contents', 'trzeci dokumencik w wyszukiwarce '.$i) );
			$index->addDocument($doc);
		}

		echo '$index->count(): ' . $index->count() . '<br/>';
		echo '$index->numDocs(): ' . $index->numDocs() . '<br/>';
	}

	echo '<form method="get" style="text-align:center">
		<input type="hidden" name="action" value="test"/>
		Type <input class="searchbox" type="text" name="search"/> and <input type="submit" value="Search"/>
	</form>';
}
?>
		</div>
	</div>
	<div class="hr_light"></div>
	<div class="footer"></div>
</body>
</html>