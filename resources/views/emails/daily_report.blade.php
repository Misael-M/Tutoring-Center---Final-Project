<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $reportTitle }}</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f9f9f9; padding: 20px; color: #333; margin: 0;">
    <div style="max-width: 800px; margin: 0 auto; background: #ffffff; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">

        {{-- HEADER --}}
        <div style="background-color: #ff6b6b; padding: 22px 30px;">
            <h1 style="color: #fff; font-size: 18px; margin: 0;">Centro de Tutorías Red Apple</h1>
        </div>

        {{-- BODY --}}
        <div style="padding: 30px;">
            <h2 style="color: #ff6b6b; font-size: 16px; margin-bottom: 4px;">📊 {{ $reportTitle }}</h2>
            <p style="color: #777; font-size: 13px; margin-bottom: 20px;">
                Fecha: <strong>{{ now()->format('d/m/Y') }}</strong>
            </p>

            @if($appointments->count() > 0)
                <table style="width: 100%; border-collapse: collapse; font-size: 13px; margin-bottom: 20px;">
                    <thead>
                        <tr style="background-color: #fff0f0; border-bottom: 2px solid #ff6b6b;">
                            <th style="padding: 10px 12px; text-align: left; color: #ff6b6b;">Hora</th>
                            <th style="padding: 10px 12px; text-align: left; color: #ff6b6b;">Estudiante</th>
                            <th style="padding: 10px 12px; text-align: left; color: #ff6b6b;">Tutor</th>
                            <th style="padding: 10px 12px; text-align: left; color: #ff6b6b;">Motivo</th>
                            <th style="padding: 10px 12px; text-align: left; color: #ff6b6b;">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($appointments as $index => $appointment)
                        <tr style="border-bottom: 1px solid #f0f0f0; {{ $index % 2 === 0 ? '' : 'background: #fff8f8;' }}">
                            <td style="padding: 10px 12px;">{{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }}</td>
                            <td style="padding: 10px 12px;">{{ $appointment->student->name }}</td>
                            <td style="padding: 10px 12px;">{{ $appointment->tutor->name }}</td>
                            <td style="padding: 10px 12px; max-width: 200px;">{{ Str::limit($appointment->reason, 50) }}</td>
                            <td style="padding: 10px 12px;">
                                <span style="padding: 3px 8px; border-radius: 12px; font-size: 11px; font-weight: bold;
                                    {{ $appointment->status === 'Completada' ? 'background:#d1fae5; color:#065f46;' : ($appointment->status === 'Cancelada' ? 'background:#fee2e2; color:#991b1b;' : 'background:#fef3c7; color:#92400e;') }}">
                                    {{ $appointment->status }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <p style="text-align: right; font-size: 13px; color: #555;">
                    Total de citas para hoy: <strong style="color: #ff6b6b;">{{ $appointments->count() }}</strong>
                </p>
            @else
                <div style="text-align: center; padding: 40px; background-color: #fff8f8; border-radius: 8px; margin: 20px 0; border: 1px dashed #ffb3b3;">
                    <p style="color: #888; font-size: 15px;">No hay citas agendadas para el día de hoy.</p>
                </div>
            @endif
        </div>

        {{-- FOOTER --}}
        <div style="background: #fafafa; border-top: 1px solid #f0f0f0; padding: 14px 30px; text-align: center; font-size: 11px; color: #aaa;">
            Centro de Tutorías Red Apple &nbsp;|&nbsp; Reporte automático generado a las {{ now()->format('H:i') }} hrs.
        </div>
    </div>
</body>
</html>
