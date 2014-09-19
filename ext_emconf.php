<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "fluidcontent".
 *
 * Auto generated 19-09-2014 13:13
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Fluid Content Engine',
	'description' => 'Fluid Content Element engine - integrates extremely compact and highly dynamic content element templates written in Fluid. See: https://github.com/FluidTYPO3/fluidcontent',
	'category' => 'misc',
	'author' => 'FluidTYPO3 Team',
	'author_email' => 'claus@namelesscoder.net',
	'author_company' => '',
	'shy' => '',
	'dependencies' => 'cms,flux',
	'conflicts' => '',
	'priority' => '',
	'module' => '',
	'state' => 'stable',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 1,
	'lockType' => '',
	'version' => '4.1.0',
	'constraints' => array(
		'depends' => array(
			'typo3' => '6.1.0-6.2.99',
			'cms' => '',
			'flux' => '7.1.0-7.1.99',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'suggests' => array(
	),
	'_md5_values_when_last_written' => 'a:29:{s:10:"bower.json";s:4:"c1b4";s:20:"class.ext_update.php";s:4:"c82f";s:13:"composer.json";s:4:"e249";s:12:"ext_icon.gif";s:4:"68b4";s:17:"ext_localconf.php";s:4:"d428";s:14:"ext_tables.php";s:4:"4712";s:14:"ext_tables.sql";s:4:"3b19";s:10:"LICENSE.md";s:4:"c813";s:9:"README.md";s:4:"a0c5";s:22:"Build/ImportSchema.sql";s:4:"0f6b";s:28:"Build/LocalConfiguration.php";s:4:"98b1";s:23:"Build/PackageStates.php";s:4:"57b5";s:35:"Classes/Backend/ContentSelector.php";s:4:"6e7a";s:48:"Classes/Controller/AbstractContentController.php";s:4:"3bd5";s:40:"Classes/Controller/ContentController.php";s:4:"3e7c";s:49:"Classes/Controller/ContentControllerInterface.php";s:4:"359e";s:43:"Classes/Hooks/WizardItemsHookSubscriber.php";s:4:"e157";s:36:"Classes/Provider/ContentProvider.php";s:4:"7153";s:40:"Classes/Service/ConfigurationService.php";s:4:"0b04";s:34:"Configuration/TypoScript/setup.txt";s:4:"53ca";s:33:"Documentation/ComplexityChart.png";s:4:"638f";s:30:"Documentation/PyramidChart.png";s:4:"d565";s:33:"Migrations/Code/ClassAliasMap.php";s:4:"4bd4";s:40:"Resources/Private/Language/locallang.xlf";s:4:"86bc";s:38:"Resources/Private/Layouts/Content.html";s:4:"da94";s:34:"Resources/Private/Layouts/FCE.html";s:4:"5a0f";s:46:"Resources/Private/Templates/Content/Error.html";s:4:"d41d";s:47:"Resources/Private/Templates/Content/Render.html";s:4:"d41d";s:33:"Resources/Public/Icons/Plugin.png";s:4:"50ed";}',
);

?>