<?php
//validamos las variables que recibimos
$nombre = (isset($_POST['Nombre']) && $_POST['Nombre'] !=  "") ? $_POST['Nombre'] : "";
$apPat = (isset($_POST['apPat']) && $_POST['apPat'] !=  "") ? $_POST['apPat'] : "";
$apMat = (isset($_POST['apMat']) && $_POST['apMat'] !=  "") ? $_POST['apMat'] : "";
$fNac = (isset($_POST['fNac']) && $_POST['fNac'] !=  "") ? $_POST['fNac'] : "";
$RFC = (isset($_POST['RFC']) && $_POST['RFC'] !=  "") ? $_POST['RFC'] : "";
$colegio = (isset($_POST['Colegio']) && $_POST['Colegio'] !=  "") ? $_POST['Colegio'] : "";
$contraseña = (isset($_POST['Contraseña']) && $_POST['Contraseña'] !=  "") ? $_POST['Contraseña'] : "";
$RFCbase = "";

//validamos con regex que sea el formato que solicitamos
if(!preg_match("/(^[A-Z][a-zñÑáéíóúÁÉÍÓÚ]+$)|(^[A-Z][a-zñÑáéíóúÁÉÍÓÚ]+[ ][A-Z][a-zñÑáéíóúÁÉÍÓÚ]+$)/", $nombre) || $nombre == "") {
    echo "Dato inválido: Nombre<br>";
}
if(!preg_match("/(^[A-Z][a-zñÑáéíóúÁÉÍÓÚ]+$)/", $apPat) || $apPat == "") {
    echo "Dato inválido: Apellido Paterno<br>";
}
if(!preg_match("/(^[A-Z][a-zñÑáéíóúÁÉÍÓÚ]+$)/", $apMat) || $apMat == "") {
    echo "Dato inválido: Apellido Materno<br>";
}
if(!preg_match("/^\d{4}\-\d{2}\-\d{2}$/", $fNac) || $fNac == "") {
    echo "Dato inválido: Fecha de Nacimiento<br>";
}
if(!preg_match("/^[A-Z]{4}[0-9]{6}[0-9A-Z]{3}$/", $RFC) || $RFC == "") {
    echo "Dato inválido: RFC<br>";
}
if(!preg_match("/^[\wáéíóúñ, ]{5,32}$/", $colegio) || $colegio == "") {
    echo "Dato inválido: Colegio<br>";
}
if(!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!-+])([A-Za-z\d!-+]|[^ ]){10,20}$/", $contraseña) || $contraseña == "") {
    echo "Dato inválido: Contraseña<br>";
}

//validamos que el formato del RFC coincida con los datos ingresados
if($RFC != "") {
    $RFCbase = strtoupper(substr($apPat, 0, 2));
    $RFCbase = $RFCbase.substr($apMat,0,1);
    $RFCbase = $RFCbase.substr($nombre,0,1);
    $RFCbase = $RFCbase.substr($fNac,2,2);
    $RFCbase = $RFCbase.substr($fNac,5,2);
    $RFCbase = $RFCbase.substr($fNac,8,2);
    if(substr($RFC,0,10) == $RFCbase) {
        echo "Todo OK";
    }
    else {
        echo "Su RFC no coincide con los datos ingresados";
    }
}
?>
