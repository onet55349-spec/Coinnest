<?php
$page_title = "Trade History";
$active_page = "history";
require_once 'includes/header.php';
require_once 'includes/functions.php';

// Fetch user trade history
$stmt = $pdo->prepare("SELECT * FROM trades WHERE user_id = ? ORDER BY created_at DESC LIMIT 100");
$stmt->execute([$_SESSION['user_id']]);
$trades = $stmt->fetchAll();
?>

    <div class="main-wrapper dash-wrapper" style="padding-top: 10px;">
        <h2 class="section-title">Trade History</h2>
        <p style="font-size: 13px; color: rgba(255,255,255,0.4); margin-bottom: 25px;">Review all your past trades and their outcomes.</p>

        <div class="section-card" style="background: rgba(255,255,255,0.02); border-radius: 24px; padding: 20px; border: 1px solid rgba(255,255,255,0.05);">
            <table class="trades-table">
                <thead>
                    <tr>
                        <th>Asset / Date</th>
                        <th style="text-align: center;">Details</th>
                        <th style="text-align: right;">Result</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($trades)): ?>
                        <tr><td colspan="3" style="text-align: center; padding: 40px; opacity: 0.3;">No trade records found.</td></tr>
                    <?php else: ?>
                        <?php foreach ($trades as $trade): ?>
                            <?php 
                                $isWin = ($trade['status'] == 'win');
                                $profit = $isWin ? ($trade['amount'] * 0.85) : -$trade['amount'];
                            ?>
                            <tr>
                                <td>
                                    <div style="font-weight: 700; color: #fff;"><?php echo $trade['asset']; ?></div>
                                    <div style="font-size: 11px; opacity: 0.4;"><?php echo date('M d, H:i', strtotime($trade['created_at'])); ?></div>
                                </td>
                                <td style="text-align: center;">
                                    <div style="font-weight: 600; color: #fff;"><?php echo number_format($trade['amount'], 2); ?> USDT</div>
                                    <div style="font-size: 10px; text-transform: uppercase; color: <?php echo ($trade['type'] == 'buy' ? '#00c9a7' : '#ff4d4d'); ?>;"><?php echo $trade['type']; ?></div>
                                </td>
                                <td style="text-align: right;">
                                    <div style="font-weight: 800; color: <?php echo ($isWin ? '#00c9a7' : '#ff4d4d'); ?>;">
                                        <?php echo ($isWin ? '+' : ''); ?><?php echo number_format($profit, 2); ?>
                                    </div>
                                    <span class="status-pill <?php echo ($isWin ? 'status-active' : 'status-pending'); ?>" style="font-size: 9px; padding: 2px 6px;">
                                        <?php echo strtoupper($trade['status']); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php require_once 'includes/footer.php'; ?>
