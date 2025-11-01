@php
    $isAuthenticated = auth()->check() || auth('employer')->check();
@endphp

<div
    x-data="supportBot({ isAuthenticated: {{ $isAuthenticated ? 'true' : 'false' }} })"
    x-cloak
    class="fixed bottom-6 right-4 z-50 flex flex-col items-end space-y-3 sm:right-6">
    <template x-if="!open">
        <button
            @click="openPanel"
            class="inline-flex items-center gap-2 rounded-full bg-blue-600 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-900/30 transition hover:bg-blue-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M7 8h10M7 12h4m-2 8a10 10 0 110-20 10 10 0 010 20z" />
            </svg>
            <span>Need help?</span>
        </button>
    </template>

    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-2 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-2 scale-95"
        class="flex w-[20rem] max-w-xs flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl shadow-slate-900/20 ring-1 ring-slate-900/10 sm:w-80 sm:max-w-sm">
        <header class="flex items-start justify-between gap-4 bg-slate-900 px-4 py-3 text-white">
            <div>
                <p class="text-sm font-semibold">ProSnap Assistant</p>
                <p class="text-xs text-slate-300">Instant answers to common questions</p>
            </div>
            <button
                @click="closePanel"
                class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-white/10 text-white transition hover:bg-white/20"
                aria-label="Close support bot">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </header>

        <div class="flex flex-1 flex-col bg-white">
            <div
                x-ref="stream"
                class="flex flex-1 flex-col gap-3 overflow-y-auto px-4 py-4 text-sm text-slate-700">
                <template x-for="item in conversation" :key="item.id">
                    <div class="flex"
                         :class="item.sender === 'bot' ? 'justify-start' : 'justify-end'">
                        <div
                            class="max-w-[85%] rounded-2xl px-3 py-2 leading-relaxed"
                            :class="item.sender === 'bot'
                                ? 'bg-slate-100 text-slate-800'
                                : 'bg-blue-600 text-white'">
                            <div x-html="item.text"></div>
                        </div>
                    </div>
                </template>
            </div>

            <div class="border-t border-slate-200 bg-slate-50 px-4 py-3">
                <template x-if="currentOptions.length">
                    <div class="grid gap-2">
                        <template x-for="option in currentOptions" :key="option.id">
                            <button
                                @click="choose(option)"
                                class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-left text-sm font-semibold text-slate-700 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700">
                                <span x-text="option.label"></span>
                            </button>
                        </template>
                    </div>
                </template>
                <template x-if="!currentOptions.length">
                    <div class="flex items-center justify-between gap-2 text-xs text-slate-500">
                        <span>Need something else?</span>
                        <button
                            @click="resetConversation"
                            class="font-semibold text-blue-600 transition hover:text-blue-500">
                            Start over
                        </button>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>

@once
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('supportBot', (config = {}) => {
                const buildAuthenticatedNodes = () => ({
                    intro: {
                        message: `Hi there! I'm <strong>ProSnap Assistant</strong>. Pick a topic and I'll walk you through it.`,
                        options: [
                            { id: 'opt-candidate', label: 'Candidate: Build my profile', next: 'candidate_intro' },
                            { id: 'opt-employer', label: 'Employer: Post a role', next: 'employer_intro' },
                            { id: 'opt-account', label: 'Account & verification', next: 'account_intro' },
                            { id: 'opt-contact', label: 'Something else / contact support', next: 'contact_human' },
                        ],
                    },
                    candidate_intro: {
                        message: `<p class="font-semibold">Building a standout candidate profile takes just a few minutes:</p>
                            <ol class="mt-2 list-decimal space-y-1 pl-5 text-sm">
                                <li>Create or log in to your candidate account.</li>
                                <li>Head to <strong>Create Post</strong> to share your wins, stack, and availability.</li>
                                <li>Publish and share the link to employers you connect with.</li>
                            </ol>`,
                        options: [
                            { id: 'opt-candidate-publish', label: 'Step-by-step: publish my first resume post', next: 'candidate_publish' },
                            { id: 'opt-candidate-manage', label: 'Edit, archive, or delete an existing post', next: 'candidate_manage' },
                            { id: 'opt-candidate-back', label: 'Back to main menu', next: 'intro' },
                        ],
                    },
                    candidate_publish: {
                        message: `<p class="font-semibold">Publish your first resume post:</p>
                            <ul class="mt-2 list-disc space-y-1 pl-5 text-sm">
                                <li>Log in, then open <a href="{{ route('make_post.index') }}" class="text-blue-600 underline">Create Post</a>.</li>
                                <li>Fill in your headline, achievements, skills, and attach an optional CV.</li>
                                <li>Click <strong>Post</strong>. Your story appears in the <em>Featured resumes</em> feed for employers browsing talent.</li>
                            </ul>
                            <p class="mt-2 text-sm text-slate-600">Tip: keep paragraphs short and focus on measurable wins.</p>`,
                        options: [
                            { id: 'opt-candidate-visibility', label: 'Where will employers see it?', next: 'candidate_visibility' },
                            { id: 'opt-candidate-return', label: 'Back to candidate help', next: 'candidate_intro' },
                            { id: 'opt-candidate-home', label: 'Main menu', next: 'intro' },
                        ],
                    },
                    candidate_visibility: {
                        message: `<p>Your post lands in:</p>
                            <ul class="mt-2 list-disc space-y-1 pl-5 text-sm">
                                <li>The <strong>Featured resumes</strong> section on the home page.</li>
                                <li>The <a href="{{ route('all_posts.index') }}" class="text-blue-600 underline">All Posts</a> feed where employers search by role or skill.</li>
                                <li>Your personal share link — perfect for LinkedIn or outreach.</li>
                            </ul>
                            <p class="mt-2 text-sm text-slate-600">Every update refreshes your spot in the feed, so keep it current.</p>`,
                        options: [
                            { id: 'opt-candidate-visibility-back', label: 'Back to candidate help', next: 'candidate_intro' },
                            { id: 'opt-candidate-visibility-home', label: 'Main menu', next: 'intro' },
                        ],
                    },
                    candidate_manage: {
                        message: `<p class="font-semibold">Editing an existing post is easy:</p>
                            <ul class="mt-2 list-disc space-y-1 pl-5 text-sm">
                                <li>Open <a href="{{ route('portfolios.index') }}" class="text-blue-600 underline">My Posts</a>.</li>
                                <li>Select the post to edit, then update the details or archive it.</li>
                                <li>You can download the CV you generated or upload a fresh version anytime.</li>
                            </ul>
                            <p class="mt-2 text-sm text-slate-600">Archived posts stay private but can be restored whenever you're ready.</p>`,
                        options: [
                            { id: 'opt-candidate-manage-return', label: 'Back to candidate help', next: 'candidate_intro' },
                            { id: 'opt-candidate-manage-home', label: 'Main menu', next: 'intro' },
                        ],
                    },
                    employer_intro: {
                        message: `<p class="font-semibold">Ready to hire? Here's how employers get started:</p>
                            <ol class="mt-2 list-decimal space-y-1 pl-5 text-sm">
                                <li>Create or log in to your employer workspace.</li>
                                <li>Publish a role so candidates can discover you.</li>
                                <li>Review interested talent and move fast with built-in messaging.</li>
                            </ol>`,
                        options: [
                            { id: 'opt-employer-post', label: 'Steps to publish a new role', next: 'employer_post' },
                            { id: 'opt-employer-manage', label: 'Manage applicants and conversations', next: 'employer_manage' },
                            { id: 'opt-employer-back', label: 'Back to main menu', next: 'intro' },
                        ],
                    },
                    employer_post: {
                        message: `<p class="font-semibold">Post a role in minutes:</p>
                            <ul class="mt-2 list-disc space-y-1 pl-5 text-sm">
                                <li>Log in, then open <a href="{{ route('employer.make_post') }}" class="text-blue-600 underline">Post a Job</a>.</li>
                                <li>Add a concise title, expectations, required skills, and your timeline.</li>
                                <li>Publish — the role appears in the candidate feed and on your employer dashboard.</li>
                            </ul>
                            <p class="mt-2 text-sm text-slate-600">Need visuals? Attach company branding to stand out.</p>`,
                        options: [
                            { id: 'opt-employer-after', label: 'What happens after publishing?', next: 'employer_after_post' },
                            { id: 'opt-employer-return', label: 'Back to employer help', next: 'employer_intro' },
                            { id: 'opt-employer-home', label: 'Main menu', next: 'intro' },
                        ],
                    },
                    employer_after_post: {
                        message: `<p>Once your role is live:</p>
                            <ul class="mt-2 list-disc space-y-1 pl-5 text-sm">
                                <li>You can monitor performance from the <a href="{{ route('employer.dashboard') }}" class="text-blue-600 underline">employer dashboard</a>.</li>
                                <li>Candidates can apply directly; you'll see new applicants in your dashboard with their CV snapshots.</li>
                                <li>Close or update the post anytime to keep details current.</li>
                            </ul>`,
                        options: [
                            { id: 'opt-employer-after-back', label: 'Back to employer help', next: 'employer_intro' },
                            { id: 'opt-employer-after-home', label: 'Main menu', next: 'intro' },
                        ],
                    },
                    employer_manage: {
                        message: `<p class="font-semibold">Managing applicants:</p>
                            <ul class="mt-2 list-disc space-y-1 pl-5 text-sm">
                                <li>Open <a href="{{ route('employer.portfolios') }}" class="text-blue-600 underline">My Posts</a> and choose the role you want to review.</li>
                                <li>Use the <em>Applicants</em> tab to shortlist, message, or mark candidates as hired.</li>
                                <li>Close the post to stop receiving new applications once the role is filled.</li>
                            </ul>
                            <p class="mt-2 text-sm text-slate-600">Pro tip: leave internal notes so teammates stay aligned.</p>`,
                        options: [
                            { id: 'opt-employer-manage-back', label: 'Back to employer help', next: 'employer_intro' },
                            { id: 'opt-employer-manage-home', label: 'Main menu', next: 'intro' },
                        ],
                    },
                    account_intro: {
                        message: `<p class="font-semibold">Account issues, sorted:</p>
                            <ul class="mt-2 list-disc space-y-1 pl-5 text-sm">
                                <li>Reset passwords instantly.</li>
                                <li>Resend verification links.</li>
                                <li>Swap between employer and candidate accounts.</li>
                            </ul>`,
                        options: [
                            { id: 'opt-account-reset', label: 'I forgot my password', next: 'account_reset' },
                            { id: 'opt-account-verify', label: 'Email verification keeps failing', next: 'account_verification' },
                            { id: 'opt-account-switch', label: 'How do I switch between employer & candidate?', next: 'account_switch' },
                            { id: 'opt-account-back', label: 'Back to main menu', next: 'intro' },
                        ],
                    },
                    account_reset: {
                        message: `<p class="font-semibold">Reset your password:</p>
                            <ol class="mt-2 list-decimal space-y-1 pl-5 text-sm">
                                <li>Open the <a href="{{ route('password.request') }}" class="text-blue-600 underline">Reset password</a> page.</li>
                                <li>Enter the email tied to your account.</li>
                                <li>Follow the link in your inbox to choose a new password.</li>
                            </ol>
                            <p class="mt-2 text-sm text-slate-600">Employer accounts reset separately — reach out if the link doesn't recognize you.</p>`,
                        options: [
                            { id: 'opt-account-reset-back', label: 'Back to account help', next: 'account_intro' },
                            { id: 'opt-account-reset-home', label: 'Main menu', next: 'intro' },
                        ],
                    },
                    account_verification: {
                        message: `<p class="font-semibold">Verification issues?</p>
                            <ul class="mt-2 list-disc space-y-1 pl-5 text-sm">
                                <li>Check spam or promotions for the confirmation email.</li>
                                <li>On the verification page, press <strong>Resend</strong> to generate a fresh link.</li>
                                <li>Links expire after 60 minutes—trigger a new one if it timed out.</li>
                            </ul>
                            <p class="mt-2 text-sm text-slate-600">Still blocked? Send us the email you registered with and we'll verify manually.</p>`,
                        options: [
                            { id: 'opt-account-verification-back', label: 'Back to account help', next: 'account_intro' },
                            { id: 'opt-account-verification-home', label: 'Main menu', next: 'intro' },
                        ],
                    },
                    account_switch: {
                        message: `<p class="font-semibold">Switching account types:</p>
                            <ul class="mt-2 list-disc space-y-1 pl-5 text-sm">
                                <li>Sign out from the profile menu in the top-right corner.</li>
                                <li>Visit the <a href="{{ route('login') }}" class="text-blue-600 underline">candidate login</a> or <a href="{{ route('employer.login') }}" class="text-blue-600 underline">employer login</a> page.</li>
                                <li>Log in with the email associated with the space you need.</li>
                            </ul>
                            <p class="mt-2 text-sm text-slate-600">You can keep separate employer and candidate accounts if you play both roles.</p>`,
                        options: [
                            { id: 'opt-account-switch-back', label: 'Back to account help', next: 'account_intro' },
                            { id: 'opt-account-switch-home', label: 'Main menu', next: 'intro' },
                        ],
                    },
                    contact_human: {
                        message: `<p class="font-semibold">Need a person?</p>
                            <p class="mt-1 text-sm">Email us at <a href="mailto:support@prosnap.io" class="text-blue-600 underline">support@prosnap.io</a> — we usually reply within one business day.</p>
                            <p class="mt-3 text-xs text-slate-500">Share as much context as you can (links, screenshots, the email you signed up with) so we can help faster.</p>`,
                        options: [
                            { id: 'opt-contact-mail', label: 'Compose an email to support', url: 'mailto:support@prosnap.io' },
                            { id: 'opt-contact-home', label: 'Back to main menu', next: 'intro' },
                        ],
                    },
                });

                const buildGuestNodes = () => ({
                    intro: {
                        message: `Hey! I'm <strong>ProSnap Assistant</strong>. Pick a topic to get quick answers before you dive in.`,
                        options: [
                            { id: 'opt-guest-candidate', label: 'What is a candidate profile?', next: 'guest_candidate' },
                            { id: 'opt-guest-employer', label: 'How do employers post roles?', next: 'guest_employer' },
                            { id: 'opt-guest-account', label: 'Create or access my account', next: 'guest_account' },
                            { id: 'opt-guest-contact', label: 'Talk to a human', next: 'guest_contact' },
                        ],
                    },
                    guest_candidate: {
                        message: `<p class="font-semibold">Candidate profiles help you showcase your wins:</p>
                            <ul class="mt-2 list-disc space-y-1 pl-5 text-sm">
                                <li>Create a free account in under two minutes.</li>
                                <li>Share your headline, role focus, and measurable achievements.</li>
                                <li>Publish and send the link to hiring teams or share publicly.</li>
                            </ul>
                            <p class="mt-2 text-sm text-slate-600">Ready to start? Register or sign in to create your first post.</p>`,
                        options: [
                            { id: 'opt-guest-candidate-register', label: 'Register as a candidate', url: '{{ route('register') }}' },
                            { id: 'opt-guest-candidate-login', label: 'Log in', url: '{{ route('login') }}' },
                            { id: 'opt-guest-candidate-back', label: 'Main menu', next: 'intro' },
                        ],
                    },
                    guest_employer: {
                        message: `<p class="font-semibold">Hiring? Get in front of ready-to-talk talent:</p>
                            <ul class="mt-2 list-disc space-y-1 pl-5 text-sm">
                                <li>Set up an employer workspace (free).</li>
                                <li>Publish a role with expectations, stack, and timeline.</li>
                                <li>Review applicants and connect directly inside ProSnap.</li>
                            </ul>
                            <p class="mt-2 text-sm text-slate-600">You’ll unlock additional guidance once you’re signed in.</p>`,
                        options: [
                            { id: 'opt-guest-employer-register', label: 'Create employer account', url: '{{ route('employer.register') }}' },
                            { id: 'opt-guest-employer-login', label: 'Employer login', url: '{{ route('employer.login') }}' },
                            { id: 'opt-guest-employer-back', label: 'Main menu', next: 'intro' },
                        ],
                    },
                    guest_account: {
                        message: `<p class="font-semibold">Pick the right starting point:</p>
                            <ul class="mt-2 list-disc space-y-1 pl-5 text-sm">
                                <li><strong>New here?</strong> Register as a candidate or employer.</li>
                                <li><strong>Returning user?</strong> Use the login links below.</li>
                                <li>Need verification help? Check your spam folder for a confirmation email.</li>
                            </ul>`,
                        options: [
                            { id: 'opt-guest-account-candidate-register', label: 'Candidate registration', url: '{{ route('register') }}' },
                            { id: 'opt-guest-account-employer-register', label: 'Employer registration', url: '{{ route('employer.register') }}' },
                            { id: 'opt-guest-account-login', label: 'Forgot password / login help', next: 'guest_account_support' },
                            { id: 'opt-guest-account-back', label: 'Main menu', next: 'intro' },
                        ],
                    },
                    guest_account_support: {
                        message: `<p class="font-semibold">Recovering access:</p>
                            <ul class="mt-2 list-disc space-y-1 pl-5 text-sm">
                                <li>Reset your password from the <a href="{{ route('password.request') }}" class="text-blue-600 underline">password reset</a> page.</li>
                                <li>Didn’t get a verification email? Use the resend link from the banner after logging in.</li>
                                <li>Still stuck? Drop us a note with the email you signed up with.</li>
                            </ul>`,
                        options: [
                            { id: 'opt-guest-account-support-mail', label: 'Email support', url: 'mailto:support@prosnap.io' },
                            { id: 'opt-guest-account-support-back', label: 'Account options', next: 'guest_account' },
                            { id: 'opt-guest-account-support-home', label: 'Main menu', next: 'intro' },
                        ],
                    },
                    guest_contact: {
                        message: `<p class="font-semibold">We're here to help.</p>
                            <p class="mt-1 text-sm">Email <a href="mailto:support@prosnap.io" class="text-blue-600 underline">support@prosnap.io</a> and we’ll reply within one business day.</p>
                            <p class="mt-3 text-xs text-slate-500">Include details about what you’re trying to do so we can point you to the right spot.</p>`,
                        options: [
                            { id: 'opt-guest-contact-mail', label: 'Compose email', url: 'mailto:support@prosnap.io' },
                            { id: 'opt-guest-contact-back', label: 'Main menu', next: 'intro' },
                        ],
                    },
                });

                return {
                    open: false,
                    conversation: [],
                    currentOptions: [],
                    currentNode: 'intro',
                    nodes: {},
                    openPanel() {
                        if (!this.open) {
                            this.open = true;
                            this.$nextTick(() => this.scrollToBottom());
                        }
                    },
                    closePanel() {
                        this.open = false;
                    },
                    resetConversation() {
                        this.conversation = [];
                        this.currentOptions = [];
                        this.visitNode('intro');
                    },
                    visitNode(key) {
                        const node = this.nodes[key];
                        if (!node) {
                            return;
                        }

                        this.currentNode = key;
                        const stamp = Date.now();
                        this.conversation.push({
                            id: `bot-${key}-${stamp}`,
                            sender: 'bot',
                            text: node.message,
                        });
                        this.currentOptions = (node.options || []).map(option => ({
                            ...option,
                            id: option.id || `opt-${Date.now()}-${Math.random()}`,
                        }));
                        this.$nextTick(() => this.scrollToBottom());
                    },
                    choose(option) {
                        this.conversation.push({
                            id: `user-${Date.now()}`,
                            sender: 'user',
                            text: option.label,
                        });

                        if (option.url) {
                            window.open(option.url, option.external ? '_blank' : '_self');
                        }

                        if (option.next) {
                            this.visitNode(option.next);
                            return;
                        }

                        if (option.restart) {
                            this.resetConversation();
                            return;
                        }

                        if (option.message) {
                            this.conversation.push({
                                id: `bot-inline-${Date.now()}`,
                                sender: 'bot',
                                text: option.message,
                            });
                        }

                        if (option.options) {
                            this.currentOptions = option.options;
                        } else {
                            this.currentOptions = [];
                        }

                        this.$nextTick(() => this.scrollToBottom());
                    },
                    scrollToBottom() {
                        if (this.$refs.stream) {
                            this.$refs.stream.scrollTop = this.$refs.stream.scrollHeight;
                        }
                    },
                    init() {
                        this.nodes = config.isAuthenticated ? buildAuthenticatedNodes() : buildGuestNodes();
                        this.resetConversation();
                    },
                };
            });
        });
    </script>
@endonce
