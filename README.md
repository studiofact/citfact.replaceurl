citfact.replaceurl
==========

����������������� ��� ��� �����

##����������� ������:

 - ���������� URL'��.
 - ��������� ������ ����� ������� � ������� ��������� ����������.
 - �������� � ����� ��� (������� ������ � ���������� ���������).
 - ����������� ������������ ��� ��� ����: #SECTION_CODE#/#ELEMENT_ID# ��� #SECTION_CODE_PATH#/#ELEMENT_ID# (��. � ���������� ���������� $itemsCode).
 - �������������� �������� �������� � ���������� ������.
 
##��������� ������:

�������� ����� citfact.replaceurl � /bitrix/modules/
����� � ���������������� ������ � ������� "Marketplace > ������������� �������" ������������� ������.

#��������� ������:

� ���������� ������ ����������� ID ��������� � ������� ����� ����������������� URL, �������� ��������(����� ��� ��������������� �������� ��������), ���������� ��� ��������.
������� ��������� ������������� �������� �������� ��� ���������.

##���������� ���������� ������:

��������� ������ ������ ������������� � result_modifier.php ����� ����������� bitrix ���:
 - catalog.section
 - catalog.element (������������� �������� ��������� ��������� � ����������� �������)


##������ ������ ����������:
``` php
$arResultMod = $APPLICATION->IncludeComponent(
	"citfact:citfact.replaceurl",
	"",
	Array(
		"array_modifier" => $arResult, //�������� ������ $arResult � ������� �� ����� ��������� URL
	),
false
); 
$arResult = $arResultMod;
```
##������ ���������� ���������:

���� ��� ������� /brand/ /catalog_section/. � ���� ���� �������� �������� ������� "�����" � ���������� ����� "tovar_example". 
��-�� �������� ������� ����� URL: /brand/tovar_example/ � /catalog_section/tovar_example/, ��������� �������� ����� �� �� ��������, ������� ������� � �������� "������� ������"