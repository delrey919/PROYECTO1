<?php
        function validateMusicFile($file) {
            $allowedTypes = ['audio/mpeg', 'audio/ogg'];
            $fileType = mime_content_type($file['tmp_name']);

            if (!in_array($fileType, $allowedTypes)) {
                return "El format del fitxer de música no és vàlid. Han de ser MP3 o OGG.";
            }

            return null; // Validació correcta
        }

        // Funció per validar el fitxer de joc o el contingut del textarea
        function validateGameFile($gameContent) {
            $lines = explode("\n", trim($gameContent)); // Divideix per línies i elimina espais extres

            if (count($lines) < 2) {
                return "El fitxer de joc ha de tenir almenys dues línies.";
            }

            $numElements = intval($lines[0]);

            if ($numElements != count($lines) - 1) {
                return "El nombre d'elements indicat no coincideix amb el nombre de línies.";
            }

            for ($i = 1; $i <= $numElements; $i++) {
                $parts = explode('#', $lines[$i]);
                if (count($parts) != 3) {
                    return "Línia $i: format incorrecte. S'esperaven tres valors separats per #.";
                }

                $key = trim($parts[0]);
                $start = floatval(trim($parts[1]));
                $end = floatval(trim($parts[2]));

                if ($start < 0 || $end < 0 || $start >= $end) {
                    return "Línia $i: els instants han de ser valors no negatius i l'instant inicial ha de ser menor que l'instant final.";
                }

                if (!ctype_digit($key) || $key < 97 || $key > 122) {
                    return "Línia $i: la tecla ha de ser una lletra Unicode en minúscula (valors de 97 a 122).";
                }
            }

            return null; // Validació correcta
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = [];

            // Validació del fitxer de música
            if (isset($_FILES['musicFile'])) {
                $error = validateMusicFile($_FILES['musicFile']);
                if ($error) {
                    $errors[] = $error;
                }
            }

            // Validació del fitxer de joc o del contingut del textarea
            if (!empty($_FILES['gameFile']['tmp_name']) && !empty($_POST['gameData'])) {
                $errors[] = "Només pots pujar un fitxer de joc o escriure'n un, no tots dos.";
            } else if (!empty($_FILES['gameFile']['tmp_name'])) {
                $gameContent = file_get_contents($_FILES['gameFile']['tmp_name']);
                $error = validateGameFile($gameContent);
                if ($error) {
                    $errors[] = $error;
                }
            } else if (!empty($_POST['gameData'])) {
                $gameContent = $_POST['gameData'];
                $error = validateGameFile($gameContent);
                if ($error) {
                    $errors[] = $error;
                }
            } else {
                $errors[] = "Has de pujar un fitxer de joc o escriure'n el contingut.";
            }

            // Si hi ha errors, mostrar-los
            if (!empty($errors)) {
                foreach ($errors as $error) {
                    echo "<div class='error'>$error</div>";
                }
            } else {
                // Processar la cançó i pujar els fitxers
                echo "<div class='success'>La cançó s'ha pujat correctament!</div>";
            }
        }
        ?>
