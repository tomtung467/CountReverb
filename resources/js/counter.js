import './bootstrap';

// DOM Elements
const counterDisplay = document.getElementById('counter-display');
const incrementBtn = document.getElementById('increment-btn');
const resetBtn = document.getElementById('reset-btn');
const stepInput = document.getElementById('step-input');
const quickBtns = document.querySelectorAll('.quick-btn');
const statusEl = document.getElementById('status');

console.log('🔧 DOM Elements loaded:', {
    counterDisplay: !!counterDisplay,
    incrementBtn: !!incrementBtn,
    resetBtn: !!resetBtn,
    stepInput: !!stepInput,
    quickBtns: quickBtns.length,
    statusEl: !!statusEl,
});

// Helper functions
function updateCounter(count) {
    console.log('🔢 Counter updated to:', count);
    counterDisplay.textContent = count;
}

function updateStatus(message, isConnected) {
    console.log(`📌 Status: ${message} (connected: ${isConnected})`);
    const dotClass = isConnected ? 'status-dot' : 'status-dot disconnected';
    statusEl.innerHTML = `
        <span class="${dotClass}"></span>
        ${message}
    `;
}

// Fetch initial counter value
async function loadInitialCounter() {
    console.log('📥 Fetching initial counter value...');
    try {
        const response = await fetch('/api/counter');
        if (!response.ok) throw new Error(`HTTP ${response.status}`);
        const data = await response.json();
        console.log('✅ Initial counter loaded:', data);
        updateCounter(data.count);
        return true;
    } catch (error) {
        console.error('❌ Failed to load initial counter:', error);
        updateStatus('Lỗi tải dữ liệu', false);
        return false;
    }
}

// Wait for Echo to be ready and setup WebSocket
function waitForEcho(callback, attempts = 0) {
    if (window.Echo && attempts < 50) {
        console.log('✅ Echo initialized, proceeding with setup');
        callback();
    } else if (attempts < 50) {
        console.log(`⏳ Waiting for Echo... (attempt ${attempts + 1}/50)`);
        setTimeout(() => waitForEcho(callback, attempts + 1), 100);
    } else {
        console.error('❌ Echo failed to initialize after 50 attempts');
        updateStatus('Lỗi kết nối WebSocket', false);
    }
}

waitForEcho(async () => {
    // First: Load initial counter value
    await loadInitialCounter();

    // Second: Subscribe to counter channel
    console.log('📡 Subscribing to counter channel...');
    const channel = window.Echo.channel('counter');
    console.log('📌 Channel object created:', {
        name: channel.name,
        subscribed: channel.subscribed,
    });

    // Add event listeners
    console.log('🔗 Attaching event listeners...');

    channel.listen('counter.updated', (data) => {
        console.log('📨 [LISTEN] Event received - counter.updated:', data);
        updateCounter(data.count);
    });

    channel.on('subscribe', () => {
        console.log('✅ [SUBSCRIBE] Connected to counter channel successfully');
        updateStatus('Đã kết nối', true);
    });

    channel.on('error', (error) => {
        console.error('❌ [ERROR] WebSocket/Channel error:', error);
        updateStatus('Lỗi kết nối', false);
    });

    channel.on('subscription_error', (error) => {
        console.error('❌ [SUBSCRIPTION_ERROR] Subscription error:', error);
        updateStatus('Lỗi subscribe', false);
    });

    channel.on('subscribed', () => {
        console.log('✅ [SUBSCRIBED] Channel fully subscribed');
        updateStatus('Đã kết nối', true);
    });

    console.log('🔗 Event listeners attached');

    // Monitor subscription status
    let checkCount = 0;
    const subscriptionChecker = setInterval(() => {
        checkCount++;
        const status = {
            subscribed: channel.subscribed,
            name: channel.name,
        };
        console.log(`📊 [Check ${checkCount}] Subscription status:`, status);

        if (channel.subscribed) {
            console.log('✅ Channel IS subscribed!');
            if (checkCount === 1) {
                updateStatus('Đã kết nối', true);
            }
        }

        if (checkCount >= 5) {
            clearInterval(subscriptionChecker);
        }
    }, 1000);

    // Setup button event listeners
    console.log('🔘 Setting up button listeners...');

    // Increment button
    incrementBtn.addEventListener('click', async () => {
        const step = parseInt(stepInput.value) || 1;
        console.log('➕ Increment clicked, step:', step);
        try {
            const response = await fetch('/api/counter/increment', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                },
                body: JSON.stringify({ step }),
            });

            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: Failed to increment counter`);
            }

            const data = await response.json();
            console.log('✅ Increment response:', data);
            updateCounter(data.count);
        } catch (error) {
            console.error('❌ Increment error:', error);
            alert('Lỗi khi cập nhật bộ đếm: ' + error.message);
        }
    });

    // Quick increment buttons
    quickBtns.forEach((btn) => {
        btn.addEventListener('click', async () => {
            const step = parseInt(btn.dataset.step);
            console.log('⚡ Quick increment clicked, step:', step);
            try {
                const response = await fetch('/api/counter/increment', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                    },
                    body: JSON.stringify({ step }),
                });

                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}`);
                }

                const data = await response.json();
                console.log('✅ Quick increment response:', data);
                updateCounter(data.count);
            } catch (error) {
                console.error('❌ Quick increment error:', error);
            }
        });
    });

    // Reset button
    resetBtn.addEventListener('click', async () => {
        if (confirm('Bạn chắc chắn muốn đặt lại bộ đếm?')) {
            console.log('🔄 Reset clicked');
            try {
                const response = await fetch('/api/counter/reset', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                    },
                });

                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}`);
                }

                const data = await response.json();
                console.log('✅ Reset response:', data);
                updateCounter(data.count);
            } catch (error) {
                console.error('❌ Reset error:', error);
                alert('Lỗi khi đặt lại bộ đếm: ' + error.message);
            }
        }
    });

    console.log('🔘 Button listeners setup complete');
});

