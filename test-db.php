<?php
// test-db.php
$db_host = 'aws-0-ap-southeast-1.pooler.supabase.com';
$db_port = 6543;
$db_name = 'postgres';
$db_user = 'postgres.hmxrblblcpbikkxcwwni';
$db_pass = 'qkoczbdhdfcmqnoi';

try {
    $dsn = "pgsql:host=$db_host;port=$db_port;dbname=$db_name;sslmode=require";
    $pdo = new PDO($dsn, $db_user, $db_pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    
    echo "✅ Database connection successful!\n\n";
    
    // Test query
    $result = $pdo->query("SELECT NOW() as time");
    $row = $result->fetch(PDO::FETCH_ASSOC);
    echo "Server time: " . $row['time'] . "\n";
    
    // Check if tables exist
    $tables = $pdo->query("
        SELECT table_name 
        FROM information_schema.tables 
        WHERE table_schema = 'public'
    ")->fetchAll();
    
    echo "\nTables in database:\n";
    foreach ($tables as $table) {
        echo "- " . $table['table_name'] . "\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "\n";
    echo "\nTroubleshooting tips:\n";
    echo "1. Check if host is correct: $db_host\n";
    echo "2. Check if username format is correct: $db_user\n";
    echo "3. Verify password\n";
    echo "4. Make sure port 6543 is not blocked\n";
}
?>