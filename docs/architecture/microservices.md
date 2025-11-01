# Distributed Microservices Orchestration

This iteration aligns the platform with the bachelor thesis theme _“Application of Distributed Microservices Architecture in Web System Development.”_ The CV generator, ATS checker, and support assistant now behave as discrete services coordinated by an API gateway with well-defined context boundaries.

## Service Topology

| Service | Responsibility | Interface | Storage / State |
| --- | --- | --- | --- |
| CV Generator | Produces DOCX resumes from structured profile payloads and template directives. | `POST /api/v1/cv/generate` | Public storage disk (`storage/app/public/cvs`). |
| ATS Checker | Scores CV payloads, surfaces warnings, and proposes auto-fixes. | `POST /api/v1/ats/analyze` | Stateless – uses in-memory computation. |
| Support Assistant | Conversational helper that directs candidates or employers and escalates to human support. | `POST /api/v1/support/sessions`, `POST /api/v1/support/sessions/{id}/messages` | Ephemeral Cache (15 min TTL per session). |

Every service is accessed exclusively through the Laravel-powered API gateway. Internal classes (`TalentPlatformGateway`, `SupportBotService`) encapsulate orchestration logic and can be replaced with remote HTTP clients if the services are deployed independently.

## Request Orchestration

1. **Client → API Gateway** – Clients hit a versioned REST endpoint (`/api/v1/...`). Payloads are validated and normalised at the edge.
2. **Gateway → Domain Service** – The gateway delegates to the relevant domain service (CV, ATS, or Support). Shared orchestration lives in `App\Services\Gateway\TalentPlatformGateway`.
3. **Service → Event Bus** – Outcomes emit domain events so other services can react without tight coupling.

```
Client
  │
  │  REST (JSON)
  ▼
API Gateway (Laravel) ───► Domain Service (CV / ATS / Support)
  │                            │
  │                            └─► Storage / Cache
  └─► Event Bus (Laravel events + queue)
```

## Asynchronous Collaboration

| Event | Produced by | Consumed by | Purpose |
| --- | --- | --- | --- |
| `CvGenerated` | API gateway after DOCX creation | `TriggerAtsAnalysis` listener | Launches async ATS analysis when `analysis_mode=async`. |
| `AtsReportGenerated` | ATS checker (sync responses) & async listener | Logging / downstream consumers | Persists unified ATS results with correlation IDs. |
| `SupportConversationUpdated` | Support bot service | `LogSupportConversationUpdate` | Captures conversational telemetry for monitoring/escalation hooks. |

Listeners implement `ShouldQueue`, so workers can be scaled independently (e.g., Horizon, Redis, SQS) without changing the contract. The correlation identifier included in all events allows dashboards to reconstruct cross-service timelines.

## Deployment Notes

- The API gateway is ready to sit behind an ingress controller or managed gateway (Traefik, Nginx, AWS API Gateway).
- Services are MVC modules today but can be extracted into dedicated containers by replacing the gateway’s service bindings with HTTP or gRPC clients.
- Events are framework-native; swap the queue driver to Redis/RabbitMQ/Kafka for production-grade asynchronous processing.
- Docker users should rebuild the PHP-FPM / queue worker containers after pulling these changes and run `php artisan config:cache` for the new configuration file.

## Next Steps

1. Introduce an event-store or message broker to persist domain events for auditing.
2. Externalise the support bot flow into a CMS or knowledge base API to enable non-technical updates.
3. Add contract tests (e.g., Pact) between the gateway and downstream services once the services live in separate deployments.
