<?php
session_start();
header('Content-Type: application/json');
include 'connect.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$action = $_POST['action'] ?? '';
$type = $_POST['type'] ?? ''; // for contacts or faqs

function respond($data) {
    echo json_encode($data);
    exit;
}

switch ($action) {
    case 'add-contact':
        $ctype = $_POST['contact_type'] ?? '';
        $cvalue = $_POST['contact_value'] ?? '';
        if (!$ctype || !$cvalue) respond(['error' => 'Missing fields']);
        $stmt = $conn->prepare("INSERT INTO about_contacts (type, value) VALUES (?, ?)");
        $stmt->bind_param("ss", $ctype, $cvalue);
        if ($stmt->execute()) {
            respond(['success' => true, 'id' => $stmt->insert_id, 'type' => $ctype, 'value' => $cvalue]);
        } else respond(['error' => 'Insert failed']);
        break;

    case 'edit-contact':
        $id = intval($_POST['id'] ?? 0);
        $ctype = $_POST['contact_type'] ?? '';
        $cvalue = $_POST['contact_value'] ?? '';
        if (!$id || !$ctype || !$cvalue) respond(['error' => 'Missing fields']);
        $stmt = $conn->prepare("UPDATE about_contacts SET type=?, value=? WHERE id=?");
        $stmt->bind_param("ssi", $ctype, $cvalue, $id);
        if ($stmt->execute()) {
            respond(['success' => true]);
        } else respond(['error' => 'Update failed']);
        break;

    case 'delete-contact':
        $id = intval($_POST['id'] ?? 0);
        if (!$id) respond(['error' => 'Missing ID']);
        $stmt = $conn->prepare("DELETE FROM about_contacts WHERE id=?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            respond(['success' => true]);
        } else respond(['error' => 'Delete failed']);
        break;

    case 'add-faq':
        $question = $_POST['question'] ?? '';
        $answer = $_POST['answer'] ?? '';
        if (!$question || !$answer) respond(['error' => 'Missing fields']);
        $stmt = $conn->prepare("INSERT INTO about_faqs (question, answer) VALUES (?, ?)");
        $stmt->bind_param("ss", $question, $answer);
        if ($stmt->execute()) {
            respond(['success' => true, 'id' => $stmt->insert_id, 'question' => $question, 'answer' => $answer]);
        } else respond(['error' => 'Insert failed']);
        break;

    case 'edit-faq':
        $id = intval($_POST['id'] ?? 0);
        $question = $_POST['question'] ?? '';
        $answer = $_POST['answer'] ?? '';
        if (!$id || !$question || !$answer) respond(['error' => 'Missing fields']);
        $stmt = $conn->prepare("UPDATE about_faqs SET question=?, answer=? WHERE id=?");
        $stmt->bind_param("ssi", $question, $answer, $id);
        if ($stmt->execute()) {
            respond(['success' => true]);
        } else respond(['error' => 'Update failed']);
        break;

    case 'delete-faq':
        $id = intval($_POST['id'] ?? 0);
        if (!$id) respond(['error' => 'Missing ID']);
        $stmt = $conn->prepare("DELETE FROM about_faqs WHERE id=?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            respond(['success' => true]);
        } else respond(['error' => 'Delete failed']);
        break;

    default:
        respond(['error' => 'Invalid action']);
}
