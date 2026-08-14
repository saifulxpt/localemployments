@extends('layouts.auth')

@section('title', 'ফোন নম্বর যাচাই')

@section('content')
    <div class="text-center mb-6">
        <div class="w-14 h-14 bg-primary-50 border border-primary-100 rounded-2xl flex items-center justify-center mx-auto mb-3.5 text-primary-600 shadow-sm">
            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">ফোন নম্বর যাচাই</h1>
        <p class="text-slate-600 text-sm mt-1.5 leading-relaxed">
            আপনার ফোন নম্বর <strong class="font-bold text-slate-900 font-mono text-sm tracking-wide">{{ format_bd_phone($user->phone ?? '') }}</strong> এ পাঠানো ৬-সংখ্যার OTP দিন
        </p>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-3.5 py-2.5 rounded-xl text-xs sm:text-sm mb-5 flex items-center gap-2">
            <svg class="w-4 h-4 shrink-0 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('info'))
        <div class="bg-blue-50 border border-blue-200 text-blue-800 px-3.5 py-2.5 rounded-xl text-xs sm:text-sm mb-5 flex items-center gap-2">
            <svg class="w-4 h-4 shrink-0 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>{{ session('info') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-3.5 py-2.5 rounded-xl text-xs sm:text-sm mb-5 flex items-center gap-2">
            <svg class="w-4 h-4 shrink-0 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>{{ $errors->first() }}</span>
        </div>
    @endif

    <form method="POST" action="{{ route('otp.verify') }}"
          x-data="{
              otp: ['', '', '', '', '', ''],
              handleInput(index, e) {
                  const val = e.target.value.replace(/\D/g, '').slice(-1);
                  this.otp[index] = val;
                  if (val && index < 5) {
                      this.$refs['otp_' + (index + 1)].focus();
                  }
                  this.syncHidden();
              },
              handleKey(index, e) {
                  if (e.key === 'Backspace' && !this.otp[index] && index > 0) {
                      this.$refs['otp_' + (index - 1)].focus();
                  }
              },
              handlePaste(e) {
                  e.preventDefault();
                  const paste = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '').slice(0, 6);
                  if (paste.length > 0) {
                      for (let i = 0; i < 6; i++) {
                          this.otp[i] = paste[i] || '';
                      }
                      this.syncHidden();
                      const nextFocus = Math.min(paste.length, 5);
                      this.$refs['otp_' + nextFocus]?.focus();
                  }
              },
              syncHidden() {
                  this.$refs.otpHidden.value = this.otp.join('');
              }
          }">
        @csrf

        {{-- 6-box OTP Input (Fully Responsive) --}}
        <div class="flex gap-1.5 sm:gap-2.5 justify-center mb-6" @paste="handlePaste($event)">
            @for($i = 0; $i < 6; $i++)
                <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1"
                       x-ref="otp_{{ $i }}"
                       @input="handleInput({{ $i }}, $event)"
                       @keydown="handleKey({{ $i }}, $event)"
                       :value="otp[{{ $i }}]"
                       class="w-10 h-13 sm:w-12 sm:h-14 text-center text-xl sm:text-2xl font-bold border-2 border-slate-200 rounded-xl focus:border-primary-600 focus:ring-4 focus:ring-primary-500/10 outline-none transition-all text-slate-900 bg-slate-50 focus:bg-white"
                       @if($i === 0) autofocus @endif>
            @endfor
        </div>

        {{-- Hidden actual input --}}
        <input type="hidden" name="otp" x-ref="otpHidden">

        <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 active:scale-[0.99] text-white font-semibold text-base py-3.5 px-4 rounded-xl shadow-md shadow-primary-600/20 transition-all flex items-center justify-center gap-2">
            যাচাই ও প্রবেশ করুন
        </button>

        {{-- Resend & Sub-links --}}
        <div class="mt-6 pt-5 border-t border-slate-100 text-center"
             x-data="{
                 canResend: false,
                 countdown: 60,
                 timer: null,
                 init() {
                     this.timer = setInterval(() => {
                         if (this.countdown > 1) {
                             this.countdown--;
                         } else {
                             this.canResend = true;
                             clearInterval(this.timer);
                         }
                     }, 1000);
                 }
             }">

            {{-- Timer or Resend Button --}}
            <div class="min-h-[28px] flex items-center justify-center mb-3 text-sm">
                <span class="text-slate-500 text-xs sm:text-sm" x-show="!canResend">
                    কোড পাননি? <strong class="font-bold text-primary-700 font-mono" x-text="countdown + 's'"></strong> পর পুনরায় পাঠানো যাবে
                </span>
                <form method="POST" action="{{ route('otp.resend') }}" x-show="canResend" x-cloak class="inline">
                    @csrf
                    <button type="submit" class="font-bold text-xs sm:text-sm text-primary-600 hover:text-primary-700 hover:underline inline-flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        OTP পুনরায় পাঠান
                    </button>
                </form>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-center gap-3 text-xs font-medium text-slate-500">
                <a href="{{ route('otp.change-phone') }}" class="text-primary-600 hover:text-primary-700 hover:underline flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    নম্বর পরিবর্তন
                </a>
                <span class="text-slate-300">•</span>
                <form method="POST" action="{{ route('otp.cancel') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-rose-500 hover:text-rose-600 hover:underline">
                        বাতিল করুন
                    </button>
                </form>
            </div>
        </div>
    </form>
@endsection
