# Fluidcontent Change log

4.2.2 - 2015-05-20
------------------

- Default template added, which is used in case Fluid Content type is not specified
  - [Technical deatils](https://github.com/FluidTYPO3/fluidcontent/commit/763fbb612e95038391d178e33295c2829623f738)

4.2.1 - 2015-03-19
------------------

- No important changes

4.2.0 - 2015-03-18
------------------

- :exclamation: Legacy TYPO3 support removed and dependencies updated
  - TYPO3 6.2 is minimum required
  - TYPO3 7.1 is supported
  - Flux 7.2 is minimum required
  - ClassAliasMap removed - switch to the proper vendor and namespace

- :exclamation: Legacy support for TS registration removed
  - `plugin.tx_fluidcontent.collections.` support removed
  - `plugin.tx_fed.fce.` support removed
  - [Source commit with more info](https://github.com/FluidTYPO3/fluidcontent/commit/0cd6448ebdcb3bdcc82103d5f22eb4d30b475767)

- [#213](https://github.com/FluidTYPO3/fluidcontent/pull/213) Possible to use *'templateRootPaths'* (plural) option from TYPO3 6.2 to overload template paths
  - `plugin.tx_yourext.view.templateRootPaths` syntax is supported
  - *'templateRootPath'* (singular) and *'overlays'* are deprecated
  - [FluidTYPO3/flux#758](https://github.com/FluidTYPO3/flux/pull/758) - source feature

- [#191](https://github.com/FluidTYPO3/fluidcontent/pull/191) Template icon can be autoloaded, based on name convention
  - Template *EXT:extensionKey/Resources/Private/Templates/$controller/$templateName.html* loads an icon from *EXT:extensionKey/Resources/Public/Icons/$controller/$templateName.(png|gif)*
  - Icon can be set manually via option attribute as before
  - [#208](https://github.com/FluidTYPO3/fluidcontent/pull/208) Icon appears at content type select
  - [FluidTYPO3/flux#687](https://github.com/FluidTYPO3/flux/pull/687) - source feature