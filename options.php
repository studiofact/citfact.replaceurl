<h1>Настройка модуля</h1>

<?
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Application;
use Bitrix\Main\Config;


Loc::loadMessages(__FILE__);

$app = Application::getInstance();
$request = $app->getContext()->getRequest();
$moduleId = 'citfact.replaceurl';

$allOptions = Array(
    array("IBLOCK_ID",Loc::getMessage('IBLOCK_ID'),"text",""),
    array("PROPERTY_NAME",Loc::getMessage('PROPERTY_NAME'),"text","Главный раздел"),
    array("PROPERTY_CODE",Loc::getMessage('PROPERTY_CODE'),"text", "MAIN_SECTION"),
    array("SECTION_BREND_ID",Loc::getMessage('SECTION_BREND_ID'),"text", ""),
    array("PROPERTY_CODE_BRENDI",Loc::getMessage('PROPERTY_CODE_BRENDI'),"text", "BRENDI"),
    array("ADD_PROPERTY",Loc::getMessage('ADD_PROPERTY'),"checkbox",""),
);

$controlTabs = array(
    array("DIV" => "edit1", "TAB" => Loc::getMessage('REPLACEURL_TAB'), "TITLE" => Loc::getMessage('REPLACEURL_TAB_TITLE')),
);
$tabControl = new CAdminTabControl("tabControl", $controlTabs);
$arNotes = array();


if ($request->isPost() && $request->getPost('RESTORE_DEFAULTS')){
    Config\Option::delete($moduleId);
}

if ($request->isPost() && $request->getPost('UPDATE')){
    foreach($allOptions as $option)
    {
        if($option[2] != "checkbox"){
            $value = $request->getPost($option[0]);
            if(!empty($value)){
                Config\Option::set($moduleId, $option[0], $value);
            }else{
                $arNotes[] = "Поле не может быть пустым";
            }
        }
    }
}

if ($request->isPost() && $request->getPost('ADD_PROPERTY') == 'Y'){
    CModule::IncludeModule('iblock');
    $IBLOCK_ID = $request->getPost('IBLOCK_ID');
    $chek_uniq_req = CIBlockProperty::GetList(Array(), Array("ACTIVE"=>"Y", "IBLOCK_ID"=>$IBLOCK_ID,"CODE"=>$request->getPost('PROPERTY_CODE')));

    if (!$chek_uniq = $chek_uniq_req->GetNext()){

        $property_add = array(
            "NAME" => $request->getPost('PROPERTY_NAME'),
            "ACTIVE" => "Y",
            "IS_REQUIRED" =>"N",
            "MULTIPLE"=>"N",
            "MULTIPLE_CNT"=>14,
            "SORT" => "500",
            "CODE" => $request->getPost('PROPERTY_CODE'),
            "PROPERTY_TYPE" => "G",
            "IBLOCK_ID" => $IBLOCK_ID,
            "LINK_IBLOCK_ID"=>$IBLOCK_ID,
        );
        $ibp = new CIBlockProperty;
        if(!$ibp->Add( $property_add)){
            $arNotes[]=$ibp->LAST_ERROR;
        }

    }else{
        $arNotes[]="Введите другой символьный код инфоблока";
    }
}
$tabControl->Begin();
?>
<form method="post" action="<?echo $app->getContext()->getServer()->getRequestUri()?>">
    <input type="hidden" name="mid" value="<?= $moduleId ?>">
    <input type="hidden" name="lang" value="<?= LANGUAGE_ID ?>">
    <?$tabControl->BeginNextTab();?>

    <?foreach($allOptions as $option):?>
        <?
        $val = Config\Option::get($moduleId, $option[0], $option[3]);
        $type = $option[2];
        ?>

        <tr>
            <td width="40%" nowrap <?if($type[0]=="textarea") echo 'class="adm-detail-valign-top"'?>>
                <label for="<?echo htmlspecialcharsbx($option[0])?>"><?echo $option[1]?>:</label>
            <td width="60%">
                <?if($type=="checkbox"):?>
                    <input type="checkbox" name="<?echo htmlspecialcharsbx($option[0])?>" id="<?echo htmlspecialcharsbx($option[0])?>" value="Y">
                <?elseif($type=="text"):?>
                    <input type="text" size="<?echo $type[1]?>" maxlength="255" value="<?echo htmlspecialcharsbx($val)?>" name="<?echo htmlspecialcharsbx($option[0])?>" id="<?echo htmlspecialcharsbx($option[0])?>"><?if($option[0] == "slow_sql_time") echo GetMessage("PERFMON_OPTIONS_SLOW_SQL_TIME_SEC")?>
                <?elseif($type=="textarea"):?>
                    <textarea rows="<?echo $type[1]?>" cols="<?echo $type[2]?>" name="<?echo htmlspecialcharsbx($option[0])?>" id="<?echo htmlspecialcharsbx($option[0])?>"><?echo htmlspecialcharsbx($val)?></textarea>
                <?endif?>
            </td>
        </tr>
    <?endforeach?>

    <?$tabControl->Buttons();?>
    <input type="submit" name="UPDATE" value="<?= Loc::getMessage('BTN_SAVE') ?>" class="adm-btn-save">
    <input type="submit" name="RESTORE_DEFAULTS" value="<?= Loc::getMessage("BTN_RESTORE") ?>">
    <?$tabControl->End();?>
</form>
<?
if(!empty($arNotes))
{
    echo BeginNote();
    foreach($arNotes as $i => $str)
    {
        ?><span class="required"><sup><?echo $i+1?></sup></span><?echo $str?><br><?
    }
    echo EndNote();
}
?>

