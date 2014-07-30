<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Loader;

Loader::includeModule('iblock');

Loader::registerAutoLoadClasses('citfact.form', array(
    'Citfact\Replaceurl\Detail' => 'lib/Detail.php',
    'Citfact\Replaceurl\Section' => 'lib/Section.php',
));

