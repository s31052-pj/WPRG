<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Rezerwacja hotelu - CMS</title>
</head>
<body>
<h2>Rezerwacja hotelu</h2>

<form method="post">
    <label>Imię: <input type="text" name="imie" required></label><br><br>
    <label>Nazwisko: <input type="text" name="nazwisko" required></label><br><br>
    <label>E-mail: <input type="email" name="email" required></label><br><br>
    <label>Numer karty kredytowej: <input type="text" name="karta" maxlength="16" pattern="\d{16}" required></label><br><br>
    <label>Data pobytu: <input type="date" name="data" required></label><br><br>
    <label>Godzina przyjazdu: <input type="time" name="godzina" required></label><br><br>
    <label>Ilość osób:
        <select name="ilosc_osob" required>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
        </select>
    </label><br><br>
    <label><input type="checkbox" name="lozko_dziecko"> Łóżko dla dziecka</label><br><br>
    <label>Udogodnienia:</label><br>
    <label><input type="checkbox" name="udogodnienia[]" value="Klimatyzacja"> Klimatyzacja</label><br>
    <label><input type="checkbox" name="udogodnienia[]" value="Popielniczka"> Popielniczka</label><br><br>

    <input type="submit" name="zapisz" value="Zapisz rezerwację">
</form>
    
<form method="post" style="margin-top: 20px;">
    <input type="submit" name="wczytaj" value="Wczytaj rezerwacje">
</form>

<hr>

<?php
$plik = "rezerwacje.csv";

// Obsługa zapisu
if (isset($_POST["zapisz"])) {
    $dane = [
        $_POST["imie"],
        $_POST["nazwisko"],
        $_POST["email"],
        substr($_POST["karta"], -4), // tylko ostatnie 4 cyfry
        $_POST["data"],
        $_POST["godzina"],
        $_POST["ilosc_osob"],
        isset($_POST["lozko_dziecko"]) ? "Tak" : "Nie",
        isset($_POST["udogodnienia"]) ? implode(",", $_POST["udogodnienia"]) : "Brak"
    ];

    $fp = fopen($plik, "a");
    fputcsv($fp, $dane, ";");
    fclose($fp);

    echo "<p style='color:green;'>✅ Rezerwacja została zapisana.</p>";
}

// Obsługa wczytania
if (isset($_POST["wczytaj"])) {
    if (!file_exists($plik)) {
        echo "<p>Brak danych do wyświetlenia.</p>";
    } else {
        echo "<h3>📋 Zapisane rezerwacje:</h3>";
        echo "<table border='1' cellpadding='5'>";
        echo "<tr>
            <th>Imię</th>
            <th>Nazwisko</th>
            <th>Email</th>
            <th>Karta (ostatnie 4)</th>
            <th>Data</th>
            <th>Godzina</th>
            <th>Ilość osób</th>
            <th>Łóżko dla dziecka</th>
            <th>Udogodnienia</th>
        </tr>";

        $fp = fopen($plik, "r");
        while (($rekord = fgetcsv($fp, 1000, ";")) !== false) {
            echo "<tr>";
            foreach ($rekord as $pole) {
                echo "<td>" . htmlspecialchars($pole) . "</td>";
            }
            echo "</tr>";
        }
        fclose($fp);
        echo "</table>";
    }
}
?>

</body>
</html>