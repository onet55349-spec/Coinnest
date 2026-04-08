<?php
$page_title = "Trade BTC/USDT";
$active_page = "trade";
require_once 'includes/header.php';
?>

        <div class="balance-display"
            style="margin-bottom: 15px; display: flex; justify-content: space-between; align-items: flex-end;">
            <div>
                <span
                    style="font-size: 11px; font-weight: 700; color: rgba(255,255,255,0.4); text-transform: uppercase;">Live
                    Balance</span>
                <div style="font-size: 24px; font-weight: 900; color: var(--primary);" id="trade-user-balance">$<?php echo number_format($user['balance'], 2); ?>
                </div>
            </div>
            <div style="font-size: 10px; color: rgba(255,255,255,0.3); font-weight: 600; text-align: right;">
                ACCOUNT STATUS<br>
                <span style="color: #00c9a7;">● SECURE</span>
            </div>
        </div>

        <div class="ticker-ribbon" id="ticker-ribbon">
            <div class="ticker-item"><span class="ticker-symbol">BTC/USDT</span> <span class="ticker-val"
                    id="tick-BTC">$0.00</span> <span class="ticker-pct" id="tick-BTC-pct">+0.00%</span></div>
            <div class="ticker-item"><span class="ticker-symbol">ETH/USDT</span> <span class="ticker-val"
                    id="tick-ETH">$0.00</span> <span class="ticker-pct" id="tick-ETH-pct">+0.00%</span></div>
            <div class="ticker-item"><span class="ticker-symbol">SOL/USDT</span> <span class="ticker-val"
                    id="tick-SOL">$0.00</span> <span class="ticker-pct" id="tick-SOL-pct">+0.00%</span></div>
        </div>

        <div class="trade-info-section">
            <h2 class="section-title" style="margin-bottom: 5px; font-size: 28px;">Trade BTC/USDT</h2>
            <p style="font-size: 13px; color: rgba(255,255,255,0.4); margin-bottom: 20px;">Execute binary and spot
                trades with real-time market precision.</p>

            <div style="display: flex; align-items: baseline; gap: 12px; margin-bottom: 10px;">
                <span style="font-size: 24px; font-weight: 900; color: #fff;" id="main-price">$0.00</span>
                <span style="font-size: 16px; font-weight: 700; color: #00c9a7;" id="main-pct">+0.00%</span>
            </div>

            <div class="trade-stats-row">
                <div class="stat-item">
                    <span class="stat-label">24h High</span>
                    <span class="stat-val" id="stat-high">0.00</span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">24h Low</span>
                    <span class="stat-val" id="stat-low">0.00</span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">24h Volume</span>
                    <span class="stat-val" id="stat-vol">0.00</span>
                </div>
            </div>
        </div>

        <!-- Live Chart Container -->
        <div class="chart-container"
            style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; overflow: hidden; height: 350px;">
            <div id="tradingview_trade_chart" style="height: 100%;"></div>
        </div>

        <!-- Live Results Ticker -->
        <div class="ticker-ribbon" style="margin-top: 15px; background: rgba(0,0,0,0.4); border: 1px solid rgba(255,255,255,0.03); border-radius: 8px; overflow: hidden; height: 32px; padding: 0 10px;">
            <div id="live-results-ticker" style="display: flex; gap: 30px; animation: ticker-scroll 30s linear infinite; white-space: nowrap; align-items: center;">
                <!-- Live wins/losses will appear here -->
            </div>
        </div>

        <!-- Order Form -->
        <div class="order-form-container">
            <div class="input-group">
                <label style="color: var(--primary);">Amount</label>
                <div class="input-row" style="margin-top: 10px;">
                    <div class="input-field" style="flex: 1;">
                        <input type="number" id="trade-amount-btc" placeholder="0.0000" style="background: rgba(0,0,0,0.3);">
                        <span style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); font-size: 12px; color: rgba(255,255,255,0.3); font-weight: 700;">BTC</span>
                    </div>
                    <div class="input-field" style="flex: 1;">
                        <input type="number" id="trade-amount-usdt" placeholder="0.00" style="background: rgba(0,0,0,0.3);">
                        <span style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); font-size: 12px; color: rgba(255,255,255,0.3); font-weight: 700;">USDT</span>
                    </div>
                </div>
                <div id="balance-info" style="display: flex; justify-content: space-between; align-items: center; margin-top: 15px; padding: 0 5px;">
                    <span style="font-size: 12px; color: rgba(255,255,255,0.3); font-weight: 600;">Available Balance</span>
                    <span id="trade-user-balance-available" style="font-size: 14px; font-weight: 800; color: #fff;">$<?php echo number_format($user['balance'], 2); ?></span>
                </div>
            </div>
            <div class="order-btn-group">
                <button class="order-btn btn-sell" onclick="openTradeProcessing('Sell Short')">SELL BTC</button>
                <button class="order-btn btn-buy" onclick="openTradeProcessing('Buy Long')">BUY BTC</button>
            </div>
        </div>

        <!-- Market Trades Section -->
        <div class="trades-section">
            <div class="trades-header" style="justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(255,255,255,0.05); margin-bottom: 15px; padding-bottom: 10px;">
                <div style="display: flex; gap: 20px;">
                    <h3 id="tab-market" onclick="switchTradeTab('market')" 
                        style="font-size: 13px; font-weight: 800; color: #fff; cursor: pointer; text-transform: uppercase; letter-spacing: 1px; transition: color 0.3s;">
                        Market Trades</h3>
                    <h3 id="tab-user" onclick="switchTradeTab('user')" 
                        style="font-size: 13px; font-weight: 800; color: rgba(255,255,255,0.3); cursor: pointer; text-transform: uppercase; letter-spacing: 1px; transition: color 0.3s; display: flex; align-items: center; gap: 6px;">
                        My Trades <span class="live-dot"></span></h3>
                </div>
                <div id="history-sync-indicator" style="font-size: 10px; color: rgba(0,201,167,0.6); font-weight: 700; display: flex; align-items: center; gap: 4px;">
                    <i class="ph ph-arrows-clockwise" style="animation: spin 2s linear infinite;"></i> LIVE
                </div>
            </div>
            
            <table class="trades-table" id="market-table">
                <thead>
                    <tr>
                        <th style="width: 40%;">Price (USDT)</th>
                        <th style="width: 30%; text-align: center;">Amount</th>
                        <th style="width: 30%; text-align: right;">Time</th>
                    </tr>
                </thead>
                <tbody id="trades-tbody"></tbody>
            </table>

            <table class="trades-table" id="user-table" style="display: none;">
                <thead>
                    <tr>
                        <th style="width: 40%;">Outcome</th>
                        <th style="width: 30%; text-align: center;">Amount</th>
                        <th style="width: 30%; text-align: right;">Time</th>
                    </tr>
                </thead>
                <tbody id="user-trades-tbody"></tbody>
            </table>
        </div>

    <!-- Processing Modal -->
    <div class="processing-modal-overlay" id="processingModal">
        <div class="processing-modal-content">
            <h2 style="color: #fff; font-size: 24px; font-weight: 800; margin-bottom: 30px; letter-spacing: 1px;">Processing</h2>
            <div style="width: 150px; height: 150px; border: 4px solid rgba(255,179,0,0.1); border-radius: 50%; margin: 0 auto; display: flex; align-items: center; justify-content: center; position: relative;">
                <svg style="position: absolute; width: 100%; height: 100%; transform: rotate(-90deg);">
                    <circle cx="75" cy="75" r="70" fill="transparent" stroke="#FFB300" stroke-width="4" stroke-dasharray="440" stroke-dashoffset="0" id="proc-ring-circle"></circle>
                </svg>
                <div style="text-align: center;">
                    <span id="timer-val" style="font-size: 56px; font-weight: 900; color: #fff; display: block; line-height: 1;">140</span>
                    <span style="font-size: 16px; color: rgba(255,255,255,0.5); position: absolute; right: 30px; bottom: 50px;">s</span>
                </div>
            </div>
            <div class="proc-details-box">
                <div class="proc-row"><span class="proc-label">Trading Pair</span><span class="proc-val">BTC/USDT</span></div>
                <div class="proc-row"><span class="proc-label">Direction</span><span id="proc-direction" style="padding: 2px 10px; border-radius: 6px; font-size: 12px; font-weight: 800; text-transform: uppercase;">Sell Short</span></div>
                <div class="proc-row"><span class="proc-label">Buy Price</span><span id="proc-buy-price" class="proc-val">0.00</span></div>
                <div class="proc-row"><span class="proc-label">Current Price</span><span id="proc-curr-price" class="proc-val" style="color: #00c9a7;">0.00</span></div>
                <div class="proc-row"><span class="proc-label">Number of lots to trade</span><span id="proc-lots" class="proc-val">0.00 USDT</span></div>
            </div>
            <button class="cta-btn" onclick="closeTradeProcessing()" style="width: 100%; margin-top: 25px; padding: 18px; border-radius: 14px; background: linear-gradient(to right, #00c9a7, #00d2ff); color: #000; font-weight: 900; font-size: 17px; border: none; cursor: pointer;">Continue to trade</button>
        </div>
    </div>

    <!-- Result Modal -->
    <div class="processing-modal-overlay" id="resultModal" style="display: none;">
        <div class="processing-modal-content">
            <h2 id="res-status" style="color: #48bb78; font-size: 28px; font-weight: 700; margin-bottom: 25px;">Completed</h2>
            <div style="margin-bottom: 25px; display: flex; flex-direction: column; align-items: center;">
                <h1 id="res-amount" style="font-size: 48px; font-weight: 800; color: #48bb78; margin: 0;">+0.0000</h1>
                <p style="font-size: 16px; color: rgba(255,255,255,0.4); font-weight: 600;">USDT</p>
            </div>
            <div class="proc-details-box">
                <div class="proc-row"><span class="proc-label">Trading Pair</span><span class="proc-val">BTC/USDT</span></div>
                <div class="proc-row"><span class="proc-label">Direction</span><span id="res-direction" style="padding: 4px 14px; border-radius: 6px; font-size: 13px; font-weight: 700; text-transform: capitalize;">Buy Long</span></div>
                <div class="proc-row"><span class="proc-label">Buy Price</span><span id="res-buy-price" class="proc-val">0.00</span></div>
                <div class="proc-row"><span class="proc-label">Current Price</span><span id="res-curr-price" class="proc-val">0.00</span></div>
                <div class="proc-row"><span class="proc-label">Number of lots to trade</span><span id="res-lots" class="proc-val">0 USDT</span></div>
            </div>
            <button class="cta-btn" onclick="document.getElementById('resultModal').style.display='none'" style="width: 100%; margin-top: 30px; padding: 18px; border-radius: 12px; background: linear-gradient(180deg, #f09819 0%, #ed8a17 100%); color: #000; font-weight: 800; border: none; cursor: pointer;">Continue to trade</button>
        </div>
    </div>

    <script>
        let tradeTimer = null;
        let timeLeft = 140;
        let entryPrice = 0;
        let tradeDirection = "";
        let tradeAmount = 0;

        async function openTradeProcessing(direction) {
            tradeAmount = parseFloat(document.getElementById('trade-amount-usdt').value) || 0;
            if (tradeAmount <= 0) {
                alert("Please enter a valid amount.");
                return;
            }
            
            // Check balance
            const currentBal = <?php echo $user['balance']; ?>;
            if (tradeAmount > currentBal) {
                alert("Insufficient balance.");
                return;
            }

            entryPrice = parseFloat(document.getElementById('main-price').innerText.replace('$', '').replace(',', ''));
            tradeDirection = direction;
            
            document.getElementById('proc-direction').innerText = direction;
            document.getElementById('proc-direction').style.background = direction.includes('Buy') ? 'rgba(0,201,167,0.2)' : 'rgba(255,77,77,0.2)';
            document.getElementById('proc-direction').style.color = direction.includes('Buy') ? '#00c9a7' : '#ff4d4d';
            document.getElementById('proc-buy-price').innerText = entryPrice.toLocaleString();
            document.getElementById('proc-lots').innerText = tradeAmount.toLocaleString() + ' USDT';
            document.getElementById('proc-curr-price').innerText = entryPrice.toLocaleString();
            document.getElementById('processingModal').style.display = 'flex';
            
            startCountdown();
        }

        function startCountdown() {
            timeLeft = 140;
            const ring = document.getElementById('proc-ring-circle');
            const timerVal = document.getElementById('timer-val');
            const total = 440;

            if (tradeTimer) clearInterval(tradeTimer);
            
            tradeTimer = setInterval(() => {
                timeLeft--;
                timerVal.innerText = timeLeft;
                const offset = total - (timeLeft / 140) * total;
                ring.style.strokeDashoffset = offset;

                const currentPrice = parseFloat(document.getElementById('main-price').innerText.replace('$', '').replace(',', ''));
                document.getElementById('proc-curr-price').innerText = currentPrice.toLocaleString();

                if (timeLeft <= 0) {
                    clearInterval(tradeTimer);
                    finalizeTrade(currentPrice);
                }
            }, 1000);
        }

        async function finalizeTrade(finalPrice) {
            document.getElementById('processingModal').style.display = 'none';
            
            let isWin = false;
            if (tradeDirection.includes('Buy')) {
                isWin = finalPrice >= entryPrice;
            } else {
                isWin = finalPrice < entryPrice;
            }

            // Simple win/loss logic (can be influenced by admin in the future)
            const winPercent = 0.85; 
            let resultAmount = isWin ? (tradeAmount * winPercent) : -tradeAmount;

            const response = await fetch('api/save_trade.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    asset: 'BTC/USDT',
                    type: tradeDirection.includes('Buy') ? 'buy' : 'sell',
                    amount: tradeAmount,
                    entry_price: entryPrice,
                    result_amount: resultAmount,
                    status: isWin ? 'win' : 'loss'
                })
            });

            const result = await response.json();
            if (result.success) {
                // Update UI balance
                document.getElementById('trade-user-balance').innerText = `$${parseFloat(result.new_balance).toLocaleString(undefined, { minimumFractionDigits: 2 })}`;
                document.getElementById('trade-user-balance-available').innerText = `$${parseFloat(result.new_balance).toLocaleString(undefined, { minimumFractionDigits: 2 })}`;
                
                // Show result modal
                const statusEl = document.getElementById('res-status');
                const amountEl = document.getElementById('res-amount');
                const directionEl = document.getElementById('res-direction');

                if (isWin) {
                    statusEl.innerText = "Completed";
                    statusEl.style.color = "#48bb78";
                    amountEl.innerText = `+${(tradeAmount * winPercent).toFixed(4)}`;
                    amountEl.style.color = "#48bb78";
                } else {
                    statusEl.innerText = "Loss";
                    statusEl.style.color = "#f56565";
                    amountEl.innerText = `-${tradeAmount.toFixed(4)}`;
                    amountEl.style.color = "#f56565";
                }

                directionEl.innerText = tradeDirection;
                directionEl.style.background = tradeDirection.includes('Buy') ? 'rgba(72,187,120,0.2)' : 'rgba(245,101,101,0.2)';
                directionEl.style.color = tradeDirection.includes('Buy') ? '#48bb78' : '#f56565';
                document.getElementById('res-buy-price').innerText = entryPrice.toLocaleString(undefined, { minimumFractionDigits: 5 });
                document.getElementById('res-curr-price').innerText = finalPrice.toLocaleString(undefined, { minimumFractionDigits: 2 });
                document.getElementById('res-lots').innerText = tradeAmount.toLocaleString() + ' USDT';
                document.getElementById('resultModal').style.display = 'flex';

                renderUserTrades();
            } else {
                alert("Error saving trade: " + result.message);
            }
        }

        async function renderUserTrades() {
            const tbody = document.getElementById('user-trades-tbody');
            const response = await fetch('api/get_trades.php');
            const data = await response.json();
            
            if (data.success) {
                tbody.innerHTML = '';
                if (data.trades.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="3" style="text-align: center; padding: 20px; color: rgba(255,255,255,0.2);">No trades yet</td></tr>';
                    return;
                }
                data.trades.forEach((trade) => {
                    const isWin = trade.status === 'win';
                    const resultVal = isWin ? (parseFloat(trade.amount) * 0.85) : -parseFloat(trade.amount);
                    const row = `
                        <tr>
                            <td>
                                <div style="display: flex; flex-direction: column; gap: 2px;">
                                    <span class="${isWin ? 'up' : 'down'}" style="font-weight: 800; font-size: 14px;">${isWin ? '+' : ''}${resultVal.toFixed(2)} USDT</span>
                                    <span class="outcome-badge ${isWin ? 'outcome-win' : 'outcome-loss'}" style="width: fit-content;">${trade.status}</span>
                                </div>
                            </td>
                            <td style="text-align: center;">
                                <div style="display: flex; flex-direction: column;">
                                    <span style="color: #fff; font-weight: 600;">${trade.amount}</span>
                                    <span style="font-size: 9px; color: rgba(255,255,255,0.3); text-transform: uppercase;">${trade.type}</span>
                                </div>
                            </td>
                            <td style="text-align: right; vertical-align: middle;">
                                <span style="color: rgba(255,255,255,0.4); font-size: 11px; font-weight: 500;">${new Date(trade.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</span>
                            </td>
                        </tr>
                    `;
                    tbody.insertAdjacentHTML('beforeend', row);
                });
            }
        }

        function closeTradeProcessing() {
            document.getElementById('processingModal').style.display = 'none';
            if (tradeTimer) clearInterval(tradeTimer);
        }

        function switchTradeTab(tab) {
            const marketTab = document.getElementById('tab-market');
            const userTab = document.getElementById('tab-user');
            const marketTable = document.getElementById('market-table');
            const userTable = document.getElementById('user-table');

            if (tab === 'market') {
                marketTab.style.color = '#fff';
                userTab.style.color = 'rgba(255,255,255,0.3)';
                marketTable.style.display = 'table';
                userTable.style.display = 'none';
            } else {
                userTab.style.color = '#fff';
                marketTab.style.color = 'rgba(255,255,255,0.3)';
                marketTable.style.display = 'none';
                userTable.style.display = 'table';
                renderUserTrades();
            }
        }

        // Ticker and Price Logic (mostly kept from original)
        const coinMap = { "BTCUSDT": "tick-BTC", "ETHUSDT": "tick-ETH", "SOLUSDT": "tick-SOL" };
        async function updateLivePrices() {
            try {
                const response = await fetch('https://api.binance.com/api/v3/ticker/24hr?symbols=["BTCUSDT","ETHUSDT","SOLUSDT"]');
                const data = await response.json();
                data.forEach(item => {
                    const price = parseFloat(item.lastPrice).toLocaleString(undefined, { minimumFractionDigits: 2 });
                    const pct = parseFloat(item.priceChangePercent).toFixed(2);
                    const tickId = coinMap[item.symbol];
                    if (tickId) {
                        document.getElementById(tickId).innerText = `$${price}`;
                        const pctEl = document.getElementById(`${tickId}-pct`);
                        pctEl.innerText = `${pct > 0 ? '+' : ''}${pct}%`;
                        pctEl.style.color = pct >= 0 ? '#00c9a7' : '#ff4d4d';
                    }
                    if (item.symbol === 'BTCUSDT') {
                        document.getElementById('main-price').innerText = `$${price}`;
                        document.getElementById('main-pct').innerText = `${pct > 0 ? '+' : ''}${pct}%`;
                        document.getElementById('main-pct').style.color = pct >= 0 ? '#00c9a7' : '#ff4d4d';
                        document.getElementById('stat-high').innerText = parseFloat(item.highPrice).toLocaleString();
                        document.getElementById('stat-low').innerText = parseFloat(item.lowPrice).toLocaleString();
                        document.getElementById('stat-vol').innerText = parseFloat(item.volume).toLocaleString(undefined, { maximumFractionDigits: 0 });
                    }
                });
            } catch (e) { console.error(e); }
        }

        // TradingView Initialization
        new TradingView.widget({
            "autosize": true,
            "symbol": "BINANCE:BTCUSDT",
            "interval": "15",
            "theme": "dark",
            "style": "1",
            "locale": "en",
            "toolbar_bg": "#f1f3f6",
            "enable_publishing": false,
            "hide_side_toolbar": false,
            "allow_symbol_change": true,
            "container_id": "tradingview_trade_chart"
        });

        setInterval(updateLivePrices, 3000);
        updateLivePrices();
        renderUserTrades();
    </script>

<?php require_once 'includes/footer.php'; ?>
