<?php
namespace FluidTYPO3\Fluidcontent\Tests\Unit\Service;

/*
 * This file is part of the FluidTYPO3/Fluidcontent project under GPLv2 or later.
 *
 * For the full copyright and license information, please read the
 * LICENSE.md file that was distributed with this source code.
 */

use FluidTYPO3\Flux\Core;
use FluidTYPO3\Flux\Form;
use TYPO3\CMS\Core\Database\PreparedStatement;
use TYPO3\CMS\Core\Tests\UnitTestCase;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class ConfigurationServiceTest
 */
class ConfigurationServiceTest extends UnitTestCase {

	public function testGetContentConfiguration() {
		Core::registerProviderExtensionKey('FluidTYPO3.Fluidcontent', 'Content');
		$service = $this->getMock('FluidTYPO3\\Fluidcontent\\Service\\ConfigurationService', array('dummy'), array(), '', FALSE);
		$service->injectConfigurationManager(GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager')
			->get('TYPO3\\CMS\\Extbase\\Configuration\\ConfigurationManagerInterface'));
		$result = $service->getContentConfiguration();
	}

	public function testWriteCachedConfigurationIfMissing() {
		$GLOBALS['TYPO3_DB'] = $this->getMock(
			'TYPO3\\CMS\\Core\\Database\\DatabaseConnection',
			array('prepare_SELECTquery'),
			array(), '', FALSE
		);
		$preparedStatementMock = $this->getMock(
			'TYPO3\\CMS\\Core\\Database\\PreparedStatement',
			array('execute', 'fetch', 'free'),
			array(), '', FALSE
		);
		$preparedStatementMock->expects($this->any())->method('execute')->willReturn(FALSE);
		$preparedStatementMock->expects($this->any())->method('free');
		$preparedStatementMock->expects($this->any())->method('fetch')->willReturn(FALSE);;
		$GLOBALS['TYPO3_DB']->expects($this->any())->method('prepare_SELECTquery')->willReturn($preparedStatementMock);
		$service = $this->getMock('FluidTYPO3\\Fluidcontent\\Service\\ConfigurationService', array('dummy'), array(), '', FALSE);
		$service->injectConfigurationManager(GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager')
			->get('TYPO3\\CMS\\Extbase\\Configuration\\ConfigurationManagerInterface'));
		$service->injectCacheManager(GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager')
			->get('TYPO3\\CMS\\Core\\Cache\\CacheManager'));
		$service->injectRecordService(GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager')
			->get('FluidTYPO3\\Flux\\Service\\WorkspacesAwareRecordService'));
		$service->writeCachedConfigurationIfMissing();
	}

	public function testBuildAllWizardTabsPageTsConfig() {
		$tabs = array(
			'tab1' => array(
				'title' => 'Tab 1',
				'key' => 'tab1',
				'elements' => array(
					'a,b,c'
				)
			),
			'tab2' => array(
				'title' => 'Tab 2',
				'key' => 'tab2',
				'elements' => array(
					'a,b,c'
				)
			)
		);
		$service = $this->getMock('FluidTYPO3\\Fluidcontent\\Service\\ConfigurationService', array(), array(), '', FALSE);
		$result = $this->callInaccessibleMethod($service, 'buildAllWizardTabsPageTsConfig', $tabs);
		foreach ($tabs as $tabId => $tab) {
			$this->assertContains($tabId, $result);
			$this->assertContains($tab['title'], $result);
			$this->assertContains($tab['key'], $result);
		}
	}

	public function testRenderWizardTabItem() {
		$form = Form::create();
		$form->setLabel('bazlabel');
		$form->setDescription('foobar');
		$service = $this->getMock('FluidTYPO3\\Fluidcontent\\Service\\ConfigurationService', array(), array(), '', FALSE);
		$result = $this->callInaccessibleMethod($service, 'buildWizardTabItem', 'tabid', 'id', $form, '');
		$this->assertContains('tabid.elements.id', $result);
		$this->assertContains('title = bazlabel', $result);
		$this->assertContains('description = foobar', $result);
	}

	/**
	 * @test
	 * @dataProvider getSanitizeStringTestValues
	 * @param string $input
	 * @param string $expected
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
