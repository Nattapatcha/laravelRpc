<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AgriculturistsController extends Controller
{
    /** แสดงรายการเกษตรกร (type = 1) */
    public function index(Request $request)
    {
        $perPage = (int) $request->input('perPage', 15);
        $kw = trim((string) $request->input('keyword', ''));

        $q = User::query()
            ->where('type', User::TYPE_AGRICULTURIST) // 1
            ->select(['id', 'name', 'surname', 'idcard', 'organization', 'created_at']);

        if ($kw !== '') {
            $q->where(function ($qq) use ($kw) {
                $qq->where('name', 'like', "%{$kw}%")
                    ->orWhere('surname', 'like', "%{$kw}%")
                    ->orWhere('idcard', 'like', "%{$kw}%")
                    ->orWhere('organization', 'like', "%{$kw}%"); // ถ้าเป็น int ก็ยังค้นหาได้
            });
        }
        $users = $q->orderByDesc('created_at')->orderByDesc('id')->paginate($perPage);
        

        if ($request->wantsJson()) {
            return response()->json(['data' => $users]);
        }

        return view('users.agriculturists', compact('users'));
    }
}
