<?php

namespace App\Helpers;

use Carbon\Carbon;

class TableRenderer
{
    public static function render($item, ?string $field, $value, string $type = 'text', array $col = []): string
    {
        // Custom render closure
        if (isset($col['render']) && is_callable($col['render'])) {
            return $col['render']($item);
        }

        if ($value === null || $value === '') {
            return '<span style="color:#d1d5db;">—</span>';
        }

        switch ($type) {
            case 'date':
                $date = $value instanceof Carbon ? $value : Carbon::parse($value);
                return '<div style="font-weight:500;color:#374151;">' . e($date->format('d M Y')) . '</div>';

            case 'datetime':
                $date = $value instanceof Carbon ? $value : Carbon::parse($value);
                return '<div style="font-weight:500;color:#374151;">' . e($date->format('d M Y')) . '</div>'
                     . '<div style="font-size:11.5px;color:#9ca3af;">' . e($date->format('H:i A')) . '</div>';

            case 'money':
                return '<span style="font-weight:700;color:#1a1a1a;font-size:14px;">' . number_format((float) $value, 2) . '</span>';

            case 'money-green':
                return '<span style="font-weight:600;color:#16a34a;">' . number_format((float) $value, 2) . '</span>';

            case 'money-red':
                return '<span style="font-weight:600;color:#dc2626;">' . number_format((float) $value, 2) . '</span>';

            case 'badge':
                $color = $col['colors'][$value] ?? 'gray';
                return self::badge(ucfirst($value), $color);

            case 'status':
                return self::statusBadge($value);

            case 'payment':
                return self::paymentBadge($value);

            case 'roles':
                $roles = $item->getRoleNames();
                if ($roles->isEmpty()) return '<span style="color:#d1d5db;">—</span>';
                return $roles->map(fn($r) => self::roleBadge($r))->implode(' ');

            case 'stock':
                return self::stockBadge((int) $value);

            case 'avatar':
                $name = data_get($item, $col['name_field'] ?? 'name');
                $email = data_get($item, $col['email_field'] ?? 'email');
                $initial = strtoupper(substr($name ?? '?', 0, 1));
                return '<div style="display:flex;align-items:center;gap:10px;">'
                    . '<div style="width:36px;height:36px;border-radius:9px;background:linear-gradient(135deg,#3b82f6,#1e40af);color:white;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:14px;flex-shrink:0;">' . e($initial) . '</div>'
                    . '<div><div style="font-weight:600;color:#1a1a1a;">' . e($name) . '</div>'
                    . ($email ? '<div style="font-size:11.5px;color:#9ca3af;">' . e($email) . '</div>' : '')
                    . '</div></div>';

            case 'warehouse':
                return '<span style="background:#eff6ff;color:#1e40af;padding:4px 10px;border-radius:6px;font-size:11.5px;font-weight:600;">🏭 ' . e($value) . '</span>';

            case 'monospace':
                return '<span style="font-family:monospace;font-size:12.5px;color:#1e40af;font-weight:600;">' . e($value) . '</span>';

            case 'bold':
                return '<span style="font-weight:600;color:#1a1a1a;">' . e($value) . '</span>';

            case 'muted':
                return '<span style="color:#6b7280;">' . e($value) . '</span>';

            case 'text':
            default:
                return '<span style="color:#374151;">' . e($value) . '</span>';
        }
    }

    protected static function badge(string $label, string $color): string
    {
        $colors = [
            'green' => 'background:#dcfce7;color:#166534;',
            'red' => 'background:#fee2e2;color:#991b1b;',
            'yellow' => 'background:#fef3c7;color:#92400e;',
            'blue' => 'background:#dbeafe;color:#1e40af;',
            'gray' => 'background:#f3f4f6;color:#6b7280;',
            'purple' => 'background:#ede9fe;color:#6d28d9;',
            'orange' => 'background:#ffedd5;color:#9a3412;',
        ];
        $style = $colors[$color] ?? $colors['gray'];
        return '<span style="display:inline-block;' . $style . 'padding:4px 10px;border-radius:6px;font-size:11.5px;font-weight:600;">' . e($label) . '</span>';
    }

    protected static function statusBadge(string $status): string
    {
        $map = [
            'active' => ['green', 'Active'],
            'inactive' => ['gray', 'Inactive'],
            'open' => ['green', '🟢 OPEN'],
            'closed' => ['gray', '⚪ CLOSED'],
            'completed' => ['green', 'Completed'],
            'pending' => ['yellow', 'Pending'],
            'cancelled' => ['red', 'Cancelled'],
            'refunded' => ['red', 'Refunded'],
            'received' => ['green', 'Received'],
        ];
        $item = $map[strtolower($status)] ?? ['gray', ucfirst($status)];
        return self::badge($item[1], $item[0]);
    }

    protected static function paymentBadge(string $method): string
    {
        $map = [
            'cash' => ['green', '💵 Cash'],
            'card' => ['blue', '💳 Card'],
            'credit' => ['yellow', '📝 Credit'],
            'bank' => ['purple', '🏦 Bank'],
        ];
        $item = $map[strtolower($method)] ?? ['gray', ucfirst($method)];
        return self::badge($item[1], $item[0]);
    }

    protected static function roleBadge(string $role): string
    {
        $map = [
            'super_admin' => ['red', 'Super Admin'],
            'company_head' => ['yellow', 'Company Head'],
            'manager' => ['blue', 'Manager'],
            'salesman' => ['green', 'Salesman'],
        ];
        $item = $map[$role] ?? ['gray', str_replace('_', ' ', $role)];
        return self::badge($item[1], $item[0]);
    }

    protected static function stockBadge(int $stock): string
    {
        if ($stock <= 0) return self::badge('Out (0)', 'red');
        if ($stock <= 10) return self::badge('Low (' . $stock . ')', 'yellow');
        return self::badge((string) $stock, 'green');
    }
}