import { motion } from 'framer-motion';
import { Divide, LucideIcon } from 'lucide-react';
import { cn } from '@/Utils/cn';

interface StatCardProps {
    title: string;
    value: string | number;
    icon: LucideIcon;
    color?: string;
    trend?: {
        value: number;
        isPositive: boolean;
    };
}

export default function StatCard({ title, value, icon: Icon, color = 'primary', trend }: StatCardProps) {
    return (
        <motion.div
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            className="stat-card"
        >
            <div className="flex items-start justify-between">
                <div className="space-y-2">
                    <p className="text-sm text-dark-4 dark:text-light-3 font-medium">{title}</p>
                    <p className="text-3xl font-bold text-secondary dark:text-light">{value}</p>
                    {trend && (
                        <div className="flex items-center gap-1">
                            <span className={cn(
                                'text-xs font-medium',
                                trend.isPositive ? 'text-success' : 'text-danger'
                            )}>
                                {trend.isPositive ? '+' : '-'}{Math.abs(trend.value)}%
                            </span>
                            <span className="text-xs text-dark-4 dark:text-light-3">vs last period</span>
                        </div>
                    )}
                </div>
                <div className={cn(
                    'w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0',
                    color === 'primary' && 'bg-primary/10 dark:bg-primary/20 text-primary',
                    color === 'danger' && 'bg-red-100 dark:bg-red-900/20 text-danger',
                    color === 'success' && 'bg-green-100 dark:bg-green-900/20 text-success',
                    color === 'warning' && 'bg-yellow-100 dark:bg-yellow-900/20 text-warning',
                    color === 'accent' && 'bg-orange-100 dark:bg-orange-900/20 text-accent',
                )}>
                    <Icon className="w-6 h-6" />
                </div>
            </div>
        </motion.div>
    );
}
