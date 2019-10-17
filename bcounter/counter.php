<?php
//Inicio de definicion de funciones
function get_contador($s_archivo, $s_count) {
	if (! file_exists($s_archivo)) {
		@fwrite(@fopen($s_archivo,"a"),$s_count);
	}
	// Lee archivo abierto (filesize() devuelve bytes del archivo)
	$s_content = @file_get_contents("$s_archivo");
	if ($s_content!==false) {
	    $s_num = strval($s_content);
	    if ($s_num == "99999999999999") {
		$s_num = "0";  // Resetea
	    }
	    else {
		$s_num++;  // Suma una visita (en cada recarga de pagina)
	    }
	    $s_num = strval($s_num);
	    // Habre el archivo para lectura y escritura
	    $fptr = @fopen($s_archivo,"w+");
	    @fwrite($fptr,$s_num); // Escribe en el archivo
	} else {
	    $s_num = "0001";
	}
	return $s_num; // Retorna el valor final
}

function generar_jpg ($s_nro = "0", $s_imgext) {
// Crea la dimension de la imagen y la guarda en la variable
if (strlen($s_nro) <= 5) { 
    $img_count = imagecreate(45,20);
}
elseif (strlen($s_nro) <= 10) {
    $img_count = imagecreate(85,20);
}
else {
    $img_count = imagecreate(118,20);
}
// Creamos los colores a usar
$color_blanco = imagecolorallocate($img_count,255,255,255);
$color_negro = imagecolorallocate($img_count,0,0,0);
// Asignamos el color a la imagen
imagefill($img_count,0,0,$color_negro);
// Escribimos el nro dentro de la imagen
ImageString($img_count, 4, 2, 2, $s_nro, $color_blanco);
// Indicamos que se genere la imagen segun la extension utilizada
    switch ($s_imgext) {
	case "jpg":
	case "jpeg":
		header ("Content-type: image/jpeg");
		imagejpeg($img_count);
		break;

	case "gif":
		header ("Content-type: image/gif");
		imagegif($img_count);
		break;

	// gif se eligio por defecto porque el peso de los mismos es menor
	default:
		header ("Content-type: image/gif");
		imagegif($img_count);
		break;
    }
}
//Fin de definicion de funciones


//Toma de variables pasadas por GET
if (isset($_GET['ARCH'])) {
	$s_archivo = $_GET['ARCH'];
}
else {
	$s_archivo = "fz_counter";
}

$s_archivo = $s_archivo.".txt";

if (isset($_GET['COUNT'])) {
	$s_count = $_GET['COUNT'];
}
else {
	$s_count = "0";
}

// Extension de los archivos graficos (gif o jpg)
if (isset($_GET['IMGTYPE'])) {
	$s_imgext = $_GET['IMGTYPE'];
}
else {
	$s_imgext = "gif";
}

$s_imgext = strtolower ($s_imgext);

// Averiguamos el valor a mostrar
$s_get_num_counter = get_contador ($s_archivo, $s_count);

// Agregamos los digitos faltantes para que se llegue a un total de 5 o 10 como minimo
if (strlen($s_get_num_counter) <= 5) {
    $s_get_num_counter = sprintf("%05s", $s_get_num_counter);
}
elseif (strlen($s_get_num_counter) <= 10 ) {
    $s_get_num_counter = sprintf("%010s", $s_get_num_counter);
}
else {
    $s_get_num_counter = sprintf("%014s", $s_get_num_counter);
}

// Generamos la imagen
generar_jpg ($s_get_num_counter, $s_imgext);
?>
