import { Head, Link } from '@inertiajs/react';
import { Shield, ArrowRight, Activity, AlertTriangle, Lock, Server } from 'lucide-react';

interface WelcomeProps {
    projectName: string;
    studentName: string;
    matricNumber: string;
    supervisorName: string;
}

export default function Welcome({ projectName, studentName, matricNumber, supervisorName }: WelcomeProps) {
    return (
        <div className="min-h-screen bg-gradient-to-br from-secondary via-dark-2 to-secondary flex flex-col">
            <Head title={projectName} />

            <div className="flex-1 flex items-center justify-center p-4">
                <div className="w-full max-w-3xl text-center">
                    <div className="mb-6">
                        <div className="inline-flex items-center justify-center w-20 h-20 rounded-3xl bg-primary mb-5 shadow-lg shadow-primary/25">
                            <Shield className="w-12 h-12 text-white" />
                        </div>
                        <h1 className="text-4xl md:text-5xl font-bold text-white mb-3 tracking-tight">
                            {projectName}
                        </h1>
                        <p className="text-lg text-light-3/80 max-w-xl mx-auto">
                            A comprehensive platform for real-time threat monitoring, intrusion detection, and security alert management.
                        </p>
                    </div>

                    <div className="grid grid-cols-3 gap-3 max-w-lg mx-auto mb-8">
                        {[
                            { icon: Activity, label: 'Real-Time Monitoring' },
                            { icon: AlertTriangle, label: 'Threat Detection' },
                            { icon: Lock, label: 'IP Blocking' },
                            { icon: Server, label: 'Audit Logging' },
                            { icon: Shield, label: 'Alert System' },
                        ].map(({ icon: Icon, label }) => (
                            <div key={label} className="bg-white/5 backdrop-blur-sm rounded-xl p-3 border border-white/10">
                                <Icon className="w-5 h-5 text-primary mx-auto mb-1" />
                                <p className="text-xs text-light-3/70">{label}</p>
                            </div>
                        ))}
                    </div>

                    <div className="bg-white/5 backdrop-blur-sm rounded-2xl border border-white/10 p-6 mb-8 max-w-md mx-auto">
                        <div className="space-y-2 text-light-3/80 text-sm">
                            <p><span className="text-white font-medium">Student:</span> {studentName}</p>
                            <p><span className="text-white font-medium">Matric No:</span> {matricNumber}</p>
                            <p><span className="text-white font-medium">Supervisor:</span> {supervisorName}</p>
                        </div>
                    </div>

                    <Link
                        href="/login"
                        className="inline-flex items-center gap-2 bg-primary hover:bg-primary/90 text-white px-8 py-3.5 rounded-xl font-semibold text-sm transition-all shadow-lg shadow-primary/25 hover:shadow-primary/40 hover:scale-105"
                    >
                        Continue to Login
                        <ArrowRight className="w-4 h-4" />
                    </Link>
                </div>
            </div>

            <div className="text-center pb-4">
                <p className="text-xs text-light-3/40">&copy; {new Date().getFullYear()} {projectName}. All rights reserved.</p>
            </div>
        </div>
    );
}
