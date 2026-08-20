import { useEffect, useState } from 'react';
import { Head } from '@inertiajs/react';
import { ShieldAlert, Clock, ArrowLeft } from 'lucide-react';

interface BlockedProps {
    reason: string;
    blockedAt: string;
    expiresAt: string | null;
}

export default function Blocked({ reason, blockedAt, expiresAt }: BlockedProps) {
    const [timeLeft, setTimeLeft] = useState('');

    useEffect(() => {
        if (!expiresAt) return;

        const updateTimer = () => {
            const remaining = new Date(expiresAt).getTime() - Date.now();
            if (remaining <= 0) {
                setTimeLeft('Expired — redirecting...');
                setTimeout(() => { window.location.href = '/login'; }, 2000);
                return;
            }
            const mins = Math.floor(remaining / 60000);
            const secs = Math.floor((remaining % 60000) / 1000);
            setTimeLeft(`${mins}m ${secs}s`);
        };

        updateTimer();
        const interval = setInterval(updateTimer, 1000);
        return () => clearInterval(interval);
    }, [expiresAt]);

    const isPermanent = !expiresAt;

    return (
        <div className="min-h-screen bg-gradient-to-br from-secondary via-dark-2 to-secondary flex items-center justify-center p-4">
            <Head title="Access Blocked | WIDAS" />

            <div className="w-full max-w-lg text-center">
                <div className="inline-flex items-center justify-center w-20 h-20 rounded-full bg-danger/20 mb-6">
                    <ShieldAlert className="w-10 h-10 text-danger" />
                </div>

                <h1 className="text-3xl font-bold text-white mb-2">Access Blocked</h1>
                <p className="text-light-3/70 mb-8">Your IP address has been restricted</p>

                <div className="bg-white/5 backdrop-blur-sm rounded-2xl border border-white/10 p-6 mb-6 space-y-4">
                    <div className="text-left space-y-3">
                        <div className="flex justify-between items-center">
                            <span className="text-light-3/60 text-sm">Reason</span>
                            <span className="text-white font-medium">{reason || 'Security violation detected'}</span>
                        </div>
                        <div className="flex justify-between items-center">
                            <span className="text-light-3/60 text-sm">Blocked At</span>
                            <span className="text-white font-medium">{new Date(blockedAt).toLocaleString()}</span>
                        </div>
                        <div className="flex justify-between items-center">
                            <span className="text-light-3/60 text-sm">Type</span>
                            <span className={`font-medium ${isPermanent ? 'text-danger' : 'text-warning'}`}>
                                {isPermanent ? 'Permanent' : 'Temporary'}
                            </span>
                        </div>
                        {!isPermanent && (
                            <div className="flex justify-between items-center">
                                <span className="text-light-3/60 text-sm">Expires</span>
                                <span className="text-light-3 font-mono text-lg font-bold flex items-center gap-2">
                                    <Clock className="w-4 h-4 text-warning" />
                                    {timeLeft || 'Calculating...'}
                                </span>
                            </div>
                        )}
                    </div>

                    {!isPermanent && (
                        <div className="w-full bg-dark-3 rounded-full h-2 overflow-hidden">
                            <TimerBar expiresAt={expiresAt!} blockedAt={blockedAt} />
                        </div>
                    )}
                </div>

                <a
                    href="/login"
                    className="inline-flex items-center gap-2 text-light-3/60 hover:text-primary transition-colors text-sm"
                >
                    <ArrowLeft className="w-4 h-4" />
                    Return to Login
                </a>
            </div>
        </div>
    );
}

function TimerBar({ expiresAt, blockedAt }: { expiresAt: string; blockedAt: string }) {
    const [progress, setProgress] = useState(100);

    useEffect(() => {
        const update = () => {
            const total = new Date(expiresAt).getTime() - new Date(blockedAt).getTime();
            const remaining = new Date(expiresAt).getTime() - Date.now();
            setProgress(Math.max(0, (remaining / total) * 100));
        };
        update();
        const interval = setInterval(update, 1000);
        return () => clearInterval(interval);
    }, [expiresAt, blockedAt]);

    return (
        <div className="h-full bg-primary transition-all duration-1000 rounded-full" style={{ width: `${progress}%` }} />
    );
}
