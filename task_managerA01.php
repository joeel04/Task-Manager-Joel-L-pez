<?php
# Comprovem que s'executa en CLI
if (php_sapi_name() !== "cli") {
 echo "Aquest programa només es pot executar en CLI";
 return 0;
}


#Funció per mostrar com podem interactuar per fer coses amb les tasques
function mostrarAyuda() {
 global $argv;
 echo "Ús: ".$argv[0]." --afegir o -a \"nom_tasca\"\n";
 echo "     ".$argv[0]." --llistar o -ll\n";
 echo "     ".$argv[0]." --completar o -c \"id_tasca\"\n";
 echo "     ".$argv[0]." --eliminar o -e \"id_tasca\"\n";
}


#Guarda les tasques en un arxiu txt anomenat tasques.txt
$tareas = [];
$arxiuTasques = "tasques.txt";


function carregarTasques() {
 global $tareas, $arxiuTasques;
 if (file_exists($arxiuTasques)) {
     $fileContent = file_get_contents($arxiuTasques);
     $tareas = explode("\n", $fileContent);
 }
}


function desarTasques() {
 global $tareas, $arxiuTasques;
 $fileContent = implode("\n", $tareas);
 file_put_contents($arxiuTasques, $fileContent);
}


function afegirTasca($tasca) {
 global $tareas;
 $id = count($tareas);
 $tareas[] = "$id: $tasca";
 echo "Tasca afegida: $tasca\n";
}


function llistarTasques() {
 global $tareas;
 if (empty($tareas)) {
     echo "No hi ha tasques.\n";
 } else {
     echo "Tasques pendents:\n";
     foreach ($tareas as $tasca) {
         echo "$tasca\n";
     }
 }
}


function completarTasca($id) {
 global $tareas;
 foreach ($tareas as &$tasca) {
     if (strpos($tasca, $id) === 0) {
         $tasca = "[COMPLETADA] " . $tasca;
         echo "Tasca $id marcada com a completada.\n";
         return;
     }
 }
 echo "No s'ha trobat la tasca amb aquest ID.\n";
}


function eliminarTasca($id) {
 global $tareas;
 $tasquesActualitzades = [];
 $eliminat = false;
 foreach ($tareas as $tasca) {
     if (strpos($tasca, $id) !== 0) {
         $tasquesActualitzades[] = $tasca;
     } else {
         $eliminat = true;
     }
 }
 if ($eliminat) {
     $tareas = $tasquesActualitzades;
     echo "Tasca $id eliminada.\n";
 } else {
     echo "No s'ha trobat la tasca amb aquest ID.\n";
 }
}


$options = getopt("a:l:c:e:", [
  "afegir:",
  "llistar",
  "completar:",
  "eliminar:"
]);


if (count($options) == 0) {
 mostrarAyuda();
} else {
 foreach ($options as $key => $value) {
     switch ($key) {
         case "a":
         case "afegir":
             carregarTasques();
             afegirTasca($value);
             desarTasques();
             break;
           case "l":
           case "llistar":
             carregarTasques();
             llistarTasques();
             break;               
         case "c":
         case "completar":
             carregarTasques();
             completarTasca($value);
             desarTasques();
             break;
         case "e":
         case "eliminar":
             carregarTasques();
             eliminarTasca($value);
             desarTasques();
             break;
         default:
             echo "Opció desconeguda: $key.\n";
             mostrarAyuda();
             break;
     }
 }
}
?>



