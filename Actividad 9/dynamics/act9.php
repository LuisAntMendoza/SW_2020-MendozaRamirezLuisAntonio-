<?php
//validamos variables
$cifrar = (isset($_POST['mensaje']) && $_POST['mensaje'] != "") ? $_POST['mensaje'] : "";
$cifrado = (isset($_POST['cifrado']) && $_POST['cifrado'] != "") ? $_POST['cifrado'] : "";
$clave = (isset($_POST['clave']) && $_POST['clave'] != "") ? $_POST['clave'] : "";
//funcion para cifrar el mensaje
function Cifrar ($mensaje) {
    //determinamos su longitud
    $long = strlen($mensaje);
    $cifrado;
    //transformamos las letras a su valor ASCII
    while($mensaje != "") {
        $bit[] = ord($mensaje);
        $mensaje = substr($mensaje, 1);
    }
    $clave = "";
    //Hacemos una operacion segun salga el numero aleatorio y lo agregamos a la clave
    for($i=0;$i<$long;$i++) {
        $a = rand(1,3);
        $clave = $clave.$a;
        if($a == 1) {
            $cifrado[] = ($bit[$i]+$long);
        }
        elseif($a == 2) {
            $cifrado[] = ($bit[$i] * $long);
        }
        elseif($a == 3) {
            $cifrado[] = (pow($bit[$i],2));
        }
    }
    //le añadimos un "." para separar
    $clave = $clave.".";
    //determina cuantos campos se va a mover a la derecha
    $move = rand(0,count($cifrado));
    //lo añadimos a la clave
    $clave = $clave.$move.".";
    $NuevoBit = [];
    //movemos las letras la cantidad indicada
    for ($i=0; $i < count($cifrado); $i++) {
        if($i+$move >= count($cifrado)) {
            $dif = $i+$move - count($cifrado);
            $NuevoBit[$dif] = $cifrado[$i];
        }
        else {
            $NuevoBit[$i+$move] = $cifrado[$i];
        }
    }
    //transformamos esas letras a binario
    for($i=0; $i<count($NuevoBit); $i++) {
        $NuevoBit[$i] = decbin($NuevoBit[$i]);
    }
    //multiplicamos ese valor por un numero aleatorio
    for($i=0; $i<count($NuevoBit); $i++) {
        $a = rand(1,9);
        $clave = $clave.$a;
        $NuevoBit[$i] = $NuevoBit[$i] * $a;
    }
    //transformamos el resultado a hexadecimal
    for($i=0; $i<count($NuevoBit); $i++) {
        $NuevoBit[$i] = bin2hex($NuevoBit[$i]);
    }
    $mCifrado[0] = "";
    //concatenamos todo a un solo string
    for ($i=0; $i<count($NuevoBit); $i++) {
        $mCifrado[0] = $mCifrado[0].".".$NuevoBit[$i];
    }
    //añadimos la longitud a la clave
    $clave = $clave.",".$long;
    $mCifrado[0] = substr($mCifrado[0], 1);
    $mCifrado[] = $clave;
    //regresamos el mensaje cifrado junto con su clave
    return $mCifrado;
}

//funcion para decifrar
function Decifrar ($cifrado,$clave) {
    $error = "";
    //separamos el mensaje en un array
    $mensaje = explode(".",$cifrado);
    $posLong = strpos($clave, ",");
    //convertimos el mensaje a binario
    for($i=0;$i<count($mensaje);$i++) {
        $mensaje[$i] = hex2bin($mensaje[$i]);
    }
    $posDividir = strrpos($clave, ".");
    $posDividir++;
    $dividir = substr($clave, $posDividir, $posLong-$posDividir);
    //validamos el formato de la clave para que mande error
    if(strlen($dividir) != count($mensaje)) {
        $error = "Error";
    }
    else {
        //dividimos los valores entre el numero de la clave
        for($i=0;$i<count($mensaje);$i++) {
            $mensaje[$i] = $mensaje[$i] / substr($dividir, $i, 1);
        }
        //pasamos esos numeros a decimal
        for($i=0;$i<count($mensaje);$i++) {
            $mensaje[$i] = bindec($mensaje[$i]);
        }
        $posMove = strpos($clave,".");
        $posMove++;
        //validamos el formato de la clave para que mande error
        if($posMove == $posDividir) {
            $error = "Error";
        }
        else {
            $longMove = ($posDividir - 1) - $posMove;
            $move = substr($clave, $posMove, $longMove);
            //validamos el formato de la clave para que mande error
            if(gettype($move) != "integer") {
                $error = "Error";
            }
            else {
                //movemos el mensaje a la izquierda el numero de veces en la clave
                for ($i=0; $i < count($mensaje); $i++) {
                    if($i-$move < 0) {
                        $dif = count($mensaje) + $i - $move;
                        $NuevoMensaje[$dif] = $mensaje[$i];
                    }
                    else {
                        $NuevoMensaje[$i-$move] = $mensaje[$i];
                    }
                }
                $long = substr($clave, $posLong+1);
                $accion = substr($clave, 0, $posMove-1);
                //validamos el formato de la clave para que mande error
                if(strlen($accion) != count($NuevoMensaje)) {
                    $error = "Error";
                }
                else {
                    //hacemos la operacion segun el caso que venga en la clave
                    for($i=0;$i<count($NuevoMensaje);$i++) {
                        $a = substr($accion, $i, 1);
                        if($a == 1) {
                            $NuevoMensaje[$i] = ($NuevoMensaje[$i]-$long);
                        }
                        elseif($a == 2) {
                            $NuevoMensaje[$i] = ($NuevoMensaje[$i] / $long);
                        }
                        elseif($a == 3) {
                            $NuevoMensaje[$i] = pow($NuevoMensaje[$i],1/2);
                        }
                        //validamos el formato de la clave para que mande error
                        else {
                            $error = "Error";
                        }
                    }
                    $decifrado = "";
                    //pasamos el valor de ASCII a su caracter correspondiente
                    for($i=0;$i<count($NuevoMensaje);$i++) {
                        $NuevoMensaje[$i] = chr($NuevoMensaje[$i]);
                        $decifrado = $decifrado.$NuevoMensaje[$i];
                    }
                }
            }
        }
    }
    if($error != "") {
        $decifrado = "Ha ocurrido un error al tratar de decifrar su mensaje";
    }
    //regresamos ya sea un error o el mensaje decifrado
    return $decifrado;
}

//formulario para cifrar mensaje
echo '  <h3>Cifrar mensaje</h3>
        <form action="act9.php" method="post">
            <fieldset>
                <label>Ingrese su mensaje: </label>
                <input type="text" name="mensaje">
                <br>
                <input type="submit" value="Cifrar">
            </fieldset>
        </form>
        ';

//muestra el mensaje cifrado
if($cifrar != "") {
    $cifrar = Cifrar($cifrar);
    echo '  <p>Su mensaje cifrado es: '.$cifrar[0].'</p>
            <p>Su clave es: '.$cifrar[1].'
    ';
}

//formulario para decifrar mensajes
echo '  <h3>Decifrar mensaje</h3>
        <form action="act9.php" method="post">
            <fieldset>
                <label>Ingrese su mensaje cifrado: </label>
                <input type="text" name="cifrado">
                <br>
                <label>Ingrese su clave: </label>
                <input type="text" name="clave">
                <br>
                <input type="submit" value="Decifrar">
            </fieldset>
        </form>
        ';

//muestra el mensaje decifrado
if($cifrado != "") {
    $cifrado = Decifrar($cifrado,$clave);
    echo '  <p>Su mensaje decifrado es: '.$cifrado.'</p>
    ';
}

?>
