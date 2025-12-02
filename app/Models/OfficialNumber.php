<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfficialNumber extends Model
{
    use HasFactory;

    protected $fillable = [
        'official_number',
        'assignment_date',
        'treasury_office_number',
        'treasury_date', // NUEVO
        'property_type',
        'owner_name',
        'curp_rfc',
        'street_name',
        'ext_number',
        'int_number',
        'suburb',
        'city',
        'colindancia_norte',
        'colindancia_sur',
        'colindancia_este',
        'colindancia_oeste',
        'referencia_cercana',
        'front_measurement',
        'depth_measurement',
        'area_sqm',
        'croquis_base64',
        'doc_escrituras',
        'doc_constancia',
        'doc_ine',
        'doc_ine_reverso',
        'doc_predial',
        'assigned_by_user_id',
    ];

    protected $casts = [
        'treasury_date' => 'date', // Para que Carbon maneje la fecha automáticamente
    ];

    public static function generateOfficialNumber(): string
    {
        $lastId = self::latest()->first()->id ?? 0;
        $nextId = $lastId + 1;
        $year = date('Y');
        $consecutiveId = str_pad($nextId, 5, '0', STR_PAD_LEFT);
        return "MXL-{$year}-{$consecutiveId}";
    }
}