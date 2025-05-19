<?php
// about-data.php

include 'connect.php';

// -------- Store History --------
$store_history = "Tonishen's Kitchen started as a small family-owned business in 2010...";
$res = $conn->query("SELECT content FROM about_history WHERE id = 1");
if ($res && $row = $res->fetch_assoc()) {
    $store_history = $row['content'];
}

// -------- Contact Info --------
$contact_info = [
    'mobile'    => '',
    'telephone' => '',
    'email'     => '',
    'address'   => ''
];
$res = $conn->query("SELECT type, value FROM about_contacts");
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $key = strtolower($row['type']);
        if (isset($contact_info[$key])) {
            $contact_info[$key] = $row['value'];
        }
    }
}

// -------- Social Media --------
$social_media = [];
$res = $conn->query("SELECT type, value FROM about_contacts");
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $key = strtolower($row['type']);
        if (!in_array($key, ['mobile', 'telephone', 'email', 'address'])) {
            $social_media[] = [
                'platform' => $row['type'],
                'url'      => $row['value'],
            ];
        }
    }
}

// -------- FAQs --------
$faqs = [];
$res = $conn->query("SELECT question, answer FROM about_faqs ORDER BY id");
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $faqs[] = $row;
    }
}

// -------- Admin Note --------
$admin_note = "Admin can update all content via the admin panel.";
?>
