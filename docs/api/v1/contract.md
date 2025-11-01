# API Gateway Contract — v1

Base URL: `/api/v1`
Content-Type: `application/json`
Authentication: token-based (future work). Current endpoints assume trusted internal consumers.

---

## POST `/cv/generate`
Generate a DOCX CV and optionally trigger ATS analysis.

### Request Body
```json
{
  "template": "Minimal",
  "analysis_mode": "async", // optional: none|sync|async (default async)
  "correlation_id": "optional-correlation-id",
  "cv": {
    "name": "Ada Lovelace",
    "email": "ada@example.com",
    "about": "Writes analytical engines...",
    "skills": [
      { "name": "PHP", "level": "advanced" }
    ],
    "experience": [],
    "education": []
  }
}
```

### Response — 201 Created
```json
{
  "correlation_id": "1f61f4fd-969d-4b7d-8d77-52fd0ef2bfd6",
  "document_path": "cvs/cv_20231114_102233_ABCD.docx",
  "document_url": "https://example.com/storage/cvs/cv_20231114_102233_ABCD.docx",
  "analysis_mode": "async"
}
```
If `analysis_mode` is `sync`, an `ats_report` object is embedded and the same payload is emitted asynchronously via `AtsReportGenerated` for downstream systems.

---

## POST `/cv/apply-fixes`
Apply ATS-proposed fixes to an existing payload.

### Request Body
```json
{
  "cv": { "about": "Original summary" },
  "fixes": {
    "about": "Updated summary"
  }
}
```

### Response — 200 OK
```
{
  "cv": {
    "about": "Updated summary"
  }
}
```

---

## POST `/ats/analyze`
Run ATS scoring on a payload.

### Request Body
```json
{
  "correlation_id": "optional",
  "dispatch_event": true,
  "cv": {
    "name": "Ada Lovelace",
    "email": "ada@example.com",
    "about": "Writes analytical engines..."
  }
}
```

### Response — 200 OK
```json
{
  "correlation_id": "4ce4c157-9920-4732-9aa2-9c5395bbd0bd",
  "report": {
    "atsScore": 86,
    "warnings": [],
    "highlights": [],
    "scoreBreakdown": []
  }
}
```
When `dispatch_event` is true (default) the response is also broadcast through the `AtsReportGenerated` event for asynchronous consumers.

---

## POST `/support/sessions`
Create a new support assistant session. Optionally bias the first node by persona.

### Request Body
```json
{
  "persona": "candidate" // optional: candidate|employer|guest
}
```

### Response — 201 Created
```json
{
  "session_id": "51bf0609-dadc-4df0-a475-708fb1696330",
  "state": "intro",
  "message": "Hi there! I'm ProSnap Assistant...",
  "options": [
    { "id": "candidate_welcome", "label": "Candidate: Build my profile" },
    { "id": "employer_welcome", "label": "Employer: Hire with ProSnap" },
    { "id": "human_support", "label": "Contact support" }
  ],
  "history": []
}
```

---

## POST `/support/sessions/{sessionId}/messages`
Progress an existing session along the decision tree.

### Request Body
```json
{
  "option_id": "candidate_welcome"
}
```

### Response — 200 OK
```json
{
  "session_id": "51bf0609-dadc-4df0-a475-708fb1696330",
  "state": "candidate_welcome",
  "message": "Start with a compelling summary...",
  "options": [
    { "id": "candidate_cv", "label": "Generate or update my CV" },
    { "id": "candidate_visibility", "label": "Showcase my work" },
    { "id": "intro", "label": "Back to main menu" }
  ],
  "history": [
    { "state": "intro", "selected_option": "candidate_welcome" }
  ],
  "selected_option": "candidate_welcome"
}
```
If an invalid `option_id` is supplied the response echoes `invalid_option: true` without changing state.

---

## Versioning Strategy

- All endpoints are namespaced under `/api/v1`.
- Breaking changes will require a new namespace (`/api/v2`) while maintaining backwards compatibility for `v1` until sunset.
- Media types may include custom vendor headers (`application/vnd.prosnap.cv+json;version=1`) in future iterations without changing the URI schema.

## Correlation IDs

`correlation_id` is a UUID shared across synchronous responses and asynchronous events:

- Provided by clients, or generated server-side.
- Enables support tooling to trace a CV through generation ➜ ATS analysis ➜ support interactions.

## Error Model

Errors use Laravel’s default JSON format:

```json
{
  "message": "The given data was invalid.",
  "errors": {
    "cv.name": ["The cv.name field is required."]
  }
}
```

Use HTTP status codes to determine outcome: `201` for creations, `200` for successful retrieval/mutations, `422` for validation, `500` for unexpected failures.
