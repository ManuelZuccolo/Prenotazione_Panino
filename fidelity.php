<?php
session_star();
// Recupera i dati inviati da index.html
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datiOrdine = $_POST; // contiene pane, carne, toppings, salse, bevanda e totale
} else {
    // Se qualcuno entra direttamente, reindirizza a index
    header("Location: index.html");
    exit;
}

// Converti eventuali array in JSON per passaggio successivo
$datiJson = json_encode($datiOrdine);
$totale = isset($datiOrdine['totaleFinale']) ? floatval($datiOrdine['totaleFinale']) : 0.00;
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>BurgerCraft 🍔</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>🎟️ Codice Fidelity</h1>

    <form method="POST" action="conferma.php" id="fidelityForm">
        <div class="price-box">
            Prezzo totale attuale: <span id="totale"><?php echo number_format($totale,2); ?></span> €
        </div>

        <div style="text-align:center; margin-top:20px;">
            <label for="codice">Inserisci il tuo codice Fidelity (opzionale):</label><br>
            <input type="text" id="codice" name="codice">
        </div>

        <div class="esito" id="esito"></div>

        <!-- Campi nascosti per passare tutti i dati a conferma.php -->
        <input type="hidden" name="datiOrdine" value='<?php echo htmlspecialchars($datiJson, ENT_QUOTES); ?>'>
        <input type="hidden" name="totaleFinale" id="totaleFinale" value="<?php echo number_format($totale,2); ?>">

        <div style="text-align:center; margin-top:25px;">
            <button type="submit">Conferma ordine →</button>
        </div>
    </form>

    <footer>
        Creato da Manuz, per qualsiasi problema ESITATE a contattarmi
    </footer>

    <script>
      //JS per verificare il codice fidelity in tempo reale (opzionale)
        const form = document.getElementById('fidelityForm');
        const esitoDiv = document.getElementById('esito');
        const totaleSpan = document.getElementById('totale');
        const totaleFinaleInput = document.getElementById('totaleFinale');

        form.addEventListener('submit', async (e) => {
            const codice = document.getElementById('codice').value.trim().toUpperCase();
            let totale = parseFloat(totaleFinaleInput.value);

            if (codice) {
                e.preventDefault(); //aspetta verifica codice

                try {
                    const response = await fetch('data/fidelity.json');
                    const codici = await response.json();

                    if (codici[codice]) {
                        const tipo = codici[codice].tipo;
                        const sconto = codici[codice].sconto || 0;
                        let messaggio = "";

                        if (tipo === "panino_gratis") {
                            totale = 0;
                            messaggio = "Panino gratis! Offerta speciale BurgerCraft!";
                        } else if (tipo === "bevanda_bonus") {
                            messaggio = `Hai ricevuto una ${codici[codice].oggetto} in omaggio!`;
                        } else if (tipo === "penalità") {
                            totale += 50;
                            messaggio = "NONMANGIAREQUI... visto che devo pulire io +50€!";
                        } else if (tipo === "sconto_percentuale") {
                            totale -= totale * (sconto / 100);
                            messaggio = `Sconto del ${sconto}% applicato!`;
                        }

                        esitoDiv.textContent = messaggio;
                        esitoDiv.style.color = "green";
                        totaleSpan.textContent = totale.toFixed(2);
                        totaleFinaleInput.value = totale.toFixed(2);

                        //Invia il form
                        form.submit();
                    } else {
                        esitoDiv.textContent = "Codice non valido!";
                        esitoDiv.style.color = "red";
                    }
                } catch (err) {
                    console.error(err);
                    esitoDiv.textContent = "Errore nel caricamento dei codici fidelity.";
                    esitoDiv.style.color = "red";
                }
            }
        });  
    </script>
</body>
</html>
