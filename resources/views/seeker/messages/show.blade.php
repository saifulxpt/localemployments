@extends('layouts.seeker')

@section('title', 'চ্যাট - ' . $booking->provider->name)

@section('content')
<div class="max-w-4xl mx-auto h-[calc(100vh-140px)] flex flex-col bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" 
     x-data="chatComponent('{{ route('seeker.messages.poll', $booking->id) }}', '{{ route('seeker.messages.send', $booking->id) }}')"
     x-init="initChat()">

    {{-- Chat Header --}}
    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between shrink-0">
        <div class="flex items-center gap-4">
            <a href="{{ route('seeker.messages.index') }}" class="text-gray-500 hover:text-primary-600 transition-colors">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div class="flex items-center gap-3">
                <img src="{{ $booking->provider->avatar_url }}" alt="" class="w-10 h-10 rounded-full object-cover border border-gray-200">
                <div>
                    <h2 class="font-bold text-gray-900">{{ $booking->provider->name }}</h2>
                    <p class="text-xs text-gray-500 font-medium">বুকিং #{{ $booking->id }} | প্রোভাইডার</p>
                </div>
            </div>
        </div>
        
        <a href="{{ route('seeker.bookings.show', $booking->id) }}" class="btn btn-outline btn-sm hidden md:inline-flex">বুকিং বিস্তারিত</a>
    </div>

    {{-- Chat Messages Area --}}
    <div class="flex-1 overflow-y-auto p-4 md:p-6 bg-slate-50 space-y-4" x-ref="messagesContainer">
        
        {{-- Loading indicator --}}
        <div x-show="loading" class="flex justify-center py-4">
            <svg class="animate-spin h-6 w-6 text-primary-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
        </div>

        {{-- Messages --}}
        <template x-for="msg in messages" :key="msg.id">
            <div class="flex w-full" :class="msg.is_mine ? 'justify-end' : 'justify-start'">
                <div class="flex max-w-[85%] md:max-w-[70%] gap-2" :class="msg.is_mine ? 'flex-row-reverse' : 'flex-row'">
                    
                    {{-- Avatar --}}
                    <img :src="msg.sender_avatar" class="w-8 h-8 rounded-full object-cover shrink-0 border border-gray-200 mt-1">
                    
                    {{-- Message Bubble --}}
                    <div class="flex flex-col" :class="msg.is_mine ? 'items-end' : 'items-start'">
                        <div class="px-4 py-2.5 rounded-2xl text-[15px] whitespace-pre-wrap"
                             :class="msg.is_mine ? 'bg-primary-600 text-white rounded-tr-none' : 'bg-white text-gray-800 border border-gray-100 shadow-sm rounded-tl-none'"
                             x-text="msg.message">
                        </div>
                        <span class="text-[10px] text-gray-400 mt-1 px-1" x-text="msg.formatted_time"></span>
                    </div>

                </div>
            </div>
        </template>
        
        <div x-show="!loading && messages.length === 0" class="h-full flex flex-col items-center justify-center text-center text-gray-400 py-10">
            <svg class="w-12 h-12 mb-3 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
            <p>এখনও কোনো মেসেজ হয়নি।<br>প্রথম মেসেজ পাঠিয়ে কথা শুরু করুন।</p>
        </div>
    </div>

    {{-- Chat Input --}}
    <div class="p-4 border-t border-gray-100 bg-white shrink-0">
        @if(in_array($booking->status, ['completed', 'cancelled']))
            <div class="text-center text-sm text-gray-500 py-2 bg-gray-50 rounded-lg">
                এই বুকিংটি {{ $booking->status === 'completed' ? 'সম্পন্ন' : 'বাতিল' }} হয়েছে। তাই চ্যাটটি বন্ধ করা হয়েছে।
            </div>
        @else
            <form @submit.prevent="sendMessage" class="flex items-end gap-3">
                <div class="flex-1 bg-gray-50 border border-gray-200 rounded-2xl p-1 focus-within:border-primary-500 focus-within:ring-1 focus-within:ring-primary-500 transition-all">
                    <textarea 
                        x-model="newMessage" 
                        @keydown.enter.prevent="if(!event.shiftKey) sendMessage()"
                        rows="1" 
                        class="w-full bg-transparent border-none focus:ring-0 resize-none max-h-32 min-h-[44px] py-3 px-4 text-sm" 
                        placeholder="আপনার মেসেজ লিখুন... (Enter চাপলে সেন্ড হবে)"
                        style="height: 44px;"></textarea>
                </div>
                <button type="submit" class="btn btn-primary h-[52px] w-[52px] shrink-0 rounded-2xl flex items-center justify-center !p-0" :disabled="sending || !newMessage.trim()">
                    <svg x-show="!sending" class="w-6 h-6 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    <svg x-show="sending" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                </button>
            </form>
        @endif
    </div>

</div>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('chatComponent', (pollUrl, sendUrl) => ({
            messages: [],
            newMessage: '',
            loading: true,
            sending: false,
            pollInterval: null,
            
            initChat() {
                this.fetchMessages();
                this.pollInterval = setInterval(() => {
                    this.fetchMessages(false);
                }, 10000); // Poll every 10 seconds
            },
            
            fetchMessages(scrollToBottom = true) {
                fetch(pollUrl)
                    .then(res => res.json())
                    .then(data => {
                        const previousCount = this.messages.length;
                        this.messages = data.messages;
                        this.loading = false;
                        
                        if (scrollToBottom && previousCount !== data.messages.length) {
                            this.$nextTick(() => {
                                this.scrollToBottom();
                            });
                        }
                    });
            },
            
            sendMessage() {
                if (!this.newMessage.trim() || this.sending) return;
                
                const msg = this.newMessage;
                this.newMessage = '';
                this.sending = true;
                
                fetch(sendUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ message: msg })
                })
                .then(res => res.json())
                .then(() => {
                    this.sending = false;
                    this.fetchMessages(true);
                })
                .catch(() => {
                    this.sending = false;
                    this.newMessage = msg;
                    alert('মেসেজ পাঠাতে সমস্যা হয়েছে।');
                });
            },
            
            scrollToBottom() {
                const container = this.$refs.messagesContainer;
                container.scrollTop = container.scrollHeight;
            },
            
            destroy() {
                if (this.pollInterval) clearInterval(this.pollInterval);
            }
        }));
    });
</script>
@endpush
@endsection
