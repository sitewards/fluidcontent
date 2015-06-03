<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_content', array(
        'tx_fed_fcefile' => array (
		'displayCond' => 'FIELD:CType:=:fluidcontent_content',
                'exclude' => 1,
                'label' => 'LLL:EXT:fluidcontent/Resources/Private/Language/locallang.xml:tt_content.tx_fed_fcefile',
                'config' => array (
                        'type' => 'user',
                        'userFunc' => TRUE === version_compare(TYPO3_version, '7.1', '<')
                                ? 'FluidTYPO3\Fluidcontent\Backend\LegacyContentSelector->renderField'
                                : 'FluidTYPO3\Fluidcontent\Backend\ContentSelector->renderField',
                )
        ),
));

$GLOBALS['TCA']['tt_content']['types']['fluidcontent_content']['showitem'] = $GLOBALS['TCA']['tt_content']['types']['text']['showitem'];
$GLOBALS['TCA']['tt_content']['types']['fluidcontent_content']['showitem'] = str_replace('bodytext;LLL:EXT:cms/locallang_ttc.xml:bodytext_formlabel;;richtext:rte_transform[flag=rte_enabled|mode=ts_css],', '', $GLOBALS['TCA']['tt_content']['types']['fluidcontent_content']['showitem']);
$GLOBALS['TCA']['tt_content']['types']['fluidcontent_content']['showitem'] = str_replace('rte_enabled;LLL:EXT:cms/locallang_ttc.xml:rte_enabled_formlabel,', '', $GLOBALS['TCA']['tt_content']['types']['fluidcontent_content']['showitem']);

$GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['fluidcontent_content'] = 'apps-pagetree-root';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette('tt_content', 'general', 'tx_fed_fcefile', 'after:CType');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('tt_content', 'pi_flexform', 'fluidcontent_content', 'after:header');

