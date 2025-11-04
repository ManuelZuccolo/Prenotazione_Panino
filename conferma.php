<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['datiOrdine'])) {
    header("Location: index.html");
    exit;
}

// Recupera dati ordine
$datiJson = $_POST['datiOrdine'];
$datiOrdine = json_decode($datiJson, true);

// Totale
$totale = isset($_POST['totaleFinale']) ? floatval(str_replace(',', '.', $_POST['totaleFinale'])) : 0.00;

// Codice Fidelity
$codice = isset($_POST['codice']) ? strtoupper(trim($_POST['codice'])) : '';

// Lista codici e bonus
$codici = [
    "INEEDPOWER" => ["tipo" => "bevanda_bonus", "oggetto" => "Monster"]
];

$bonusMessaggio = '';
if ($codice && isset($codici[$codice])) {
    $tipo = $codici[$codice]['tipo'];
    if ($tipo === "bevanda_bonus") {
        if (strtolower($datiOrdine['bevanda']) === 'nessuna') {
            $datiOrdine['bevanda'] = $codici[$codice]['oggetto'] . " (gratis)";
        } else {
            $datiOrdine['bevanda'] .= ", " . $codici[$codice]['oggetto'] . " (gratis)";
        }
        $bonusMessaggio = "Hai ricevuto una " . $codici[$codice]['oggetto'] . " in omaggio! ⚡";
    }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Conferma Ordine - BurgerCraft 🍔</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>✅ Ordine Confermato!</h1>

    <table>
        <tr><th colspan="2">Dati Cliente</th></tr>
        <tr><td>Nome</td><td><?= htmlspecialchars($datiOrdine['nome']) ?></td></tr>
        <tr><td>Data e Ora Prenotazione</td><td><?= htmlspecialchars($datiOrdine['dataPrenotazione']) ?></td></tr>

        <tr><th colspan="2">Scelte Hamburger</th></tr>
        <tr><td>Pane</td><td><?= htmlspecialchars($datiOrdine['pane']) ?></td></tr>
        <tr><td>Carne</td><td><?= htmlspecialchars($datiOrdine['carne']) ?></td></tr>
        <tr><td>Toppings</td><td><?= !empty($datiOrdine['toppings']) ? htmlspecialchars(implode(', ', $datiOrdine['toppings'])) : "Nessuno" ?></td></tr>
        <tr><td>Salse</td><td><?= !empty($datiOrdine['salse']) ? htmlspecialchars(implode(', ', $datiOrdine['salse'])) : "Nessuna" ?></td></tr>
        <tr><td>Bevanda</td><td><?= htmlspecialchars($datiOrdine['bevanda']) ?></td></tr>

        <?php if ($bonusMessaggio): ?>
        <tr><td>Bonus Fidelity</td><td class="bonus"><?= $bonusMessaggio ?></td></tr>
        <?php endif; ?>

        <tr><th>Totale Finale</th><td>€<?= number_format($totale, 2, '.', '') ?></td></tr>
        <tr><th>Data Ordine</th><td><?= date("d/m/Y H:i:s") ?></td></tr>
        <tr><th>Codice Fidelity</th><td><?= htmlspecialchars($codice ?: "Nessuno") ?></td></tr>
    </table>

    <footer>
        Grazie per aver ordinato da BurgerCraft! 🍔
    </footer>
</body>
</html>
