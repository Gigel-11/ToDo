<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
        echo "<h1>DAW-241</h1>";
    ?>
    <?php
        $mesaj = "Salut in consola";
        echo "<script>console.log('$mesaj');</script>";
    ?>

    <?php
        $numere = [3, 8, 15, 34, 7, 41, 11, 6, 19, 30];

        $pare = 0;
        $impare = 0;

        for ($i = 0; $i < count($numere); $i++) {
            if ($numere[$i] % 2 == 0) {
                $pare++;
            } else {
                $impare++;
            }
        }

        echo "<p>Numere pare: $pare</p>";
        echo "<p>Numere impare: $impare</p>";
    ?>

    <script src="js/script.js"></script>
</body>
</html>