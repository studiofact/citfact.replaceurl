<h1>Настройка модуля</h1>

<?
$module_id = "citfact.replaceurl";
IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/options.php");
$RIGHT = $APPLICATION->GetGroupRight($module_id);
if($RIGHT >= "R") :
///// Читаем данные и формируем для вывода
$arAllOptions = Array(
	array("IBLOCK_ID","Индентификатор инфоблока","text",""),
	array("PROPERTY_NAME","Название свойства","text","Главный раздел"),
    array("PROPERTY_CODE", "Символьный код свойства","text", "MAIN_SECTION"),
	array("ADD_PROPERTY","Добавить новое свойство для инфоблока","checkbox","")
);

$aTabs = array(
    array("DIV" => "edit1", "TAB" => GetMessage("MAIN_TAB_SET"), "ICON" => "perfmon_settings", "TITLE" => GetMessage("MAIN_TAB_TITLE_SET")),
    array("DIV" => "edit2", "TAB" => GetMessage("MAIN_TAB_RIGHTS"), "ICON" => "perfmon_settings", "TITLE" => GetMessage("MAIN_TAB_TITLE_RIGHTS")),
);
$tabControl = new CAdminTabControl("tabControl", $aTabs);
$arNotes = array();
CModule::IncludeModule($module_id);

if($REQUEST_METHOD=="POST" && strlen($Update.$Apply.$RestoreDefaults) > 0 && $RIGHT=="W" && check_bitrix_sessid())
{
    require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/perfmon/prolog.php");

    if(strlen($RestoreDefaults)>0){
        COption::RemoveOption($module_id,"IBLOCK_ID");
		COption::RemoveOption($module_id,"PROPERTY_NAME");
		COption::RemoveOption($module_id,"PROPERTY_CODE");
		COption::RemoveOption($module_id,"ADD_PROPERTY");
	}
    else
    {
        foreach($arAllOptions as $arOption)
        {
			if($arOption[2] != "checkbox"){
				$name=$arOption[0];
				$val=$_REQUEST[$name];
				if(!empty($val)){
					COption::SetOptionString($module_id, $name, $val);
				}else{
					$arNotes[] = "Поле не может быть пустым";
				}
			}
        }
	}

	if($_REQUEST["ADD_PROPERTY"] == "Y"){
		if(CModule::IncludeModule('iblock')){
			$IBLOCK_ID = $_REQUEST["IBLOCK_ID"];
			$chek_uniq_req = CIBlockProperty::GetList(Array(), Array("ACTIVE"=>"Y", "IBLOCK_ID"=>$IBLOCK_ID,"CODE"=>$_REQUEST["PROPERTY_CODE"]));
			
			if (!$chek_uniq = $chek_uniq_req->GetNext()){
			
				$property_add = array(
					"NAME" => $_REQUEST["PROPERTY_NAME"],
					"ACTIVE" => "Y",
					"IS_REQUIRED" =>"N",
					"MULTIPLE"=>"N",
					"MULTIPLE_CNT"=>14,
					"SORT" => "500",
					"CODE" => $_REQUEST["PROPERTY_CODE"],
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
	}

    ob_start();
    $Update = $Update.$Apply;
    require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/admin/group_rights.php");
    ob_end_clean();
}

?>
<form method="post" action="<?echo $APPLICATION->GetCurPage()?>?mid=<?=urlencode($module_id)?>&amp;lang=<?=LANGUAGE_ID?>">
    <?
    $tabControl->Begin();
    $tabControl->BeginNextTab();

    foreach($arAllOptions as $arOption):
        $val = COption::GetOptionString($module_id, $arOption[0], $arOption[3]);
        $type = $arOption[2];
        if(isset($arOption[4]))
            $arNotes[] = $arOption[4];
        ?>
        <tr>
            <td width="40%" nowrap <?if($type[0]=="textarea") echo 'class="adm-detail-valign-top"'?>>
                <?if(isset($arOption[4])):?>
                    <span class="required"><sup><?echo count($arNotes)?></sup></span>
                <?endif;?>
                <label for="<?echo htmlspecialcharsbx($arOption[0])?>"><?echo $arOption[1]?>:</label>
            <td width="60%">
                <?if($type=="checkbox"):?>
                    <input type="checkbox" name="<?echo htmlspecialcharsbx($arOption[0])?>" id="<?echo htmlspecialcharsbx($arOption[0])?>" value="Y">
                <?elseif($type=="text"):?>
                    <input type="text" size="<?echo $type[1]?>" maxlength="255" value="<?echo htmlspecialcharsbx($val)?>" name="<?echo htmlspecialcharsbx($arOption[0])?>" id="<?echo htmlspecialcharsbx($arOption[0])?>"><?if($arOption[0] == "slow_sql_time") echo GetMessage("PERFMON_OPTIONS_SLOW_SQL_TIME_SEC")?>
                <?elseif($type=="textarea"):?>
                    <textarea rows="<?echo $type[1]?>" cols="<?echo $type[2]?>" name="<?echo htmlspecialcharsbx($arOption[0])?>" id="<?echo htmlspecialcharsbx($arOption[0])?>"><?echo htmlspecialcharsbx($val)?></textarea>
                <?endif?>
            </td>
        </tr>
    <?endforeach?>
    <?$tabControl->BeginNextTab();?>
    <?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/admin/group_rights.php");?>
    <?$tabControl->Buttons();?>
    <input <?if ($RIGHT<"W") echo "disabled" ?> type="submit" name="Update" value="<?=GetMessage("MAIN_SAVE")?>" title="<?=GetMessage("MAIN_OPT_SAVE_TITLE")?>" class="adm-btn-save">
    <input <?if ($RIGHT<"W") echo "disabled" ?> type="submit" name="Apply" value="<?=GetMessage("MAIN_OPT_APPLY")?>" title="<?=GetMessage("MAIN_OPT_APPLY_TITLE")?>">
    <?if(strlen($_REQUEST["back_url_settings"])>0):?>
        <input <?if ($RIGHT<"W") echo "disabled" ?> type="button" name="Cancel" value="<?=GetMessage("MAIN_OPT_CANCEL")?>" title="<?=GetMessage("MAIN_OPT_CANCEL_TITLE")?>" onclick="window.location='<?echo htmlspecialcharsbx(CUtil::addslashes($_REQUEST["back_url_settings"]))?>'">
        <input type="hidden" name="back_url_settings" value="<?=htmlspecialcharsbx($_REQUEST["back_url_settings"])?>">
    <?endif?>
    <input type="submit" name="RestoreDefaults" title="<?echo GetMessage("MAIN_HINT_RESTORE_DEFAULTS")?>" OnClick="confirm('<?echo AddSlashes(GetMessage("MAIN_HINT_RESTORE_DEFAULTS_WARNING"))?>')" value="<?echo GetMessage("MAIN_RESTORE_DEFAULTS")?>">
    <?=bitrix_sessid_post();?>
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
<?endif;?>
