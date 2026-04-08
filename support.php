<?php
$page_title = "Customer Support";
$active_page = "support";
require_once 'includes/header.php';
?>

    <div class="main-wrapper dash-wrapper" style="padding-top: 10px;">
        <h2 class="section-title">Support Center</h2>
        <p style="font-size: 13px; color: rgba(255,255,255,0.4); margin-bottom: 25px;">How can we help you today?</p>

        <div class="section-card">
            <h3 style="color: #fff; margin-bottom: 20px;">Contact Support</h3>
            <div style="display: flex; flex-direction: column; gap: 20px;">
                <div style="background: rgba(255,255,255,0.02); padding: 20px; border-radius: 12px; display: flex; align-items: center; gap: 15px;">
                    <i class="ph-fill ph-chat-centered-text" style="font-size: 32px; color: var(--primary);"></i>
                    <div>
                        <h4 style="color: #fff;">Live Chat</h4>
                        <p style="font-size: 12px; color: rgba(255,255,255,0.4);">Average response time: 5 mins</p>
                    </div>
                </div>
                <div style="background: rgba(255,255,255,0.02); padding: 20px; border-radius: 12px; display: flex; align-items: center; gap: 15px;">
                    <i class="ph-fill ph-envelope-simple" style="font-size: 32px; color: var(--primary);"></i>
                    <div>
                        <h4 style="color: #fff;">Email Support</h4>
                        <p style="font-size: 12px; color: rgba(255,255,255,0.4);">support@coinnest.com</p>
                    </div>
                </div>
            </div>

            <form style="margin-top: 30px;">
                <div class="input-group">
                    <label class="stat-label">Subject</label>
                    <input type="text" class="admin-input" placeholder="What is your issue about?">
                </div>
                <div class="input-group" style="margin-top: 15px;">
                    <label class="stat-label">Message</label>
                    <textarea class="admin-input" rows="4" placeholder="Describe your problem in detail..."></textarea>
                </div>
                <button type="button" class="cta-btn primary-cta" style="margin-top: 15px;">Send Message</button>
            </form>
        </div>
    </div>

<?php require_once 'includes/footer.php'; ?>
