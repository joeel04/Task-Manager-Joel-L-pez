<?php
# Comprovem que s'ejecuta en CLI
if (php_sapi_name() !== "cli") {
   echo "Aquest programa només es pot executar en CLI";
   return 0;
}


function mostrarAyuda() {
   global $argv;
   echo "Ús: ".$argv[0]." afegir \"nom_tasca\"\n";
   echo "     ".$argv[0]." llistar\n";
   echo "     ".$argv[0]." completar \"id_tasca\"\n";
   echo "     ".$argv[0]." eliminar \"id_tasca\"\n";
}


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


if ($argc < 2 || $argc > 3) {
   mostrarAyuda();
} else {
   $accio = $argv[1];
   if ($accio == "afegir" && $argc == 3) {
       carregarTasques();
       afegirTasca($argv[2]);
       desarTasques();
   } elseif ($accio == "llistar") {
       carregarTasques();
       llistarTasques();
   } elseif ($accio == "completar" && $argc == 3) {
       carregarTasques();
       completarTasca($argv[2]);
       desarTasques();
   } elseif ($accio == "eliminar" && $argc == 3) {
       carregarTasques();
       eliminarTasca($argv[2]);
       desarTasques();
   } else {
       echo "Acció desconeguda o nombre d'arguments incorrecte.\n";
       mostrarAyuda();
   }
}
?>

