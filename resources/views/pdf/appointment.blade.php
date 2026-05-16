<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comprobante de Cita de Tutoría</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 13px; color: #333; background: #fff; }

        /* ── CABECERA ── */
        .header {
            background-color: #ff6b6b;
            color: #fff;
            padding: 20px 30px;
            display: table;
            width: 100%;
        }
        .header-left { display: table-cell; vertical-align: middle; width: 70px; }
        .header-logo { width: 65px; height: 65px; }
        .header-text { display: table-cell; vertical-align: middle; padding-left: 16px; }
        .header-text h1 { font-size: 20px; font-weight: bold; letter-spacing: 0.5px; }
        .header-text p  { font-size: 12px; opacity: 0.85; margin-top: 3px; }
        .header-right { display: table-cell; vertical-align: middle; text-align: right; font-size: 11px; opacity: 0.85; }

        /* ── FOLIO ── */
        .folio-bar {
            background: #fff0f0;
            border-left: 4px solid #ff6b6b;
            padding: 10px 30px;
            margin: 20px 30px 0;
            border-radius: 4px;
        }
        .folio-bar strong { color: #ff6b6b; }

        /* ── SECCIONES ── */
        .section { margin: 20px 30px 0; }
        .section-title {
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #ff6b6b;
            border-bottom: 1px solid #ffe0e0;
            padding-bottom: 5px;
            margin-bottom: 12px;
        }

        /* ── TABLA DE DATOS ── */
        .info-table { width: 100%; border-collapse: collapse; }
        .info-table tr:nth-child(even) td { background: #fafafa; }
        .info-table td {
            padding: 9px 12px;
            border: 1px solid #eeeeee;
            vertical-align: top;
        }
        .info-table td.label {
            width: 35%;
            font-weight: bold;
            color: #555;
            background: #fff8f8;
        }

        /* ── BADGE ESTADO ── */
        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
        }
        .badge-programada  { background: #fef3c7; color: #92400e; }
        .badge-completada  { background: #d1fae5; color: #065f46; }
        .badge-cancelada   { background: #fee2e2; color: #991b1b; }

        /* ── AVISO ── */
        .notice {
            margin: 20px 30px 0;
            background: #fff8f8;
            border: 1px dashed #ffb3b3;
            border-radius: 6px;
            padding: 12px 16px;
            font-size: 11px;
            color: #666;
        }

        /* ── PIE DE PÁGINA ── */
        .footer {
            margin-top: 40px;
            border-top: 1px solid #eee;
            padding: 14px 30px;
            text-align: center;
            font-size: 10px;
            color: #999;
        }
        .footer strong { color: #ff6b6b; }
    </style>
</head>
<body>

    {{-- ── CABECERA ── --}}
    <div class="header">
        <div class="header-left">
            <img class="header-logo" src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/logo.png'))) }}">
        </div>
        <div class="header-text">
            <h1>Centro de Tutorías Red Apple</h1>
            <p>Comprobante de Cita de Tutoría</p>
        </div>
        <div class="header-right">
            Folio: <strong>#{{ str_pad($appointment->id, 6, '0', STR_PAD_LEFT) }}</strong><br>
            Generado: {{ now()->format('d/m/Y H:i') }}
        </div>
    </div>

    {{-- ── FOLIO ── --}}
    <div class="folio-bar">
        <strong>Estado:</strong>
        @php $statusClass = strtolower(str_replace('é','e',$appointment->status)); @endphp
        <span class="badge badge-{{ $statusClass }}">{{ $appointment->status }}</span>
        &nbsp;&nbsp;|&nbsp;&nbsp;
        <strong>ID de Cita:</strong> #{{ str_pad($appointment->id, 6, '0', STR_PAD_LEFT) }}
    </div>

    {{-- ── DATOS DEL ESTUDIANTE ── --}}
    <div class="section">
        <div class="section-title">Datos del Estudiante</div>
        <table class="info-table">
            <tr>
                <td class="label">Nombre completo</td>
                <td>{{ $appointment->student->name }}</td>
            </tr>
            <tr>
                <td class="label">Correo electrónico</td>
                <td>{{ $appointment->student->email }}</td>
            </tr>
            <tr>
                <td class="label">Teléfono</td>
                <td>
                    @if($appointment->student->phone)
                        +{{ $appointment->student->country_code ?? '52' }} {{ $appointment->student->phone }}
                    @else
                        No registrado
                    @endif
                </td>
            </tr>
        </table>
    </div>

    {{-- ── DATOS DEL TUTOR ── --}}
    <div class="section" style="margin-top:16px;">
        <div class="section-title">Tutor Asignado</div>
        <table class="info-table">
            <tr>
                <td class="label">Nombre</td>
                <td>{{ $appointment->tutor->name }}</td>
            </tr>
            <tr>
                <td class="label">Especialidad</td>
                <td>{{ $appointment->tutor->tutor->specialty ?? '—' }}</td>
            </tr>
            <tr>
                <td class="label">Correo</td>
                <td>{{ $appointment->tutor->email }}</td>
            </tr>
        </table>
    </div>

    {{-- ── DATOS DE LA CITA ── --}}
    <div class="section" style="margin-top:16px;">
        <div class="section-title">Detalles de la Cita</div>
        <table class="info-table">
            <tr>
                <td class="label">Fecha</td>
                <td>{{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td class="label">Hora</td>
                <td>{{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }} — {{ \Carbon\Carbon::parse($appointment->end_time)->format('H:i') }}</td>
            </tr>
            <tr>
                <td class="label">Motivo de la tutoría</td>
                <td>{{ $appointment->reason }}</td>
            </tr>
        </table>
    </div>

    {{-- ── AVISO ── --}}
    <div class="notice">
        📋 Este documento es un comprobante oficial generado automáticamente por el sistema del Centro de Tutorías Red Apple.
        Por favor consérvelo como constancia de su cita programada. Ante cualquier duda, contáctenos.
    </div>

    {{-- ── PIE DE PÁGINA ── --}}
    <div class="footer">
        <strong>Centro de Tutorías Red Apple</strong> &nbsp;|&nbsp; Sistema de Gestión de Tutorías<br>
        Documento generado el {{ now()->format('d/m/Y') }} a las {{ now()->format('H:i:s') }} hrs.
    </div>

</body>
</html>
