<?php
            $text = "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has
            been the industry's standard dummy text ever since the 1500s, when an unknown printer took a
            galley of type and scrambled it to make a type specimen book. It has survived not only five
            centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was
            popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages,
            and more recently with desktop publishing software like Aldus PageMaker including versions of
            Lorem Ipsum.";

            $tablica = array_filter(explode(" ", $text), function($el) {
                return $el !== "";
            });
            $tablica = array_values($tablica);


            $interpunkcyjne = [".", ",", "'", "\"", ";", ":", "?", "!", "(", ")", "-", "—"];

            for ($i = 0; $i < count($tablica); $i++) {
                $czyUsunac = false;
                for ($j = 0; $j < strlen($tablica[$i]); $j++) {
                    foreach ($interpunkcyjne as $znak) {
                        if ($tablica[$i][$j] == $znak) {
                            $czyUsunac = true;
                            break 2;
                        }
                    }
                }

                if ($czyUsunac) {
                    for ($k = $i; $k < count($tablica) - 1; $k++) {
                        $tablica[$k] = $tablica[$k + 1];
                    }
                    array_pop($tablica);
                    $i--;
                }
            }
    
            $asoc = [];
            for ($i = 0; $i < count($tablica) - 1; $i += 2) {
                $klucz = $tablica[$i];
                $wartosc = $tablica[$i + 1];
                if ($klucz !== "" && $wartosc !== "") {
                    $asoc[$klucz] = $wartosc;
                }
            }

            foreach ($asoc as $klucz => $wartosc) {
                echo "$klucz => $wartosc<br>";
            }
        ?>