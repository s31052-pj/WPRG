<?php

        $owoce = ["jablko", "banan", "pomarancza", "gruszka", "papaja"];

        foreach ($owoce as $owoc) {
            $odwrocony = "";
            $dlugosc = 0;

            while (isset($owoc[$dlugosc])) {
                $dlugosc++;
            }

            for ($i = $dlugosc - 1; $i >= 0; $i--) {
                $odwrocony .= $owoc[$i];
            }

            $czyP = ($owoc[0] === 'p') ? "Zaczyna sie na literę 'p'" : "Nie zaczyna się na litere 'p'";

            echo $odwrocony . " - " . $czyP . "<br>";
        }
?>