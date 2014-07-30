<?php

/*
 * This file is part of the Studio Fact package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class citfact_replaceurl extends CModule
{
    /**
     * @var string
     */
    public $MODULE_ID = 'citfact.replaceurl';

    /**
     * @var string
     */
    public $MODULE_VERSION;

    /**
     * @var string
     */
    public $MODULE_VERSION_DATE;

    /**
     * @var string
     */
    public $MODULE_NAME;

    /**
     * @var string
     */
    public $MODULE_DESCRIPTION;

    /**
     * @var string
     */
    public $PARTNER_NAME;

    /**
     * @var string
     */
    public $PARTNER_URI;

    /**
     * @var string
     */
    public $MODULE_PATH;

    /**
     * Construct object
     */
    public function __construct()
	{
		$this->MODULE_NAME = Loc::getMessage("CITFACT_REPLACEURL_MODULE_NAME");
		$this->MODULE_DESCRIPTION = Loc::getMessage("CITFACT_REPLACEURL_MODULE_DESC");
		$this->PARTNER_NAME = Loc::getMessage("CITFACT_REPLACEURL_PARTNER_NAME");
		$this->PARTNER_URI = Loc::getMessage("CITFACT_REPLACEURL_PARTNER_URI");

        $arModuleVersion = array();
        include $this->MODULE_PATH . "/install/version.php";

        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
	}

    /**
     * Return path module
     *
     * @return string
     */
    protected function getModulePath()
    {
        $modulePath = explode('/', __FILE__);
        $modulePath = array_slice($modulePath, 0, array_search($this->MODULE_ID, $modulePath) + 1);

        return join('/', $modulePath);
    }

    /**
     * Return components path for install
     *
     * @param bool $absolute
     * @return string
     */
    protected function getComponentsPath($absolute = true)
    {
        $documentRoot = getenv('DOCUMENT_ROOT');
        if (strpos($this->MODULE_PATH, 'local/modules') !== false) {
            $componentsPath = '/local/components';
        } else {
            $componentsPath = '/bitrix/components';
        }

        if ($absolute) {
            $componentsPath = sprintf('%s%s', $documentRoot, $componentsPath);
        }

        return $componentsPath;
    }

    /**
     * Add tables to the database
     *
     * @return bool
     */
    public function InstallDB()
	{
		return true;
	}

    /**
     * Remove tables from the database
     *
     * @return bool
     */
    public function UnInstallDB()
	{
		return true;
	}

    /**
     * Add post events
     *
     * @return bool
     */
    public function InstallEvents()
	{
		return true;
	}

    /**
     * Delete post events
     *
     * @return bool
     */
    public function UnInstallEvents()
	{
		return true;
	}

    /**
     * Remove files module
     *
     * @return bool
     */
    public function InstallFiles()
	{
        CopyDirFiles($this->MODULE_PATH . '/install/components', $this->getComponentsPath(), true, true);

		return true;
	}

    /**
     * Remove files module
     *
     * @return bool
     */
    public function UnInstallFiles()
	{
        DeleteDirFilesEx($this->getComponentsPath(false) . '/citfact/citfact.replaceurl');
        if (!glob($this->getComponentsPath() . '/citfact/*')) {
        @rmdir($this->getComponentsPath() . '/citfact/');
        }

		return true;
	}

    /**
     * Install module
     *
     * @return void
     */
    public function DoInstall()
	{
		$this->InstallFiles();
		$this->InstallDB();
		RegisterModule($this->MODULE_ID);
   }


    /**
     * Remove module
     *
     * @return void
     */
    public function DoUninstall()
	{
		UnRegisterModule($this->MODULE_ID);
		$this->UnInstallDB();
		$this->UnInstallFiles();
	}
}

