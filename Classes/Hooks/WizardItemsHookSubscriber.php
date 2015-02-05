<?php
namespace FluidTYPO3\Fluidcontent\Hooks;

/*
 * This file is part of the FluidTYPO3/Fluidcontent project under GPLv2 or later.
 *
 * For the full copyright and license information, please read the
 * LICENSE.md file that was distributed with this source code.
 */

use FluidTYPO3\Fluidcontent\Service\ConfigurationService;
use FluidTYPO3\Flux\Form\FormInterface;
use FluidTYPO3\Flux\Service\WorkspacesAwareRecordService;
use TYPO3\CMS\Backend\Controller\ContentElement\NewContentElementController;
use TYPO3\CMS\Backend\Wizard\NewContentElementWizardHookInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;

/**
 * WizardItems Hook Subscriber
 */
class WizardItemsHookSubscriber implements NewContentElementWizardHookInterface {

	/**
	 * @var ConfigurationService
	 */
	protected $configurationService;

	/**
	 * @var WorkspacesAwareRecordService
	 */
	protected $recordService;

	/**
	 * @var ObjectManagerInterface
	 */
	protected $objectManager;

	/**
	 * @param ConfigurationService $configurationService
	 * @return void
	 */
	public function injectConfigurationService(ConfigurationService $configurationService) {
		$this->configurationService = $configurationService;
	}

	/**
	 * @param WorkspacesAwareRecordService $recordService
	 * @return void
	 */
	public function injectRecordService(WorkspacesAwareRecordService $recordService) {
		$this->recordService = $recordService;
	}

	/**
	 * @param ObjectManagerInterface $objectManager
	 * @return void
	 */
	public function injectObjectManager(ObjectManagerInterface $objectManager) {
		$this->objectManager = $objectManager;
	}

	/**
	 * Constructor
	 */
	public function __construct() {
		$objectManager = GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Object\ObjectManager');
		$this->injectObjectManager($objectManager);
		$configurationService = $this->objectManager->get('FluidTYPO3\Fluidcontent\Service\ConfigurationService');
		$this->injectConfigurationService($configurationService);
		$recordService = $this->objectManager->get('FluidTYPO3\Flux\Service\WorkspacesAwareRecordService');
		$this->injectRecordService($recordService);
	}

	/**
	 * @param array $items
	 * @param NewContentElementController $parentObject
	 * @return void
	 */
	public function manipulateWizardItems(&$items, &$parentObject) {
		$this->configurationService->writeCachedConfigurationIfMissing();
		$items = $this->filterPermittedFluidContentTypesByInsertionPosition($items, $parentObject);
	}

	/**
	 * @param array $items
	 * @param NewContentElementController $parentObject
	 * @return array
	 */
	protected function filterPermittedFluidContentTypesByInsertionPosition(array $items, $parentObject) {
		list ($whitelist, $blacklist) = $this->getWhiteAndBlackListsFromPageAndContentColumn(
			$parentObject->id,
			$parentObject->colPos,
			$parentObject->uid_pid
		);
		$items = $this->applyWhitelist($items, $whitelist);
		$items = $this->applyBlacklist($items, $blacklist);
		$items = $this->cleanEmptySections($items);
		return $items;
	}

	/**
	 * @param array $items
	 * @return array
	 */
	protected function cleanEmptySections(array $items) {
		$preserveHeaders = array();
		foreach ($items as $name => $item) {
			if (FALSE !== strpos($name, '_')) {
				array_push($preserveHeaders, reset(explode('_', $name)));
			}
		}
		foreach ($items as $name => $item) {
			if (FALSE === strpos($name, '_') && FALSE === in_array($name, $preserveHeaders)) {
				unset($items[$name]);
			}
		}
		return $items;
	}

	/**
	 * @param array $items
	 * @param array $whitelist
	 * @return array
	 */
	protected function applyWhitelist(array $items, array $whitelist) {
		if (0 < count($whitelist)) {
			foreach ($items as $name => $item) {
				if (FALSE !== strpos($name, '_') && 'fluidcontent_content' === $item['tt_content_defValues']['CType'] && FALSE === in_array($item['tt_content_defValues']['tx_fed_fcefile'], $whitelist)) {
					unset($items[$name]);
				}
			}
		}
		return $items;
	}

	/**
	 * @param array $items
	 * @param array $blacklist
	 * @return array
	 */
	protected function applyBlacklist(array $items, array $blacklist) {
		if (0 < count($blacklist)) {
			foreach ($blacklist as $contentElementType) {
				foreach ($items as $name => $item) {
					if ('fluidcontent_content' === $item['tt_content_defValues']['CType'] && $item['tt_content_defValues']['tx_fed_fcefile'] === $contentElementType) {
						unset($items[$name]);
					}
				}
			}
		}
		return $items;
	}

	/**
	 * @param integer $pageUid
	 * @param integer $columnPosition
	 * @param integer $relativeUid
	 * @return array
	 */
	protected function getWhiteAndBlackListsFromPageAndContentColumn($pageUid, $columnPosition, $relativeUid) {
		$whitelist = array();
		$blacklist = array();
		// if a Provider is registered for the "pages" table, try to get a Grid from it. If the Grid
		// returned contains a Column which matches the desired colPos value, attempt to read a list
		// of allowed/denied content element types from it.
		$pageRecord = (array) $this->recordService->getSingle('pages', '*', $pageUid);
		$pageProviders = $this->configurationService->resolveConfigurationProviders('pages', NULL, $pageRecord);
		$this->appendToWhiteAndBlacklistFromProviders($pageProviders, $pageRecord, $whitelist, $blacklist, $columnPosition);
		// Detect what was clicked in order to create the new content element; decide restrictions
		// based on this.
		$defaultValues = GeneralUtility::_GET('defVals');
		if (0 > $relativeUid) {
			// pasting after another element means we should try to resolve the Flux content relation
			// from that element instead of GET parameters (clicked: "create new" icon after other element)
			$parentRecord = $this->recordService->getSingle('tt_content', '*', abs($relativeUid));
			$fluxAreaName = (string) $parentRecord['tx_flux_column'];
			$parentRecordUid = (integer) $parentRecord['tx_flux_parent'];
		} elseif (TRUE === isset($defaultValues['tt_content']['tx_flux_column'])) {
			// attempt to read the target Flux content area from GET parameters (clicked: "create new" icon
			// in top of nested Flux content area
			$fluxAreaName = (string) $defaultValues['tt_content']['tx_flux_column'];
			$parentRecordUid = (integer) $defaultValues['tt_content']['tx_flux_parent'];
		}
		// if these variables now indicate that we are inserting content elements into a Flux-enabled content
		// area inside another content element, attempt to read allowed/denied content types from the
		// Grid returned by the Provider that applies to the parent element's type and configuration
		// (admitted, that's quite a mouthful - but it's not that different from reading the values from
		// a page template like above; it's the same principle).
		if (0 < $parentRecordUid && FALSE === empty($fluxAreaName)) {
			$parentRecord = (array) $this->recordService->getSingle('tt_content', '*', $parentRecordUid);
			$contentProviders = $this->configurationService->resolveConfigurationProviders('tt_content', NULL, $parentRecord);
			$this->appendToWhiteAndBlacklistFromProviders($contentProviders, $parentRecord, $whitelist, $blacklist, NULL, $fluxAreaName);
		}
		// White/blacklist filtering. If whitelist contains elements, filter the list
		// of possible types by whitelist first. Then apply the blacklist, removing
		// any element types recorded herein.
		$whitelist = array_unique($whitelist);
		$blacklist = array_unique($blacklist);
		return array($whitelist, $blacklist);
	}

	/**
	 * @param array $providers
	 * @param array $record
	 * @param integer $columnPosition
	 * @param string $fluxAreaName
	 */
	protected function appendToWhiteAndBlacklistFromProviders(
		array $providers,
		array $record,
		array &$whitelist,
		array &$blacklist,
		$columnPosition,
		$fluxAreaName = NULL
	) {
		foreach ($providers as $provider) {
			$grid = $provider->getGrid($record);
			if (NULL === $grid) {
				continue;
			}
			foreach ($grid->getRows() as $row) {
				foreach ($row->getColumns() as $column) {
					if (FALSE === empty($fluxAreaName)) {
						if ($column->getName() === $fluxAreaName) {
							list ($whitelist, $blacklist) = $this->appendToWhiteAndBlacklistFromComponent($column, $whitelist, $blacklist);
						}
					} elseif ($column->getColumnPosition() === $columnPosition) {
						list ($whitelist, $blacklist) = $this->appendToWhiteAndBlacklistFromComponent($column, $whitelist, $blacklist);
					}
				}
			}
		}

	}

	/**
	 * @param FormInterface $component
	 * @param array $whitelist
	 * @param array $blacklist
	 * @return array
	 */
	protected function appendToWhiteAndBlacklistFromComponent(FormInterface $component, array $whitelist, array $blacklist) {
		$allowed = $component->getVariable('Fluidcontent.allowedContentTypes');
		if (NULL !== $allowed) {
			$whitelist = array_merge($whitelist, GeneralUtility::trimExplode(',', $allowed));
		}
		$denied = $component->getVariable('Fluidcontent.deniedContentTypes');
		if (NULL !== $denied) {
			$blacklist = array_merge($blacklist, GeneralUtility::trimExplode(',', $denied));
		}
		return array($whitelist, $blacklist);
	}
}
