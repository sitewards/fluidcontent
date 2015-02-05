<?php
namespace FluidTYPO3\Fluidcontent\Tests\Unit\Provider;

/*
 * This file is part of the FluidTYPO3/Fluidcontent project under GPLv2 or later.
 *
 * For the full copyright and license information, please read the
 * LICENSE.md file that was distributed with this source code.
 */

use FluidTYPO3\Flux\Form\Container\Grid;
use FluidTYPO3\Flux\Provider\Provider;
use TYPO3\CMS\Backend\Controller\ContentElement\NewContentElementController;
use TYPO3\CMS\Core\Tests\UnitTestCase;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class WizardItemsHookSubscriberTest
 */
class WizardItemsHookSubscriberTest extends UnitTestCase {

	public function testCreatesInstance() {
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
		$instance = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager')
			->get('FluidTYPO3\\Fluidcontent\\Hooks\\WizardItemsHookSubscriber');
		$this->assertInstanceOf('FluidTYPO3\\Fluidcontent\\Hooks\\WizardItemsHookSubscriber', $instance);
	}

	public function testManipulateWizardItemsCallsExpectedMethodSequenceWithoutProviders() {
		$instance = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager')
			->get('FluidTYPO3\\Fluidcontent\\Hooks\\WizardItemsHookSubscriber');
		$configurationService = $this->getMock(
			'FluidTYPO3\\Fluidcontent\\Service\\ConfigurationService',
			array('writeCachedConfigurationIfMissing', 'resolveConfigurationProviders')
		);
		$recordService = $this->getMock(
			'FluidTYPO3\\Flux\\Service\\WorkspacesAwareRecordService',
			array('getSingle')
		);
		$configurationService->expects($this->once())->method('writeCachedConfigurationIfMissing');
		$configurationService->expects($this->once())->method('resolveConfigurationProviders')->willReturn(array());
		$recordService->expects($this->once())->method('getSingle')->willReturn(NULL);
		$instance->injectConfigurationService($configurationService);
		$instance->injectRecordService($recordService);
		$parent = new NewContentElementController();
		$items = array();
		$instance->manipulateWizardItems($items, $parent);
	}

	public function testManipulateWizardItemsCallsExpectedMethodSequenceWithProvidersWithColPosWithoutRelativeElement() {
		$instance = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager')
			->get('FluidTYPO3\\Fluidcontent\\Hooks\\WizardItemsHookSubscriber');
		$configurationService = $this->getMock(
			'FluidTYPO3\\Fluidcontent\\Service\\ConfigurationService',
			array('writeCachedConfigurationIfMissing', 'resolveConfigurationProviders')
		);
		$recordService = $this->getMock(
			'FluidTYPO3\\Flux\\Service\\WorkspacesAwareRecordService',
			array('getSingle')
		);
		$record = array('uid' => 0);
		$provider1 = $this->getMockProvider($record);
		$provider2 = $this->getMockProvider($record);
		$provider3 = $this->getMockProvider($record, FALSE);
		$configurationService->expects($this->once())->method('writeCachedConfigurationIfMissing');
		$configurationService->expects($this->once())->method('resolveConfigurationProviders')->willReturn(array(
			$provider1, $provider2, $provider3
		));
		$recordService->expects($this->once())->method('getSingle')->willReturn($record);
		$instance->injectConfigurationService($configurationService);
		$instance->injectRecordService($recordService);
		$parent = new NewContentElementController();
		$parent->colPos = 1;
		$items = array();
		$instance->manipulateWizardItems($items, $parent);
	}

	/**
	 * @param array $record
	 * @param boolean $withGrid
	 * @return Provider
	 */
	protected function getMockProvider(array $record, $withGrid = TRUE) {
		$instance = $this->getMock('FluidTYPO3\\Flux\\Provider\\Provider', array('getViewVariables', 'getGrid'));
		if (FALSE === $withGrid) {
			$instance->expects($this->any())->method('getGrid')->willReturn($grid);
		} else {
			$grid = Grid::create();
			$grid->createContainer('Row', 'row')->createContainer('Column', 'column')->setColumnPosition(1)
				->setVariable('Fluidcontent', array('deniedContentTypes' => 'html', 'allowedContentTypes' => 'text'));
			$instance->expects($this->any())->method('getGrid')->willReturn($grid);
		}
		return $instance;
	}

}
