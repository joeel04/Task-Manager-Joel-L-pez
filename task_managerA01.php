<?php
# Comprovem que s'executa en CLI
if (php_sapi_name() !== "cli") {
 echo "Aquest programa només es pot executar en CLI";
 return 0;
}


# Funció que mostra les instruccions de com utilitzar el programa segons el que vols fer
function mostrarAyuda() {
 global $argv;
 echo "Ús: ".$argv[0]." --afegir o -a \"nom_tasca\"\n";
 echo "     ".$argv[0]." --llistar o -ll\n";
 echo "     ".$argv[0]." --completar o -c \"id_tasca\"\n";
 echo "     ".$argv[0]." --eliminar o -e \"id_tasca\"\n";
}


# Inicialització de variables per emmagatzemar les tasques i el nom de l'arxiu de tasques.
$tareas = [];
$arxiuTasques = "tasques.txt";

# Funció per carregar les tasques des de l'arxiu de tasques.
function carregarTasques() {
 global $tareas, $arxiuTasques;
 if (file_exists($arxiuTasques)) {
     $fileContent = file_get_contents($arxiuTasques);
     $tareas = explode("\n", $fileContent);
 }
}

# Funció per desar les tasques a l'arxiu de tasques.
function desarTasques() {
 global $tareas, $arxiuTasques;
 $fileContent = implode("\n", $tareas);
 file_put_contents($arxiuTasques, $fileContent);
}

# Funció per afegir una nova tasca.
function afegirTasca($tasca) {
 global $tareas;
 $id = count($tareas);
 $tareas[] = "$id: $tasca";
 echo "Tasca afegida: $tasca\n";
}

# Funció per llistar totes les tasques.
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

# Funció per marcar una tasca com a completada.
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

# Funció per eliminar una tasca.
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

# Analitza els arguments de la línia de comandes i executa les funcions corresponents.
$options = getopt("a:l:c:e:", [
  "afegir:",
  "llistar",
  "completar:",
  "eliminar:"
]);

# Verifica si s'han proporcionat opcions i mostra ajuda si no.
if (count($options) == 0) {
 mostrarAyuda();
} else {
# Itera sobre les opcions i executa les funcions corresponents.
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



