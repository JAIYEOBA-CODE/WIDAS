export interface User {
    id: number;
    name: string;
    email: string;
    role_id: number | null;
    is_active: boolean;
    email_verified_at: string | null;
    locked_until: string | null;
    login_attempts: number;
    created_at: string;
    updated_at: string;
    role?: Role;
}

export interface Role {
    id: number;
    name: string;
    slug: string;
    description: string | null;
    permissions?: Permission[];
}

export interface Permission {
    id: number;
    name: string;
    slug: string;
    description: string | null;
}

export interface ThreatRule {
    id: number;
    name: string;
    slug: string;
    description: string;
    category: ThreatCategory;
    severity: Severity;
    threat_score: number;
    patterns: string[] | null;
    config: Record<string, any> | null;
    is_active: boolean;
    auto_block: boolean;
    threshold: number;
    action: 'log' | 'alert' | 'block';
    created_at: string;
    updated_at: string;
}

export type ThreatCategory =
    | 'brute_force'
    | 'sql_injection'
    | 'xss'
    | 'session_abuse'
    | 'unauthorized_access'
    | 'api_abuse';

export type Severity = 'low' | 'medium' | 'high' | 'critical';

export interface IntrusionEvent {
    id: number;
    user_id: number | null;
    threat_rule_id: number | null;
    type: ThreatCategory;
    severity: Severity;
    threat_score: number;
    source_ip: string | null;
    user_agent: string | null;
    method: string | null;
    url: string | null;
    payload: Record<string, any> | null;
    headers: Record<string, any> | null;
    description: string | null;
    is_resolved: boolean;
    resolved_at: string | null;
    resolved_by: number | null;
    resolution_notes: string | null;
    created_at: string;
    updated_at: string;
    user?: User;
    threatRule?: ThreatRule;
    resolver?: User;
    alerts?: Alert[];
}

export interface Alert {
    id: number;
    user_id: number | null;
    intrusion_event_id: number | null;
    type: 'threat' | 'system' | 'security' | 'info';
    severity: Severity;
    title: string;
    message: string;
    metadata: Record<string, any> | null;
    is_read: boolean;
    read_at: string | null;
    is_resolved: boolean;
    resolved_at: string | null;
    created_at: string;
    updated_at: string;
    user?: User;
    intrusionEvent?: IntrusionEvent;
}

export interface BlockedIp {
    id: number;
    ip_address: string;
    reason: string | null;
    blocked_by: number | null;
    is_permanent: boolean;
    blocked_at: string;
    expires_at: string | null;
    attempts: number;
    metadata: Record<string, any> | null;
    created_at: string;
    updated_at: string;
    blocker?: User;
}

export interface LoginAttempt {
    id: number;
    user_id: number | null;
    email: string;
    ip_address: string | null;
    user_agent: string | null;
    was_successful: boolean;
    failure_reason: string | null;
    metadata: Record<string, any> | null;
    created_at: string;
    updated_at: string;
    user?: User;
}

export interface SecurityLog {
    id: number;
    user_id: number | null;
    type: string;
    severity: 'info' | 'warning' | 'danger' | 'critical';
    source_ip: string | null;
    user_agent: string | null;
    metadata: Record<string, any> | null;
    message: string;
    created_at: string;
    updated_at: string;
    user?: User;
}

export interface ActivityLog {
    id: number;
    user_id: number | null;
    action: string;
    module: string | null;
    description: string | null;
    old_values: Record<string, any> | null;
    new_values: Record<string, any> | null;
    ip_address: string | null;
    user_agent: string | null;
    created_at: string;
    updated_at: string;
    user?: User;
}

export interface AuditLog {
    id: number;
    user_id: number | null;
    event: string;
    auditable_type: string;
    auditable_id: number;
    old_values: Record<string, any> | null;
    new_values: Record<string, any> | null;
    metadata: Record<string, any> | null;
    ip_address: string | null;
    user_agent: string | null;
    created_at: string;
    updated_at: string;
    user?: User;
}

export interface ThreatScore {
    id: number;
    user_id: number | null;
    source_ip: string | null;
    score: number;
    breakdown: Record<string, any> | null;
    risk_level: 'safe' | 'low' | 'medium' | 'high' | 'critical';
    is_active: boolean;
    last_updated_at: string;
    created_at: string;
    updated_at: string;
}

export interface SystemSetting {
    id: number;
    key: string;
    value: string;
    group: string;
    description: string | null;
    is_editable: boolean;
    created_at: string;
    updated_at: string;
}

export interface DashboardStats {
    total_threats?: number;
    active_threats?: number;
    critical_alerts?: number;
    blocked_ips?: number;
    active_users?: number;
    total_alerts?: number;
    resolved_threats?: number;
    total_incidents?: number;
    critical_incidents?: number;
    pending_alerts?: number;
    resolved_today?: number;
    total_logins?: number;
    failed_attempts?: number;
    alerts?: number;
    unread_alerts?: number;
}

export interface ChartDataPoint {
    date: string;
    count: number;
    successful?: number;
    failed?: number;
}

export interface ThreatDistribution {
    [key: string]: number;
}

export interface SeverityDistribution {
    [key: string]: number;
}

export interface PaginatedResponse<T> {
    data: T[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    from: number;
    to: number;
}

export interface InertiaPageProps {
    auth: {
        user: {
            id: number;
            name: string;
            email: string;
            role: string;
            is_admin: boolean;
            is_analyst: boolean;
        } | null;
    };
    flash: {
        success?: string;
        error?: string;
        warning?: string;
    };
    unread_alerts_count: number;
    critical_alerts_count: number;
    [key: string]: any;
}
