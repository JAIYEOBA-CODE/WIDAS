export function TableSkeleton({ rows = 5, cols = 5 }: { rows?: number; cols?: number }) {
    return (
        <div className="bg-white dark:bg-dark-2 rounded-xl shadow-sm border border-light-2 dark:border-dark-3 overflow-hidden">
            <div className="bg-dark-2 dark:bg-dark-3 px-4 py-3">
                <div className="flex gap-4">
                    {Array.from({ length: cols }).map((_, i) => (
                        <div key={i} className="skeleton h-4 flex-1" />
                    ))}
                </div>
            </div>
            {Array.from({ length: rows }).map((_, row) => (
                <div key={row} className="flex gap-4 px-4 py-3 border-b border-light-2 dark:border-dark-3">
                    {Array.from({ length: cols }).map((_, col) => (
                        <div key={col} className="skeleton h-4 flex-1" style={{ opacity: 1 - row * 0.1 }} />
                    ))}
                </div>
            ))}
        </div>
    );
}

export function StatCardSkeleton() {
    return (
        <div className="stat-card">
            <div className="flex items-start justify-between">
                <div className="space-y-2 flex-1">
                    <div className="skeleton h-4 w-24" />
                    <div className="skeleton h-8 w-16" />
                </div>
                <div className="skeleton w-12 h-12 rounded-xl" />
            </div>
        </div>
    );
}
