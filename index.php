<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kalkulator zużycia energii</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        h1 {
            color: #4CAF50;
            text-align: center;
        }
        h2 {
            color: #4CAF50;
            text-align: center;
        }
        form {
            background-color: #e7f4e4;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .device {
            border: 1px solid #4CAF50; 
            padding: 15px;
            margin: 10px 0; 
            border-radius: 8px; 
            background-color: #ffffff; 
        }
        label {
            display: block;
            margin: 10px 0 5px;
        }
        input[type="number"] {
            width: calc(100% - 22px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 10px;
        }
        input[type="submit"],
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover,
        button:hover {
            background-color: #45a049;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #4CAF50;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Kalkulator zużycia energii</h1>
    <form method="POST" action="">
        <div id="deviceInputs">
            <?php
            if (isset($_POST['power']) && isset($_POST['time'])) {
                $powers = $_POST['power'];
                $times = $_POST['time'];
                for ($i = 0; $i < count($powers); $i++) {
                    echo '<div class="device">';
                    echo '<label>Moc urządzenia (W):</label>';
                    echo '<input type="number" name="power[]" value="' . htmlspecialchars($powers[$i]) . '" required>';
                    echo '<br>';
                    echo '<label>Czas użytkowania (godziny):</label>';
                    echo '<input type="number" name="time[]" value="' . htmlspecialchars($times[$i]) . '" required>';
                    echo '<br>';
                    echo '</div>';
                }
            } else {
                echo '<div class="device">';
                echo '<label>Moc urządzenia (W):</label>';
                echo '<input type="number" name="power[]" required>';
                echo '<br>';
                echo '<label>Czas użytkowania (godziny):</label>';
                echo '<input type="number" name="time[]" required>';
                echo '<br>';
                echo '</div>';
            }
            ?>
        </div>
        <button type="button" onclick="addDevice()">Dodaj urządzenie</button>
        <button type="button" onclick="removeDevice()">Usuń urządzenie</button>
        <br><br>
        <input type="hidden" id="rate" name="rate" value="0.70"> 
        <p>Stawka za kWh: 0,70 zł</p>
        <input type="submit" value="Oblicz">
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $powers = $_POST['power']; 
        $times = $_POST['time']; 
        $rate = 0.70; 
        $totalCost = 0; 
        
        echo "<h2>Wynik</h2>";
        echo "<table>";
        echo "<tr><th>Urządzenie</th><th>Zużycie energii (kWh)</th><th>Koszt (zł)</th></tr>";

        for ($i = 0; $i < count($powers); $i++) {
            $power = $powers[$i]; 
            $time = $times[$i]; 

            $energyConsumption = ($power / 1000) * $time; 
            $cost = $energyConsumption * $rate;
            $totalCost += $cost; 

            echo "<tr>";
            echo "<td>Urządzenie " . ($i + 1) . "</td>";
            echo "<td>" . number_format($energyConsumption, 2) . "</td>";
            echo "<td>" . number_format($cost, 2) . "</td>";
            echo "</tr>";
        }

        echo "<tr><td colspan='2'><strong>Łączny koszt:</strong></td><td>" . number_format($totalCost, 2) . " zł</td></tr>";
        echo "</table>";
    }
    ?>

    <script>
        function addDevice() {
            const deviceInputs = document.getElementById('deviceInputs');
            const newDevice = document.createElement('div');
            newDevice.classList.add('device');
            newDevice.innerHTML = `
                <label>Moc urządzenia (W):</label>
                <input type="number" name="power[]" required>
                <br>
                <label>Czas użytkowania (godziny):</label>
                <input type="number" name="time[]" required>
                <br>
            `;
            deviceInputs.appendChild(newDevice);
        }

        function removeDevice() {
            const deviceInputs = document.getElementById('deviceInputs');
            const devices = deviceInputs.getElementsByClassName('device');
            if (devices.length > 1) {
                deviceInputs.removeChild(devices[devices.length - 1]); 
            } else {
                alert("Musisz mieć przynajmniej jedno urządzenie.");
            }
        }
    </script>
</body>
</html>
