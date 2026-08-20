import { PieChart as RePieChart, Pie, Cell, Tooltip, ResponsiveContainer, Legend } from 'recharts';

interface PieChartProps {
    data: { name: string; value: number }[];
    title?: string;
    colors?: string[];
}

const DEFAULT_COLORS = ['#EF4444', '#F97316', '#EAB308', '#22C55E', '#3B82F6', '#8B5CF6'];

export default function PieChart({ data, title, colors = DEFAULT_COLORS }: PieChartProps) {
    return (
        <div className="bg-white dark:bg-dark-2 rounded-xl p-6 shadow-sm border border-light-2 dark:border-dark-3">
            {title && (
                <h3 className="text-sm font-semibold text-secondary dark:text-light mb-4">{title}</h3>
            )}
            <div className="h-64">
                <ResponsiveContainer width="100%" height="100%">
                    <RePieChart>
                        <Pie
                            data={data}
                            cx="50%"
                            cy="50%"
                            innerRadius={60}
                            outerRadius={90}
                            paddingAngle={2}
                            dataKey="value"
                        >
                            {data.map((_, index) => (
                                <Cell key={`cell-${index}`} fill={colors[index % colors.length]} />
                            ))}
                        </Pie>
                        <Tooltip
                            contentStyle={{
                                backgroundColor: '#1E293B',
                                border: '1px solid #334155',
                                borderRadius: '8px',
                                color: '#fff',
                            }}
                        />
                        <Legend />
                    </RePieChart>
                </ResponsiveContainer>
            </div>
        </div>
    );
}
