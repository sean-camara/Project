<?php
// about.php
include 'about-data.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>About Us - Tonishen's Kitchen</title>
    <link rel="stylesheet" href="about-style.css" />
</head>
<body>
    <main class="container">
        <section id="history" class="fade-in">
            <h2>Our History</h2>
            <p><?= nl2br(htmlspecialchars($store_history)) ?></p>
        </section>

        <section id="contact" class="fade-in">
            <h2>Contact Us</h2>
            <ul>
                <li><strong>Mobile:</strong> <?= htmlspecialchars($contact_info['mobile']) ?></li>
                <li><strong>Telephone:</strong> <?= htmlspecialchars($contact_info['telephone']) ?></li>
                <li><strong>Email:</strong> <a id="link" href="mailto:<?= htmlspecialchars($contact_info['email']) ?>"><?= htmlspecialchars($contact_info['email']) ?></a></li>
                <li><strong>Address:</strong> <?= htmlspecialchars($contact_info['address']) ?></li>
            </ul>
            <div class="social-media">
                <h3>Follow Us</h3>
                <ul>
                    <?php foreach ($social_media as $social): ?>
                        <li><a href="<?= htmlspecialchars($social['url']) ?>" target="_blank" rel="noopener noreferrer"><?= htmlspecialchars($social['platform']) ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </section>

        <section id="faqs" class="fade-in">
            <h2>Frequently Asked Questions</h2>
            <div class="faq-list">
                <?php foreach ($faqs as $faq): ?>
                    <details>
                        <summary><?= htmlspecialchars($faq['question']) ?></summary>
                        <p><?= htmlspecialchars($faq['answer']) ?></p>
                    </details>
                <?php endforeach; ?>
            </div>
        </section>

        <section id="admin-note" class="fade-in">
            <p><em><?= htmlspecialchars($admin_note) ?></em></p>
        </section>
    </main>

    <script src="about-script.js"></script>
</body>
</html>
