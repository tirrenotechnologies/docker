<?php

declare(strict_types=1);

chdir(dirname(__FILE__));

$databaseUrl = getenv('DATABASE_URL');

$parts = parse_url($databaseUrl);

if (!is_array($parts)) {
    throw new \InvalidArgumentException('Invalid DSN format');
}

//$schm = $parts['scheme'] ?? '';
$host = $parts['host'] ?? '';
$port = $parts['port'] ?? 5432;
$user = $parts['user'] ?? '';
$pass = $parts['pass'] ?? '';
$path = $parts['path'] ?? '';

if (!$host || !$port || !$user || !$pass || !$path) {
    throw new \InvalidArgumentException('Invalid DSN format');
}

$database = ltrim($path, '/');

$dsn = sprintf('pgsql:host=%s;port=%s;dbname=%s', $host, $port, $database);

$driverOptions = [
    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
];

$database = new \PDO($dsn, $user, $pass, $driverOptions);

$sql = file_get_contents('./install.sql', false, null);

$database->exec($sql);

// create config

$configData = [
    'FORCE_HTTPS'   => getenv('FORCE_HTTPS') ?: 'false',
    'SITE'          => getenv('SITE') ?: 'localhost',
    'DATABASE_URL'  => $databaseUrl,
    'PEPPER'        => strval(bin2hex(random_bytes(32))),
];


$config = "\n[globals]";
foreach ($configData as $key => $value) {
    $value = str_replace('\\', '\\\\', $value);
    $value = str_replace('"', '\\"', $value);
    $value = '"' . $value . '"';

    $config .= "\n$key=$value";
}

$config .= "\n";

$configPath = '../config/local/config.local.ini';
$configFile = fopen($configPath, 'w');
fwrite($configFile, $config);
fclose($configFile);
