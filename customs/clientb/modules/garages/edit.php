<?php
$clientId = 'clientb';

// Charger les données (voitures + garages)
$carsPath = __DIR__ . '/../../../../data/cars.json';
$garagesPath = __DIR__ . '/../../../../data/garages.json';

$cars = json_decode(@file_get_contents($carsPath), true);
$garages = json_decode(@file_get_contents($garagesPath), true);

// Vérification basique : arrêt si données invalides
if (!is_array($cars) || !is_array($garages)) {
  echo "<p>Erreur : impossible de charger les données.</p>";
  return;
}

// Récupération de l'id du garage demandé
$gid = $_GET['id'] ?? null;
if ($gid === null) {
  echo "<p>Garage introuvable.</p>";
  return;
}

// Recherche du garage correspondant, en s'assurant qu'il appartient bien au client B
$garage = null;
foreach ($garages as $g) {
  if ((string)$g['id'] === (string)$gid && $g['customer'] === $clientId) {
    $garage = $g;
    break;
  }
}

// Si aucun garage trouvé, on arrête
if (!$garage) {
  echo "<p>Garage introuvable.</p>";
  return;
}

// Récupérer toutes les voitures de ce garage (uniquement pour client B)
$garageCars = array_values(array_filter($cars, function($c) use ($clientId, $gid) {
  return ($c['customer'] ?? null) === $clientId && (string)$c['garageId'] === (string)$gid;
}));
?>

<h2>Garage : <?= htmlspecialchars($garage['title']) ?></h2>
<p><strong>Adresse :</strong> <?= htmlspecialchars($garage['address']) ?></p>

<h3>Voitures dans ce garage</h3>
<?php if (empty($garageCars)): ?>
  <!-- Cas où aucun véhicule n'est associé -->
  <p>Aucune voiture associée.</p>
<?php else: ?>
  <ul>
    <?php foreach ($garageCars as $car): ?>
      <li>
        <!-- Chaque voiture est cliquable pour afficher son détail -->
        <a href="#" class="car-link" data-id="<?= htmlspecialchars($car['id']) ?>">
          <?= strtolower(htmlspecialchars($car['modelName'])) ?> — <?= htmlspecialchars($car['brand']) ?>
        </a>
      </li>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>

<!-- Navigation : retour à la liste des garages ou aller aux voitures -->
<p>
  <a href="#" class="back-to-garages">← Retour à la liste des garages</a>
  &nbsp;|&nbsp;
  <a href="#" class="switch-module" data-module="cars">Aller aux voitures</a>
</p>
