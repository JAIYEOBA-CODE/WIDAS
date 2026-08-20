<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Security Report - {{ $report['period'] }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; }
        h1 { color: #FF5A36; border-bottom: 2px solid #FF5A36; padding-bottom: 10px; }
        h2 { color: #0F172A; margin-top: 20px; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        th, td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #0F172A; color: white; }
        .summary { display: flex; gap: 15px; flex-wrap: wrap; }
        .card { background: #f8f9fa; padding: 15px; border-radius: 5px; flex: 1; min-width: 150px; }
        .card h3 { margin: 0 0 5px; font-size: 14px; color: #666; }
        .card .value { font-size: 24px; font-weight: bold; color: #FF5A36; }
        .footer { margin-top: 30px; padding-top: 10px; border-top: 1px solid #ddd; font-size: 10px; color: #999; }
    </style>
</head>
<body>
    <h1>Security Report - {{ ucfirst($report['period']) }}</h1>
    <p>Generated: {{ now()->format('Y-m-d H:i:s') }}</p>

    <h2>Summary</h2>
    <div class="summary">
        @foreach($report['summary'] as $key => $value)
        <div class="card">
            <h3>{{ ucfirst(str_replace('_', ' ', $key)) }}</h3>
            <div class="value">{{ $value }}</div>
        </div>
        @endforeach
    </div>

    @if(isset($report['threats_by_type']) && count($report['threats_by_type']))
    <h2>Threats by Type</h2>
    <table>
        <thead>
            <tr>
                <th>Type</th>
                <th>Count</th>
            </tr>
        </thead>
        <tbody>
            @foreach($report['threats_by_type'] as $type => $count)
            <tr>
                <td>{{ ucfirst(str_replace('_', ' ', $type)) }}</td>
                <td>{{ $count }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    @if(isset($report['threats_by_severity']) && count($report['threats_by_severity']))
    <h2>Threats by Severity</h2>
    <table>
        <thead>
            <tr>
                <th>Severity</th>
                <th>Count</th>
            </tr>
        </thead>
        <tbody>
            @foreach($report['threats_by_severity'] as $severity => $count)
            <tr>
                <td>{{ ucfirst($severity) }}</td>
                <td>{{ $count }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    @if(isset($report['daily_breakdown']) && count($report['daily_breakdown']))
    <h2>Daily Breakdown</h2>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Threats</th>
                <th>Alerts</th>
            </tr>
        </thead>
        <tbody>
            @foreach($report['daily_breakdown'] as $day)
            <tr>
                <td>{{ $day['date'] }}</td>
                <td>{{ $day['threats'] }}</td>
                <td>{{ $day['alerts'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <div class="footer">
        <p>WIDAS - Web-Based Intrusion Detection and Alert System</p>
        <p>This report is automatically generated. For questions, contact your system administrator.</p>
    </div>
</body>
</html>
