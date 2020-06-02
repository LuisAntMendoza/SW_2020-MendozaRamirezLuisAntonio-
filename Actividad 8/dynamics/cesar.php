<?php
$mensaje = (isset($_POST['mensaje']) && $_POST['mensaje'] != "") ? $_POST['mensaje'] : "";
$move = (isset($_POST['mover']) && $_POST['mover'] != "") ? $_POST['mover'] : "";

$ABC = ["a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z"];
$move += 1;
$NuevoABC = "";
for ($i=0; $i < count($ABC); $i++) {
    if($i+$move > count($ABC)) {
        $dif = $i+$move - count($ABC);
        $NuevoABC[$dif] = $ABC[$i];
    }
    else {
        $NuevoABC[$i+$move] = $ABC[$i];
    }
}
$NuevoABC = str_ireplace(" ", "", $NuevoABC);
if(!preg_match("/^[\w,?. ]+$/", $mensaje)) {
    echo "Error al ingresar sus datos";
}
else {
    if($mensaje == "" || $move == "") {
        echo "Error al ingresar sus datos";
    }
    else {
        for ($i=0; $i < 26; $i++) {
            $mensaje = str_ireplace($ABC[$i], $NuevoABC[$i], $mensaje);
        }
    }
}
echo "Su mensaje codificado es: ".strtoupper($mensaje);



 ?>
