<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once "Class/Crest/crest.php";
require_once "Helpers/batchGetAll.php";
require_once "Helpers/CsvReader.php";
require_once "Helpers/dd.php";
require_once "Helpers/getRqDoesNotExist.php";
require_once "Helpers/updateRq.php";

$companyIdList = [];
$firstLoop     = true;

$rqData        = new CsvReader("storage/Данные для загрузки (2).csv");
foreach ($rqData->rows() as $row) {
    if(is_numeric($row[0])){
        $companyIdList[] =$row[0]; // собрали id обновляемых компаний
    }
}

$rqList   = batchGetAll("crm.requisite.list", [ // получили все существующие реквизиты по нужным компаниям
    "ENTITY_TYPE_ID"    => 4,
    "?ENTITY_ID"         => $companyIdList,
]);


$rqData        = new CsvReader("storage/Данные для загрузки (2).csv");
foreach ($rqData->rows() as $row) {
    if(is_numeric($row[0])){
        updateRqList($rqList,$row); // обновляем все существующие компании
    }
}

$newRq         = getRqDoesNotExist($rqList,$companyIdList);
$rqData        = new CsvReader("storage/Данные для загрузки (2).csv"); // каждый раз создаем новые экзепляр потому что работает через генератор

foreach ($rqData->rows() as $row) {
    if(is_numeric($row[0])){
        if(array_key_exists($row[0],$newRq)){
            CRest::call('crm.requisite.add',[ // создаем новые реквизиты
                'fields'=> [
                    "ENTITY_TYPE_ID"    => 4,
                    "ENTITY_ID"         => $row[0],
                    "PRESET_ID"         => 1,
                    "NAME"              => "Реквизит " . date('d-m-Y',time()),
                    "ACTIVE"            => "Y",
                    'TITLE'             => $row[1],
                    'RQ_INN'            => $row[2],
                    'RQ_KPP'            => $row[3],
                ]
            ]);
        }

    }
}




