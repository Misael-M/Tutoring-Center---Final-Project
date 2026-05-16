<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Confirmación de Cita — Red Apple</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f9f9f9; padding: 20px; color: #333; margin: 0;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">

        {{-- HEADER --}}
        <div style="background-color: #ff6b6b; padding: 24px 30px; display: flex; align-items: center;">
            <h1 style="color: #ffffff; font-size: 20px; margin: 0;">Centro de Tutorías Red Apple</h1>
        </div>

        {{-- BODY --}}
        <div style="padding: 30px;">
            <h2 style="color: #ff6b6b; font-size: 16px; margin-bottom: 8px;">✅ Confirmación de Cita de Tutoría</h2>
            <p style="margin-bottom: 20px;">
                Hola <strong>{{ $appointment->student->name }}</strong>,<br>
                tu cita de tutoría ha sido agendada exitosamente en el Centro Red Apple.
                A continuación encontrarás los detalles:
            </p>

            <table style="width: 100%; border-collapse: collapse; margin-bottom: 24px;">
                <tr>
                    <td style="padding: 10px 12px; border-bottom: 1px solid #f0f0f0; font-weight: bold; color: #555; width: 38%;">Tutor</td>
                    <td style="padding: 10px 12px; border-bottom: 1px solid #f0f0f0;">{{ $appointment->tutor->name }}</td>
                </tr>
                <tr style="background: #fff8f8;">
                    <td style="padding: 10px 12px; border-bottom: 1px solid #f0f0f0; font-weight: bold; color: #555;">Especialidad</td>
                    <td style="padding: 10px 12px; border-bottom: 1px solid #f0f0f0;">{{ $appointment->tutor->tutor->specialty ?? '—' }}</td>
                </tr>
                <tr>
                    <td style="padding: 10px 12px; border-bottom: 1px solid #f0f0f0; font-weight: bold; color: #555;">Fecha</td>
                    <td style="padding: 10px 12px; border-bottom: 1px solid #f0f0f0;">{{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }}</td>
                </tr>
                <tr style="background: #fff8f8;">
                    <td style="padding: 10px 12px; border-bottom: 1px solid #f0f0f0; font-weight: bold; color: #555;">Hora</td>
                    <td style="padding: 10px 12px; border-bottom: 1px solid #f0f0f0;">{{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }} — {{ \Carbon\Carbon::parse($appointment->end_time)->format('H:i') }}</td>
                </tr>
                <tr>
                    <td style="padding: 10px 12px; font-weight: bold; color: #555;">Motivo</td>
                    <td style="padding: 10px 12px;">{{ $appointment->reason }}</td>
                </tr>
            </table>

            <div style="background: #fff0f0; border-left: 4px solid #ff6b6b; padding: 12px 16px; border-radius: 4px; margin-bottom: 24px; font-size: 13px; color: #555;">
                📎 Se adjunta a este correo el <strong>comprobante en PDF</strong> con los datos completos de tu cita. Guárdalo para tus registros.
            </div>

            <p style="font-size: 12px; color: #888; text-align: center; margin-top: 30px;">
                Gracias por confiar en el Centro de Tutorías Red Apple.<br>
                Si tienes alguna duda, responde a este correo o contáctanos directamente.
            </p>
        </div>

        {{-- FOOTER --}}
        <div style="background: #fafafa; border-top: 1px solid #f0f0f0; padding: 14px 30px; text-align: center; font-size: 11px; color: #aaa;">
            Centro de Tutorías Red Apple &nbsp;|&nbsp; Correo generado automáticamente — {{ now()->format('d/m/Y') }}
        </div>
    </div>
</body>
</html>
