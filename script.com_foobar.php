<?php
/**
 * This is a sample script file to show how you can install TCPDF library with your component.
 * The folder tree of your component should be something like that:
 * 		- backend
 * 		- frontend
 * 		- zzz_tcpdf  (remember to add zzz to the folder, or due a Joomla bug you couldn't install your own extension!)
 *
 * Inside the zzz_tcpdf folder, put the entire uncompressed library: make sure that you include the tcpdf.xml manifest file
 * Simply modify this script file to fullfill your needs and your done!
 *
 * I hope this helps
 */
defined('_JEXEC') or die();

class Com_foobarInstallerScript
{
	public function postflight($type, $parent)
	{
		$tcpdfStatus = $this->_installTCPDF($parent);
	}

	private function _installTCPDF($parent)
	{
		$src = $parent->getParent()->getPath('source');

		// Install the TCPDF library
		jimport('joomla.filesystem.folder');
		jimport('joomla.filesystem.file');
		jimport('joomla.utilities.date');
		$source = $src.'/zzz_tcpdf';
		if(!defined('JPATH_LIBRARIES')) {
			$target = JPATH_ROOT.'/libraries/tcpdf';
		} else {
			$target = JPATH_LIBRARIES.'/tcpdf';
		}

		$installer = new JInstaller;
		$installedTCPDF = $installer->install($source);

		$extension = JTable::getInstance('extension');
		$tcpdf = $extension->find(array('element' => 'tcpdf'));

		$params = json_decode($tcpdf->manifest_cache, true);

		$tcpdfVersion = array(
				'version'	=> $params['version'],
				'date'		=> new JDate($params['creationdate'])
			);

		return array(
			'required'	=> true,
			'installed'	=> $installedTCPDF,
			'version'	=> $tcpdfVersion['version'],
			'date'		=> $tcpdfVersion['date']->toFormat('%Y-%m-%d'),
		);
	}
}