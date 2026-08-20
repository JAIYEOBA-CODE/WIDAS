import { motion } from 'framer-motion';
import { ChevronUp, ChevronDown, ChevronsUpDown, Loader2 } from 'lucide-react';
import { cn } from '@/Utils/cn';

interface Column<T> {
    key: string;
    header: string;
    render?: (item: T) => React.ReactNode;
    sortable?: boolean;
    className?: string;
}

interface DataTableProps<T> {
    columns: Column<T>[];
    data: T[];
    loading?: boolean;
    sortKey?: string;
    sortDirection?: 'asc' | 'desc';
    onSort?: (key: string) => void;
    emptyMessage?: string;
}

export default function DataTable<T extends Record<string, any>>({
    columns,
    data,
    loading = false,
    sortKey,
    sortDirection,
    onSort,
    emptyMessage = 'No data found',
}: DataTableProps<T>) {
    if (loading) {
        return (
            <div className="bg-white dark:bg-dark-2 rounded-xl shadow-sm border border-light-2 dark:border-dark-3 overflow-hidden">
                <div className="p-8 flex items-center justify-center">
                    <Loader2 className="w-8 h-8 text-primary animate-spin" />
                </div>
            </div>
        );
    }

    return (
        <motion.div
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            className="bg-white dark:bg-dark-2 rounded-xl shadow-sm border border-light-2 dark:border-dark-3 overflow-hidden"
        >
            <div className="overflow-x-auto">
                <table className="w-full">
                    <thead>
                        <tr className="bg-dark-2 dark:bg-dark-3">
                            {columns.map((col) => (
                                <th
                                    key={col.key}
                                    className={cn(
                                        'table-header',
                                        col.sortable && 'cursor-pointer select-none hover:bg-dark-3 dark:hover:bg-dark-4',
                                        col.className
                                    )}
                                    onClick={() => col.sortable && onSort?.(col.key)}
                                >
                                    <div className="flex items-center gap-2">
                                        <span>{col.header}</span>
                                        {col.sortable && (
                                            <span className="text-light-3">
                                                {sortKey === col.key ? (
                                                    sortDirection === 'asc' ? (
                                                        <ChevronUp className="w-4 h-4" />
                                                    ) : (
                                                        <ChevronDown className="w-4 h-4" />
                                                    )
                                                ) : (
                                                    <ChevronsUpDown className="w-4 h-4" />
                                                )}
                                            </span>
                                        )}
                                    </div>
                                </th>
                            ))}
                        </tr>
                    </thead>
                    <tbody>
                        {data.length === 0 ? (
                            <tr>
                                <td colSpan={columns.length} className="text-center py-8 text-dark-4 dark:text-light-3">
                                    {emptyMessage}
                                </td>
                            </tr>
                        ) : (
                            data.map((item, index) => (
                                <motion.tr
                                    key={item.id || index}
                                    initial={{ opacity: 0, y: 10 }}
                                    animate={{ opacity: 1, y: 0 }}
                                    transition={{ delay: index * 0.03 }}
                                    className="hover:bg-light dark:hover:bg-dark-3/50 transition-colors"
                                >
                                    {columns.map((col) => (
                                        <td key={col.key} className={cn('table-cell', col.className)}>
                                            {col.render ? col.render(item) : item[col.key]}
                                        </td>
                                    ))}
                                </motion.tr>
                            ))
                        )}
                    </tbody>
                </table>
            </div>
        </motion.div>
    );
}
