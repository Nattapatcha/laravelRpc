<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    /**
     * The trusted proxies for this application.
     *
     * @var array|string|null
     */
    protected $proxies = '*';  // หรือระบุ IP/CIDR ของ proxy ได้

    /**
     * The headers that should be used to detect proxies.
     *
     * @var int
     */
    // ใช้แบบรวมทุก header (ทั่วไป)
    protected $headers = Request::HEADER_X_FORWARDED_ALL;

    // ถ้าใช้งาน AWS ELB/ALB ให้ใช้ตัวนี้แทน:
    // protected $headers = Request::HEADER_X_FORWARDED_AWS_ELB;
}
