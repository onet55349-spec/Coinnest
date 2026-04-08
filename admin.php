<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';
require_once 'includes/functions.php';
requireAdmin();

// Simple Stat fetch
$total_users = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$total_balance = $pdo->query("SELECT SUM(balance) FROM users")->fetchColumn() ?: 0;
$pending_kyc = $pdo->query("SELECT COUNT(*) FROM kyc_requests WHERE status = 'pending'")->fetchColumn();
$pending_withdrawals = $pdo->query("SELECT COUNT(*) FROM transactions WHERE type='withdrawal' AND status='pending'")->fetchColumn();

// Fetch platform settings for the settings tab
$usdt_address = getSetting('usdt_address', $pdo);
$qr_url = getSetting('qr_url', $pdo);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel | CoinNest Platform</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        :root {
            --admin-primary: #FFB300;
            --admin-bg: #0D0D0D;
            --admin-side: #141414;
            --admin-card: #1A1A1A;
            --admin-text: #FFFFFF;
            --admin-text-dim: rgba(255, 255, 255, 0.4);
            --admin-border: rgba(255, 255, 255, 0.05);
            --admin-success: #00c9a7;
            --admin-danger: #ff4d4d;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { background: var(--admin-bg); color: var(--admin-text); display: flex; min-height: 100vh; }
        
        .sidebar { width: 260px; background: var(--admin-side); border-right: 1px solid var(--admin-border); display: flex; flex-direction: column; position: fixed; height: 100vh; z-index: 100; }
        .sidebar-header { padding: 30px 24px; font-size: 22px; font-weight: 800; color: var(--admin-primary); text-transform: uppercase; letter-spacing: -0.5px; display: flex; align-items: center; gap: 12px; }
        .sidebar-nav { flex: 1; padding: 0 12px; }
        .nav-link { display: flex; align-items: center; gap: 12px; padding: 14px 16px; color: var(--admin-text-dim); text-decoration: none; font-weight: 600; font-size: 14px; border-radius: 10px; margin-bottom: 4px; transition: all 0.2s; cursor: pointer; }
        .nav-link:hover, .nav-link.active { color: #fff; background: rgba(255, 255, 255, 0.04); }
        .nav-link.active { color: var(--admin-primary); background: rgba(255, 179, 0, 0.08); }
        .sidebar-footer { padding: 24px; border-top: 1px solid var(--admin-border); }
        .logout-btn { display: flex; align-items: center; gap: 12px; color: var(--admin-danger); text-decoration: none; font-weight: 700; font-size: 14px; }
        
        .main-content { flex: 1; margin-left: 260px; padding: 30px 40px; }
        .top-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; }
        .page-title h1 { font-size: 28px; font-weight: 800; margin-bottom: 4px; }
        .page-title p { font-size: 14px; color: var(--admin-text-dim); }
        .admin-profile { display: flex; align-items: center; gap: 12px; background: var(--admin-card); padding: 8px 16px; border-radius: 12px; border: 1px solid var(--admin-border); }
        
        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 40px; }
        .stat-card { background: var(--admin-card); padding: 24px; border-radius: 20px; border: 1px solid var(--admin-border); }
        .stat-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; }
        .stat-icon { width: 40px; height: 40px; background: rgba(255, 179, 0, 0.1); color: var(--admin-primary); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 20px; }
        .stat-val { font-size: 32px; font-weight: 800; margin-bottom: 4px; }
        .stat-label { font-size: 14px; color: var(--admin-text-dim); font-weight: 500; }
        
        .section-card { background: var(--admin-card); border-radius: 24px; border: 1px solid var(--admin-border); padding: 30px; margin-bottom: 30px; }
        .section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
        .section-header h2 { font-size: 20px; font-weight: 800; }
        
        .btn-sm { background: rgba(255, 255, 255, 0.05); border: 1px solid var(--admin-border); color: #fff; padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 700; cursor: pointer; text-decoration: none; transition: 0.2s; }
        .btn-primary { background: var(--admin-primary); color: #000; border: none; }
        .btn-primary:hover { background: #e6a100; }
        
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; font-size: 12px; font-weight: 700; color: var(--admin-text-dim); text-transform: uppercase; letter-spacing: 1px; padding-bottom: 12px; border-bottom: 1px solid var(--admin-border); }
        td { padding: 20px 0; border-bottom: 1px solid var(--admin-border); font-size: 14px; font-weight: 500; }
        
        .status-pill { padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700; text-transform: uppercase; }
        .status-active { background: rgba(0, 201, 167, 0.1); color: var(--admin-success); }
        .status-pending { background: rgba(255, 179, 0, 0.1); color: var(--admin-primary); }
        
        .overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.8); backdrop-filter: blur(5px); z-index: 1000; display: none; justify-content: center; align-items: center; }
        .modal { background: var(--admin-card); width: 450px; border-radius: 24px; padding: 30px; border: 1px solid var(--admin-border); }
        .admin-input { width: 100%; background: rgba(255, 255, 255, 0.05); border: 1px solid var(--admin-border); padding: 12px 16px; border-radius: 10px; color: #fff; font-size: 14px; transition: 0.3s; }
        .admin-input:focus { outline: none; border-color: var(--admin-primary); }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header"><i class="ph ph-shield-check"></i> AdminPanel</div>
        <nav class="sidebar-nav">
            <a onclick="switchSection('dashboard')" class="nav-link active" id="nav-dashboard">
                <i class="ph-fill ph-circles-four"></i>
                Dashboard
            </a>
            <a onclick="switchSection('users')" class="nav-link" id="nav-users">
                <i class="ph ph-users"></i>
                User Management
            </a>
            <a onclick="switchSection('kyc')" class="nav-link" id="nav-kyc">
                <i class="ph ph-identification-card"></i>
                KYC Review Hub
            </a>
            <a onclick="switchSection('withdrawals')" class="nav-link" id="nav-withdrawals">
                <i class="ph ph-hand-coins"></i>
                Withdrawals
            </a>
            <a onclick="switchSection('chat')" class="nav-link" id="nav-chat">
                <i class="ph ph-chat-centered-text"></i>
                Support Chat
                <span id="chat-count" style="background: var(--admin-danger); color: #fff; padding: 2px 6px; border-radius: 10px; font-size: 10px; margin-left: auto;">2</span>
            </a>
            <a onclick="switchSection('notifications')" class="nav-link" id="nav-notifications">
                <i class="ph ph-megaphone"></i>
                Notify Users
            </a>
            <a onclick="switchSection('settings')" class="nav-link" id="nav-settings">
                <i class="ph ph-gear-six"></i>
                Platform Settings
            </a>
        </nav>
        <div class="sidebar-footer"><a href="dashboard.php" class="logout-btn"><i class="ph ph-sign-out"></i> Return to Site</a></div>
    </div>

    <div class="main-content">
        <header class="top-bar">
            <div class="page-title"><h1 id="topbar-title">Dashboard Overview</h1><p>Welcome back, Administrator.</p></div>
            <div class="admin-profile">
                <div class="user-avatar" style="background: var(--admin-primary); color: #000; width:32px; height:32px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:800;">AD</div>
                <div style="font-size: 14px; font-weight: 700;">Admin User</div>
            </div>
        </header>

        <!-- DASHBOARD SECTION -->
        <div id="section-dashboard">
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-header"><div class="stat-icon"><i class="ph ph-users"></i></div></div>
                    <div class="stat-val"><?php echo number_format($total_users); ?></div>
                    <div class="stat-label">Total Users</div>
                </div>
                <div class="stat-card">
                    <div class="stat-header"><div class="stat-icon"><i class="ph ph-wallet"></i></div></div>
                    <div class="stat-val"><?php echo formatCurrency($total_balance); ?></div>
                    <div class="stat-label">Total Balances</div>
                </div>
                <div class="stat-card">
                    <div class="stat-header"><div class="stat-icon" style="color: var(--admin-danger);"><i class="ph ph-identification-badge"></i></div></div>
                    <div class="stat-val"><?php echo $pending_kyc; ?></div>
                    <div class="stat-label">Pending KYC</div>
                </div>
                <div class="stat-card">
                    <div class="stat-header"><div class="stat-icon" style="color: var(--admin-success);"><i class="ph ph-check-circle"></i></div></div>
                    <div class="stat-val"><?php echo $pending_withdrawals; ?></div>
                    <div class="stat-label">Pending Withdrawals</div>
                </div>
            </div>

            <div class="section-card">
                <div class="section-header"><h2>Recent Registered Users</h2></div>
                <div id="dashboard-users-table">
                    <p style="color: var(--admin-text-dim); text-align: center; padding: 20px;">Fetching latest data...</p>
                </div>
            </div>
        </div>

        <!-- USERS SECTION -->
        <div id="section-users" style="display: none;">
            <div class="section-card">
                <div class="section-header"><h2>User Database Management</h2></div>
                <div id="all-users-table">
                    <p style="color: var(--admin-text-dim); text-align: center; padding: 20px;">Loading users list...</p>
                </div>
            </div>
        </div>

        <!-- KYC SECTION -->
        <div id="section-kyc" style="display: none;">
            <div class="section-card">
                <div class="section-header"><h2>KYC Verification Requests</h2></div>
                <div id="kyc-requests-table">
                    <p style="color: var(--admin-text-dim); text-align: center; padding: 20px;">No pending requests found.</p>
                </div>
            </div>
        </div>

        <!-- WITHDRAWALS SECTION -->
        <div id="section-withdrawals" style="display: none;">
            <div class="section-card">
                <div class="section-header"><h2>Withdrawal Approval Hub</h2></div>
                <div id="withdrawals-table">
                    <p style="color: var(--admin-text-dim); text-align: center; padding: 20px;">No pending withdrawals found.</p>
                </div>
            </div>
        </div>

        <!-- SETTINGS SECTION -->
        <div id="section-settings" style="display: none;">
            <div class="section-card">
                <div class="section-header"><h2>Global Platform Settings</h2></div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
                    <div>
                        <form id="settings-form">
                            <div style="margin-bottom: 25px;">
                                <label class="stat-label">System USDT (BEP20) Deposit Address</label>
                                <input type="text" name="usdt_address" class="admin-input" value="<?php echo $usdt_address; ?>" style="margin-top:8px;">
                            </div>
                            <div style="margin-bottom: 25px;">
                                <label class="stat-label">System QR Code URL</label>
                                <input type="text" name="qr_url" class="admin-input" value="<?php echo $qr_url; ?>" style="margin-top:8px;">
                            </div>
                            <button type="button" onclick="saveAdminSettings()" class="btn-sm btn-primary" style="padding: 12px 25px;">Update Platform Configuration</button>
                        </form>
                    </div>
                    <div style="background: rgba(255,255,255,0.02); padding: 25px; border-radius: 20px; border: 1px dashed var(--admin-border);">
                        <h3 style="font-size: 16px; margin-bottom: 10px;">Maintenance Mode</h3>
                        <p style="font-size: 13px; color: var(--admin-text-dim); margin-bottom: 20px;">When enabled, regular users cannot trade or withdraw.</p>
                        <button class="btn-sm" style="background: var(--admin-danger); width: 100%; padding: 12px;">Enable Maintenance</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- CHAT SECTION -->
        <div id="section-chat" style="display: none;">
            <div style="display: grid; grid-template-columns: 300px 1fr; gap: 24px; height: calc(100vh - 250px);">
                <div class="section-card" style="padding: 20px; overflow-y: auto;">
                    <h3 style="font-size: 16px; margin-bottom: 20px;">Support Tickets</h3>
                    <div id="chat-list-container" style="display: flex; flex-direction: column; gap: 10px;">
                        <p style="color: var(--admin-text-dim); text-align: center; padding: 20px;">No active tickets.</p>
                    </div>
                </div>
                <div class="section-card" style="padding: 0; display: flex; flex-direction: column; overflow: hidden;">
                    <div id="chat-header" style="padding: 20px; border-bottom: 1px solid var(--admin-border); display: flex; align-items: center; gap: 15px;">
                        <div class="user-avatar" style="background: #333; width:32px; height:32px; border-radius:50%; display:flex; align-items:center; justify-content:center;">?</div>
                        <div>
                            <h4>Select a conversation</h4>
                            <span style="font-size: 12px; color: var(--admin-success);">Online</span>
                        </div>
                    </div>
                    <div style="flex: 1; padding: 25px; display: flex; align-items: center; justify-content: center; color: var(--admin-text-dim);">
                        <div style="text-align: center;">
                            <i class="ph ph-chat-centered-dots" style="font-size: 48px; opacity: 0.2;"></i>
                            <p>Pick a ticket to start chatting</p>
                        </div>
                    </div>
                    <div style="padding: 20px; border-top: 1px solid var(--admin-border);">
                        <textarea class="admin-input" placeholder="Type your multi-line reply here..." style="min-height: 80px; resize: none;"></textarea>
                        <button class="btn-sm btn-primary" style="margin-top: 10px; float: right;">Send Reply</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- NOTIFICATIONS SECTION -->
        <div id="section-notifications" style="display: none;">
            <div class="section-card">
                <div class="section-header">
                    <h2>Broadcast Notifier</h2>
                    <p style="font-size: 13px; color: var(--admin-text-dim);">Send platform-wide alerts or targeted messages.</p>
                </div>
                <div style="display: flex; flex-direction: column; gap: 15px; max-width: 600px;">
                    <div class="input-group">
                        <label class="stat-label">Select Recipient</label>
                        <select class="admin-input"><option>All Users</option><option>Verified Only</option></select>
                    </div>
                    <div class="input-group">
                        <label class="stat-label">Notification Title</label>
                        <input type="text" class="admin-input" placeholder="e.g. Account Alert: Withdrawal Successful">
                    </div>
                    <div class="input-group">
                        <label class="stat-label">Message Content</label>
                        <textarea class="admin-input" rows="4" placeholder="Type your message to the user..."></textarea>
                    </div>
                    <button class="btn-sm btn-primary" style="padding: 14px;">Push Notification to System</button>
                </div>
            </div>
        </div>
    </div>

    <!-- User Edit Modal -->
    <div class="overlay" id="edit-modal">
        <div class="modal">
            <h2 style="margin-bottom: 10px; font-size: 22px;">Edit Account</h2>
            <p style="color: var(--admin-text-dim); font-size: 13px; margin-bottom: 25px;">Modify account balance and market outcome control.</p>
            <form id="edit-user-form">
                <input type="hidden" id="edit-user-id">
                <div style="margin-bottom: 20px;">
                    <label class="stat-label">Manual Balance Correction (USDT)</label>
                    <input type="number" id="edit-balance" class="admin-input" step="0.01" style="margin-top:8px;">
                </div>
                <div style="margin-bottom: 25px;">
                    <label class="stat-label">Forced Trade Win Rate (%)</label>
                    <select id="edit-win-rate" class="admin-input" style="margin-top:8px;">
                        <option value="0">0% (Force Loss)</option>
                        <option value="10">10% (High Loss)</option>
                        <option value="50">50% (Market Default)</option>
                        <option value="75">75% (High Win)</option>
                        <option value="90">90% (Near Guaranteed)</option>
                        <option value="100">100% (Force Win)</option>
                    </select>
                </div>
                <div style="display: flex; gap: 12px; margin-top: 10px;">
                    <button type="button" onclick="saveUserChanges()" class="btn-sm btn-primary" style="flex:1; padding: 14px;">Update User</button>
                    <button type="button" onclick="closeModal()" class="btn-sm" style="flex:1; padding: 14px;">Discard</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function switchSection(id) {
            document.querySelectorAll('[id^="section-"]').forEach(s => s.style.display = 'none');
            document.getElementById('section-' + id).style.display = 'block';
            document.querySelectorAll('.nav-link').forEach(n => n.classList.remove('active'));
            document.getElementById('nav-' + id).classList.add('active');
            document.getElementById('topbar-title').innerText = id.charAt(0).toUpperCase() + id.slice(1) + (id === 'dashboard' ? ' Overview' : ' Management');
            
            if (id === 'users' || id === 'dashboard') loadUsers();
            if (id === 'kyc') loadKYC();
            if (id === 'withdrawals') loadWithdrawals();
            if (id === 'chat') console.log('Chat loaded');
            if (id === 'notifications') console.log('Notifications loaded');
        }

        async function loadUsers() {
            try {
                const response = await fetch('api/admin_get_users.php');
                const data = await response.json();
                if(!data.success) return;

                const containers = ['dashboard-users-table', 'all-users-table'];
                containers.forEach(containerId => {
                    const el = document.getElementById(containerId);
                    if(!el) return;
                    
                    if (data.users.length === 0) {
                        el.innerHTML = '<p style="color: var(--admin-text-dim); text-align: center; padding: 20px;">No users registered yet.</p>';
                        return;
                    }

                    let html = '<table><thead><tr><th>User / Email</th><th>Join Date</th><th>KYC Status</th><th>Current Balance</th><th>AI Win Rate</th><th>Actions</th></tr></thead><tbody>';
                    data.users.forEach(user => {
                        html += `<tr>
                            <td>
                                <div style="display:flex; align-items:center; gap:10px;">
                                    <div class="user-avatar" style="background:#222; width:30px; height:30px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:10px;">${user.first_name.charAt(0)}${user.last_name.charAt(0)}</div>
                                    <div>
                                        <div style="font-weight:700">${user.email}</div>
                                        <div style="font-size:11px;opacity:0.5">${user.first_name} ${user.last_name}</div>
                                    </div>
                                </div>
                            </td>
                            <td style="font-size:12px; opacity:0.7;">${new Date(user.created_at).toLocaleDateString()}</td>
                            <td><span class="status-pill ${user.kyc_status === 'verified' ? 'status-active' : 'status-pending'}">${user.kyc_status}</span></td>
                            <td style="font-weight:800; color:#fff;">$${parseFloat(user.balance).toLocaleString(undefined, {minimumFractionDigits:2})}</td>
                            <td style="color:var(--admin-primary);font-weight:800">${user.win_rate}%</td>
                            <td>
                                <button class="btn-sm" onclick='openEditModal(${JSON.stringify(user).replace(/'/g, "&apos;")})'>Manage</button>
                                <button class="btn-sm" style="background:rgba(255,77,77,0.1); color:var(--admin-danger); border-color:rgba(255,77,77,0.2)" onclick='deleteUser(${user.id})'>Delete</button>
                            </td>
                        </tr>`;
                    });
                    html += '</tbody></table>';
                    el.innerHTML = html;
                });
            } catch (e) { console.error(e); }
        }

        async function deleteUser(userId) {
            if (!confirm("Are you sure you want to permanently delete this user and all their records?")) return;
            
            try {
                const response = await fetch('api/admin_delete_user.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: userId })
                });
                const result = await response.json();
                if (result.success) {
                    loadUsers();
                } else {
                    alert("Error: " + result.message);
                }
            } catch (e) { alert("Failed to connect to server."); }
        }

        async function loadKYC() {
            // Simplified placeholder - can be linked to api if needed
            document.getElementById('kyc-requests-table').innerHTML = '<p style="color: var(--admin-text-dim); text-align: center; padding: 20px;">No pending verification requests at the moment.</p>';
        }

        async function loadWithdrawals() {
            // Simplified placeholder - can be linked to api if needed
            document.getElementById('withdrawals-table').innerHTML = '<p style="color: var(--admin-text-dim); text-align: center; padding: 20px;">No withdrawal requests awaiting approval.</p>';
        }

        function openEditModal(user) {
            document.getElementById('edit-user-id').value = user.id;
            document.getElementById('edit-balance').value = user.balance;
            document.getElementById('edit-win-rate').value = user.win_rate;
            document.getElementById('edit-modal').style.display = 'flex';
        }

        function closeModal() { document.getElementById('edit-modal').style.display = 'none'; }

        async function saveUserChanges() {
            const id = document.getElementById('edit-user-id').value;
            const balance = document.getElementById('edit-balance').value;
            const win_rate = document.getElementById('edit-win-rate').value;

            try {
                const response = await fetch('api/admin_update_user.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id, balance, win_rate })
                });
                const result = await response.json();
                if(result.success) { 
                    closeModal(); 
                    loadUsers(); 
                } else { 
                    alert("Update failed: " + result.message); 
                }
            } catch (e) { alert("Network error occurred."); }
        }

        async function saveAdminSettings() {
            const form = document.getElementById('settings-form');
            const data = {
                usdt_address: form.usdt_address.value,
                qr_url: form.qr_url.value
            };
            try {
                const response = await fetch('api/admin_update_settings.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
                const result = await response.json();
                alert(result.success ? 'Platform configuration updated successfully!' : 'Error: ' + result.message);
            } catch (e) { alert("Failed to connect to server."); }
        }

        // Initialize
        loadUsers();
    </script>
</body>
</html>
