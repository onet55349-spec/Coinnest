<?php
$page_title = "My Profile";
$active_page = "profile";
require_once 'includes/header.php';
?>

    <div class="main-wrapper dash-wrapper" style="padding-top: 10px;">
        <h2 class="section-title">Account Profile</h2>
        
        <div class="section-card" style="margin-top: 20px;">
            <div style="display: flex; align-items: center; gap: 20px; margin-bottom: 30px;">
                <div style="width: 80px; height: 80px; background: var(--primary); color: #000; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 32px; font-weight: 800;">
                    <?php echo strtoupper(substr($user['first_name'], 0, 1) . substr($user['last_name'], 0, 1)); ?>
                </div>
                <div>
                    <h3 style="font-size: 24px; color: #fff;"><?php echo $user['first_name'] . ' ' . $user['last_name']; ?></h3>
                    <p style="color: rgba(255,255,255,0.4);"><?php echo $user['email']; ?></p>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="input-group">
                    <label class="stat-label">First Name</label>
                    <input type="text" class="admin-input" value="<?php echo $user['first_name']; ?>" disabled>
                </div>
                <div class="input-group">
                    <label class="stat-label">Last Name</label>
                    <input type="text" class="admin-input" value="<?php echo $user['last_name']; ?>" disabled>
                </div>
                <div class="input-group">
                    <label class="stat-label">Address</label>
                    <input type="text" class="admin-input" value="<?php echo $user['address']; ?>" disabled>
                </div>
                <div class="input-group">
                    <label class="stat-label">State</label>
                    <input type="text" class="admin-input" value="<?php echo $user['state']; ?>" disabled>
                </div>
                <div class="input-group">
                    <label class="stat-label">Account Role</label>
                    <input type="text" class="admin-input" value="<?php echo strtoupper($user['role']); ?>" disabled>
                </div>
                <div class="input-group">
                    <label class="stat-label">KYC Status</label>
                    <input type="text" class="admin-input" value="<?php echo strtoupper($user['kyc_status']); ?>" disabled style="color: <?php echo ($user['kyc_status'] == 'verified' ? '#00c9a7' : '#ffb300'); ?>">
                </div>
            </div>

            <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.05);">
                <button class="cta-btn secondary-cta" onclick="window.location.href='logout.php'" style="background: rgba(255,77,77,0.1); color: #ff4d4d; border: 1px solid rgba(255,77,77,0.2);">Sign Out</button>
            </div>
        </div>
    </div>

<?php require_once 'includes/footer.php'; ?>
