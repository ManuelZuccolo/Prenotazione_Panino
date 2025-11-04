<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datiOrdine = $_POST; // contiene pane, carne, toppings, salse, bevanda e totale
} else {
    header("Location: index.html");
    exit;
}

// Converti eventuali array in JSON per passaggio successivo
$datiJson = json_encode($datiOrdine);
$totale = isset($datiOrdine['totaleFinale']) ? floatval(str_replace(',', '.', $datiOrdine['totaleFinale'])) : 0.00;
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>BurgerCraft 🍔 - Codice Fidelity</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>🎟️ Codice Fidelity</h1>

    <form method="POST" action="conferma.php" id="fidelityForm">
        <div class="price-box">
            Prezzo totale attuale: <span id="totale"><?= number_format($totale, 2, '.', '') ?></span> €
        </div>

        <div style="text-align:center; margin-top:20px;">
            <label for="codice">Inserisci il tuo codice Fidelity (opzionale):</label><br>
            <input type="text" id="codice" name="codice">
        </div>

        <div class="esito" id="esito"></div>

        <!-- Campi nascosti -->
        <input type="hidden" name="datiOrdine" value='<?= htmlspecialchars($datiJson, ENT_QUOTES) ?>'>
        <input type="hidden" name="totaleFinale" id="totaleFinale" value="<?= number_format($totale, 2, '.', '') ?>">

        <div style="text-align:center; margin-top:25px;">
            <button type="submit">Conferma ordine →</button>
        </div>
    </form>

    <footer>
        Creato da Manuz, per qualsiasi problema ESITATE a contattarmi
    </footer>

    <script>
        const form = document.getElementById('fidelityForm');
        const esitoDiv = document.getElementById('esito');
        const totaleSpan = document.getElementById('totale');
        const totaleHidden = document.getElementById('totaleFinale');

        // Lista codici fidelity
        const codici = {
            "INEEDPOWER": { tipo: "bevanda_bonus", oggetto: "Monster", valore: 3 }
        };

        document.getElementById('codice').addEventListener('input', function() {
            const codice = this.value.trim().toUpperCase();
            if (codici[codice]) {
                const tipo = codici[codice].tipo;
                if (tipo === "bevanda_bonus") {
                    esitoDiv.textContent = `Hai ricevuto una ${codici[codice].oggetto} in omaggio!`;
                    // Aggiorna totale: se Monster non era selezionata, sottrai il prezzo
                    let totale = parseFloat(totaleHidden.value);
                    const bevandaSelezionata = JSON.parse(form.querySelector('input[name="datiOrdine"]').value).bevanda.toLowerCase();
                    if (bevandaSelezionata === "nessuna") {
                        totale -= codici[codice].valore;
                        if (totale < 0) totale = 0;
                        totaleSpan.textContent = totale.toFixed(2);
                        totaleHidden.value = totale.toFixed(2);
                    }
                }
            } else {
                esitoDiv.textContent = '';
            }
        });
    </script>
</body>
</html>
