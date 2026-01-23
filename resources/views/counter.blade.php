<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Counter Realtime - Laravel Reverb</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #eff6ff 0%, #e0e7ff 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .container {
            width: 100%;
            max-width: 500px;
        }

        .card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            space-y: 2rem;
        }

        .card > * + * {
            margin-top: 2rem;
        }

        .header {
            text-align: center;
        }

        .header h1 {
            font-size: 2rem;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }

        .header p {
            color: #6b7280;
        }

        .counter-display {
            background: linear-gradient(to right, #3b82f6, #4f46e5);
            border-radius: 0.75rem;
            padding: 2rem;
            text-align: center;
            color: white;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .counter-display .label {
            font-size: 0.875rem;
            font-weight: 600;
            opacity: 0.9;
            margin-bottom: 0.5rem;
        }

        #counter-display {
            font-size: 3.5rem;
            font-weight: bold;
            transition: all 0.3s ease-in-out;
        }

        .input-section {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .input-group {
            display: flex;
            gap: 0.5rem;
        }

        #step-input {
            flex: 1;
            padding: 0.75rem 1rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            font-size: 1rem;
            transition: all 0.2s;
        }

        #step-input:focus {
            outline: none;
            ring: 2px;
            ring-color: #3b82f6;
            border-color: transparent;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            font-weight: 600;
            border: none;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        #increment-btn {
            background-color: #3b82f6;
            color: white;
        }

        #increment-btn:hover {
            background-color: #2563eb;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .quick-buttons {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.5rem;
        }

        .quick-btn {
            padding: 0.5rem 1rem;
            background-color: #f3f4f6;
            color: #374151;
            font-weight: 600;
            border: none;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .quick-btn:hover {
            background-color: #e5e7eb;
        }

        .quick-btn:active {
            transform: scale(0.95);
        }

        #reset-btn {
            width: 100%;
            padding: 0.75rem 1rem;
            background-color: #ef4444;
            color: white;
            font-weight: 600;
            border: none;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        #reset-btn:hover {
            background-color: #dc2626;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .status {
            text-align: center;
            font-size: 0.875rem;
            color: #6b7280;
        }

        .status-dot {
            display: inline-block;
            width: 0.5rem;
            height: 0.5rem;
            background-color: #22c55e;
            border-radius: 50%;
            margin-right: 0.5rem;
        }

        .status-dot.disconnected {
            background-color: #ef4444;
        }

        .info {
            margin-top: 2rem;
            text-align: center;
            color: #4b5563;
            font-size: 0.875rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <!-- Header -->
            <div class="header">
                <h1>Counter Realtime</h1>
                <p>Đếm số với Laravel Reverb</p>
            </div>

            <!-- Display Counter -->
            <div class="counter-display">
                <p class="label">Số đếm hiện tại</p>
                <p id="counter-display">0</p>
            </div>

            <!-- Input Section -->
            <div class="input-section">
                <div class="input-group">
                    <input
                        type="number"
                        id="step-input"
                        placeholder="Nhập số cần cộng"
                        value="1"
                        min="1"
                    >
                    <button id="increment-btn" class="btn">
                        Cộng
                    </button>
                </div>

                <!-- Quick increment buttons -->
                <div class="quick-buttons">
                    <button class="quick-btn" data-step="1">+1</button>
                    <button class="quick-btn" data-step="5">+5</button>
                    <button class="quick-btn" data-step="10">+10</button>
                </div>
            </div>

            <!-- Reset Button -->
            <button id="reset-btn">Đặt lại</button>

            <!-- Status -->
            <div class="status" id="status">
                <span class="status-dot"></span>
                Đang kết nối...
            </div>
        </div>

        <!-- Info -->
        <div class="info">
            <p>Mở trang này trong nhiều tab để xem cập nhật realtime</p>
        </div>
    </div>
</body>
</html>
