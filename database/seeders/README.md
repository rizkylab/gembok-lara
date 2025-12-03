# Database Seeders

Dokumentasi lengkap untuk semua seeder yang tersedia di aplikasi.

## Cara Menjalankan

```bash
# Jalankan semua seeder
php artisan db:seed

# Jalankan seeder tertentu
php artisan db:seed --class=CustomerSeeder

# Reset database dan jalankan seeder
php artisan migrate:fresh --seed
```

## Daftar Seeder

### 1. Core Settings
- **UserSeeder** - Membuat user admin dan staff
- **AppSettingSeeder** - Konfigurasi aplikasi dasar

### 2. Package & Pricing
- **PackageSeeder** - Paket internet (10 Mbps, 20 Mbps, 50 Mbps, 100 Mbps)
- **VoucherPricingSeeder** - Harga voucher untuk berbagai durasi

### 3. Staff Management
- **TechnicianSeeder** - Data teknisi
- **CollectorSeeder** - Data kolektor pembayaran

### 4. Agent System
- **AgentSeeder** - Data agen penjualan (3 agen)
- **AgentBalanceSeeder** - Saldo agen
- **AgentTransactionSeeder** - Transaksi agen (topup, withdrawal, commission, dll)
- **AgentBalanceRequestSeeder** - Request penambahan saldo
- **AgentNotificationSeeder** - Notifikasi untuk agen
- **AgentPaymentSeeder** - Pembayaran melalui agen
- **AgentMonthlyPaymentSeeder** - Pembayaran bulanan melalui agen
- **AgentVoucherSaleSeeder** - Penjualan voucher oleh agen

### 5. Network Infrastructure
- **OdpSeeder** - Optical Distribution Point (5 ODP)
- **NetworkSegmentSeeder** - Segmen jaringan backbone/distribution
- **CableRouteSeeder** - Rute kabel untuk setiap customer
- **OnuDeviceSeeder** - Perangkat ONU customer
- **CableMaintenanceLogSeeder** - Log maintenance kabel

### 6. Customer & Billing
- **CustomerSeeder** - Data customer (5 customer)
- **InvoiceSeeder** - Invoice bulanan untuk customer

### 7. Voucher System
- **VoucherPurchaseSeeder** - Pembelian voucher (20 transaksi)
- **VoucherGenerationSettingSeeder** - Pengaturan generate voucher
- **VoucherOnlineSettingSeeder** - Pengaturan voucher online (1H, 3H, 1D, 7D, 30D)
- **VoucherDeliveryLogSeeder** - Log pengiriman voucher

### 8. Reports
- **MonthlySummarySeeder** - Ringkasan bulanan (3 bulan terakhir)

## Data yang Dihasilkan

### Users
- Admin: admin@gembok.com / password

### Customers
- 5 customer dengan status active/suspended
- Masing-masing memiliki 2 invoice (bulan lalu dan bulan ini)

### Agents
- 3 agen dengan username: berkah, jaya, makmur
- Masing-masing memiliki balance, transaksi, dan notifikasi

### Network
- 5 ODP dengan berbagai kapasitas
- 5 network segment
- Cable routes untuk setiap customer
- 10 ONU devices

### Vouchers
- 20 voucher purchase dengan status completed/pending/failed
- 5 voucher online settings
- Delivery logs untuk voucher yang completed

### Maintenance
- Log maintenance untuk cable routes dan network segments

## Urutan Eksekusi

Seeder dijalankan dalam urutan berikut (penting untuk foreign key constraints):

1. Core Settings (User, AppSetting)
2. Package & Pricing
3. Staff (Technician, Collector)
4. Agent System
5. Network Infrastructure (ODP, NetworkSegment)
6. Customer & Invoice
7. Cable & ONU
8. Voucher System
9. Agent Payments & Sales
10. Summary Reports

## Catatan

- Semua password default adalah: `password`
- Data yang dihasilkan adalah dummy data untuk testing
- Beberapa data menggunakan random untuk variasi
- Foreign key constraints dijaga dengan urutan seeder yang tepat
