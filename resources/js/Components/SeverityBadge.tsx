import { cn } from '@/Utils/cn';

interface SeverityBadgeProps {
    severity: string;
    size?: 'sm' | 'md' | 'lg';
}

export default function SeverityBadge({ severity, size = 'sm' }: SeverityBadgeProps) {
    const classes = cn(
        'inline-flex items-center rounded-full font-medium',
        severity === 'critical' && 'badge-critical',
        severity === 'high' && 'badge-high',
        severity === 'medium' && 'badge-medium',
        severity === 'low' && 'badge-low',
        size === 'sm' && 'px-2.5 py-0.5 text-xs',
        size === 'md' && 'px-3 py-1 text-sm',
        size === 'lg' && 'px-4 py-1.5 text-sm',
    );

    return (
        <span className={classes}>
            {severity.charAt(0).toUpperCase() + severity.slice(1)}
        </span>
    );
}
