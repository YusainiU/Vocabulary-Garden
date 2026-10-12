<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f7f8f2">
    <meta name="description" content="Grow your vocabulary with Word Garden. Learn new words, practice with randomized quizzes, and remember more with spaced repetition.">

    <title>{{ __('Welcome') }} — {{ config('app.name', 'Word Garden') }}</title>

    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    @fonts

    <style>
        /* -------------------------------------------------------
           Word Garden — design system
        ------------------------------------------------------- */

        :root {
            color-scheme: light dark;

            --wg-bg: #f7f8f2;
            --wg-surface: #ffffff;
            --wg-surface-soft: #f0f4e9;
            --wg-text: #202b21;
            --wg-muted: #697367;
            --wg-border: #e3e9dc;
            --wg-primary: #23734c;
            --wg-primary-hover: #195c3b;
            --wg-accent: #e1edce;
            --wg-shadow: 0 24px 70px rgba(35, 55, 33, 0.08);

            --wg-radius: 1.5rem;
            --wg-radius-sm: 0.875rem;
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        html {
            min-height: 100%;
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: var(--wg-bg);
            color: var(--wg-text);
            font-family: var(--font-sans, ui-sans-serif, system-ui, sans-serif);
            -webkit-font-smoothing: antialiased;
            text-rendering: optimizeLegibility;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        a:focus-visible {
            outline: 3px solid #77a98a;
            outline-offset: 4px;
            border-radius: 5px;
        }

        button,
        a {
            -webkit-tap-highlight-color: transparent;
        }

        .wg-shell {
            width: min(100% - 2rem, 1120px);
            margin-inline: auto;
        }

        /* Header */

        .wg-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding-block: 1.5rem;
        }

        .wg-brand {
            display: inline-flex;
            align-items: center;
            gap: 0.65rem;
            flex-shrink: 0;
        }

        .wg-brand-mark {
            display: grid;
            place-items: center;
            width: 2.75rem;
            height: 2.75rem;
            border-radius: 1rem;
            background: var(--wg-accent);
            color: var(--wg-primary);
        }

        .wg-brand-name {
            font-size: 1.1rem;
            font-weight: 750;
            letter-spacing: -0.045em;
        }

        .wg-brand-name em {
            color: var(--wg-primary);
            font-family: var(--font-serif, Georgia, serif);
            font-weight: 400;
        }

        .wg-nav {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 0.75rem;
        }

        .wg-nav-link {
            padding: 0.7rem 0.9rem;
            color: var(--wg-muted);
            font-size: 0.875rem;
            font-weight: 600;
            transition: color 160ms ease;
        }

        .wg-nav-link:hover {
            color: var(--wg-primary);
        }

        /* Buttons */

        .wg-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            min-height: 2.85rem;
            padding: 0.8rem 1.2rem;
            border: 1px solid transparent;
            border-radius: 0.8rem;
            font: inherit;
            font-size: 0.9rem;
            font-weight: 650;
            text-align: center;
            transition:
                background-color 160ms ease,
                border-color 160ms ease,
                transform 160ms ease;
        }

        .wg-button:hover {
            transform: translateY(-1px);
        }

        .wg-button-primary {
            background: var(--wg-primary);
            color: #ffffff;
            box-shadow: 0 5px 14px rgba(35, 115, 76, 0.15);
        }

        .wg-button-primary:hover {
            background: var(--wg-primary-hover);
        }

        .wg-button-secondary {
            background: var(--wg-surface);
            border-color: var(--wg-border);
            color: var(--wg-text);
        }

        .wg-button-secondary:hover {
            border-color: var(--wg-primary);
        }

        /* Main hero */

        .wg-main {
            display: grid;
            grid-template-columns: minmax(0, 1.05fr) minmax(0, 0.95fr);
            align-items: center;
            gap: clamp(2rem, 6vw, 5.5rem);
            padding-block: clamp(3rem, 9vw, 7.5rem);
        }

        .wg-copy {
            min-width: 0;
        }

        .wg-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            margin-bottom: 1.5rem;
            padding: 0.5rem 0.8rem;
            border: 1px solid var(--wg-border);
            border-radius: 999px;
            background: var(--wg-surface);
            color: var(--wg-primary);
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.07em;
            text-transform: uppercase;
        }

        .wg-eyebrow-dot {
            width: 0.45rem;
            height: 0.45rem;
            border-radius: 50%;
            background: #72a56e;
            box-shadow: 0 0 0 3px var(--wg-accent);
        }

        .wg-headline {
            max-width: 11ch;
            margin: 0 0 1.5rem;
            font-family: var(--font-serif, Georgia, serif);
            font-size: clamp(2.8rem, 5.5vw, 5rem);
            font-weight: 500;
            line-height: 1.02;
            letter-spacing: -0.065em;
        }

        .wg-headline em {
            color: var(--wg-primary);
            font-weight: 400;
        }

        .wg-description {
            max-width: 34rem;
            margin: 0;
            color: var(--wg-muted);
            font-size: clamp(1rem, 1.5vw, 1.125rem);
            line-height: 1.85;
        }

        .wg-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            margin-top: 2rem;
        }

        .wg-note {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 1.25rem;
            color: var(--wg-muted);
            font-size: 0.8rem;
            line-height: 1.5;
        }

        .wg-note svg {
            flex-shrink: 0;
            color: var(--wg-primary);
        }

        /* Vocabulary preview */

        .wg-preview {
            position: relative;
            min-width: 0;
            padding: clamp(1.25rem, 3vw, 2rem);
            overflow: hidden;
            border: 1px solid var(--wg-border);
            border-radius: var(--wg-radius);
            background: var(--wg-surface);
            box-shadow: var(--wg-shadow);
            isolation: isolate;
        }

        .wg-preview::before {
            position: absolute;
            z-index: -1;
            top: -7rem;
            right: -6rem;
            width: 18rem;
            height: 18rem;
            border-radius: 50%;
            background: var(--wg-surface-soft);
            content: "";
        }

        .wg-preview-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1.75rem;
        }

        .wg-kicker {
            margin: 0 0 0.35rem;
            color: var(--wg-muted);
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .wg-preview-title {
            margin: 0;
            font-size: 1.35rem;
            font-weight: 700;
            letter-spacing: -0.04em;
        }

        .wg-status {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.45rem 0.65rem;
            border-radius: 999px;
            background: var(--wg-surface-soft);
            color: var(--wg-primary);
            font-size: 0.7rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .wg-word-list {
            display: grid;
            gap: 0.75rem;
        }

        .wg-word {
            display: flex;
            align-items: center;
            gap: 0.9rem;
            padding: 1rem;
            border: 1px solid var(--wg-border);
            border-radius: var(--wg-radius-sm);
            background: var(--wg-surface);
        }

        .wg-word-icon {
            display: grid;
            place-items: center;
            width: 2.75rem;
            height: 2.75rem;
            flex-shrink: 0;
            border-radius: 0.85rem;
            background: var(--wg-surface-soft);
            font-size: 1.25rem;
        }

        .wg-word-content {
            flex: 1;
            min-width: 0;
        }

        .wg-word-name {
            margin: 0;
            font-family: var(--font-serif, Georgia, serif);
            font-size: 1.15rem;
            font-weight: 600;
            letter-spacing: -0.025em;
        }

        .wg-word-definition {
            margin: 0.2rem 0 0;
            color: var(--wg-muted);
            font-size: 0.8rem;
            line-height: 1.5;
        }

        .wg-word-tag {
            flex-shrink: 0;
            padding: 0.35rem 0.55rem;
            border-radius: 0.5rem;
            background: var(--wg-surface-soft);
            color: var(--wg-primary);
            font-size: 0.65rem;
            font-weight: 700;
        }

        .wg-progress {
            margin-top: 1.5rem;
            padding: 1.15rem;
            border-radius: var(--wg-radius-sm);
            background: var(--wg-surface-soft);
        }

        .wg-progress-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.75rem;
            font-size: 0.8rem;
            font-weight: 650;
        }

        .wg-progress-top span:last-child {
            color: var(--wg-primary);
        }

        .wg-progress-track {
            height: 0.45rem;
            overflow: hidden;
            border-radius: 999px;
            background: var(--wg-border);
        }

        .wg-progress-fill {
            width: 72%;
            height: 100%;
            border-radius: inherit;
            background: var(--wg-primary);
        }

        .wg-progress-caption {
            margin: 0.65rem 0 0;
            color: var(--wg-muted);
            font-size: 0.75rem;
            line-height: 1.5;
        }

        /* Benefits */

        .wg-benefits {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1.5rem;
            padding-block: 2rem;
            border-top: 1px solid var(--wg-border);
        }

        .wg-benefit {
            display: flex;
            align-items: flex-start;
            gap: 0.85rem;
        }

        .wg-benefit-icon {
            display: grid;
            place-items: center;
            width: 2.5rem;
            height: 2.5rem;
            flex-shrink: 0;
            border-radius: 0.8rem;
            background: var(--wg-accent);
            color: var(--wg-primary);
        }

        .wg-benefit h2 {
            margin: 0 0 0.35rem;
            font-size: 0.9rem;
            font-weight: 700;
        }

        .wg-benefit p {
            margin: 0;
            color: var(--wg-muted);
            font-size: 0.8rem;
            line-height: 1.65;
        }

        /* Footer */

        .wg-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            padding-block: 1.5rem;
            border-top: 1px solid var(--wg-border);
            color: var(--wg-muted);
            font-size: 0.75rem;
        }

        /* Responsive layouts */

        @media (max-width: 760px) {
            .wg-main {
                grid-template-columns: minmax(0, 1fr);
                gap: 2.5rem;
                padding-block: 3rem;
            }

            .wg-headline {
                max-width: 12ch;
                font-size: clamp(2.8rem, 10vw, 4.25rem);
            }

            .wg-preview {
                width: 100%;
            }

            .wg-benefits {
                grid-template-columns: minmax(0, 1fr);
                gap: 1.5rem;
            }
        }

        @media (max-width: 420px) {
            .wg-shell {
                width: min(100% - 1.25rem, 1120px);
            }

            .wg-header {
                padding-block: 1rem;
            }

            .wg-brand {
                gap: 0.45rem;
            }

            .wg-brand-mark {
                width: 2.35rem;
                height: 2.35rem;
            }

            .wg-brand-name {
                font-size: 0.98rem;
            }

            .wg-nav {
                gap: 0.2rem;
            }

            .wg-nav-link {
                padding-inline: 0.45rem;
                font-size: 0.8rem;
            }

            .wg-nav .wg-button {
                min-height: 2.5rem;
                padding: 0.65rem 0.75rem;
                font-size: 0.8rem;
            }

            .wg-word {
                gap: 0.65rem;
                padding: 0.75rem;
            }

            .wg-word-icon {
                width: 2.35rem;
                height: 2.35rem;
            }

            .wg-word-tag {
                font-size: 0.6rem;
            }

            .wg-footer {
                align-items: flex-start;
                flex-direction: column;
                gap: 0.5rem;
            }
        }

        /* Dark theme */

        @media (prefers-color-scheme: dark) {
            :root {
                color-scheme: dark;

                --wg-bg: #111711;
                --wg-surface: #192119;
                --wg-surface-soft: #232f23;
                --wg-text: #edf2e8;
                --wg-muted: #a6b1a2;
                --wg-border: #303d30;
                --wg-primary: #83c99b;
                --wg-primary-hover: #a1dfb3;
                --wg-accent: #263d2c;
                --wg-shadow: 0 24px 70px rgba(0, 0, 0, 0.2);
            }

            .wg-button-primary {
                color: #132519;
                box-shadow: none;
            }

            .wg-button-primary:hover {
                color: #132519;
            }
        }

        /* Honour motion preferences */

        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                scroll-behavior: auto !important;
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>
</head>

<body>
    <div class="wg-shell">

        {{-- Header --}}
        <header class="wg-header">
            <a href="{{ url('/') }}" class="wg-brand" aria-label="Word Garden home">
                <span class="wg-brand-mark">
                    <svg viewBox="0 0 32 32" width="25" height="25"
                         fill="none" stroke="currentColor" stroke-width="1.8"
                         stroke-linecap="round" stroke-linejoin="round"
                         aria-hidden="true">
                        <path d="M16 17v10" />
                        <path d="M16 17C16 10.5 20.5 5 27 5c0 6.5-4.5 12-11 12Z" />
                        <path d="M16 17C16 12 12.5 8.5 6 8.5 6 13.5 10 17 16 17Z" />
                        <path d="M8 27c4 2.5 12 2.5 16 0" />
                    </svg>
                </span>

                <span class="wg-brand-name">Word <em>Garden</em></span>
            </a>

            @if (Route::has('login'))
                <nav class="wg-nav" aria-label="Main navigation">
                    @auth
                        <a href="{{ route('dashboard') }}" class="wg-button wg-button-primary">
                            Go to dashboard
                            <span aria-hidden="true">→</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="wg-nav-link">
                            Log in
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                               class="wg-button wg-button-primary">
                                Get started
                                <span aria-hidden="true">→</span>
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif
        </header>

        <main>
            {{-- Hero --}}
            <section class="wg-main" aria-labelledby="wg-headline">
                <div class="wg-copy">
                    <div class="wg-eyebrow">
                        <span class="wg-eyebrow-dot" aria-hidden="true"></span>
                        A little learning, every day
                    </div>

                    <h1 id="wg-headline" class="wg-headline">
                        Grow your vocabulary.
                        <em>One word at a time.</em>
                    </h1>

                    <p class="wg-description">
                        Make room for new words. Build a personal vocabulary,
                        practise what you learn, and turn fleeting discoveries
                        into knowledge that stays with you.
                    </p>

                    <div class="wg-actions">
                        @auth
                            <a href="{{ route('dashboard') }}"
                               class="wg-button wg-button-primary">
                                Visit your garden
                                <span aria-hidden="true">→</span>
                            </a>
                        @else
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                   class="wg-button wg-button-primary">
                                    Plant your first word
                                    <span aria-hidden="true">→</span>
                                </a>
                            @endif

                            @if (Route::has('login'))
                                <a href="{{ route('login') }}"
                                   class="wg-button wg-button-secondary">
                                    Welcome back
                                </a>
                            @endif
                        @endauth
                    </div>

                    <p class="wg-note">
                        <svg viewBox="0 0 20 20" width="17" height="17"
                             fill="none" stroke="currentColor" stroke-width="1.7"
                             stroke-linecap="round" stroke-linejoin="round"
                             aria-hidden="true">
                            <path d="M10 17s-6.5-3.5-6.5-8.5a3.5 3.5 0 0 1 6.5-1.8 3.5 3.5 0 0 1 6.5 1.8C16.5 13.5 10 17 10 17Z" />
                        </svg>
                        Small daily habits. A richer vocabulary over time.
                    </p>
                </div>

                {{-- Illustrative vocabulary preview --}}
                <aside class="wg-preview" aria-label="Example vocabulary garden">
                    <div class="wg-preview-header">
                        <div>
                            <p class="wg-kicker">Your learning garden</p>
                            <h2 class="wg-preview-title">Words in bloom</h2>
                        </div>

                        <span class="wg-status">
                            <span aria-hidden="true">✳</span>
                            Keep growing
                        </span>
                    </div>

                    <div class="wg-word-list">
                        <article class="wg-word">
                            <div class="wg-word-icon" aria-hidden="true">🌱</div>
                            <div class="wg-word-content">
                                <h3 class="wg-word-name">Ephemeral</h3>
                                <p class="wg-word-definition">
                                    Lasting for only a short time.
                                </p>
                            </div>
                            <span class="wg-word-tag">New</span>
                        </article>

                        <article class="wg-word">
                            <div class="wg-word-icon" aria-hidden="true">🌿</div>
                            <div class="wg-word-content">
                                <h3 class="wg-word-name">Resilient</h3>
                                <p class="wg-word-definition">
                                    Able to recover and grow stronger.
                                </p>
                            </div>
                            <span class="wg-word-tag">Growing</span>
                        </article>

                        <article class="wg-word">
                            <div class="wg-word-icon" aria-hidden="true">🌸</div>
                            <div class="wg-word-content">
                                <h3 class="wg-word-name">Serendipity</h3>
                                <p class="wg-word-definition">
                                    Finding something wonderful by chance.
                                </p>
                            </div>
                            <span class="wg-word-tag">Blooming</span>
                        </article>
                    </div>

                    <div class="wg-progress">
                        <div class="wg-progress-top">
                            <span>A little progress adds up</span>
                            <span>Keep it up 🌿</span>
                        </div>

                        <div class="wg-progress-track"
                             role="img"
                             aria-label="Illustrative learning progress">
                            <div class="wg-progress-fill"></div>
                        </div>

                        <p class="wg-progress-caption">
                            Revisit your words regularly to help them take root.
                        </p>
                    </div>
                </aside>
            </section>

            {{-- Core benefits --}}
            <section class="wg-benefits" aria-label="How Word Garden works">
                <article class="wg-benefit">
                    <div class="wg-benefit-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" width="21" height="21"
                             fill="none" stroke="currentColor" stroke-width="1.7"
                             stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 21v-9" />
                            <path d="M12 12C12 6 16 3 22 3c0 6-4 9-10 9Z" />
                            <path d="M12 16C12 11 8 8 2 8c0 5 4 8 10 8Z" />
                        </svg>
                    </div>
                    <div>
                        <h2>Build your collection</h2>
                        <p>Save the words that catch your attention and make each one part of your own garden.</p>
                    </div>
                </article>

                <article class="wg-benefit">
                    <div class="wg-benefit-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" width="21" height="21"
                             fill="none" stroke="currentColor" stroke-width="1.7"
                             stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 5h16v14H4z" />
                            <path d="m8 12 3 3 5-6" />
                        </svg>
                    </div>
                    <div>
                        <h2>Practise actively</h2>
                        <p>Use randomized reviews and tests to strengthen recall, not just recognition.</p>
                    </div>
                </article>

                <article class="wg-benefit">
                    <div class="wg-benefit-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" width="21" height="21"
                             fill="none" stroke="currentColor" stroke-width="1.7"
                             stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 3v18" />
                            <path d="M12 12c0-5 3-8 8-8 0 5-3 8-8 8Z" />
                            <path d="M12 16c0-4-3-6-7-6 0 4 3 6 7 6Z" />
                        </svg>
                    </div>
                    <div>
                        <h2>Remember for longer</h2>
                        <p>Spaced repetition helps you revisit words at useful intervals and reinforce what you've learned.</p>
                    </div>
                </article>
            </section>
        </main>

        <footer class="wg-footer">
            <a href="{{ url('/') }}" class="wg-brand-name">
                Word <em>Garden</em>
            </a>

            <p>Every word is a seed. Give it room to grow.</p>
        </footer>

    </div>
</body>
</html>

