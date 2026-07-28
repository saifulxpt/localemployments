@props(['status'])

@php
    $classes = [
        'pending'     => 'bg-yellow-50 text-yellow-700 border-yellow-200',
        'assigned'    => 'bg-blue-50 text-blue-700 border-blue-200',
        'confirmed'   => 'bg-blue-50 text-blue-700 border-blue-200',
        'in_progress' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
        'completed'   => 'bg-green-50 text-green-700 border-green-200',
        'cancelled'   => 'bg-red-50 text-red-700 border-red-200',
        'rejected'    => 'bg-red-50 text-red-700 border-red-200',
        'disputed'    => 'bg-orange-50 text-orange-700 border-orange-200',
    ];
    
    $labels = [
        'pending'     => 'অপেক্ষমাণ',
        'assigned'    => 'কর্মী নিযুক্ত',
        'confirmed'   => 'নিশ্চিতকৃত',
        'in_progress' => 'চলমান',
        'completed'   => 'সম্পন্ন',
        'cancelled'   => 'বাতিল',
        'rejected'    => 'বাতিল',
        'disputed'    => 'বিবাদমান',
    ];

    $class = $classes[$status] ?? 'bg-gray-50 text-gray-700 border-gray-200';
    $label = $labels[$status] ?? ucfirst($status);
@endphp

<span {{ $attributes->merge(['class' => "px-2 py-1 text-xs font-semibold rounded-md border $class whitespace-nowrap inline-flex items-center justify-center"]) }}>
    {{ $label }}
</span>
