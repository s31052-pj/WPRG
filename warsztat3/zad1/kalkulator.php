<!DOCTYPE html>
<html>
<head>
    <title>Prosty Kalkulator</title>
</head>
<body>
    <h2>Prosty Kalkulator</h2>
    <form method="post">
        Liczba 1: <input type="number" step="any" name="liczba1" required><br><br>
        Liczba 2: <input type="number" step="any" name="liczba2" required><br><br>
        Działanie:
        <select name="dzialanie">
            <option value="dodaj">Dodawanie</option>
            <option value="odejmij">Odejmowanie</option>
            <option value="mnozenie">Mnożenie</option>
            <option value="dzielenie">Dzielenie</option>
        </select><br><br>
        <input type="submit" value="Oblicz">
    </form>

    <?php
    require_once "funkcje.php";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $a = floatval($_POST["liczba1"]);
        $b = floatval($_POST["liczba2"]);
        $dzialanie = $_POST["dzialanie"];
        $wynik = "";

        switch ($dzialanie) {
            case "dodaj":
                $wynik = dodaj($a, $b);
                break;
            case "odejmij":
                $wynik = odejmij($a, $b);
                break;
            case "mnozenie":
                $wynik = mnoz($a, $b);
                break;
            case "dzielenie":
                $wynik = dziel($a, $b);
                break;
            default:
                $wynik = "Nieznane działanie.";
        }

        echo "<h3>Wynik: $wynik</h3>";
    }
    ?>
</body>
</html>