<?php

use MODX\Revolution\modExtraManagerController;
use xPDO\xPDO;

/**
 * Base controller for showing ExtraBuilder
 * 
 */
class ExtraBuilderIndexManagerController extends modExtraManagerController
{

	/** @var ExtraBuilder\ExtraBuilder $eb */
    public $eb;

	/**
	 * This version relies on smarty replacement heavily to avoid using
	 * static script files. Scripts are included through smarty templates
	 * within <script> tags.
	 * 
	 * Use smarty replacement by setting placholder values in the process() 
	 * function. All placeholder variables will be available in the template.
	 * Placeholders are defined for our model within our main class file.
	 * 
	 * The template returned can then include other template files, loop
	 * through placeholder arrays, etc. as needed.
	 * 
	 */
    public function initialize()
    {
        // Get our service class
		$this->eb = $this->modx->services->has('ExtraBuilder') ? $this->modx->services->get('ExtraBuilder') : "";
		if (!$this->eb) {
			return $this->failure("Unable to load ExtraBuilder Service Class.");
		}

		// Return true or parent::initialize() which also just returns true
		return parent::initialize();
    }

	/**
     * Do any page-specific logic and/or processing here.
	 * 
	 * Any properties returned are then set as smarty placeholders
	 * to be used in fetch template calls.
     *
     * @param array $scriptProperties A array of REQUEST parameters.
     *
     * @return mixed Either an error or output string, or an array of placeholders to set.
     */
    public function process(array $scriptProperties = [])
    {
		// Check for the loadapp parameter
        $loadApp = isset($_GET['loadapp']) ? $_GET['loadapp'] : '';
		$map = [
			'package-builder' => 'PackageBuilder',
			'transport-builder' => 'TransportBuilder'
		];
		
		// Define placeholders to be used for smarty replacement
		$placeholders = [
			"jsPrefix" => "EB",
			"config" => $this->eb->config,
			"model"	=> $this->eb->model,
			"loadApp" => $map[$loadApp]
		];

		// Merge request properties into placeholders
		$placeholders = array_merge($placeholders, $scriptProperties);

		return $placeholders;
    }

    /**
     * The pagetitle to put in the <title> attribute.
     * @return null|string
     */
    public function getPageTitle() { return "Extrabuilder"; }

	public function checkPermissions() { return true;}

	public function getTemplateFile() { return 'home.tpl'; }

	/**
     * Get an array of possible paths to this controller's template's directory.
     * Override this to point to a custom directory.
     *
     * @param bool $coreOnly Ensure that it grabs the path from the core namespace only.
     *
     * @return array|string
     */
    public function getTemplatesPaths($coreOnly = false)
	{
		/* extras */
        if (!empty($this->config['namespace']) && $this->config['namespace'] != 'core' && !$coreOnly) {
            $namespacePath = $this->config['namespace_path'];
			$paths[] = $this->eb->config['templatesPath'];
			$paths[] = $this->eb->config['templatesPath'].'js/grids/';
			$paths[] = $this->eb->config['templatesPath'].'html/';
			$paths[] = $this->eb->config['templatesPath'].'css/';
            $paths[] = $namespacePath . 'templates/' . $this->theme . '/';
            $paths[] = $namespacePath . 'templates/default/';
            $paths[] = $namespacePath . 'templates/';
        }
        $managerPath = $this->modx->getOption('manager_path', null, MODX_MANAGER_PATH);
        $paths[] = $managerPath . 'templates/' . $this->theme . '/';
        $paths[] = $managerPath . 'templates/default/';
        $paths = array_unique($paths);

        return $paths;
	}

	/**
     * Specify an array of language topics to load for this controller
     *
     * @return array
     */
    public function getLanguageTopics()
    {
        return ['ExtraBuilder:default'];
    }
}
