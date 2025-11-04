<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dati = json_decode($_POST['datiOrdine'], true);
    $totale = $_POST['totaleFinale'];
    $codice = $_POST['codice'] ?? 'Nessuno';

    // Salvataggio su file (scontrino)
    $file = fopen("scontrino.txt", "a");
    fwrite($file, "---- NUOVO ORDINE ----\n");
    fwrite($file, "Nome: {$dati['nome']}\n");
    fwrite($file, "Data prenotazione: {$dati['dataPrenotazione']}\n");
    fwrite($file, "Pane: {$dati['pane']}\n");
    fwrite($file, "Carne: {$dati['carne']}\n");
    fwrite($file, "Toppings: " . implode(", ", $dati['toppings'] ?? []) . "\n");
    fwrite($file, "Salse: " . implode(", ", $dati['salse'] ?? []) . "\n");
    fwrite($file, "Bevanda: {$dati['bevanda']}\n");
    fwrite($file, "Codice Fidelity: $codice\n");
    fwrite($file, "Totale finale: €$totale\n");
    fwrite($file, "Data ordine: " . date("d/m/Y H:i:s") . "\n\n");
    fclose($file);
} else {
    header("Location: index.html");
    exit;
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Conferma Ordine 🍔</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>✅ Ordine Confermato!</h1>

    <table border="1" style="margin:auto; border-collapse:collapse;">
        <tr><th>Nome</th><td><?= htmlspecialchars($dati['nome']) ?></td></tr>
        <tr><th>Data Prenotazione</th><td><?= htmlspecialchars($dati['dataPrenotazione']) ?></td></tr>
        <tr><th>Pane</th><td><?= htmlspecialchars($dati['pane']) ?></td></tr>
        <tr><th>Carne</th><td><?= htmlspecialchars($dati['carne']) ?></td></tr>
        <tr><th>Toppings</th><td><?= htmlspecialchars(implode(", ", $dati['toppings'] ?? [])) ?></td></tr>
        <tr><th>Salse</th><td><?= htmlspecialchars(implode(", ", $dati['salse'] ?? [])) ?></td></tr>
        <tr><th>Bevanda</th><td><?= htmlspecialchars($dati['bevanda']) ?></td></tr>
        <tr><th>Codice Fidelity</th><td><?= htmlspecialchars($codice) ?></td></tr>
        <tr><th>Totale</th><td><strong>€<?= number_format($totale, 2) ?></strong></td></tr>
    </table>

    <p style="text-align:center; margin-top:20px;">
        Il tuo ordine è stato registrato con successo! 🍟<br>
        Data ordine: <?= date("d/m/Y H:i:s") ?>
    </p>

    <footer>Creato da Manuz - BurgerCraft 🍔</footer>
</body>
</html>
