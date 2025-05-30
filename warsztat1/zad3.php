<h2>Wyznaczanie nieparzystych elementow ciagu Fibonacciego</h2>
        <form method="post">
            Podaj N: <input type="number" name="n" min="1" required>
            <input type="submit" value="Oblicz">
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["n"])) {
            $n = intval($_POST["n"]);

            if ($n < 1) {
                echo "<p style='color:red;'>N musi byc wieksze od zera.</p>";
            } else {
                $fibonacci = [];
                $fibonacci[0] = 0;
                if ($n > 1) {
                    $fibonacci[1] = 1;
                    for ($i = 2; $i < $n; $i++) {
                        $fibonacci[$i] = $fibonacci[$i - 1] + $fibonacci[$i - 2];
                    }
                }

                echo "<h3>Nieparzyste elementy ciagu Fibonacciego:</h3>";
                $index = 1;
                for ($i = 0; $i < $n; $i++) {
                    if ($fibonacci[$i] % 2 != 0) {
                        echo $index . ". " . $fibonacci[$i] . "<br>";
                        $index++;
                    }
                }
            }
        }
        ?>