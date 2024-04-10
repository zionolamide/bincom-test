<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Submit Election Results</title>
<?php include_once('connection.php'); ?>
<style>
    /* CSS for the form */
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

    input[type="text"],
    input[type="number"] {
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
</style>
</head>
<body>
<?php include_once('header.php');?>
    <h2>Submit Election Results</h2>
    <form method="post">
        <label for="lga">Select Local Government:</label>
        <select id="lga" name="lga" required>
            <option value="">Select LGA</option>
            <?php
            // Include the connection file
            include 'connection.php';

            // Retrieve local governments from the database
            $sql = "SELECT * FROM lga";
            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['uniqueid'] . "'>" . $row['lga_name'] . "</option>";
                }
            }
            ?>
        </select>

        <label for="party_abbreviation">Party Abbreviation:</label>
        <select id="party_abbreviation" name="party_abbreviation" required>
            <option value="">Select Party</option>
            <?php
            // Include the connection file
            include 'connection.php';

            // Retrieve local governments from the database
            $sql = "SELECT * FROM party";
            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['id'] . "'>" . $row['partyname'] . "</option>";
                }
            }
            ?>
            <!-- Add options for other parties -->
        </select>

        <label for="party_score">Party Score:</label>
        <input type="number" id="party_score" name="party_score" required>

        <label for="entered_by_user">Your Name</label>
        <input type="text" id="entered_by_user" name="entered_by_user" required>

        <input type="submit" value="Submit">
    </form>

    <?php
    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Check if all form fields are set
        if (isset($_POST['lga'], $_POST['party_abbreviation'], $_POST['party_score'], $_POST['entered_by_user'])) {
            // Retrieve form data
            $lga_id = $_POST['lga'];
            $party_abbreviation = $_POST['party_abbreviation'];
            $party_score = $_POST['party_score'];
            $entered_by_user = $_POST['entered_by_user'];

            // Retrieve polling unit unique ID based on the selected LGA
            $sql = "SELECT uniqueid FROM polling_unit WHERE lga_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $lga_id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result && $result->num_rows > 0) {
                // Assuming the first polling unit found
                $row = $result->fetch_assoc();
                $polling_unit_id = $row['uniqueid'];

                // Insert the election result into the database
                $sql = "INSERT INTO announced_pu_results (polling_unit_uniqueid, party_abbreviation, party_score, entered_by_user) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("isss", $polling_unit_id, $party_abbreviation, $party_score, $entered_by_user);
                if ($stmt->execute()) {
                    echo "<p>Election results submitted successfully.</p>";
                } else {
                    echo "<p>Error: " . $conn->error . "</p>";
                }
            } else {
                echo "<p>Error: No polling unit found for the selected LGA.</p>";
            }

            // Close statement and connection
            $stmt->close();
        } else {
            echo "<p>Error: Form fields are not set.</p>";
        }
    }
    ?>
</body>
</html>
