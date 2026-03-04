<?php
// test-db.php
$db_host = 'db.hmxrblblcpbikkxcwwni.supabase.co';
$db_name = 'postgres';
$db_user = 'postgres';
$db_pass = 'qkoczbdhdfcmqnoi';
$db_port = 6543;

try {
    $dsn = "pgsql:host=$db_host;port=$db_port;dbname=$db_name";
    $pdo = new PDO($dsn, $db_user, $db_pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_TIMEOUT => 5
    ]);
    
    echo "✅ Database connection successful!";
    
    // Test query
    $result = $pdo->query("SELECT NOW() as current_time");
    $row = $result->fetch(PDO::FETCH_ASSOC);
    echo "<br>Server time: " . $row['current_time'];
    
} catch (PDOException $e) {
    echo "❌ Database connection failed: " . $e->getMessage();
}
?>