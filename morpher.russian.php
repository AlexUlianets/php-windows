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
        case "И":
        case "im":
            $result = $data->GetNominative();
            break;
        case "Р":
        case "rod":
            $result = $data->GetGenitive();
            break;
        case "Д":
        case "dat":
            $result = $data->GetDative();
            break;
        case "В":
        case "vin":
            $result = $data->GetAccusative();
            break;
        case "Т":
        case "tvor":
            $result = $data->GetInstrumental();
            break;
        case "П":
        case "predl":
            $result = $data->GetPrepositional();
            break;
        case "П_о":
        case "predl-o":
            $result = $data->GetPrepositionalO();
            break;
        case "М":
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
        static $rub = array("российский рубль", "копейка", true, true);
        static $usd = array("доллар США", "цент", true, true);
        static $usd_short = array("долл США", "цент", false, true);
        static $usd_short_dot = array("долл. США", "цент", false, true);
        static $eur = array("евро", "цент", true, true);
        static $kzt = array("казахский тенге", "тиын", true, true);
        static $byr = array("белорусский рубль", "", true, false);
        static $byn = array("белорусский рубль", "копейка", true, true);
        static $uah = array("украинская гривна", "копейка", true, true);

        static $ruble = array("рубль", "копейка", true, true);
        static $ruble_short = array("руб", "коп", false, false);
        static $ruble_short_dot = array("руб.", "коп.", false, false);
        static $dollar = array("доллар США", "цент", true, true);
        static $tenge = array("тенге", "тиын", true, true);
        static $tng = array("тнг", "тиын", false, true);
        static $tng_dot = array("тнг.", "тиын", false, false);
        static $grivna = array("гривна", "копейка", true, true);
        static $grn = array("грн", "коп", false, false);
        static $grn_dot = array("грн.", "коп.", false, false);

        $ccys = array(
          "RUB" => $rub,
          "RUR" => $rub,
          "руб" => $ruble_short,
          "руб." => $ruble_short_dot,
          "рубль" => $ruble,
          "рубли" => $ruble,
          "российский рубль" => $rub,
          "российские рубли" => $rub,

          "USD" => $usd,
          "долл" => $usd_short,
          "долл." => $usd_short_dot,
          "доллар" => $dollar,
          "доллары" => $dollar,
          "долл США" => $usd_short,
          "долл. США" => $usd_short_dot,
          "доллар США" => $usd,
          "доллары США" => $usd,

          "EUR" => $eur,
          "евро" => $eur,

          "KZT" => $kzt,
          "тнг" => $tng,
          "тнг." => $tng_dot,
          "тенге" => $tenge,
          "казахский тенге" => $kzt,
          "казахские тенге" => $kzt,

          "UAH" => $uah,
          "грн" => $grn,
          "грн." => $grn_dot,
          "гривна" => $grivna,
          "гривны" => $grivna,

          "BYR" => $byr,
          "BYN" => $byn,
          "бел. руб" => $byn,
          "бел. руб." => $byn,
          "белорусский рубль" => $byn,
          "белорусские рубли" => $byn,
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
        case "И":
        case "im":
            $padegidx = 0;
            break;
        case "Р":
        case "rod":
            $padegidx = 1;
            break;
        case "Д":
        case "dat":
            $padegidx = 2;
            break;
        case "В":
        case "vin":
            $padegidx = 3;
            break;
        case "Т":
        case "tvor":
            $padegidx = 4;
            break;
        case "П":
        case "predl":
            $padegidx = 5;
            break;
        //case "П_о":
        //case "predl-o":
        //    $padegidx = 6;
        //    break;
        case "М":
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