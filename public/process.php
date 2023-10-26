<?php
$servername = "localhost";
$username = "root";
$password = "MyNewPass";
$database = "ballbuckets";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['create_bucket'])) {
    $bucketName = $_POST['bucket_name'];
    $capacity = $_POST['capacity'];
    $sql = "INSERT INTO buckets (name, capacity) VALUES ('$bucketName', '$capacity')";
    if ($conn->query($sql) === TRUE) {
        echo "Bucket created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

if (isset($_POST['create_ball'])) {
    $color = $_POST['color'];
    $size = $_POST['size'];
    $quantity = $_POST['quantity'];
    $sql = "INSERT INTO balls (color, size, quantity) VALUES ('$color', '$size', '$quantity')";
    if ($conn->query($sql) === TRUE) {
        echo "Balls created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

if (isset($_POST['suggest_buckets'])) {
    $redBalls = $_POST['red_balls'];
    $blueBalls = $_POST['blue_balls'];
    
    // Calculate total size of balls brought from the shop
    $totalSize = $redBalls * 5 + $blueBalls * 3;
    
    // Fetch buckets from the database
    $buckets = [];
    $result = $conn->query("SELECT * FROM buckets");
    while($row = $result->fetch_assoc()) {
        $buckets[] = $row;
    }
    
    // Distribute balls into buckets
    foreach ($buckets as &$bucket) {
        while ($redBalls > 0 && $bucket['capacity'] >= 5) {
            $bucket['capacity'] -= 5;
            $redBalls--;
        }
        while ($blueBalls > 0 && $bucket['capacity'] >= 3) {
            $bucket['capacity'] -= 3;
            $blueBalls--;
        }
    }
    
    // Check if there are extra balls that couldn't fit in any bucket
    if ($redBalls > 0 || $blueBalls > 0) {
        echo "$redBalls Red Balls and $blueBalls Blue Balls cannot be accommodated in any bucket since there is no available space.";
    } else {
        echo "Buckets and Balls distributed successfully.";
    }
}

// Close MySQL connection
$conn->close();
?>

