<?php
session_start();

$poprawny_login = "admin";
$poprawne_haslo = "admin";

if (isset($_POST['login']) && isset($_POST['haslo'])) {
    if ($_POST['login'] === $poprawny_login && $_POST['haslo'] === $poprawne_haslo) {
        $_SESSION['zalogowany'] = true;
        $_SESSION['login'] = $_POST['login'];
        setcookie("uzytkownik", $_POST['login'], time() + 3600);
    } else {
        $blad_logowania = "Niepoprawny login lub hasło.";
    }
}

if (isset($_GET['akcja']) && $_GET['akcja'] == "wyloguj") {
    session_unset();
    session_destroy();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

if (isset($_POST['zapisz_formularz'])) {
    foreach ($_POST as $klucz => $wartosc) {
        if ($klucz !== 'zapisz_formularz') {
            setcookie("formularz_$klucz", $wartosc, time() + 3600);
        }
    }
}

if (isset($_POST['wyczysc_formularz'])) {
    foreach ($_COOKIE as $klucz => $wartosc) {
        if (strpos($klucz, 'formularz_') === 0) {
            setcookie($klucz, '', time() - 3600);
        }
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Panel Rezerwacji Hotelowej</title>
</head>
<body>
<h2>System Rezerwacji</h2>

<?php if (!isset($_SESSION['zalogowany'])): ?>
    <h3>Logowanie</h3>
    <?php if (isset($blad_logowania)) echo "<p style='color:red;'>$blad_logowania</p>"; ?>
    <form method="post">
        Login: <input type="text" name="login" required><br><br>
        Hasło: <input type="password" name="haslo" required><br><br>
        <input type="submit" value="Zaloguj">
    </form>
    <p style="color:gray;">Dostęp do formularza rezerwacji wymaga logowania. Zaloguj się, aby kontynuować.</p>
<?php else: ?>
    <p>Witaj, <?= htmlspecialchars($_COOKIE['uzytkownik'] ?? $_SESSION['login']) ?>! <a href="?akcja=wyloguj">Wyloguj</a></p>

    <h3>Formularz Rezerwacji</h3>
    <form method="post">
        Imię: <input type="text" name="imie" value="<?= $_COOKIE['formularz_imie'] ?? '' ?>" required><br><br>
        Nazwisko: <input type="text" name="nazwisko" value="<?= $_COOKIE['formularz_nazwisko'] ?? '' ?>" required><br><br>
        E-mail: <input type="email" name="email" value="<?= $_COOKIE['formularz_email'] ?? '' ?>" required><br><br>
        Data pobytu: <input type="date" name="data" value="<?= $_COOKIE['formularz_data'] ?? '' ?>" required><br><br>
        Godzina przyjazdu: <input type="time" name="godzina" value="<?= $_COOKIE['formularz_godzina'] ?? '' ?>" required><br><br>

        <input type="submit" name="zapisz_formularz" value="Zapisz dane w ciasteczkach">
        <input type="submit" name="wyczysc_formularz" value="Wyczyść formularz">
    </form>
<?php endif; ?>
</body>
</html>