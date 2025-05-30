<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Czy liczba jest pierwsza?</title>
</head>
<body>

<h2>Sprawdź, czy liczba jest pierwsza</h2>

<form method="post">
    <label>Wpisz liczbę całkowitą dodatnią:
        <input type="number" name="liczba" min="1" required>
    </label>
    <br><br>
    <input type="submit" value="Sprawdź">
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $liczba = $_POST["liczba"];

    if (!ctype_digit($liczba) || intval($liczba) < 1) {
        echo "<p style='color:red;'>Podaj poprawną liczbę całkowitą dodatnią.</p>";
    } else {
        $liczba = intval($liczba);
        $iteracje = 0;

        function czyPierwsza($n, &$iteracje) {
            if ($n < 2) return false;
            if ($n == 2) return true;
            if ($n % 2 == 0) {
                $iteracje++;
                return false;
            }

            for ($i = 3; $i * $i <= $n; $i += 2) {
                $iteracje++;
                if ($n % $i == 0) {
                    return false;
                }
            }
            return true;
        }

        $czy = czyPierwsza($liczba, $iteracje);

        echo "<h3>Wynik:</h3>";
        echo "<p>Liczba <strong>$liczba</strong> " . ($czy ? "jest" : "nie jest") . " liczbą pierwszą.</p>";
        echo "<p>Ilość iteracji pętli: <strong>$iteracje</strong></p>";
    }
}
?>

</body>
</html>
