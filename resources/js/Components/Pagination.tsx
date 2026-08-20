import { ChevronLeft, ChevronRight } from 'lucide-react';
import { cn } from '@/Utils/cn';

interface PaginationProps {
    currentPage?: number;
    lastPage?: number;
    total?: number;
    from?: number;
    to?: number;
    current_page?: number;
    last_page?: number;
    onPageChange: (page: number) => void;
}

export default function Pagination({ currentPage, lastPage, total, from, to, current_page, last_page, onPageChange }: PaginationProps) {
    const page = currentPage ?? current_page ?? 1;
    const last = lastPage ?? last_page ?? 1;
    const tot = total ?? 0;
    const f = from ?? 0;
    const t = to ?? 0;

    if (last <= 1) return null;

    const pages: (number | string)[] = [];
    for (let i = 1; i <= last; i++) {
        if (i === 1 || i === last || (i >= page - 2 && i <= page + 2)) {
            pages.push(i);
        } else if (pages[pages.length - 1] !== '...') {
            pages.push('...');
        }
    }

    return (
        <div className="flex items-center justify-between px-4 py-3 bg-white dark:bg-dark-2 border-t border-light-2 dark:border-dark-3">
            <div className="text-sm text-dark-4 dark:text-light-3">
                Showing <span className="font-medium">{f}</span> to{' '}
                <span className="font-medium">{t}</span> of{' '}
                <span className="font-medium">{tot}</span> results
            </div>

            <div className="flex items-center gap-1">
                <button
                    onClick={() => onPageChange(page - 1)}
                    disabled={page === 1}
                    className="p-2 rounded-lg hover:bg-light-2 dark:hover:bg-dark-3 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                >
                    <ChevronLeft className="w-4 h-4 text-dark-4 dark:text-light-3" />
                </button>

                {pages.map((p, index) =>
                    typeof p === 'string' ? (
                        <span key={`ellipsis-${index}`} className="px-2 text-dark-4 dark:text-light-3">
                            ...
                        </span>
                    ) : (
                        <button
                            key={p}
                            onClick={() => onPageChange(p)}
                            className={cn(
                                'w-8 h-8 rounded-lg text-sm font-medium transition-colors',
                                p === page
                                    ? 'bg-primary text-white'
                                    : 'text-dark-4 dark:text-light-3 hover:bg-light-2 dark:hover:bg-dark-3'
                            )}
                        >
                            {p}
                        </button>
                    )
                )}

                <button
                    onClick={() => onPageChange(page + 1)}
                    disabled={page === last}
                    className="p-2 rounded-lg hover:bg-light-2 dark:hover:bg-dark-3 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                >
                    <ChevronRight className="w-4 h-4 text-dark-4 dark:text-light-3" />
                </button>
            </div>
        </div>
    );
}
