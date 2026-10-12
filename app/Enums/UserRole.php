<?php

namespace App\Enums;

enum UserRole: string
{
    case SUPER_ADMIN = 'super_admin';
    case COMPANY_HEAD = 'company_head';
    case MANAGER = 'manager';
    case SALESMAN = 'salesman';

    public function label(): string
    {
        return match($this) {
            self::SUPER_ADMIN => 'Super Admin',
            self::COMPANY_HEAD => 'Company Head',
            self::MANAGER => 'Manager',
            self::SALESMAN => 'Salesman',
        };
    }
}