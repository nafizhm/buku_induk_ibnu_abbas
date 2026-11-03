<?php

namespace App\Http\Controllers\Pengaturan;

use App\Http\Controllers\Controller;
use App\Models\HakAkses;
use App\Support\SpmbQuota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class KuotaPendaftaranController extends Controller
{
    public function update(Request $request)
    {
        $data = $request->validate([
            'jenjang' => ['required', Rule::in(array_keys(SpmbQuota::LIMITS))],
            'jk' => ['required', Rule::in(array_keys(SpmbQuota::LIMITS[$request->input('jenjang')] ?? []))],
            'kuota' => ['required', 'integer', 'min:0', 'max:1000000'],
        ]);
        $route = $data['jenjang'] === 'SMP' ? 'admin.smp.spmb.index' : 'admin.spmb.index';
        abort_unless(HakAkses::where('id_user', auth()->id())->where('lihat', 1)->where('edit', 1)
            ->whereHas('menu', fn ($query) => $query->where('route_name', $route))->exists(), 403);

        DB::transaction(function () use ($data) {
            $query = DB::table('spmb_quota_locks')->where('jenjang', $data['jenjang'])->where('jk', $data['jk']);
            $query->lockForUpdate()->firstOrFail();
            $query->update(['kuota' => $data['kuota']]);
        }, 3);

        return redirect()->route('dashboard')->with('success', 'Kuota '.$data['jenjang'].' '.$data['jk'].' berhasil disimpan.');
    }
}
