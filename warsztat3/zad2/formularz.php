<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Zapis do pliku</title>
</head>
<body>

<h2>Formularz zapisu danych</h2>

<form method="post">
    Imię: <input type="text" name="imie" required><br><br>
    Nazwisko: <input type="text" name="nazwisko" required><br><br>
    Wiadomość: <textarea name="wiadomosc" rows="4" cols="30" required></textarea><br><br>
    <input type="submit" value="Zapisz do pliku">
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $imie = trim($_POST["imie"]);
    $nazwisko = trim($_POST["nazwisko"]);
    $wiadomosc = trim($_POST["wiadomosc"]);

    // Łączenie danych w jedną linię
    $linia = "$imie $nazwisko: $wiadomosc" . PHP_EOL;

    // Ścieżka do pliku docelowego
    $plik = "dane.txt";

    // Dopisanie do pliku
    if (file_put_contents($plik, $linia, FILE_APPEND | LOCK_EX)) {
        echo "<p style='color:green;'>Dane zostały zapisane do pliku!</p>";
    } else {
        echo "<p style='color:red;'>Błąd przy zapisie do pliku.</p>";
    }
}
?>

</body>
</html>