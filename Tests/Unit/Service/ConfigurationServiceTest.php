<?php
namespace FluidTYPO3\Fluidcontent\Tests\Unit\Service;

/*
 * This file is part of the FluidTYPO3/Fluidcontent project under GPLv2 or later.
 *
 * For the full copyright and license information, please read the
 * LICENSE.md file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Tests\UnitTestCase;

/**
 * Class ConfigurationServiceTest
 */
class ConfigurationServiceTest extends UnitTestCase {

	/**
	 * @param string $input
	 * @param string $expected
	 * @test
	 * @dataProvider getSanitizeStringTestValues
	 */
	public function testSanitizeString($input, $expected) {
		$service = $this->getMock('FluidTYPO3\\Fluidcontent\\Service\\ConfigurationService', array(), array(), '', FALSE);
		$result = $this->callInaccessibleMethod($service, 'sanitizeString', $input);
		$this->assertEquals($expected, $result);
	}

	/**
	 * @return array
	 */
	public function getSanitizeStringTestValues() {
		return array(
			array('foo bar', 'foo-bar')
		);
	}

}
