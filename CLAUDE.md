# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Commands

```bash
# Development — must use netlify dev for Edge Functions and Blob Store to work
netlify dev          # Runs at localhost:8888 (requires netlify-cli installed globally)
npm run dev          # Next.js only, without Netlify primitives (Edge Functions, Blob Store won't function)

npm run build        # Production build
npm run lint         # ESLint (extends next/core-web-vitals)
```

There is no test suite in this project.

To set up a fresh local environment:
```bash
npm install
npm install netlify-cli@latest -g
netlify link         # Links to deployed Netlify site for consistent runtime
netlify dev
```

## Architecture

This is a **Next.js 16 App Router** demo site for Netlify's platform primitives, deployed on Netlify. It showcases three Netlify-specific capabilities through dedicated demo pages:

### Netlify Primitives

**Blob Store** (`app/blobs/`): Uses `@netlify/blobs` via Next.js Server Actions (`'use server'`). The store name is `shapes`, with `consistency: 'strong'`. Server Actions live in `app/blobs/actions.js` and are called from client components.

**Edge Functions** (`netlify/edge-functions/`): Framework-agnostic functions that run at the CDN edge. `rewrite.js` intercepts `/edge` and rewrites to `/edge/australia` or `/edge/not-australia` based on `context.geo.country.code`. These are distinct from Next.js Middleware — they can access and transform the response body.

**Image CDN**: The `/.netlify/images` endpoint handles image optimization. Used both implicitly via `next/image` and explicitly with raw `<img srcSet>` tags (ESLint's `no-img-element` rule is disabled for this reason). In `dev` context, format is forced to WebP since automatic format detection isn't available locally.

**On-Demand Revalidation** (`app/revalidation/`): Demonstrates Next.js `revalidateTag` with `'max'` cacheLife profile (new in Next.js 16) for background revalidation.

### Key Patterns

**`getNetlifyContext()`** (in `utils.js`): Returns `process.env.CONTEXT`, which is only set when running via `netlify dev` or deployed to Netlify. Server-side only. Pages use this to gate Netlify-specific UI sections.

**`ContextAlert` component**: Renders a warning when `CONTEXT` is unset (plain `npm run dev`), informing users that full functionality requires `netlify dev` or a deployed environment. Pages pass an `addedChecksFunction` callback for additional context-specific warnings.

**`uploadDisabled`**: Boolean derived from `NEXT_PUBLIC_DISABLE_UPLOADS` env var, used to disable blob uploads on the public demo site.

### Path Aliases

`jsconfig.json` sets `baseUrl: "."`, so all imports use root-relative paths without `../../`:
```js
import { getNetlifyContext } from 'utils';
import { Card } from 'components/card';
import data from 'data/quotes.json';
```

### Styling

Tailwind CSS v4 with CSS-first configuration — there is no `tailwind.config.js`. All theme customization lives in `styles/globals.css` under `@theme`. Key design tokens:
- `--color-primary: #2bdcd2` (teal)
- `--color-primary-content: #171717`
- `--color-secondary: #016968`
- Background: `bg-blue-900` with a noise texture overlay (`bg-noise`)
- Custom utility classes: `.btn`, `.btn-lg`, `.input`, `.markdown`, `.diff`/`.diff-resizer`/`.diff-item-*`

The React Compiler is enabled (`reactCompiler: true` in `next.config.js`). The project uses ES modules (`"type": "module"` in `package.json`).

### Route Map

| Route | Description |
|---|---|
| `/` | Home / landing page |
| `/blobs` | Netlify Blob Store demo |
| `/edge` | Rewrites to `/edge/australia` or `/edge/not-australia` via Edge Function |
| `/image-cdn` | Netlify Image CDN demo |
| `/revalidation` | On-demand ISR with `revalidateTag` |
| `/middleware` | Next.js Middleware demo |
| `/routing` | Routing features demo |
| `/classics` | Static page (also reachable via `/blog` rewrite) |
| `/quotes/random` | Route Handler returning a random movie quote (also at `/api/health`) |

### Deployment

`netlify.toml` sets `publish = ".next"` and `command = "npm run build"`. The Netlify Next.js runtime handles serverless functions and edge function wiring automatically from the `.next` build output.
