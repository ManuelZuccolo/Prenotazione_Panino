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
$bonusMessaggio = '';
$bonusOggetto = '';

if ($codice === "INEEDPOWER") {
    $bonusMessaggio = "Hai ricevuto una Monster in omaggio! ⚡";
    $bonusOggetto = "Monster";
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

    <div class="ordine-box">
        <h2>Dati Cliente</h2>
        <p><strong>Nome:</strong> <?= htmlspecialchars($datiOrdine['nome']) ?></p>
        <p><strong>Data e Ora prenotazione:</strong> <?= htmlspecialchars($datiOrdine['dataPrenotazione']) ?></p>

        <h2>Scelte Hamburger</h2>
        <p><strong>Pane:</strong> <?= htmlspecialchars($datiOrdine['pane']) ?></p>
        <p><strong>Carne:</strong> <?= htmlspecialchars($datiOrdine['carne']) ?></p>

        <p><strong>Toppings:</strong>
            <?php
            if (!empty($datiOrdine['toppings'])) {
                echo htmlspecialchars(implode(', ', $datiOrdine['toppings']));
            } else {
                echo "Nessuno";
            }
            ?>
        </p>

        <p><strong>Salse:</strong>
            <?php
            if (!empty($datiOrdine['salse'])) {
                echo htmlspecialchars(implode(', ', $datiOrdine['salse']));
            } else {
                echo "Nessuna";
            }
            ?>
        </p>

        <p><strong>Bevanda:</strong> <?= htmlspecialchars($datiOrdine['bevanda']) ?></p>

        <?php if ($bonusMessaggio): ?>
            <p style="color:green;"><strong>Bonus Fidelity:</strong> <?= $bonusMessaggio ?></p>
        <?php endif; ?>

        <h2>Totale</h2>
        <p><strong>Prezzo da pagare:</strong> <?= number_format($totale, 2, '.', '') ?> €</p>
    </div>

    <footer>
        Non sporcate
    </footer>
</body>
</html>
