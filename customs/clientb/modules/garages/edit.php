<?php
$clientId = 'clientb';

$carsPath = __DIR__ . '/../../../../data/cars.json';
$garagesPath = __DIR__ . '/../../../../data/garages.json';

$cars = json_decode(@file_get_contents($carsPath), true);
$garages = json_decode(@file_get_contents($garagesPath), true);

if (!is_array($cars) || !is_array($garages)) {
  echo "<p>Erreur : impossible de charger les données.</p>";
  return;
}

$gid = $_GET['id'] ?? null;
if ($gid === null) { echo "<p>Garage introuvable.</p>"; return; }

// Trouver le garage demandé (et appartenant à clientb)
$garage = null;
foreach ($garages as $g) {
  if ((string)$g['id'] === (string)$gid && $g['customer'] === $clientId) {
    $garage = $g; break;
  }
}
if (!$garage) { echo "<p>Garage introuvable.</p>"; return; }

// Récupérer les voitures de ce garage (client B)
$garageCars = array_values(array_filter($cars, function($c) use ($clientId, $gid) {
  return ($c['customer'] ?? null) === $clientId && (string)$c['garageId'] === (string)$gid;
}));
?>
<h2>Garage : <?= htmlspecialchars($garage['title']) ?></h2>
<p><strong>Adresse :</strong> <?= htmlspecialchars($garage['address']) ?></p>

<h3>Voitures dans ce garage</h3>
<?php if (empty($garageCars)): ?>
  <p>Aucune voiture associée.</p>
<?php else: ?>
  <ul>
    <?php foreach ($garageCars as $car): ?>
      <li>
        <!-- lien cliquable pour ouvrir la fiche voiture -->
        <a href="#" class="car-link" data-id="<?= htmlspecialchars($car['id']) ?>">
          <?= strtolower(htmlspecialchars($car['modelName'])) ?> — <?= htmlspecialchars($car['brand']) ?>
        </a>
      </li>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>

<p>
  <a href="#" class="back-to-garages">← Retour à la liste des garages</a>
  &nbsp;|&nbsp;
  <a href="#" class="switch-module" data-module="cars">Aller aux voitures</a>
</p>
