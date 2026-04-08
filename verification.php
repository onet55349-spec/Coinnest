<?php
$page_title = "Identity Verification";
$active_page = "profile";
require_once 'includes/header.php';
?>

    <div class="main-wrapper dash-wrapper" style="padding-top: 10px;">
        <header class="dash-header">
            <h1 class="reg-logo" style="margin-bottom: 0;">CoinNest</h1>
        </header>

        <div class="section-card" style="margin-top: 30px; text-align: center; padding: 40px 30px;">
            <div style="width: 70px; height: 70px; background: rgba(255,179,0,0.1); color: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 32px; margin: 0 auto 25px;">
                <i class="ph ph-identification-card"></i>
            </div>
            <h2 style="font-size: 24px; color: #fff; margin-bottom: 10px;">Identity Verification</h2>
            <p style="color: rgba(255,255,255,0.4); font-size: 14px; margin-bottom: 30px;">Upload a clear photo of your Government ID to verify your account.</p>

            <form action="api/submit_kyc.php" method="POST" enctype="multipart/form-data">
                <div class="input-group" style="text-align: left; margin-bottom: 20px;">
                    <label class="stat-label">Full Name as on ID</label>
                    <input type="text" name="full_name" class="admin-input" required placeholder="John Doe">
                </div>
                <div class="input-group" style="text-align: left; margin-bottom: 30px;">
                    <label class="stat-label">Upload ID Card Front</label>
                    <input type="file" name="id_front" class="admin-input" required accept="image/*" style="padding: 10px;">
                </div>
                <button type="submit" class="cta-btn primary-cta">Submit for Review</button>
            </form>
        </div>
    </div>

<?php require_once 'includes/footer.php'; ?>
