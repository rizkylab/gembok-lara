<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Vouchers</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            background: #fff;
        }
        
        .voucher-grid {
            display: flex;
            flex-wrap: wrap;
            padding: 10px;
        }
        
        .voucher {
            width: 190px;
            border: 2px solid #000;
            border-radius: 6px;
            margin: 5px;
            page-break-inside: avoid;
            background: #fff;
            overflow: hidden;
        }
        
        .voucher-header {
            background-color: #2b3a4a; /* Laska dark theme */
            color: #fff;
            padding: 5px;
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            border-bottom: 2px solid #000;
            letter-spacing: 1px;
        }
        
        .voucher-table {
            width: 100%;
            border-collapse: collapse;
            margin: 5px 0;
        }
        
        .voucher-table td {
            padding: 2px 5px;
            font-size: 13px;
        }
        
        .voucher-table .label {
            width: 40px;
            font-weight: bold;
        }
        
        .voucher-table .value {
            font-weight: bold;
            font-size: 16px;
            letter-spacing: 1px;
        }
        
        .voucher-footer {
            background-color: #f0f0f0;
            text-align: center;
            padding: 5px;
            font-size: 11px;
            border-top: 1px solid #000;
            color: #333;
            font-weight: bold;
        }
        
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .no-print {
                display: none;
            }
            
            .voucher-grid {
                padding: 0;
            }

            .voucher {
                margin: 4px;
            }
        }
    </style>
</head>
<body>
    <div class="no-print" style="padding: 20px; background: #f3f4f6; text-align: center;">
        <button onclick="window.print()" style="padding: 10px 30px; background: #0891b2; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 16px;">
            🖨️ Print Vouchers
        </button>
        <button onclick="window.close()" style="padding: 10px 30px; background: #6b7280; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 16px; margin-left: 10px;">
            ✕ Close
        </button>
        <p style="margin-top: 10px; color: #666;">Total: {{ count($vouchers) }} vouchers</p>
    </div>

    <div class="voucher-grid">
        @foreach($vouchers as $voucher)
        @php
            $profileName = is_array($voucher) ? $voucher['profile'] : $voucher->profile_name;
            $username = is_array($voucher) ? $voucher['username'] : $voucher->username;
            $password = is_array($voucher) ? $voucher['password'] : $voucher->password;
            
            $validity = isset($profile) && $profile->validity ? $profile->validity : '';
            $price = isset($profile) && $profile->price ? 'Rp'.number_format($profile->price, 0, ',', '.') : '';
        @endphp
        <div class="voucher">
            <div class="voucher-header">
                HOTSPOT VOUCHER
            </div>
            <table class="voucher-table">
                <tr>
                    <td class="label">User</td>
                    <td>:</td>
                    <td class="value">{{ $username }}</td>
                </tr>
                <tr>
                    <td class="label">Pass</td>
                    <td>:</td>
                    <td class="value">{{ $password }}</td>
                </tr>
            </table>
            <div class="voucher-footer">
                @if($price || $validity)
                    {{ $price }}{{ $price && $validity ? ' / ' : '' }}{{ $validity }}<br>
                @endif
                {{ $profileName }}
            </div>
        </div>
        @endforeach
    </div>
</body>
</html>
