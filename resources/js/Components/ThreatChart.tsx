import { LineChart, Line, XAxis, YAxis, CartesianGrid, Tooltip, ResponsiveContainer, Area, AreaChart } from 'recharts';

interface ThreatChartProps {
    data: { date: string; count: number }[];
    color?: string;
}

export default function ThreatChart({ data, color = '#FF5A36' }: ThreatChartProps) {
    return (
        <div className="bg-white dark:bg-dark-2 rounded-xl p-6 shadow-sm border border-light-2 dark:border-dark-3">
            <h3 className="text-sm font-semibold text-secondary dark:text-light mb-4">Threat Trend</h3>
            <div className="h-64">
                <ResponsiveContainer width="100%" height="100%">
                    <AreaChart data={data}>
                        <defs>
                            <linearGradient id="colorCount" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="5%" stopColor={color} stopOpacity={0.3} />
                                <stop offset="95%" stopColor={color} stopOpacity={0} />
                            </linearGradient>
                        </defs>
                        <CartesianGrid strokeDasharray="3 3" stroke="#334155" opacity={0.3} />
                        <XAxis dataKey="date" tick={{ fontSize: 12 }} tickFormatter={(val) => {
                            const d = new Date(val);
                            return `${d.getMonth() + 1}/${d.getDate()}`;
                        }} />
                        <YAxis tick={{ fontSize: 12 }} />
                        <Tooltip
                            contentStyle={{
                                backgroundColor: '#1E293B',
                                border: '1px solid #334155',
                                borderRadius: '8px',
                                color: '#fff',
                            }}
                        />
                        <Area type="monotone" dataKey="count" stroke={color} fill="url(#colorCount)" strokeWidth={2} />
                    </AreaChart>
                </ResponsiveContainer>
            </div>
        </div>
    );
}
