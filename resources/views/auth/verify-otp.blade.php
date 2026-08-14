@extends('layouts.auth')

@section('title', 'ফোন নম্বর যাচাই')

@section('content')
    <div class="text-center mb-6">
        <div class="w-16 h-16 bg-primary-50 border border-primary-100 rounded-2xl flex items-center justify-center mx-auto mb-4 text-primary-600 shadow-sm">
            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-gray-900">ফোন নম্বর যাচাই</h1>
        <p class="text-gray-600 text-sm mt-2">
            আপনার ফোন নম্বর <span class="font-bold text-gray-900">{{ format_bd_phone($user->phone ?? '') }}</span> এ পাঠানো ৬-সংখ্যার OTP কোডটি দিন
        </p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger mb-4">
            {{ $errors->first() }}
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

        {{-- 6-box OTP Input --}}
        <div class="flex gap-2.5 justify-center mb-6" @paste="handlePaste($event)">
            @for($i = 0; $i < 6; $i++)
                <input type="text" inputmode="numeric" maxlength="1"
                       x-ref="otp_{{ $i }}"
                       @input="handleInput({{ $i }}, $event)"
                       @keydown="handleKey({{ $i }}, $event)"
                       :value="otp[{{ $i }}]"
                       class="w-12 h-14 text-center text-2xl font-bold border-2 border-gray-200 rounded-xl focus:border-primary-600 focus:ring-4 focus:ring-primary-500/10 outline-none transition-all text-gray-900 bg-gray-50 focus:bg-white"
                       @if($i === 0) autofocus @endif>
            @endfor
        </div>

        {{-- Hidden actual input --}}
        <input type="hidden" name="otp" x-ref="otpHidden">

        <button type="submit" class="btn btn-primary w-full justify-center py-3.5 text-base font-bold shadow-md hover:shadow-lg transition-all">
            যাচাই ও লগইন করুন
        </button>

        {{-- Resend & Options --}}
        <div class="text-center mt-5 pt-4 border-t border-gray-100" x-data="otpResend()">
            <div class="flex items-center justify-center gap-2 mb-3">
                <span class="text-sm text-gray-500" x-show="!canResend">
                    কোড পাননি? <span class="font-bold text-primary-700" x-text="countdown + ' সেকেন্ড পর'"></span> পুনরায় পাঠাতে পারবেন
                </span>
                <form method="POST" action="{{ route('otp.resend') }}" x-show="canResend" x-cloak>
                    @csrf
                    <button type="submit" class="text-sm text-primary-600 font-bold hover:underline">
                        🔄 OTP পুনরায় পাঠান
                    </button>
                </form>
            </div>

            <div class="flex items-center justify-center gap-4 text-xs font-medium text-gray-500">
                <a href="{{ route('otp.change-phone') }}" class="text-primary-700 hover:underline flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    নম্বর পরিবর্তন করুন
                </a>
                <span>•</span>
                <form method="POST" action="{{ route('otp.cancel') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-red-500 hover:underline">
                        বাতিল করুন
                    </button>
                </form>
            </div>
        </div>
    </form>
@endsection
