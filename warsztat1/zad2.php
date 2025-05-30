<h2>Sprawdz liczby pierwsze w podanym zakresie</h2>
        <form method="post">
            Od: <input type="number" name="od" required><br><br>
            Do: <input type="number" name="do" required><br><br>
            <input type="submit" value="Sprawdź">
        </form>
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["od"], $_POST["do"])) {
            $od = intval($_POST["od"]);
            $do = intval($_POST["do"]);

            if ($od > $do) {
                echo "<p style='color:red;'>Zakres jest nieprawidlowy – liczba poczatkowa musi byc mniejsza lub rowna koncowej.</p>";
            } else {
                echo "<h3>Liczby pierwsze od $od do $do:</h3>";
                for ($i = $od; $i <= $do; $i++) {
                    if (czyPierwsza($i)) {
                        echo $i . "<br>";
                    }
                }
            }
        }

        function czyPierwsza($n) {
            if ($n < 2) return false;
            for ($i = 2; $i * $i <= $n; $i++) {
                if ($n % $i == 0) return false;
            }
            return true;
        }
        ?>