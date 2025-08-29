<?php
$clientId = 'clientb';

// Charger les données (voitures + garages) depuis les fichiers JSON
$carsPath = __DIR__ . '/../../../../data/cars.json';
$garagesPath = __DIR__ . '/../../../../data/garages.json';

$cars = json_decode(@file_get_contents($carsPath), true);
$garages = json_decode(@file_get_contents($garagesPath), true);

// Vérification basique : on arrête si les données ne sont pas valides
if (!is_array($cars) || !is_array($garages)) {
  echo "<p>Erreur : impossible de charger les données.</p>";
  return;
}

// Ne garder que les garages du client B
$garages = array_values(array_filter($garages, fn($g) => ($g['customer'] ?? null) === $clientId));

// Compter le nombre de voitures par garageId (uniquement pour client B)
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
  <!-- Cas où aucun garage n'est disponible -->
  <p>Aucun garage trouvé.</p>
<?php else: ?>
  <ul>
    <?php foreach ($garages as $g): ?>
      <li>
        <!-- Chaque garage est cliquable et mène au détail (edit.php) -->
        <a href="#" class="garage-link" data-id="<?= htmlspecialchars($g['id']) ?>">
          <?= htmlspecialchars($g['title']) ?>
        </a>
        — <?= htmlspecialchars($g['address']) ?>
        <!-- Afficher le nombre de voitures associées -->
        — Voitures : <?= (int)($countByGarage[$g['id']] ?? 0) ?>
      </li>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>

<!-- Lien pour revenir au module voitures -->
<p><a href="#" class="switch-module" data-module="cars">← Revenir aux voitures</a></p>