<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

header("Content-Type: application/json");

// Database connection settings
function getDatabaseConnection() {
    $host = '127.0.0.1';
    $db = 'sky_survey_db';
    $user = 'root';
    $pass = '';
    $charset = 'utf8mb4';
    $port = 3306;

    $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    try {
        return new PDO($dsn, $user, $pass, $options);
    } catch (\PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]);
        exit;
    }
}

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        handleGetRequest();
        break;

    case 'POST':
        handlePostRequest();
        break;

    default:
        echo json_encode(['error' => 'Method not supported']);
        http_response_code(405);
}
function handleGetRequest() {
    $pdo = getDatabaseConnection();

    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $recordsPerPage = isset($_GET['records_per_page']) ? (int)$_GET['records_per_page'] : 10;
    $offset = ($page - 1) * $recordsPerPage;

    try {
        $totalStmt = $pdo->query("SELECT COUNT(*) FROM survey_responses");
        $totalCount = $totalStmt->fetchColumn();
        $totalPages = ceil($totalCount / $recordsPerPage);

        $stmt = $pdo->prepare("SELECT * FROM survey_responses LIMIT :offset, :recordsPerPage");
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindParam(':recordsPerPage', $recordsPerPage, PDO::PARAM_INT);
        $stmt->execute();
        $responses = $stmt->fetchAll();

        echo json_encode([
            'data' => $responses,
            'total_pages' => $totalPages,
            'current_page' => $page,
            'total_records' => $totalCount
        ]);
    } catch (\PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Query failed: ' . $e->getMessage()]);
    }
}


function handlePostRequest() {
    $pdo = getDatabaseConnection();

    $uploadedFiles = [];
    $upload_dir = "C:/xampp/htdocs/simple_survey_client/uploads/";

    // Check if files are uploaded
    if (!empty($_FILES['certificates']['name'][0])) {
        foreach ($_FILES['certificates']['name'] as $key => $name) {
            $file_tmp = $_FILES['certificates']['tmp_name'][$key];
            $file_name = basename($_FILES['certificates']['name'][$key]); // basename() may prevent filesystem traversal attacks
            $targetFilePath = $upload_dir . $file_name;

            // Ensure the upload directory exists
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            if (move_uploaded_file($file_tmp, $targetFilePath)) {
                $uploadedFiles[] = $file_name;
            } else {
                // Respond with JSON without exiting the script, so the loop can continue for other files
                echo json_encode(['status' => 'failure', 'error' => "Failed to upload file: $file_name"]);
                continue; // Skip this iteration and try the next file
            }
        }
    }

    // Check if any files have been uploaded successfully
    if (!empty($uploadedFiles)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO survey_responses (full_name, email_address, description, gender, programming_stack, certificates, date_responded) VALUES (?, ?, ?, ?, ?, ?, NOW())");
            $success = $stmt->execute([
                $_POST['full_name'],
                $_POST['email_address'],
                $_POST['description'],
                $_POST['gender'],
                implode(',', $_POST['programming_stack'] ?? []),
                implode(',', $uploadedFiles)
            ]);

            // Respond with JSON
            if ($success) {
                echo json_encode(['status' => 'success', 'uploaded_files' => $uploadedFiles]);
            } else {
                echo json_encode(['status' => 'failure', 'error' => 'Failed to insert survey response into the database.']);
            }
        } catch (\PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Insert query failed: ' . $e->getMessage()]);
        }
    } else {
        // If no files were uploaded successfully, respond with an error
        echo json_encode(['status' => 'failure', 'error' => 'No certificates were uploaded or file upload failed.']);
    }
}


?>
