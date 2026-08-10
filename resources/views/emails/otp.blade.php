<x-mail::message>
# আপনার একাউন্ট যাচাই করুন

প্রিয় ইউজার,

আপনার {{ config('app.name') }} একাউন্ট যাচাই করতে নিচের ৬ ডিজিটের OTP কোডটি ব্যবহার করুন। কোডটি ৫ মিনিটের জন্য কার্যকর থাকবে।

<x-mail::panel>
<div style="text-align: center; font-size: 24px; font-weight: bold; letter-spacing: 4px;">
{{ $otp }}
</div>
</x-mail::panel>

যদি আপনি এই অনুরোধটি না করে থাকেন, তবে দয়া করে এই ইমেইলটি এড়িয়ে যান।

ধন্যবাদ,<br>
{{ config('app.name') }} টিম
</x-mail::message>
