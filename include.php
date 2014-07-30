<?

use Bitrix\Main\Loader;

Loader::includeModule('iblock');

$arClasses=array(
    'cMainRPL'=>'classes/general/cMainRPL.php'
);

CModule::AddAutoloadClasses("citfact.replaceurl",$arClasses);
Class CCitfactReplaceurl
{
    function OnBuildGlobalMenu(&$aGlobalMenu, &$aModuleMenu){

    }
}
?>
