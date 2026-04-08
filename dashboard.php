<?php
$page_title = "Live Market Dashboard";
$active_page = "dashboard";
require_once 'includes/header.php';
?>

        <div class="dash-balance-bar" style="display: flex; justify-content: space-between; align-items: center; margin-top: 10px;">
            <div class="balance-display">
                <span style="font-size: 13px; color: rgba(255,255,255,0.4); font-weight: 600;">Total Balance</span>
                <h2 id="dash-live-balance" style="font-size: 28px; font-weight: 800; color: #fff;">$<?php echo number_format($user['balance'], 2); ?></h2>
            </div>
            <div id="dash-admin-signal"
                style="display: none; background: rgba(0, 201, 167, 0.1); color: #00c9a7; padding: 6px 12px; border-radius: 20px; font-size: 11px; font-weight: 800; border: 1px solid rgba(0, 201, 167, 0.2);">
                <i class="ph-fill ph-trend-up"></i> LIVE SIGNAL <span id="dash-win-val">50%</span>
            </div>
        </div>

        <h2 class="section-title">Live Market</h2>

        <div class="market-tabs">
            <div class="market-tab active" id="tab-gainers" onclick="switchTab('gainers')">Top Gainers</div>
            <div class="market-tab" id="tab-losers" onclick="switchTab('losers')">Losers</div>
            <div class="market-tab" id="tab-volume" onclick="switchTab('volume')">Volume</div>
        </div>

        <div class="coin-list" id="market-list">
            <!-- Market items will be dynamically rendered here -->
            <div style="text-align: center; padding: 40px; opacity: 0.5;">Loading live market data...</div>
        </div>

        <!-- Live Chart Section -->
        <div class="chart-container"
            style="margin-top: 30px; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.1); border-radius: 20px; overflow: hidden; height: 350px;">
            <div id="tradingview_chart" style="height: 100%;"></div>
        </div>
        <button class="cta-btn primary-cta"
            style="width: 100%; border: none; margin-top: 15px; font-size: 16px; cursor: pointer;"
            onclick="window.location.href='trade.php'">Buy / Sell</button>

        <script>
            // TradingView Widget Configuration
            let currentSymbol = "BTCUSDT";
            let activeTab = "gainers";
            let marketData = [];

            const coinMap = {
                "BTCUSDT": { name: "Bitcoin", color: "#F7931A", icon: "ph-currency-btc" },
                "ETHUSDT": { name: "Ethereum", color: "#627EEA", icon: "ph-currency-eth" },
                "BNBUSDT": { name: "Binance Coin", color: "#F3BA2F", icon: "ph-diamond" },
                "SOLUSDT": { name: "Solana", color: "#14F195", icon: "ph-lightning" },
                "ADAUSDT": { name: "Cardano", color: "#0033AD", icon: "ph-circle-dashed" },
                "XRPUSDT": { name: "XRP", color: "#23292F", icon: "ph-wave-sine" },
                "DOTUSDT": { name: "Polkadot", color: "#E6007A", icon: "ph-dots-three-circle" },
                "DOGEUSDT": { name: "Dogecoin", color: "#C2A633", icon: "ph-dog" },
                "AVAXUSDT": { name: "Avalanche", color: "#E84142", icon: "ph-sneaker-move" },
                "MATICUSDT": { name: "Polygon", color: "#8247E5", icon: "ph-hexagon" }
            };

            const coins = Object.keys(coinMap);

            function loadChart(symbol) {
                new TradingView.widget({
                    "autosize": true,
                    "symbol": "BINANCE:" + symbol,
                    "interval": "D",
                    "theme": "dark",
                    "style": "1",
                    "locale": "en",
                    "toolbar_bg": "#f1f3f6",
                    "enable_publishing": false,
                    "hide_top_toolbar": true,
                    "hide_legend": true,
                    "container_id": "tradingview_chart"
                });
            }

            function updateChart(symbol) {
                currentSymbol = symbol;
                loadChart(symbol);
            }

            function switchTab(tab) {
                activeTab = tab;
                document.querySelectorAll('.market-tab').forEach(t => t.classList.remove('active'));
                document.getElementById(`tab-${tab}`).classList.add('active');
                renderMarket();
            }

            async function fetchPrices() {
                try {
                    const response = await fetch('https://api.binance.com/api/v3/ticker/24hr?symbols=[' + coins.map(c => `"${c}"`).join(',') + ']');
                    marketData = await response.json();
                    renderMarket();
                } catch (error) {
                    console.error("Market fetch error:", error);
                }
            }

            function renderMarket() {
                const listEl = document.getElementById('market-list');
                let sortedData = [...marketData];

                if (activeTab === 'gainers') {
                    sortedData.sort((a, b) => b.priceChangePercent - a.priceChangePercent);
                } else if (activeTab === 'losers') {
                    sortedData.sort((a, b) => a.priceChangePercent - b.priceChangePercent);
                } else if (activeTab === 'volume') {
                    sortedData.sort((a, b) => b.quoteVolume - a.quoteVolume);
                }

                listEl.innerHTML = sortedData.slice(0, 6).map(item => {
                    const info = coinMap[item.symbol] || { name: item.symbol.replace('USDT', ''), color: '#666', icon: 'ph-currency-dollar' };
                    const price = parseFloat(item.lastPrice).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: (parseFloat(item.lastPrice) < 1 ? 4 : 2) });
                    const change = parseFloat(item.priceChangePercent);
                    const isPositive = change >= 0;

                    return `
                        <div class="coin-item" onclick="updateChart('${item.symbol}')">
                            <div class="coin-icon" style="background: ${info.color};"><i class="ph-fill ${info.icon}"></i></div>
                            <div class="coin-info">
                                <span class="coin-name">${info.name}</span>
                                <span class="coin-price" id="price-${item.symbol}">$${price}</span>
                            </div>
                            <div class="coin-stats">
                                <svg class="mini-chart"><path class="chart-line" style="stroke: ${isPositive ? '#00c9a7' : '#ff4d4d'}" d="${isPositive ? 'M0,15 L10,12 L20,18 L30,5 L40,8 L50,2 L60,5' : 'M0,5 L10,8 L20,2 L30,15 L40,12 L50,18 L60,15'}"></path></svg>
                                <span class="percent-badge ${isPositive ? 'percent-green' : 'percent-red'}">${isPositive ? '+' : ''}${change.toFixed(2)}%</span>
                            </div>
                        </div>
                    `;
                }).join('');
            }

            // Initial setup
            loadChart(currentSymbol);
            setInterval(fetchPrices, 3000);
            fetchPrices();
        </script>

<?php require_once 'includes/footer.php'; ?>
