<?php
$contentFile = __DIR__ . '/content.json';
$jsonData = file_get_contents($contentFile);
$data = json_decode($jsonData, true) ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Pterodactyl-Inspired Portfolio (Mobile-Friendly, Editable Menu)</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- Mobile Header (small screens) -->
<div class="mobile-header">
  <span class="brand">My Panel</span>
  <div>
    <button id="mobileMenuBtn" class="mobile-menu-btn">Menu</button>
    <button id="mobileThemeBtn" class="mobile-theme-btn">Theme</button>
  </div>
</div>

<!-- Sidebar -->
<div class="sidebar hidden-mobile" id="sidebar">
  <div class="sidebar-header">
    <h1>My Panel</h1>
  </div>
  <nav>
    <?php if (!empty($data['menuLinks']) && is_array($data['menuLinks'])): ?>
      <?php foreach ($data['menuLinks'] as $link): ?>
        <a href="<?php echo htmlspecialchars($link['url']); ?>">
          <?php echo htmlspecialchars($link['label']); ?>
        </a>
      <?php endforeach; ?>
    <?php endif; ?>

    <!-- The theme toggle button stays separate -->
    <button id="themeToggle" class="btn">Toggle Theme</button>
  </nav>
  <div class="sidebar-footer">
    <p>&copy; <?php echo date('Y'); ?></p>
  </div>
</div>

<!-- Main Content -->
<div class="main">
  <!-- Hero Panel -->
  <div class="panel">
    <h3><?php echo htmlspecialchars($data['heroTitle'] ?? 'Welcome'); ?></h3>
    <p><?php echo htmlspecialchars($data['heroSubtitle'] ?? 'Pterodactyl-inspired layout'); ?></p>
  </div>

  <!-- About + Profile Picture -->
  <div class="panel">
    <h3><?php echo htmlspecialchars($data['aboutTitle'] ?? 'About'); ?></h3>
    <?php if (!empty($data['profilePic'])): ?>
      <div style="text-align:center; margin-bottom:1rem;">
        <img src="<?php echo htmlspecialchars($data['profilePic']); ?>" 
             alt="Profile Picture"
             style="width:120px; height:120px; border-radius:50%; object-fit:cover;">
      </div>
    <?php endif; ?>
    <p><?php echo nl2br(htmlspecialchars($data['aboutText'] ?? '')); ?></p>
  </div>

  <!-- Skills Section -->
  <h3 class="section-title">Skills</h3>
  <div class="panel">
    <?php if (!empty($data['skills']) && is_array($data['skills'])): ?>
      <ul style="list-style:none; padding:0;">
        <?php foreach ($data['skills'] as $skill): ?>
          <li style="margin-bottom:0.5rem;">
            <strong><?php echo htmlspecialchars($skill['name']); ?></strong> -
            <?php echo htmlspecialchars($skill['description']); ?>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php else: ?>
      <p>No skills found.</p>
    <?php endif; ?>
  </div>

  <!-- Projects Section -->
  <h3 class="section-title">Projects</h3>
  <?php if (!empty($data['projects']) && is_array($data['projects'])): ?>
    <?php foreach ($data['projects'] as $project): ?>
      <div class="panel">
        <h4><?php echo htmlspecialchars($project['title']); ?></h4>
        <p><?php echo htmlspecialchars($project['description']); ?></p>
        <a href="<?php echo htmlspecialchars($project['link']); ?>" target="_blank">View Project</a>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>

  <!-- Contact -->
  <h3 id="contact" class="section-title">Contact</h3>
  <div class="panel">
    <p>Email: <a href="mailto:<?php echo htmlspecialchars($data['contactEmail'] ?? ''); ?>">
      <?php echo htmlspecialchars($data['contactEmail'] ?? ''); ?></a></p>
    <p>LinkedIn: <a href="<?php echo htmlspecialchars($data['contactLinkedIn'] ?? ''); ?>" target="_blank">
      <?php echo htmlspecialchars($data['contactLinkedIn'] ?? ''); ?></a></p>
    <p>GitHub: <a href="<?php echo htmlspecialchars($data['contactGitHub'] ?? ''); ?>" target="_blank">
      <?php echo htmlspecialchars($data['contactGitHub'] ?? ''); ?></a></p>
  </div>
</div>

<script src="script.js"></script>
</body>
</html>
