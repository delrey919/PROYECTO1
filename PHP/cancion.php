<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Directoris per emmagatzemar els arxius
    $audioDir = "./CANCIONES";
    $portadaDir = "./CANCIONES";
    $archivoDir = "./CANCIONES";

    // Recollim les dades del formulari
    $titol = $_POST['titol'];
    $artista = $_POST['artista'];

    // Guardem els arxius pujats amb verificacions
    $audioPath = $audioDir . basename($_FILES["audio"]["name"]);
    $portadaPath = $portadaDir . basename($_FILES["portada"]["name"]);
    $archivoPath = $archivoDir . basename($_FILES["arxiu"]["name"]);

    $errors = []; // Array per emmagatzemar errors

    if (is_uploaded_file($_FILES['audio']['tmp_name'])) {
        if (!move_uploaded_file($_FILES["audio"]["tmp_name"], $audioPath)) {
            $errors[] = "Error pujant l'arxiu d'àudio.";
        }
    } else {
        $errors[] = "No s'ha pujat cap arxiu d'àudio.";
    }

    if (is_uploaded_file($_FILES['portada']['tmp_name'])) {
        if (!move_uploaded_file($_FILES["portada"]["tmp_name"], $portadaPath)) {
            $errors[] = "Error pujant l'arxiu de caràtula.";
        }
    } else {
        $errors[] = "No s'ha pujat cap arxiu de caràtula.";
    }

    if (is_uploaded_file($_FILES['arxiu']['tmp_name'])) {
        if (!move_uploaded_file($_FILES["arxiu"]["tmp_name"], $archivoPath)) {
            $errors[] = "Error pujant l'arxiu de joc.";
        }
    } else {
        $errors[] = "No s'ha pujat cap arxiu de joc.";
    }

    // Verifiquem si s'ha escrit el fitxer de joc manualment (textarea)
    if (!empty($_POST['textarea'])) {
        $customFileContent = $_POST['textarea'];
        $archivoPath = $archivoDir . "/fitxer_manual.txt";
        if (file_put_contents($archivoPath, $customFileContent) === false) {
            $errors[] = "Error guardant l'arxiu de joc creat manualment.";
        }
    }

    // Verifiquem l'arxiu JSON
    $jsonFile = './PHP/canciones.json';
    if (file_exists($jsonFile)) {
        $jsonData = file_get_contents($jsonFile);
        $data = json_decode($jsonData, true);
        if ($data === null) {
            $errors[] = "Error al desxifrar JSON: " . json_last_error_msg();
        }
    } else {
        $errors[] = "L'arxiu JSON no existeix.";
    }

    // Si no hi ha errors, afegim la nova cançó
    if (empty($errors)) {
        $novaCançó = array(
            "titol" => $titol,
            "artista" => $artista,
            "audio" => $audioPath,
            "portada" => $portadaPath,
            "arxiu" => $archivoPath
        );

        // Afegim la nova cançó a l'array existent
        array_push($data["Cancions"], $novaCançó);

        // Guardem de nou les dades a app.json
        $jsonData = json_encode($data, JSON_PRETTY_PRINT);
        if (file_put_contents($jsonFile, $jsonData) === false) {
            $errors[] = "Error guardant l'arxiu JSON.";
        } else {
            // Redirigim a "correcte.html" si tot està bé
            header("Location: correcte.html");
            exit();
        }
    }

    // Si hi ha errors, els mostrem
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo $error . "<br>";
        }
    }
}
?>
