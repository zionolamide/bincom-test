<?php
// Include the connection file
include 'connection.php';

// Retrieve local governments from the database
$sql = "SELECT * FROM lga";
$result = $conn->query($sql);

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get selected local government ID
    $selected_lga_id = $_POST['local_government'];

    // Retrieve all polling units under the selected local government
    $sql = "SELECT * FROM polling_unit WHERE lga_id = $selected_lga_id";
    $polling_units_result = $conn->query($sql);

    // Initialize an array to store party scores
    $party_scores = array();

    // Loop through each polling unit
    while ($row = $polling_units_result->fetch_assoc()) {
        $polling_unit_id = $row['uniqueid'];

        // Retrieve election results for the polling unit
        $sql = "SELECT party_abbreviation, SUM(party_score) AS total_score FROM announced_pu_results WHERE polling_unit_uniqueid = $polling_unit_id GROUP BY party_abbreviation";
        $election_results_result = $conn->query($sql);

        // Calculate the total score for each party across all polling units
        while ($result_row = $election_results_result->fetch_assoc()) {
            $party_abbreviation = $result_row['party_abbreviation'];
            $total_score = $result_row['total_score'];

            // Add the total score to the party_scores array
            if (!isset($party_scores[$party_abbreviation])) {
                $party_scores[$party_abbreviation] = 0;
            }
            $party_scores[$party_abbreviation] += $total_score;
        }
    }

    // Display the summed total result in a table
    echo "<h2>Summed Total Result</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Party Abbreviation</th><th>Total Score</th></tr>";
    foreach ($party_scores as $party_abbreviation => $total_score) {
        echo "<tr><td>$party_abbreviation</td><td>$total_score</td></tr>";
    }
    echo "</table>";
}
?>
<style>
    /* CSS for the HTML form */
    form {
        margin: 20px;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 5px;
        background-color: #f9f9f9;
        width: 300px;
    }

    label {
        display: block;
        margin-bottom: 10px;
    }

    select {
        width: 100%;
        padding: 8px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    input[type="submit"] {
        width: 100%;
        padding: 10px;
        background-color: #4caf50;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    /* CSS for the result table */
    table {
        margin: 20px;
        border-collapse: collapse;
        width: 400px;
    }

    th, td {
        padding: 8px;
        border: 1px solid #ddd;
    }

    th {
        background-color: #f2f2f2;
    }

    tr:nth-child(even) {
        background-color: #f9f9f9;
    }
</style>
<?php include_once('header.php');?><!-- HTML Form -->
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="local_government">Select Local Government:</label>
    <select id="local_government" name="local_government">
        <?php
        // Check if $result is set and not empty
        if ($result && $result->num_rows > 0) {
            // Display options for local governments
            while ($row = $result->fetch_assoc()) {
                echo "<option value='" . $row['uniqueid'] . "'>" . $row['lga_name'] . "</option>";
            }
        } else {
            // Display an error message if no local governments are found
            echo "<option value=''>No local governments found</option>";
        }
        ?>
    </select>
    <input type="submit" value="Submit">
</form>
