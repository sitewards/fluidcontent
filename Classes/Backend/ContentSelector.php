<?php
namespace FluidTYPO3\Fluidcontent\Backend;

/*
 * This file is part of the FluidTYPO3/Fluidcontent project under GPLv2 or later.
 *
 * For the full copyright and license information, please read the
 * LICENSE.md file that was distributed with this source code.
 */

use FluidTYPO3\Fluidcontent\Service\ConfigurationService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class that renders a selection field for Fluid FCE template selection
 *
 * @package	Fluidcontent
 * @subpackage Backend
 */
class ContentSelector {

	/**
	 * Render a Flexible Content Element type selection field
	 *
	 * @param array $parameters
	 * @param mixed $parentObject
	 * @return string
	 */
	public function renderField(array &$parameters, &$parentObject) {
		/** @var ConfigurationService $contentService */
		$contentService = GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Object\ObjectManager')->get('FluidTYPO3\Fluidcontent\Service\ConfigurationService');
		$setup = $contentService->getContentElementFormInstances();
		$name = $parameters['itemFormElName'];
		$value = $parameters['itemFormElValue'];
		$select = '<div><select name="' . htmlspecialchars($name) . '"  class="formField select" onchange="if (confirm(TBE_EDITOR.labels.onChangeAlert) && TBE_EDITOR.checkSubmit(-1)){ TBE_EDITOR.submitForm() };">' . LF;
		$select .= '<option value="">' . $GLOBALS['LANG']->sL('LLL:EXT:fluidcontent/Resources/Private/Language/locallang.xml:tt_content.tx_fed_fcefile', TRUE) . '</option>' . LF;
		foreach ($setup as $groupLabel => $configuration) {
			$select .= '<optgroup label="' . htmlspecialchars($groupLabel) . '">' . LF;
			foreach ($configuration as $form) {
				$optionValue = $form->getOption('contentElementId');
				$selected = ($optionValue === $value ? ' selected="selected"' : '');
				$label = $form->getLabel();
				$label = $GLOBALS['LANG']->sL($label);
				$select .= '<option value="' . htmlspecialchars($optionValue) . '"' . $selected . '>' . htmlspecialchars($label) . '</option>' . LF;
			}
			$select .= '</optgroup>' . LF;
		}
		$select .= '</select></div>' . LF;
		unset($parentObject);
		return $select;
	}

}
