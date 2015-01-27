<?php
namespace FluidTYPO3\Fluidcontent\Tests\Unit\Backend;

/*
 * This file is part of the FluidTYPO3/Fluidcontent project under GPLv2 or later.
 *
 * For the full copyright and license information, please read the
 * LICENSE.md file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Tests\UnitTestCase;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class ContentSelectorTest
 */
class ContentSelectorTest extends UnitTestCase {

	public function testCreatesInstance() {
		$instance = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager')
			->get('FluidTYPO3\\Fluidcontent\\Backend\\ContentSelector');
		$this->assertInstanceOf('FluidTYPO3\\Fluidcontent\\Backend\\ContentSelector', $instance);
	}

}
