<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Rezerwacja Hotelu z Danymi Osób</title>
</head>
<body>

<h2>Rezerwacja hotelu – krok 1: Wybierz liczbę osób</h2>

<?php
if (!isset($_POST["krok"])) {
    ?>
    <form method="post">
        <label>Ile osób? 
            <select name="ilosc_osob" required>
                <option value="">-- Wybierz --</option>
                <option value="1">1 osoba</option>
                <option value="2">2 osoby</option>
                <option value="3">3 osoby</option>
                <option value="4">4 osoby</option>
            </select>
        </label><br><br>
        <input type="hidden" name="krok" value="2">
        <input type="submit" value="Dalej">
    </form>
    <?php
} elseif ($_POST["krok"] == "2") {
    $ilosc_osob = intval($_POST["ilosc_osob"]);
    ?>
    <h2>Krok 2: Dane osoby rezerwującej i uczestników</h2>
    <form method="post">
        <h3>Dane osoby rezerwującej</h3>
        Imię: <input type="text" name="imie" required><br><br>
        Nazwisko: <input type="text" name="nazwisko" required><br><br>
        Adres: <input type="text" name="adres" required><br><br>
        E-mail: <input type="email" name="email" required><br><br>
        Numer karty kredytowej: <input type="text" name="karta" pattern="\d{16}" required maxlength="16"><br><br>
        Data przyjazdu: <input type="date" name="data" required><br><br>
        Godzina przyjazdu: <input type="time" name="godzina" required><br><br>
        <label><input type="checkbox" name="lozko_dziecko"> Łóżko dla dziecka</label><br><br>
        <label>Udogodnienia:</label><br>
        <label><input type="checkbox" name="udogodnienia[]" value="klimatyzacja"> Klimatyzacja</label><br>
        <label><input type="checkbox" name="udogodnienia[]" value="popielniczka"> Popielniczka</label><br><br>

        <h3>Dane uczestników</h3>
        <?php for ($i = 1; $i <= $ilosc_osob; $i++): ?>
            <fieldset style="margin-bottom: 15px;">
                <legend>Osoba <?= $i ?></legend>
                Imię: <input type="text" name="osoba[<?= $i ?>][imie]" required><br>
                Nazwisko: <input type="text" name="osoba[<?= $i ?>][nazwisko]" required><br>
                Wiek: <input type="number" name="osoba[<?= $i ?>][wiek]" min="0" required><br>
            </fieldset>
        <?php endfor; ?>

        <input type="hidden" name="ilosc_osob" value="<?= $ilosc_osob ?>">
        <input type="hidden" name="krok" value="3">
        <input type="submit" value="Zakończ rezerwację">
    </form>
    <?php
} elseif ($_POST["krok"] == "3") {
    $imie = htmlspecialchars($_POST["imie"]);
    $nazwisko = htmlspecialchars($_POST["nazwisko"]);
    $adres = htmlspecialchars($_POST["adres"]);
    $email = htmlspecialchars($_POST["email"]);
    $karta = htmlspecialchars($_POST["karta"]);
    $data = $_POST["data"];
    $godzina = $_POST["godzina"];
    $lozko = isset($_POST["lozko_dziecko"]) ? "Tak" : "Nie";
    $udogodnienia = isset($_POST["udogodnienia"]) ? $_POST["udogodnienia"] : [];
    $ilosc_osob = intval($_POST["ilosc_osob"]);
    $osoby = $_POST["osoba"];

    echo "<h2>✅ Rezerwacja zakończona pomyślnie!</h2>";
    echo "<h3>Dane rezerwującego:</h3>";
    echo "<p><strong>$imie $nazwisko</strong><br>Adres: $adres<br>Email: $email<br>";
    echo "Karta: **** **** **** " . substr($karta, -4) . "<br>";
    echo "Data: $data o $godzina<br>Łóżko dla dziecka: $lozko</p>";

    echo "<h3>Udogodnienia:</h3>";
    if (count($udogodnienia) > 0) {
        echo "<ul>";
        foreach ($udogodnienia as $u) {
            echo "<li>" . htmlspecialchars($u) . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>Brak</p>";
    }

    echo "<h3>Uczestnicy rezerwacji:</h3>";
    echo "<ol>";
    foreach ($osoby as $index => $osoba) {
        echo "<li><strong>" . htmlspecialchars($osoba["imie"]) . " " . htmlspecialchars($osoba["nazwisko"]) .
             "</strong>, Wiek: " . htmlspecialchars($osoba["wiek"]) . "</li>";
    }
    echo "</ol>";
}
?>

</body>
</html>
