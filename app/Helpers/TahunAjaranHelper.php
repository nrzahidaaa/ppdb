<?php

namespace App\Helpers;

use App\Models\TahunAjaran;

class TahunAjaranHelper
{
    public static function getSelectedId()
    {
        $selectedId = session('selected_tahun_ajaran_id');

        if ($selectedId) {
            return $selectedId;
        }

        $aktif = TahunAjaran::where('is_active', true)->first();
        return $aktif?->id;
    }

    public static function getSelected()
    {
        $id = self::getSelectedId();
        return $id ? TahunAjaran::find($id) : null;
    }
}