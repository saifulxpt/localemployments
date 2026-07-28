@extends('layouts.auth')

@section('title', 'ফোন যাচাই')

@section('content')
    <div class="text-center mb-6">
        <div class="w-16 h-16 bg-primary-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-gray-900">ফোন নম্বর যাচাই</h1>
        <p class="text-gray-500 text-sm mt-2">আপনার ফোনে পাঠানো ৬-সংখ্যার OTP কোড দিন</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger mb-4">{{ $errors->first() }}</div>
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
              syncHidden() {
                  this.$refs.otpHidden.value = this.otp.join('');
              }
          }">
        @csrf

        {{-- 6-box OTP Input --}}
        <div class="flex gap-2 justify-center mb-6">
            @for($i = 0; $i < 6; $i++)
                <input type="text" inputmode="numeric" maxlength="1"
                       x-ref="otp_{{ $i }}"
                       @input="handleInput({{ $i }}, $event)"
                       @keydown="handleKey({{ $i }}, $event)"
                       :value="otp[{{ $i }}]"
                       class="w-11 h-14 text-center text-xl font-bold border-2 border-gray-200 rounded-xl focus:border-primary-500 focus:ring-2 focus:ring-primary-100 outline-none transition-all"
                       @if($i === 0) autofocus @endif>
            @endfor
        </div>

        {{-- Hidden actual input --}}
        <input type="hidden" name="otp" x-ref="otpHidden">

        <button type="submit" class="btn btn-primary w-full justify-center py-3 text-base">
            যাচাই করুন
        </button>

        {{-- Resend --}}
        <div class="text-center mt-4" x-data="otpResend()">
            <p class="text-sm text-gray-500" x-show="!canResend">
                পুনরায় পাঠান:
                <span class="font-bold text-primary-600" x-text="countdown + ' সেকেন্ড পরে'"></span>
            </p>
            <form method="POST" action="{{ route('otp.resend') }}" x-show="canResend">
                @csrf
                <button type="submit" class="text-sm text-primary-600 font-medium hover:underline">
                    OTP পুনরায় পাঠান
                </button>
            </form>
        </div>
    </form>
@endsection
