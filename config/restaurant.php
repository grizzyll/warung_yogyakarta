<?php

return [
    // Pesanan >= nilai ini menunggu approval owner. Kode lamamu: 10 juta.
    'approval_threshold' => (int) env('ORDER_APPROVAL_THRESHOLD', 10000000),
];