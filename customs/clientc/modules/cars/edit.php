<?php
$clientId = 'clientc';

$carsPath = __DIR__ . '/../../../../data/cars.json';
$garagesPath = __DIR__ . '/../../../../data/garages.json';

$cars = json_decode(@file_get_contents($carsPath), true);
$garages = json_decode(@file_get_contents($garagesPath), true);

if (!is_array($cars) || !is_array($garages)) {
    echo "<p>Erreur : impossible de charger les données.</p>";
    return;
}

//Recherche de la bonne voiture à afficher
$carId = $_GET['id'] ?? null;
$car = null;
foreach ($cars as $c) {
    if ((string)$c['id'] === (string)$carId && $c['customer'] === $clientId) {
        $car = $c;
        break;
    }
}

if (!$car) {
    echo "<p>Voiture introuvable.</p>";
    return;
}

// Recherche du garage associé
$garageTitle = null;
foreach ($garages as $g) {
    if ($g['id'] == $car['garageId']) {
        $garageTitle = $g['title'];
        break;
    }
}
?>
<h2>Détail voiture (Client C)</h2>
<ul>
  <li>ID : <?= htmlspecialchars($car['id']) ?></li>
  <li>Modèle : <?= htmlspecialchars($car['modelName']) ?></li>
  <li>Marque : <?= htmlspecialchars($car['brand']) ?></li>
  <li>Année : <?= date('Y', (int)$car['year']) ?></li>
  <li>Puissance : <?= htmlspecialchars($car['power']) ?> ch</li>
  <li>Couleur : <span style="background:<?= htmlspecialchars($car['colorHex']) ?>;padding:0 10px"></span> <?= htmlspecialchars($car['colorHex']) ?></li>
  <li>Garage : <?= htmlspecialchars($garageTitle ?? 'Inconnu') ?></li>
</ul>

<p><a href="#" class="back-to-list">← Retour à la liste</a></p>
