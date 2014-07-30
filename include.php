<?

use Bitrix\Main\Loader;

Loader::includeModule('iblock');

$arClasses=array(
    'cMainRPL'=>'classes/general/cMainRPL.php'
);

CModule::AddAutoloadClasses("citfact.replaceurl",$arClasses);
?>
