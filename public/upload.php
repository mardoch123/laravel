<?php

// Configuration de la base de données
$host = 'localhost'; // Adresse de votre serveur MySQL
$dbname = 'poseursprojet'; // Nom de votre base de données
$username = 'root'; // Nom d'utilisateur MySQL
$password = 'root'; // Mot de passe MySQL

// Connexion à la base de données
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die(json_encode(['success' => false, 'message' => "Erreur de connexion à la base de données : " . $e->getMessage()]));
}

// Fonction principale pour traiter le téléchargement des images
function handleImageUpload($pdo, $request, $files)
{
    $response = ['success' => false, 'message' => '', 'images' => []];

    // Vérification et validation de l'ID de mission
    if (!isset($request['mission_id']) || !is_numeric($request['mission_id'])) {
        $response['message'] = 'ID de mission manquant ou invalide.';
        return $response;
    }

    $mission_id = intval($request['mission_id']);

    // Log pour vérifier l'ID envoyé
    error_log("Mission ID reçu : " . $mission_id);

    // Valider si l'ID de mission existe réellement dans la base
    $stmt = $pdo->prepare("SELECT id FROM clients WHERE id = ?");
    $stmt->execute([$mission_id]);
    $missionExists = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$missionExists) {
        $response['message'] = "L'ID de mission spécifié ($mission_id) n'existe pas.";
        return $response;
    }

    // Champs requis
    $requiredFields = [
        'photo_emplacement_evaporateur',
        'photo_numero_serie_evaporateur',
        'photo_raccordement_electrique',
        'photo_emplacement_condensateur',
        'photo_numero_serie_condensateur'
    ];

    // Vérifier les fichiers
    foreach ($requiredFields as $field) {
        if (!isset($files[$field]) || $files[$field]['error'] !== UPLOAD_ERR_OK) {
            $response['message'] = "Le fichier $field est manquant ou invalide.";
            return $response;
        }
    }

    // Répertoire de stockage
    $uploadDir = __DIR__ . '/uploads/';
    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0777, true)) {
            $response['message'] = "Impossible de créer le répertoire de téléchargement.";
            return $response;
        }
    }

    // Stocker les fichiers
    $storedFiles = [];
    foreach ($requiredFields as $field) {
        $file = $files[$field];

        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        $maxFileSize = 10 * 1024 * 1024; // 10 Mo

        // Valider type MIME et taille
        if (!in_array($file['type'], $allowedMimeTypes)) {
            $response['message'] = "Le fichier $field doit être au format JPEG, PNG ou JPG.";
            return $response;
        }

        if ($file['size'] > $maxFileSize) {
            $response['message'] = "Le fichier $field dépasse la taille maximale de 10 Mo.";
            return $response;
        }

        // Générer un nom unique pour le fichier
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $uniqueName = uniqid('img_', true) . '.' . $extension;

        // Déplacer le fichier
        if (!move_uploaded_file($file['tmp_name'], $uploadDir . $uniqueName)) {
            $response['message'] = "Erreur lors du stockage du fichier $field.";
            return $response;
        }

        $storedFiles[$field] = $uniqueName;
    }

    // Insérer ou mettre à jour la base de données
    try {
        $pdo->beginTransaction();

        // Vérifier si une mission existe déjà
        $stmt = $pdo->prepare("SELECT * FROM poseterminer WHERE mission_id = ?");
        $stmt->execute([$mission_id]);
        $record = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($record) {
            // Mise à jour des fichiers
            $updateQuery = "UPDATE poseterminer SET 
                photo_emplacement_evaporateur = :photo_emplacement_evaporateur,
                photo_numero_serie_evaporateur = :photo_numero_serie_evaporateur,
                photo_raccordement_electrique = :photo_raccordement_electrique,
                photo_emplacement_condensateur = :photo_emplacement_condensateur,
                photo_numero_serie_condensateur = :photo_numero_serie_condensateur,
                updated_at = NOW()
                WHERE mission_id = :mission_id";

            $pdo->prepare($updateQuery)->execute(array_merge($storedFiles, ['mission_id' => $mission_id]));
        } else {
            // Nouvelle insertion
            $insertQuery = "INSERT INTO poseterminer (
                mission_id,
                photo_emplacement_evaporateur,
                photo_numero_serie_evaporateur,
                photo_raccordement_electrique,
                photo_emplacement_condensateur,
                photo_numero_serie_condensateur,
                created_at,
                updated_at
            ) VALUES (
                :mission_id,
                :photo_emplacement_evaporateur,
                :photo_numero_serie_evaporateur,
                :photo_raccordement_electrique,
                :photo_emplacement_condensateur,
                :photo_numero_serie_condensateur,
                NOW(),
                NOW()
            )";

            $pdo->prepare($insertQuery)->execute(array_merge(['mission_id' => $mission_id], $storedFiles));
        }

        // Marquer la mission comme terminée
        $updateMissionQuery = "UPDATE clients SET raisonsocial = 1 WHERE id = :mission_id";
        $pdo->prepare($updateMissionQuery)->execute(['mission_id' => $mission_id]);

        $pdo->commit();

        $response['success'] = true;
        $response['message'] = 'Fichiers téléchargés et enregistrés avec succès.';
        $response['images'] = $storedFiles;
    } catch (Exception $e) {
        $pdo->rollBack();
        $response['message'] = 'Erreur lors de la mise à jour de la base de données : ' . $e->getMessage();
    }

    return $response;
}

// Point d'entrée pour le traitement
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = handleImageUpload($pdo, $_POST, $_FILES);

    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
