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

// Garages du client B
$garages = array_values(array_filter($garages, fn($g) => ($g['customer'] ?? null) === $clientId));

// Compter les voitures par garageId (client B uniquement)
$countByGarage = [];
foreach ($cars as $c) {
  if (($c['customer'] ?? null) === $clientId) {
    $gid = $c['garageId'];
    $countByGarage[$gid] = ($countByGarage[$gid] ?? 0) + 1;
  }
}
?>
<h2>Garages (Client B)</h2>
<?php if (empty($garages)): ?>
  <p>Aucun garage trouvé.</p>
<?php else: ?>
  <ul>
    <?php foreach ($garages as $g): ?>
      <li>
        <a href="#" class="garage-link" data-id="<?= htmlspecialchars($g['id']) ?>">
          <?= htmlspecialchars($g['title']) ?>
        </a>
        — <?= htmlspecialchars($g['address']) ?>
        — Voitures : <?= (int)($countByGarage[$g['id']] ?? 0) ?>
      </li>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>
<p><a href="#" class="switch-module" data-module="cars">← Revenir aux voitures</a></p>
