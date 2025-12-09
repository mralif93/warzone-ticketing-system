@extends('layouts.admin')

@section('title', 'Export / Import Payments')

@section('content')
<div class="min-h-screen bg-wwc-neutral-50 py-8 px-6">
    <div class="max-w-6xl mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-wwc-neutral-200">
            <div class="px-6 py-4 border-b border-wwc-neutral-100 flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-bold text-wwc-neutral-900">Export / Import Payments</h1>
                    <p class="text-sm text-wwc-neutral-600">Choose fields with checkboxes. Leave all checked to export everything.</p>
                </div>
                <a href="{{ route('admin.payments.index') }}" class="text-sm text-wwc-primary font-semibold hover:underline">← Back to Payments</a>
            </div>
            <div class="p-6 grid grid-cols-1 gap-6 lg:grid-cols-2">
                <form method="GET" action="{{ route('admin.payments.export') }}" class="space-y-4">
                    <h3 class="text-md font-semibold text-wwc-neutral-900">Fields to Export</h3>
                    <div class="grid grid-cols-2 gap-3">
                        @foreach($fields as $field)
                            <label class="inline-flex items-center space-x-2 text-sm text-wwc-neutral-800">
                                <input type="checkbox" name="fields[]" value="{{ $field }}" checked class="rounded border-wwc-neutral-300 text-wwc-primary focus:ring-wwc-primary">
                                <span>{{ $field }}</span>
                            </label>
                        @endforeach
                    </div>
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-semibold rounded-lg text-white bg-wwc-primary hover:bg-wwc-primary-dark transition">
                        Export CSV
                    </button>
                </form>

                <form method="POST" action="{{ route('admin.payments.import') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <h3 class="text-md font-semibold text-wwc-neutral-900">Import CSV</h3>
                    <p class="text-xs text-wwc-neutral-500">Include a header row. If “id” is present, matching records will update; otherwise they will be created.</p>
                    <input type="file" name="file" accept=".csv" class="block w-full text-sm text-wwc-neutral-700">
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-semibold rounded-lg text-white bg-wwc-success hover:bg-wwc-success-dark transition">
                        Import CSV
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

