<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$contentFile = __DIR__ . '/content.json';
$jsonData = file_get_contents($contentFile);
$data = json_decode($jsonData, true) ?? [];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Basic text fields
    $data['heroTitle'] = $_POST['heroTitle'] ?? '';
    $data['heroSubtitle'] = $_POST['heroSubtitle'] ?? '';
    $data['aboutTitle'] = $_POST['aboutTitle'] ?? '';
    $data['aboutText'] = $_POST['aboutText'] ?? '';
    $data['contactEmail'] = $_POST['contactEmail'] ?? '';
    $data['contactLinkedIn'] = $_POST['contactLinkedIn'] ?? '';
    $data['contactGitHub'] = $_POST['contactGitHub'] ?? '';

    // Profile pic upload
    if (!empty($_FILES['profilePic']['name'])) {
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        $fileName = $_FILES['profilePic']['name'];
        $fileTmp = $_FILES['profilePic']['tmp_name'];
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (in_array($ext, $allowedExtensions)) {
            $newName = 'profile_' . time() . '.' . $ext;
            $uploadPath = __DIR__ . '/uploads/' . $newName;

            if (move_uploaded_file($fileTmp, $uploadPath)) {
                $data['profilePic'] = 'uploads/' . $newName;
            } else {
                $error = "Failed to upload profile picture.";
            }
        } else {
            $error = "Invalid file type. Only JPG, PNG, and GIF are allowed.";
        }
    }

    // =============================
    // 1) Update Menu Links
    // =============================
    $updatedLinks = [];
    if (!empty($_POST['menuLinks']) && is_array($_POST['menuLinks'])) {
        foreach ($_POST['menuLinks'] as $link) {
            if (empty($link['remove'])) {
                // Keep link if not removed
                $updatedLinks[] = [
                    'label' => $link['label'] ?? '',
                    'url'   => $link['url']   ?? ''
                ];
            }
        }
    }
    // Add a new link if provided
    if (!empty($_POST['newLinkLabel']) || !empty($_POST['newLinkURL'])) {
        $updatedLinks[] = [
            'label' => $_POST['newLinkLabel'] ?? '',
            'url'   => $_POST['newLinkURL']   ?? ''
        ];
    }
    $data['menuLinks'] = $updatedLinks;

    // =============================
    // 2) Update Skills
    // =============================
    $updatedSkills = [];
    if (!empty($_POST['skills']) && is_array($_POST['skills'])) {
        foreach ($_POST['skills'] as $skill) {
            if (empty($skill['remove'])) {
                $updatedSkills[] = [
                    'name' => $skill['name'] ?? '',
                    'description' => $skill['description'] ?? ''
                ];
            }
        }
    }
    // New skill
    if (!empty($_POST['newSkillName']) || !empty($_POST['newSkillDescription'])) {
        $updatedSkills[] = [
            'name' => $_POST['newSkillName'] ?? '',
            'description' => $_POST['newSkillDescription'] ?? ''
        ];
    }
    $data['skills'] = $updatedSkills;

    // Save to JSON
    file_put_contents($contentFile, json_encode($data, JSON_PRETTY_PRINT));
    $message = "Content updated successfully!";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Panel | Ptero-Style Mobile</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- Mobile Header -->
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
    <?php
    // Show existing links from JSON
    if (!empty($data['menuLinks'])) {
      foreach ($data['menuLinks'] as $link) {
        echo '<a href="'.htmlspecialchars($link['url']).'">'.htmlspecialchars($link['label']).'</a>';
      }
    }
    ?>
    <button id="themeToggle" class="btn">Toggle Theme</button>
  </nav>
  <div class="sidebar-footer">
    <p>&copy; <?php echo date('Y'); ?></p>
  </div>
</div>

<!-- Main Content -->
<div class="main">
  <div class="main-header">
    <h2>Admin Dashboard</h2>
  </div>

  <?php if (!empty($message)): ?>
    <div class="panel">
      <p class="success-message"><?php echo htmlspecialchars($message); ?></p>
    </div>
  <?php endif; ?>
  <?php if (!empty($error)): ?>
    <div class="panel">
      <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
    </div>
  <?php endif; ?>

  <!-- Form Panel -->
  <div class="panel">
    <form action="admin.php" method="POST" enctype="multipart/form-data">
      <!-- Hero Section -->
      <h3>Hero Section</h3>
      <div class="form-group">
        <label for="heroTitle">Hero Title</label>
        <input type="text" name="heroTitle" id="heroTitle"
               value="<?php echo htmlspecialchars($data['heroTitle'] ?? ''); ?>">
      </div>
      <div class="form-group">
        <label for="heroSubtitle">Hero Subtitle</label>
        <input type="text" name="heroSubtitle" id="heroSubtitle"
               value="<?php echo htmlspecialchars($data['heroSubtitle'] ?? ''); ?>">
      </div>

      <!-- About Section -->
      <h3>About Section</h3>
      <div class="form-group">
        <label for="aboutTitle">About Title</label>
        <input type="text" name="aboutTitle" id="aboutTitle"
               value="<?php echo htmlspecialchars($data['aboutTitle'] ?? ''); ?>">
      </div>
      <div class="form-group">
        <label for="aboutText">About Text</label>
        <textarea name="aboutText" id="aboutText" rows="4">
          <?php echo htmlspecialchars($data['aboutText'] ?? ''); ?>
        </textarea>
      </div>

      <!-- Profile Pic -->
      <h3>Profile Picture</h3>
      <?php if (!empty($data['profilePic'])): ?>
        <div style="margin-bottom:0.5rem;">
          <img src="<?php echo htmlspecialchars($data['profilePic']); ?>"
               alt="Profile Picture"
               style="width:80px; height:80px; border-radius:50%; object-fit:cover;">
        </div>
      <?php endif; ?>
      <div class="form-group">
        <label for="profilePic">Upload New Picture</label>
        <input type="file" name="profilePic" id="profilePic">
      </div>

      <!-- Contact Info -->
      <h3>Contact Info</h3>
      <div class="form-group">
        <label for="contactEmail">Email</label>
        <input type="email" name="contactEmail" id="contactEmail"
               value="<?php echo htmlspecialchars($data['contactEmail'] ?? ''); ?>">
      </div>
      <div class="form-group">
        <label for="contactLinkedIn">LinkedIn URL</label>
        <input type="text" name="contactLinkedIn" id="contactLinkedIn"
               value="<?php echo htmlspecialchars($data['contactLinkedIn'] ?? ''); ?>">
      </div>
      <div class="form-group">
        <label for="contactGitHub">GitHub URL</label>
        <input type="text" name="contactGitHub" id="contactGitHub"
               value="<?php echo htmlspecialchars($data['contactGitHub'] ?? ''); ?>">
      </div>

      <!-- Menu Links -->
      <h3>Menu Links</h3>
      <?php if (!empty($data['menuLinks'])): ?>
        <?php foreach ($data['menuLinks'] as $i => $link): ?>
          <div class="form-group" style="display:flex; gap:0.5rem;">
            <input type="text" name="menuLinks[<?php echo $i; ?>][label]"
                   placeholder="Link Label"
                   style="width:30%;"
                   value="<?php echo htmlspecialchars($link['label']); ?>">
            <input type="text" name="menuLinks[<?php echo $i; ?>][url]"
                   placeholder="Link URL"
                   style="width:60%;"
                   value="<?php echo htmlspecialchars($link['url']); ?>">
            <label style="display:flex;align-items:center;color:#fff;">
              <input type="checkbox" name="menuLinks[<?php echo $i; ?>][remove]" value="1">
              &nbsp;Remove
            </label>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p>No menu links found. Add one below.</p>
      <?php endif; ?>
      <div class="form-group" style="display:flex; gap:0.5rem;">
        <input type="text" name="newLinkLabel" placeholder="New Link Label" style="width:30%;">
        <input type="text" name="newLinkURL" placeholder="New Link URL" style="width:60%;">
      </div>

      <!-- Skills -->
      <h3>Skills</h3>
      <?php if (!empty($data['skills'])): ?>
        <?php foreach ($data['skills'] as $index => $skill): ?>
          <div class="form-group" style="display:flex; gap:0.5rem;">
            <input type="text" name="skills[<?php echo $index; ?>][name]"
                   placeholder="Skill Name"
                   style="width:30%;"
                   value="<?php echo htmlspecialchars($skill['name']); ?>">
            <input type="text" name="skills[<?php echo $index; ?>][description]"
                   placeholder="Skill Description"
                   style="width:60%;"
                   value="<?php echo htmlspecialchars($skill['description']); ?>">
            <label style="display:flex;align-items:center;color:#fff;">
              <input type="checkbox" name="skills[<?php echo $index; ?>][remove]" value="1">
              &nbsp;Remove
            </label>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p>No skills found. Add one below.</p>
      <?php endif; ?>
      <div class="form-group" style="display:flex; gap:0.5rem;">
        <input type="text" name="newSkillName" placeholder="New Skill Name" style="width:30%;">
        <input type="text" name="newSkillDescription" placeholder="New Skill Description" style="width:60%;">
      </div>

      <button class="btn" type="submit">Save Changes</button>
    </form>
  </div>
</div>

<script src="script.js"></script>
</body>
</html>
