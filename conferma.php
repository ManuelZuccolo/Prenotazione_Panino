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
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f2e7;
            color: #333;
            text-align: center;
        }
        h1 { margin-top: 20px; }
        table {
            margin: 20px auto;
            border-collapse: collapse;
            width: 80%;
            max-width: 600px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #ffcc66;
        }
        td.price {
            text-align: right;
        }
        .bonus {
            color: green;
            font-weight: bold;
        }
        footer {
            margin-top: 30px;
            font-size: 0.9em;
            color: #555;
        }
    </style>
</head>
<body>
    <h1>✅ Ordine Confermato!</h1>

    <table>
        <tr>
            <th colspan="2">Dati Cliente</th>
        </tr>
        <tr>
            <td>Nome</td>
            <td><?= htmlspecialchars($datiOrdine['nome']) ?></td>
        </tr>
        <tr>
            <td>Data e Ora Prenotazione</td>
            <td><?= htmlspecialchars($datiOrdine['dataPrenotazione']) ?></td>
        </tr>

        <tr>
            <th colspan="2">Scelte Hamburger</th>
        </tr>
        <tr>
            <td>Pane</td>
            <td><?= htmlspecialchars($datiOrdine['pane']) ?></td>
        </tr>
        <tr>
            <td>Carne</td>
            <td><?= htmlspecialchars($datiOrdine['carne']) ?></td>
        </tr>
        <tr>
            <td>Toppings</td>
            <td><?= !empty($datiOrdine['toppings']) ? htmlspecialchars(implode(', ', $datiOrdine['toppings'])) : "Nessuno" ?></td>
        </tr>
        <tr>
            <td>Salse</td>
            <td><?= !empty($datiOrdine['salse']) ? htmlspecialchars(implode(', ', $datiOrdine['salse'])) : "Nessuna" ?></td>
        </tr>
        <tr>
            <td>Bevanda</td>
            <td><?= htmlspecialchars($datiOrdine['bevanda']) ?></td>
        </tr>

        <?php if ($bonusMessaggio): ?>
        <tr>
            <td>Bonus Fidelity</td>
            <td class="bonus"><?= $bonusMessaggio ?></td>
        </tr>
        <?php endif; ?>

        <tr>
            <th>Totale Finale</th>
            <td class="price">€<?= number_format($totale, 2, '.', '') ?></td>
        </tr>

        <tr>
            <th>Data Ordine</th>
            <td><?= date("d/m/Y H:i:s") ?></td>
        </tr>
        <tr>
            <th>Codice Fidelity</th>
            <td><?= htmlspecialchars($codice ?: "Nessuno") ?></td>
        </tr>
    </table>

    <footer>
        Grazie per aver ordinato da BurgerCraft! 🍔<br>
        Ci vediamo presto!
    </footer>
</body>
</html>
