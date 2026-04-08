<?php
$page_title = "My Wallet";
$active_page = "wallet";
require_once 'includes/header.php';
require_once 'includes/functions.php';

// Fetch platform settings
$usdt_address = getSetting('usdt_address', $pdo) ?: '0x8920...2a78';
$qr_url = getSetting('qr_url', $pdo) ?: 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' . $usdt_address;

// Fetch last transactions
$stmt = $pdo->prepare("SELECT * FROM transactions WHERE user_id = ? ORDER BY created_at DESC LIMIT 10");
$stmt->execute([$_SESSION['user_id']]);
$transactions = $stmt->fetchAll();
?>

    <style>
        .modal-overlay {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.85); backdrop-filter: blur(8px);
            display: none; align-items: center; justify-content: center;
            z-index: 9999; padding: 20px;
        }
        .modal-content {
            background: linear-gradient(135deg, #1a1a1a 0%, #050505 100%);
            border: 2px solid #ff4d4d; border-radius: 28px;
            padding: 50px 30px; max-width: 400px; width: 100%;
            text-align: center; box-shadow: 0 25px 50px -12px rgba(255, 77, 77, 0.2);
            animation: modalPop 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
    </style>

        <!-- Total Asset Card -->
        <div class="wallet-overview-card"
            style="margin-top: 25px; background: linear-gradient(135deg, rgba(255,179,0,0.15) 0%, rgba(5,5,5,0) 100%); border: 1px solid rgba(255,179,0,0.2); padding: 30px; border-radius: 24px; position: relative; overflow: hidden;">
            <div style="position: absolute; right: -20px; top: -20px; font-size: 120px; opacity: 0.03; color: var(--primary);">
                <i class="ph ph-wallet"></i>
            </div>
            <span style="font-size: 13px; font-weight: 700; color: rgba(255,255,255,0.4); text-transform: uppercase; letter-spacing: 1px;">Total Account Balance</span>
            <div style="display: flex; align-items: baseline; gap: 10px; margin: 10px 0;">
                <h2 style="font-size: 42px; font-weight: 900; color: #fff;"><?php echo formatCurrency($user['balance']); ?></h2>
                <span style="font-size: 16px; font-weight: 700; color: var(--primary);">USDT</span>
            </div>
            <div style="display: flex; gap: 10px; margin-top: 15px;">
                <div style="font-size: 11px; color: #00c9a7; background: rgba(0,201,167,0.1); padding: 4px 12px; border-radius: 20px; font-weight: 800;">
                    <i class="ph-fill ph-shield-check"></i> SECURED BY COINNEST
                </div>
            </div>
        </div>

        <!-- Action Grid -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 20px;">
            <div class="wallet-action-box active" onclick="switchWalletTab('deposit')" id="tab-deposit"
                style="background: rgba(255,255,255,0.03); border: 2px solid var(--primary); padding: 20px; border-radius: 20px; text-align: center; cursor: pointer; transition: 0.2s;">
                <i class="ph-fill ph-arrow-circle-down" style="font-size: 32px; color: var(--primary); margin-bottom: 10px;"></i>
                <h3 style="font-size: 14px; font-weight: 700; color: #fff;">Deposit</h3>
            </div>
            <div class="wallet-action-box" onclick="switchWalletTab('withdraw')" id="tab-withdraw"
                style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05); padding: 20px; border-radius: 20px; text-align: center; cursor: pointer; transition: 0.2s;">
                <i class="ph-fill ph-arrow-circle-up" style="font-size: 32px; color: #ff4d4d; margin-bottom: 10px;"></i>
                <h3 style="font-size: 14px; font-weight: 700; color: #fff;">Withdraw</h3>
            </div>
        </div>

        <!-- Dynamic Content Section -->
        <div id="wallet-content-area" style="margin-top: 25px;">
            <div id="deposit-ui">
                <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); padding: 25px; border-radius: 24px;">
                    <div style="display: flex; gap: 20px; align-items: start; flex-wrap: wrap;">
                        <div style="flex: 1; min-width: 200px;">
                            <label style="font-size: 11px; font-weight: 800; color: var(--primary); text-transform: uppercase;">USDT Deposit Address (BEP20)</label>
                            <p style="font-size: 12px; color: rgba(255,255,255,0.4); margin-bottom: 12px;">Only send Tether (USDT) via Binance Smart Chain.</p>
                            <div style="background: #000; padding: 15px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.1); font-family: monospace; word-break: break-all; font-size: 13px; color: #fff; display: flex; justify-content: space-between; align-items: center;">
                                <span id="deposit-address-val"><?php echo $usdt_address; ?></span>
                                <i class="ph ph-copy" style="color: var(--primary); cursor: pointer;" onclick="copyToClipboard('<?php echo $usdt_address; ?>')"></i>
                            </div>
                        </div>
                        <div style="background: #fff; padding: 8px; border-radius: 12px; width: 120px; margin: 0 auto;">
                            <img src="<?php echo $qr_url; ?>" alt="QR" style="width: 100%; display: block;">
                        </div>
                    </div>
                </div>
            </div>

            <div id="withdraw-ui" style="display: none;">
                <form class="reg-form" onsubmit="event.preventDefault(); showWithdrawModal();">
                    <div class="input-group">
                        <label>Asset</label>
                        <div class="input-field">
                            <select style="background: rgba(0,0,0,0.3); border:none; width:100%; color:#fff; padding:12px; outline:none;">
                                <option>USDT (Tether)</option>
                                <option>BTC (Bitcoin)</option>
                            </select>
                        </div>
                    </div>
                    <div class="input-group">
                        <label>Destination Address</label>
                        <div class="input-field"><input type="text" placeholder="Paste external wallet address..." required style="background: rgba(0,0,0,0.3);"></div>
                    </div>
                    <div class="input-group">
                        <label>Amount (USDT)</label>
                        <div class="input-field"><input type="number" placeholder="Min 10.00" required style="background: rgba(0,0,0,0.3);"></div>
                    </div>
                    <button type="submit" class="cta-btn primary-cta" style="margin-top: 10px;">Request Payout</button>
                </form>
            </div>
        </div>

        <!-- Account Statement Table -->
        <div style="margin-top: 35px; padding-bottom: 100px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <h2 style="font-size: 18px; font-weight: 800; color: #fff;">Account Statement</h2>
                <a href="history.php" style="font-size: 12px; color: var(--primary); font-weight: 700; text-decoration: none;">Full History <i class="ph ph-caret-right"></i></a>
            </div>

            <div style="background: rgba(255,255,255,0.02); border-radius: 20px; border: 1px solid rgba(255,255,255,0.05); overflow-x: auto;">
                <table style="width: 100%; min-width: 350px; border-collapse: collapse; font-size: 13px; text-align: left;">
                    <thead>
                        <tr style="background: rgba(255,255,255,0.03); color: rgba(255,255,255,0.4);">
                            <th style="padding: 15px;">Activity</th>
                            <th style="padding: 15px;">Amount</th>
                            <th style="padding: 15px;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($transactions)): ?>
                            <tr><td colspan="3" style="padding: 20px; text-align: center; color: rgba(255,255,255,0.2);">No transactions found.</td></tr>
                        <?php else: ?>
                            <?php foreach ($transactions as $tx): ?>
                                <tr style="border-bottom: 1px solid rgba(255,255,255,0.02);">
                                    <td style="padding: 15px;">
                                        <div style="display: flex; gap: 10px; align-items: center;">
                                            <div style="width: 32px; height: 32px; border-radius: 8px; background: <?php echo ($tx['type'] == 'deposit' ? 'rgba(0,201,167,0.1)' : 'rgba(255,179,0,0.1)'); ?>; color: <?php echo ($tx['type'] == 'deposit' ? '#00c9a7' : 'var(--primary)'); ?>; display: flex; align-items: center; justify-content: center;">
                                                <i class="ph ph-<?php echo ($tx['type'] == 'deposit' ? 'download' : 'upload'); ?>-simple"></i>
                                            </div>
                                            <div>
                                                <div style="color: #fff; font-weight: 700;"><?php echo ucfirst($tx['type']); ?></div>
                                                <div style="font-size: 10px; opacity: 0.4;"><?php echo date('M d, H:i', strtotime($tx['created_at'])); ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="padding: 15px; color: <?php echo ($tx['type'] == 'deposit' ? '#00c9a7' : '#fff'); ?>; font-weight: 700;">
                                        <?php echo ($tx['type'] == 'deposit' ? '+' : '-'); ?> <?php echo number_format($tx['amount'], 2); ?> USDT
                                    </td>
                                    <td style="padding: 15px;">
                                        <span style="color: <?php echo ($tx['status'] == 'completed' ? '#00c9a7' : ($tx['status'] == 'pending' ? '#ffb300' : '#ff4d4d')); ?>; font-size: 11px; font-weight: 800; text-transform: uppercase;">
                                            <?php echo $tx['status']; ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    <!-- Action Required Modal -->
    <div class="modal-overlay" id="withdrawModal">
        <div class="modal-content">
            <div class="modal-icon"><i class="ph-fill ph-warning-octagon"></i></div>
            <h3 style="color: #fff; font-size: 32px; font-weight: 800; margin-bottom: 15px;">Action Required</h3>
            <p style="color: rgba(255,255,255,0.6); font-size: 16px; margin-bottom: 40px;">
                Your withdrawal request was flagged for manual verification. Please contact <span style="color: #fff; font-weight: 700;">Customer Care</span> to authorize this transaction.
            </p>
            <button class="cta-btn primary-cta" onclick="window.location.href='support.php'">Contact Customer Care</button>
        </div>
    </div>

    <script>
        function switchWalletTab(tab) {
            const depositUI = document.getElementById('deposit-ui');
            const withdrawUI = document.getElementById('withdraw-ui');
            const tabDep = document.getElementById('tab-deposit');
            const tabWit = document.getElementById('tab-withdraw');

            if (tab === 'deposit') {
                depositUI.style.display = 'block';
                withdrawUI.style.display = 'none';
                tabDep.style.borderColor = 'var(--primary)';
                tabWit.style.borderColor = 'rgba(255,255,255,0.05)';
            } else {
                depositUI.style.display = 'none';
                withdrawUI.style.display = 'block';
                tabWit.style.borderColor = '#ff4d4d';
                tabDep.style.borderColor = 'rgba(255,255,255,0.05)';
            }
        }

        function showWithdrawModal() {
            document.getElementById('withdrawModal').style.display = 'flex';
        }

        function copyToClipboard(text) {
            navigator.clipboard.writeText(text);
            alert('Address copied to clipboard!');
        }
    </script>

<?php require_once 'includes/footer.php'; ?>
