<?php
class cMainRPL {
    static $MODULE_ID="citfact.replaceurl";

    /**
     * Хэндлер, отслеживающий изменения в инфоблоках
     * @param $arFields
     * @return bool
     */
    static function onBeforeElementAddHandler($arFields){
        // чтение параметров модуля
        // $iblock_id = ;
        // Активная деятельность
        $IBLOCK_ID = COption::GetOptionString("citfact.replaceurl", "IBLOCK_ID", "");
        $SECTION_ID = COption::GetOptionString("citfact.replaceurl", "SECTION_BREND_ID", "BRENDI");
        $CODE_PROP = COption::GetOptionString("citfact.replaceurl", "PROPERTY_CODE_BRENDI", "BRENDI");

        if($IBLOCK_ID == $arFields["IBLOCK_ID"]){


            $propertyBrand = CIBlockProperty::GetByID($CODE_PROP, $arFields["IBLOCK_ID"]);
            if($arResultProperty = $propertyBrand->GetNext())

            $propertyBrandValue = CIBlockPropertyEnum::GetByID($arFields["PROPERTY_VALUES"][$arResultProperty["ID"]][0]["VALUE"]);

            $rqParentSection = CIBlockSection::GetByID($SECTION_ID);
            if ($arParentSection = $rqParentSection->GetNext())
            {
                $arFilter = array('IBLOCK_ID' => $arParentSection['IBLOCK_ID'],'>LEFT_MARGIN' => $arParentSection['LEFT_MARGIN'],'<RIGHT_MARGIN' => $arParentSection['RIGHT_MARGIN'],'>DEPTH_LEVEL' => $arParentSection['DEPTH_LEVEL']); // выберет потомков без учета активности
                $rqSectChild = CIBlockSection::GetList(array('left_margin' => 'asc'),$arFilter);
                while ($arSectChild = $rqSectChild->GetNext())
                {
                    $arSections[$arSectChild["NAME"]] = $arSectChild["ID"];
                }
            }

            if(array_key_exists($propertyBrandValue["VALUE"],$arSections)){
                $arFields["IBLOCK_SECTION"][] = $arSections[$propertyBrandValue["VALUE"]];
            }else{
                $arParams = array("replace_space"=>"-","replace_other"=>"-");
                $trans = Cutil::translit($propertyBrandValue["VALUE"],"ru",$arParams);
                $bs = new CIBlockSection;
                $arFieldsNewSection = Array(
                    "ACTIVE" => "Y",
                    "IBLOCK_SECTION_ID" => $SECTION_ID,
                    "IBLOCK_ID" => $IBLOCK_ID,
                    "NAME" => $propertyBrandValue["VALUE"],
                    "CODE" => $trans,
                );
                $resultAdd = $bs->Add($arFieldsNewSection);
                $arFields["IBLOCK_SECTION"][] = $resultAdd;
            }
        }
        return true;
    }
}