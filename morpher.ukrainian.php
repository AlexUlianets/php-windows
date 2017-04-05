<?php

function MorpherUkrainianDeclension() {
    //static $declension;
    //if($declension === null)
        $declension = new COM("Morpher.Ukrainian");
    return $declension;
} 

function morpher_ukr_get_gender($phrase)
{
    $declension = MorpherUkrainianDeclension();
    $genders = array('m', 'f', 'n', 'p');
    $data = $declension->DeclensionParse($phrase);
    if($data == null)
        throw new Exception("Passed text " . $phrase . " cannot be parsed"); 
    $genderidx = $data->GetGender();
    return $genders[$genderidx];
}

function morpher_ukr_inflect($phrase, $padeg)
{
    $declension = MorpherUkrainianDeclension();
    $params = explode(" ", $padeg);
   
    $data = $declension->DeclensionParse($phrase);
    if($data == null)
        throw new Exception("Passed text " . $phrase . " cannot be parsed");

    $result = null;
    switch($padeg)
    {
        case "�":
        case "naz":
            $result = $data->GetNominative();
            break;
        case "�":
        case "rod":
            $result = $data->GetGenitive();
            break;
        case "�":
        case "dav":
            $result = $data->GetDative();
            break;
        case "�":
        case "zna":
            $result = $data->GetAccusative();
            break;
        case "�":
        case "oru":
            $result = $data->GetInstrumental();
            break;
        case "�":
        case "mis":
            $result = $data->GetPrepositional();
            break;
        case "�":
        case "kly":
            $result = $data->GetVocative();
            break;
        default:
            throw new Exception("Unknown padeg " . $params[0] . "passed");
    }
    return $result;   

}

?>