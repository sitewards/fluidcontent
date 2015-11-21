<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('be_groups', array(
	'tx_fluidcontent_allowedfluidcontent' => array(
		'exclude' => 0,
		'label' => 'LLL:EXT:fluidcontent/Resources/Private/Language/locallang.xlf:be_groups.tx_fluidcontent_allowedfluidcontent',
		'config' => array(
			'type' => 'select',
			'renderType' => 'selectCheckBox',
			'items' => array()
		)
	),
	'tx_fluidcontent_deniedfluidcontent' => array(
		'exclude' => 0,
		'label' => 'LLL:EXT:fluidcontent/Resources/Private/Language/locallang.xlf:be_groups.tx_fluidcontent_deniedfluidcontent',
		'config' => array(
			'type' => 'select',
			'renderType' => 'selectCheckBox',
			'items' => array()
		)
	),
));

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
	'be_groups',
	'tx_fluidcontent_allowedfluidcontent,tx_fluidcontent_deniedfluidcontent',
	'0',
	'after:pagetypes_select'
);
