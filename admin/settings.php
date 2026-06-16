<?php
require_once '../config/database.php';
require_once 'includes/auth.php';

// Handle Form Submission
if(isset($_POST['update_hero'])) {
    $title = mysqli_real_escape_string($conn, $_POST['hero_title']);
    $subtitle = mysqli_real_escape_string($conn, $_POST['hero_subtitle']);
    $title_size = mysqli_real_escape_string($conn, $_POST['hero_title_size']);
    
    // Update texts
    mysqli_query($conn, "UPDATE settings SET setting_value='$title' WHERE setting_key='hero_title'");
    mysqli_query($conn, "UPDATE settings SET setting_value='$subtitle' WHERE setting_key='hero_subtitle'");
    mysqli_query($conn, "UPDATE settings SET setting_value='$title_size' WHERE setting_key='hero_title_size'");
    
    // Handle Image Upload
    if(isset($_FILES['hero_image']) && $_FILES['hero_image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $filename = $_FILES['hero_image']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if(in_array($ext, $allowed)) {
            $new_name = 'hero_banner_' . time() . '.' . $ext;
            $destination = '../assets/images/' . $new_name;
            
            if(move_uploaded_file($_FILES['hero_image']['tmp_name'], $destination)) {
                mysqli_query($conn, "UPDATE settings SET setting_value='$new_name' WHERE setting_key='hero_image'");
            }
        }
    }
    
    header("Location: settings.php?success=1");
    exit;
}

require_once 'includes/header.php';

// Fetch current settings
$settings_result = mysqli_query($conn, "SELECT * FROM settings");
$settings = [];
while($row = mysqli_fetch_assoc($settings_result)) {
    $settings[$row['setting_key']] = $row['setting_value'];
}
?>

<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">Website Settings</h1>
</div>

<?php if(isset($_GET['success'])): ?>
    <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6">
        <p class="text-green-700">Settings updated successfully.</p>
    </div>
<?php endif; ?>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 max-w-2xl">
    <h3 class="text-xl font-medium text-gray-900 mb-6 border-b pb-4">Hero Section Configuration</h3>
    
    <form action="settings.php" method="POST" enctype="multipart/form-data">
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Current Hero Image</label>
            <div class="h-48 w-full bg-gray-100 rounded-lg overflow-hidden border border-gray-300">
                <img src="<?php echo get_image_url($settings['hero_image']); ?>" class="w-full h-full object-cover">
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700">Upload New Image (Optional)</label>
            <input type="file" name="hero_image" accept="image/*" class="mt-2 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            <p class="mt-2 text-sm text-gray-500">
                <span class="font-semibold">Recommended size:</span> 800x800 pixels (Square). <br>
                For the best seamless "cutout" effect, use an image with a pure white background.
            </p>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700">Hero Headline (Title)</label>
            <input type="text" name="hero_title" value="<?php echo htmlspecialchars($settings['hero_title']); ?>" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring-accent sm:text-sm p-3 border">
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700">Hero Title Size</label>
            <select name="hero_title_size" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring-accent sm:text-sm p-3 border">
                <option value="small" <?php echo (isset($settings['hero_title_size']) && $settings['hero_title_size'] == 'small') ? 'selected' : ''; ?>>Small</option>
                <option value="medium" <?php echo (isset($settings['hero_title_size']) && $settings['hero_title_size'] == 'medium') ? 'selected' : ''; ?>>Medium</option>
                <option value="large" <?php echo (!isset($settings['hero_title_size']) || $settings['hero_title_size'] == 'large') ? 'selected' : ''; ?>>Large (Default)</option>
            </select>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700">Hero Subtitle / Description</label>
            <textarea name="hero_subtitle" rows="3" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring-accent sm:text-sm p-3 border"><?php echo htmlspecialchars($settings['hero_subtitle']); ?></textarea>
        </div>

        <div class="pt-4 border-t border-gray-200">
            <button type="submit" name="update_hero" class="bg-accent text-white py-3 px-6 rounded-md hover:bg-blue-700 font-medium transition-colors">Save Changes</button>
        </div>
    </form>
</div>

<?php require_once 'includes/footer.php'; ?>
