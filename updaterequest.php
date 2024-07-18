<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bloodbank";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$req_id = $_GET["req_id"];
$bloodgroup = $_GET["bloodgroup"];
echo $bloodgroup;
// Update stock table: decrease stock for the given blood group
$sql = "UPDATE stock SET stock = stock - 1 WHERE bloodgroup = '$bloodgroup'";
// Execute the stock update query
if ($conn->query($sql) === TRUE) {
    // Check if any rows were affected
    if ($conn->affected_rows > 0) {
        // If the stock was successfully updated, proceed to delete the request record
        $sql2 = "DELETE FROM request WHERE req_id='$req_id'";

        // Execute the delete query
        if ($conn->query($sql2) === TRUE) {
            echo "Successfully accepted and updated stock.";
            // Redirect to adminhome.php
            header("Location: adminhome.php");
            exit(); // Ensure script stops executing after redirection
        } else {
            echo "Error deleting record: " . $conn->error;
        }
    } else {
        echo "No rows were updated.";
    }
} else {
    echo "Error updating stock: " . $conn->error;
}

$conn->close();
