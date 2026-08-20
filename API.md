# WIDAS API Documentation

## Authentication

All API endpoints require authentication via Laravel Sanctum.

### Headers
```
Authorization: Bearer <token>
Accept: application/json
```

## Endpoints

### Alerts

#### List All Alerts
```
GET /api/alerts
```

Query Parameters:
- `page` (optional) - Page number
- `per_page` (optional) - Items per page (default: 20)

Response:
```json
{
    "data": [
        {
            "id": 1,
            "type": "security",
            "severity": "critical",
            "title": "SQL Injection Detected",
            "message": "SQL Injection attempt detected...",
            "is_read": false,
            "is_resolved": false,
            "created_at": "2026-05-30T12:00:00Z",
            "intrusion_event": {...}
        }
    ],
    "current_page": 1,
    "last_page": 5,
    "total": 100
}
```

#### Get Alert Details
```
GET /api/alerts/{id}
```

#### Get Unread Alert Count
```
GET /api/alerts/unread-count
```

Response:
```json
{
    "total": 15,
    "critical": 3
}
```

#### Mark Alert as Read
```
POST /api/alerts/{id}/mark-read
```

Response:
```json
{
    "message": "Alert marked as read"
}
```

### Intrusions

#### List Intrusion Events
```
GET /api/intrusions
```

#### Get Intrusion Details
```
GET /api/intrusions/{id}
```

#### Get Intrusion Statistics
```
GET /api/intrusions/stats
```

Response:
```json
{
    "total": 250,
    "unresolved": 45,
    "by_severity": {
        "critical": 12,
        "high": 33,
        "medium": 78,
        "low": 127
    }
}
```

## Error Responses

### 401 Unauthorized
```json
{
    "message": "Unauthenticated."
}
```

### 403 Forbidden
```json
{
    "message": "Access denied. Your IP address has been blocked.",
    "reason": "Auto-blocked: Brute force attack detected",
    "blocked_at": "2026-05-30T12:00:00Z",
    "expires_at": "2026-05-31T12:00:00Z"
}
```

### 429 Too Many Requests
```json
{
    "message": "Too Many Attempts."
}
```
