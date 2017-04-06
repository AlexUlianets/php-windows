<?php

function MorpherRussianDeclension() {
    //static $declension;
    //if($declension === null)
        //$declension = new DOTNET("Morpher", "Slepov.Russian.Morpher.COM.MorpherRussian");
        $declension = new COM("Morpher.Russian");
    return $declension;
} 

function morpher_get_gender($phrase)
{
    $declension = MorpherRussianDeclension();
    $genders = array('m', 'f', 'n', 'p');
    $data = $declension->DeclensionParse($phrase);
    if($data == null)
        throw new Exception("Passed text " . $phrase . " cannot be parsed"); 
    $genderidx = $data->GetGender();
    return $genders[$genderidx];
}

function morpher_inflect($phrase, $padeg)
{
    $declension = MorpherRussianDeclension();
    $params = explode(" ", $padeg);
   
    $data = $declension->DeclensionParse($phrase);
    if($data == null)
        throw new Exception("Passed text " . $phrase . " cannot be parsed");

    if(count($params) > 1 && $params[1] == "mn")
        $data = $data->GetPlural();
        if($data == null)
            throw new Exception("Passed text " . $phrase . " is plural");

    $result = null;
    switch($params[0])
    {
        case "�":
        case "im":
            $result = $data->GetNominative();
            break;
        case "�":
        case "rod":
            $result = $data->GetGenitive();
            break;
        case "�":
        case "dat":
            $result = $data->GetDative();
            break;
        case "�":
        case "vin":
            $result = $data->GetAccusative();
            break;
        case "�":
        case "tvor":
            $result = $data->GetInstrumental();
            break;
        case "�":
        case "predl":
            $result = $data->GetPrepositional();
            break;
        case "�_�":
        case "predl-o":
            $result = $data->GetPrepositionalO();
            break;
        case "�":
        case "gde":
            $result = $data->GetLocative();
            break;
        default:
            throw new Exception("Unknown padeg " . $params[0] . "passed");
    }
    return $result;   
}


function CCYS()
{
    static $ccys;
    if($ccys == null)
    {  
        static $rub = array("���������� �����", "�������", true, true);
        static $usd = array("������ ���", "����", true, true);
        static $usd_short = array("���� ���", "����", false, true);
        static $usd_short_dot = array("����. ���", "����", false, true);
        static $eur = array("����", "����", true, true);
        static $kzt = array("��������� �����", "����", true, true);
        static $byr = array("����������� �����", "", true, false);
        static $byn = array("����������� �����", "�������", true, true);
        static $uah = array("���������� ������", "�������", true, true);

        static $ruble = array("�����", "�������", true, true);
        static $ruble_short = array("���", "���", false, false);
        static $ruble_short_dot = array("���.", "���.", false, false);
        static $dollar = array("������ ���", "����", true, true);
        static $tenge = array("�����", "����", true, true);
        static $tng = array("���", "����", false, true);
        static $tng_dot = array("���.", "����", false, false);
        static $grivna = array("������", "�������", true, true);
        static $grn = array("���", "���", false, false);
        static $grn_dot = array("���.", "���.", false, false);

        $ccys = array(
          "RUB" => $rub,
          "RUR" => $rub,
          "���" => $ruble_short,
          "���." => $ruble_short_dot,
          "�����" => $ruble,
          "�����" => $ruble,
          "���������� �����" => $rub,
          "���������� �����" => $rub,

          "USD" => $usd,
          "����" => $usd_short,
          "����." => $usd_short_dot,
          "������" => $dollar,
          "�������" => $dollar,
          "���� ���" => $usd_short,
          "����. ���" => $usd_short_dot,
          "������ ���" => $usd,
          "������� ���" => $usd,

          "EUR" => $eur,
          "����" => $eur,

          "KZT" => $kzt,
          "���" => $tng,
          "���." => $tng_dot,
          "�����" => $tenge,
          "��������� �����" => $kzt,
          "��������� �����" => $kzt,

          "UAH" => $uah,
          "���" => $grn,
          "���." => $grn_dot,
          "������" => $grivna,
          "������" => $grivna,

          "BYR" => $byr,
          "BYN" => $byn,
          "���. ���" => $byn,
          "���. ���." => $byn,
          "����������� �����" => $byn,
          "����������� �����" => $byn,
       );
    }
    return $ccys;
}

function morpher_spell($number, $unit, $padeg = "im")
{
    if($unit == "")
        throw new Exception("$unit should not be empty");

    $num = $number;

    if(is_string($num))
    {
        $num = floatval(str_replace(',', '.', $num));
        if($num == 0)
            throw new Exception("$number is not a number");
    }
    else if(!is_numeric($num))
    {
        throw new Exception("$number is not a number");
    }


    $declension = MorpherRussianDeclension();

    $padegidx = 0;

    switch($padeg)
    {
        case "�":
        case "im":
            $padegidx = 0;
            break;
        case "�":
        case "rod":
            $padegidx = 1;
            break;
        case "�":
        case "dat":
            $padegidx = 2;
            break;
        case "�":
        case "vin":
            $padegidx = 3;
            break;
        case "�":
        case "tvor":
            $padegidx = 4;
            break;
        case "�":
        case "predl":
            $padegidx = 5;
            break;
        //case "�_�":
        //case "predl-o":
        //    $padegidx = 6;
        //    break;
        case "�":
        case "gde":
            $padegidx = 7;
            break;
        default:
           throw new Exception("Unknown padeg " . $padeg . "passed");
    }
    $ccys = CCYS();
    $result = null;
    if(array_key_exists($unit, $ccys))
    {
        $ccy = $ccys[$unit];
        $data1 = $declension->SpellNumber(intval($num), $ccy[0], $padegidx);
        $decimal = $num * 100 % 100;
        $data2 = $declension->SpellNumber($decimal, $ccy[1], $padegidx);
        
        $result = ucfirst($data1->GetNumber()) . " " . ($ccy[2] ? $data1->GetUnit() : $ccy[0]) . " " 
            . ($decimal == 0 ? "00" : $decimal) . " " . ($ccy[3] ? $data2->GetUnit() : $ccy[1]);
    }
    else
    {
        $data = $declension->SpellNumber($num, $unit, $padegidx);
        $result = $number . " (" . ucfirst($data->GetNumber()) . ") " . $data->GetUnit();
    }
   
    return $result;
}

?>