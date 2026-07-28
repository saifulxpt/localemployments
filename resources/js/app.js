import Alpine from 'alpinejs';

window.Alpine = Alpine;

// ─────────────────────────────────────────
// Alpine.js Global Components
// ─────────────────────────────────────────

// Flash message auto-dismiss
Alpine.data('flashMessage', () => ({
    show: true,
    init() {
        setTimeout(() => { this.show = false; }, 5000);
    }
}));

// Notification bell
Alpine.data('notificationBell', () => ({
    open: false,
    count: 0,
    notifications: [],
    polling: null,
    init() {
        this.fetchData();
        this.polling = setInterval(() => this.fetchData(), 30000);
    },
    async fetchData() {
        try {
            const res = await fetch('/notifications/bell');
            const data = await res.json();
            this.count = data.count;
            this.notifications = data.notifications;
        } catch (e) {}
    },
    toggle() {
        this.open = !this.open;
        if (this.open) this.fetchData();
    },
    async markAllRead() {
        try {
            await fetch('/notifications/read-all', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                    'Accept': 'application/json',
                }
            });
            this.count = 0;
            this.notifications = this.notifications.map(n => ({ ...n, is_read: true }));
        } catch (e) {}
    }
}));

// OTP resend countdown
Alpine.data('otpResend', () => ({
    canResend: false,
    countdown: 60,
    timer: null,
    init() {
        this.timer = setInterval(() => {
            this.countdown--;
            if (this.countdown <= 0) {
                clearInterval(this.timer);
                this.canResend = true;
            }
        }, 1000);
    }
}));

// Chat polling component
Alpine.data('chat', (bookingId, pollUrl, sendUrl, csrfToken) => ({
    messages: [],
    newMessage: '',
    sending: false,
    polling: null,
    init() {
        this.poll();
        this.polling = setInterval(() => this.poll(), 5000);
    },
    async poll() {
        try {
            const res = await fetch(pollUrl);
            const data = await res.json();
            const prevCount = this.messages.length;
            this.messages = data.messages;
            if (this.messages.length !== prevCount) {
                this.$nextTick(() => this.scrollToBottom());
            }
        } catch (e) {}
    },
    async send() {
        if (!this.newMessage.trim() || this.sending) return;
        this.sending = true;
        try {
            await fetch(sendUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ message: this.newMessage })
            });
            this.newMessage = '';
            await this.poll();
        } catch (e) {}
        this.sending = false;
    },
    scrollToBottom() {
        const el = this.$refs.messages;
        if (el) el.scrollTop = el.scrollHeight;
    }
}));

// Confirm action (delete, cancel, etc.)
Alpine.data('confirmAction', (message = 'Are you sure?') => ({
    open: false,
    message,
    confirm(callback) {
        this.open = true;
    }
}));

// Image preview on file input
Alpine.data('imagePreview', () => ({
    previews: [],
    handle(event) {
        this.previews = [];
        const files = Array.from(event.target.files);
        files.forEach(file => {
            const reader = new FileReader();
            reader.onload = (e) => {
                this.previews.push(e.target.result);
            };
            reader.readAsDataURL(file);
        });
    }
}));

// District → Area AJAX loader
Alpine.data('locationSelect', (areasUrl) => ({
    areas: [],
    loadAreas(districtId) {
        if (!districtId) { this.areas = []; return; }
        fetch(`${areasUrl}/${districtId}`)
            .then(r => r.json())
            .then(data => { this.areas = data; });
    }
}));

// Animated counter
Alpine.data('counter', (target, duration = 2000) => ({
    current: 0,
    start() {
        const step = target / (duration / 16);
        const timer = setInterval(() => {
            this.current += step;
            if (this.current >= target) {
                this.current = target;
                clearInterval(timer);
            }
        }, 16);
    }
}));

// Star rating selector
Alpine.data('starRating', (initialValue = 0) => ({
    value: initialValue,
    hovered: 0,
    set(val) { this.value = val; },
    hover(val) { this.hovered = val; },
    leave() { this.hovered = 0; },
    isActive(val) { return val <= (this.hovered || this.value); }
}));

// Tabs component
Alpine.data('tabs', (defaultTab = 0) => ({
    active: defaultTab,
    setTab(index) { this.active = index; }
}));

// Mobile menu
Alpine.data('mobileMenu', () => ({
    open: false,
    toggle() { this.open = !this.open; },
    close() { this.open = false; }
}));

Alpine.start();
