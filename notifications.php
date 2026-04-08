<?php
$page_title = "Notifications";
$active_page = "notifications";
require_once 'includes/header.php';

// Fetch user notifications (if the table exists - I didn't add it to schema yet, but I'll add a simple fetch)
// For now, let's keep it static but in PHP
?>

    <div class="main-wrapper dash-wrapper" style="padding-top: 10px;">
        <h2 class="section-title">Notifications</h2>
        
        <div class="section-card" style="margin-top: 20px;">
            <div style="display: flex; flex-direction: column; gap: 15px;">
                <div style="background: rgba(0,201,167,0.1); border: 1px solid rgba(0,201,167,0.2); padding: 15px; border-radius: 12px; display: flex; gap: 15px; align-items: start;">
                    <i class="ph-fill ph-check-circle" style="color: #00c9a7; font-size: 20px;"></i>
                    <div>
                        <h4 style="color: #fff; font-size: 14px;">Welcome to CoinNest!</h4>
                        <p style="font-size: 12px; color: rgba(255,255,255,0.6); margin-top: 4px;">Your account has been successfully created. You can now start trading.</p>
                        <span style="font-size: 10px; color: rgba(255,255,255,0.3); display: block; margin-top: 8px;">Just now</span>
                    </div>
                </div>
                
                <div style="background: rgba(255,255,255,0.02); padding: 15px; border-radius: 12px; display: flex; gap: 15px; align-items: start; opacity: 0.6;">
                    <i class="ph ph-bell" style="color: var(--primary); font-size: 20px;"></i>
                    <div>
                        <h4 style="color: #fff; font-size: 14px;">Identity Verification</h4>
                        <p style="font-size: 12px; color: rgba(255,255,255,0.6); margin-top: 4px;">Please complete your KYC to unlock full withdrawal capabilities.</p>
                        <span style="font-size: 10px; color: rgba(255,255,255,0.3); display: block; margin-top: 8px;">2 hours ago</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php require_once 'includes/footer.php'; ?>
