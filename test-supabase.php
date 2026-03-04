<?php
// test-supabase.php
echo "<h2>Testing Supabase Configuration</h2>";

// Test 1: Supabase URL
$supabase_url = 'https://hmxrblblcpbikkxcwwni.supabase.co';
echo "<h3>Test 1: Supabase URL</h3>";
echo "URL: " . $supabase_url . "<br>";
echo "Status: " . (filter_var($supabase_url, FILTER_VALIDATE_URL) ? "✅ Valid" : "❌ Invalid") . "<br>";

// Test 2: Database Connection
echo "<h3>Test 2: Database Connection</h3>";
$db_host = 'aws-1-ap-southeast-2.pooler.supabase.com';
$db_port = 6543;
$db_name = 'postgres';
$db_user = 'postgres.hmxrblblcpbikkxcwwni';
$db_pass = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImhteHJibGJsY3BiaWtreGN3d25pIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzIyODY0MDksImV4cCI6MjA4Nzg2MjQwOX0.qC4Lm2KbToc0f1syHpMWJmQqRhQTosNfFzBrfTXSWDw';

try {
    $dsn = "pgsql:host=$db_host;port=$db_port;dbname=$db_name;sslmode=require";
    $pdo = new PDO($dsn, $db_user, $db_pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_TIMEOUT => 5
    ]);
    echo "✅ Database connection successful!<br>";
    
    // Check tables
    $result = $pdo->query("SELECT table_name FROM information_schema.tables WHERE table_schema='public'");
    echo "Tables found:<br>";
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        echo "- " . $row['table_name'] . "<br>";
    }
    
} catch (PDOException $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "<br>";
}

// Test 3: Supabase Auth API
echo "<h3>Test 3: Supabase Auth API</h3>";
$ch = curl_init($supabase_url . '/auth/v1/settings');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'apikey: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImhteHJibGJsY3BiaWtreGN3d25pIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzIyODY0MDksImV4cCI6MjA4Nzg2MjQwOX0.qC4Lm2KbToc0f1syHpMWJmQqRhQTosNfFzBrfTXSWDw'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode === 200) {
    echo "✅ Supabase Auth API is accessible<br>";
} else {
    echo "❌ Supabase Auth API error (HTTP $httpCode)<br>";
}
?>