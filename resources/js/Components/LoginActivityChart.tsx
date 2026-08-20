import { BarChart, Bar, XAxis, YAxis, CartesianGrid, Tooltip, ResponsiveContainer, Legend } from 'recharts';

interface LoginActivityChartProps {
    data: { date: string; successful: number; failed: number }[];
}

export default function LoginActivityChart({ data }: LoginActivityChartProps) {
    return (
        <div className="bg-white dark:bg-dark-2 rounded-xl p-6 shadow-sm border border-light-2 dark:border-dark-3">
            <h3 className="text-sm font-semibold text-secondary dark:text-light mb-4">Login Activity</h3>
            <div className="h-64">
                <ResponsiveContainer width="100%" height="100%">
                    <BarChart data={data}>
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
                        <Legend />
                        <Bar dataKey="successful" fill="#22C55E" name="Successful" radius={[4, 4, 0, 0]} />
                        <Bar dataKey="failed" fill="#EF4444" name="Failed" radius={[4, 4, 0, 0]} />
                    </BarChart>
                </ResponsiveContainer>
            </div>
        </div>
    );
}
